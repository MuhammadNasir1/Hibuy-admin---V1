<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Seller;
use App\Models\Products;
use Illuminate\Http\Request;
use App\Models\product_category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{


    public function index()
    {
        return view('pages.products');
    }

    public function getProductwithCategories($editid = null)
    {
        try {
            // Fetch all categories from the database
            $categories = product_category::select('id', 'name', 'image', 'sub_categories')->get();

            // Initialize products variable
            $products = null;

            // Fetch product only if editid is provided
            if ($editid !== null) {
                $products = Products::find($editid);
            }

            // Decode JSON fields if sub_categories are stored as JSON
            foreach ($categories as $category) {
                $category->sub_categories = json_decode($category->sub_categories, true);
            }
            // return $products;
            // Return to the Blade view with compacted data
            return view('pages.AddProduct', compact('categories', 'products'));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    public function getSubCategories($category_id)
    {
        try {
            $category = product_category::find($category_id);

            if (!$category) {
                return response()->json(['success' => false, 'message' => 'Category not found'], 404);
            }

            // Decode JSON stored subcategories
            $subCategories = json_decode($category->sub_categories, true) ?? [];

            return response()->json(['success' => true, 'sub_categories' => $subCategories], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getFileName(Request $request)
    {
        $request->validate([
            'product_images' => 'required|array',
            'product_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $filePaths = [];

        foreach ($request->file('product_images') as $file) {
            if ($file) {
                $fileName = time() . "_" . $file->getClientOriginalName();
                $filePath = "products/{$fileName}";

                // Store the file in storage/app/public/products
                Storage::disk('public')->put($filePath, file_get_contents($file));

                // Store only the relative file path
                $filePaths[] = "storage/{$filePath}";
            }
        }

        return response()->json($filePaths);
    }

    public function storeProduct(Request $request)
    {
        try {
            // return $request;
            // Retrieve user details from session
            $userDetails = session('user_details');
            if (!$userDetails) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            // Find the seller record for the authenticated user
            $seller = Seller::where('user_id', $userDetails['user_id'])->first();
            if (!$seller) {
                return response()->json(['error' => 'Seller record not found'], 404);
            }

            // Fetch store_id based on seller_id
            $store = Store::where('seller_id', $seller->seller_id)->first();
            if (!$store) {
                return response()->json(['error' => 'Store record not found'], 404);
            }

            $productId = $request->product_edit_id;



            // Validate request data
            $validatedData = $request->validate([
                'title'            => 'required|string|max:255',
                'description'      => 'nullable|string',
                'company'          => 'required|string|max:255',
                'category'         => 'required|integer|max:255',
                'sub_category'     => 'required|string|max:255',
                'purchase_price'   => 'required|numeric|min:0',
                'product_price'    => 'required|numeric|min:0',
                'discount'         => 'nullable|numeric|min:0',
                'discounted_price' => 'nullable|numeric|min:0',
                'variants'         => 'nullable|array',
                'product_status'   => 'nullable|integer|in:0,1',
                'product_edit_id'  => 'nullable|integer', // Add this field for update condition
            ]);



            // Check if updating an existing product
            if ($request->filled('product_edit_id')) {
                // Process Variants
                $product = Products::where('product_id', $productId)
                    ->where('user_id', $userDetails['user_id'])
                    ->first();

                if (!$product) {
                    return response()->json(['error' => 'Product not found or unauthorized'], 404);
                }
                $productVariants = $request->variants ?? [];
                $existingVariants = json_decode($product->product_variation, true) ?? [];

                foreach ($productVariants as $parentIndex => &$parentVariant) {
                    $existingParent = $existingVariants[$parentIndex] ?? [];

                    // Process parent image
                    if ($request->hasFile("variants.$parentIndex.parentimage")) {
                        $parentImage = $request->file("variants.$parentIndex.parentimage");
                        $parentImagePath = $parentImage->store('variants', 'public');
                        $parentVariant['parentimage'] = "storage/" . $parentImagePath;
                    } else {
                        $parentVariant['parentimage'] = $existingParent['parentimage'] ?? null;
                    }

                    // Merge any missing parent fields from existing
                    foreach ($existingParent as $key => $value) {
                        if (!isset($parentVariant[$key]) && $key !== 'children') {
                            $parentVariant[$key] = $value;
                        }
                    }

                    // Ensure children array
                    $parentVariant['children'] = $parentVariant['children'] ?? [];
                    $existingChildren = $existingParent['children'] ?? [];

                    foreach ($parentVariant['children'] as $childIndex => &$child) {
                        $existingChild = $existingChildren[$childIndex] ?? [];

                        // Handle child image
                        if ($request->hasFile("variants.$parentIndex.children.$childIndex.image")) {
                            $childImage = $request->file("variants.$parentIndex.children.$childIndex.image");
                            $childImagePath = $childImage->store('variants', 'public');
                            $child['image'] = "storage/" . $childImagePath;
                        } else {
                            $child['image'] = $existingChild['image'] ?? null;
                        }

                        // Merge any missing child fields
                        foreach ($existingChild as $key => $value) {
                            if (!isset($child[$key]) && $key !== 'image') {
                                $child[$key] = $value;
                            }
                        }
                    }
                }

                $storedImagePaths = [];
                if ($request->has('product_images')) {
                    $storedImagePaths = json_decode($request->product_images, true) ?? [];
                }

                // return $productVariants;
                // Fetch the product that needs to be updated
                $product = Products::where('product_id', $request->product_edit_id)
                    ->where('user_id', $userDetails['user_id'])
                    ->first();
                // return $request->product_edit_id;
                if (!$product) {
                    return response()->json(['error' => 'Product not found or unauthorized'], 404);
                }

                // Prepare update data (excluding specified fields)
                $updateData = [
                    'user_id'                  => $userDetails['user_id'],
                    'store_id'                 => $store->store_id,
                    'product_name'             => $validatedData['title'],
                    'product_description'      => $validatedData['description'] ?? null,
                    'product_brand'            => $validatedData['company'],
                    'product_category'         => $validatedData['category'],
                    'product_subcategory'      => $validatedData['sub_category'],
                    'purchase_price'           => $validatedData['purchase_price'],
                    'product_price'            => $validatedData['product_price'],
                    'product_discount'         => $validatedData['discount'] ?? 0,
                    'product_discounted_price' => $validatedData['discounted_price'] ?? 0,
                ];
                // Conditionally update product_variation
                if (!empty($productVariants)) {
                    $updateData['product_variation'] = json_encode($productVariants);
                }

                if (!empty($storedImagePaths)) {
                    $updateData['product_images'] = json_encode($storedImagePaths);
                }
                // Update the product
                $product->update($updateData);

                return redirect()->route('products')->with('success', 'Product updated successfully');
            } else {
                // Decode product images JSON if it exists
                $storedImagePaths = [];
                if ($request->has('product_images')) {
                    $storedImagePaths = json_decode($request->product_images, true) ?? [];
                }

                // Process Variants
                $productVariants = $request->variants ?? [];

                foreach ($productVariants as $parentIndex => &$parentVariant) {
                    // Handle parent image upload
                    if ($request->hasFile("variants.$parentIndex.parentimage")) {
                        $parentImage = $request->file("variants.$parentIndex.parentimage");
                        $parentImagePath = $parentImage->store('variants', 'public');
                        $parentVariant['parentimage'] = "storage/" . $parentImagePath;
                    }

                    // Ensure children key exists
                    if (!isset($parentVariant['children']) || !is_array($parentVariant['children'])) {
                        $parentVariant['children'] = []; // Ensure it's always an array
                    }

                    // Handle child images
                    foreach ($parentVariant['children'] as $childIndex => &$child) {
                        if ($request->hasFile("variants.$parentIndex.children.$childIndex.image")) {
                            $childImage = $request->file("variants.$parentIndex.children.$childIndex.image");
                            $childImagePath = $childImage->store('variants', 'public');
                            $child['image'] = "storage/" . $childImagePath;
                        }
                    }
                }
                // return $productVariants;
                // Insert new product (including all fields)
                $productData = [
                    'user_id'                  => $userDetails['user_id'],
                    'store_id'                 => $store->store_id,
                    'product_name'             => $validatedData['title'],
                    'product_description'      => $validatedData['description'] ?? null,
                    'product_brand'            => $validatedData['company'],
                    'product_category'         => $validatedData['category'],
                    'product_subcategory'      => $validatedData['sub_category'],
                    'purchase_price'           => $validatedData['purchase_price'],
                    'product_price'            => $validatedData['product_price'],
                    'product_discount'         => $validatedData['discount'] ?? 0,
                    'product_discounted_price' => $validatedData['discounted_price'] ?? 0,
                    'product_images'           => json_encode($storedImagePaths),
                    'product_variation'        => json_encode($productVariants),
                    'product_status'           => $validatedData['product_status'] ?? 0,
                ];

                Products::create($productData);

                return redirect()->route('products')->with('success', 'Product added successfully');
            }
        } catch (\Exception $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }



    public function categories(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3048',
                'sub_categories' => 'required|string', // It comes as a JSON string
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
                $newpath = "storage/" . $imagePath;
            }


            // Convert JSON string to array
            $subCategories = json_decode($request->input('sub_categories'));
            if (!is_array($subCategories)) {
                return response()->json(['error' => 'Invalid sub_categories format'], 400);
            }
            $category = new product_category();
            $category->name = $request->input('name');
            $category->image = $newpath;
            $category->sub_categories = json_encode($subCategories); // Save as JSON
            $category->save();

            return response()->json(['message' => 'Category added successfully', 'category' => $category], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function showcat()
    {
        $categories = product_category::all();

        // Loop through each category and count subcategories
        foreach ($categories as $category) {
            $category->subcategory_count = is_array(json_decode($category->sub_categories, true))
                ? count(json_decode($category->sub_categories, true))
                : 0;
        }

        return view('admin.ProductCategory', compact('categories'));
    }

    public function fetchCategory($id)
    {
        $category = product_category::find($id);
        // return $category;
        if ($category) {
            return response()->json([
                'status' => 'success',
                'data' => $category,
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No record found!'
        ], 404);
    }
    public function deleteCategory($id)
    {
        $category = product_category::find($id);
        if ($category) {
            $category->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Category deleted successfully',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No record found!'
        ], 404);
    }
    public function getforupdate($id)
    {
        try {
            $category = product_category::find($id);

            return response()->json([
                'status' => 'success',
                'data' => $category,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'false',
                'data' => null,
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'sub_categories' => 'required|string', // JSON string of subcategories
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            // Find the category by ID
            $category = product_category::find($id);
            if (!$category) {
                return response()->json(['error' => 'Category not found'], 404);
            }

            // Update category details
            $category->name = $request->input('name');

            // Handle new image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
                $category->image = $imagePath; // Update image only if a new one is uploaded
            }

            // Convert subcategories JSON string to an array and save it
            $subCategories = json_decode($request->input('sub_categories'));
            if (!is_array($subCategories)) {
                return response()->json(['error' => 'Invalid sub_categories format'], 400);
            }
            $category->sub_categories = json_encode($subCategories); // Save as JSON

            // Save the updated category
            $category->save();

            return response()->json(['status' => 'success', 'message' => 'Category updated successfully', 'category' => $category], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function showAllProducts()
    {
        // Retrieve user details from session
        $userDetails = session('user_details');
        if (!$userDetails) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $loggedInUserId = $userDetails['user_id'];
        $loggedInUserRole = $userDetails['user_role']; // Get user role

        // Base query
        $query = DB::table('products')
            ->join('categories', 'products.product_category', '=', 'categories.id')
            ->join('users', 'products.user_id', '=', 'users.user_id')
            ->select(
                'products.product_id',
                'products.user_id',
                'products.product_name',
                'categories.name as product_category',
                'products.product_discounted_price',
                'products.product_images',
                'products.product_status',
                'products.is_boosted',
                'products.created_at',
                'products.updated_at',
                'users.user_name as user_name'
            );

        // If not admin, filter by logged-in user_id
        if ($loggedInUserRole !== 'admin') {
            $query->where('products.user_id', $loggedInUserId);
        }

        // Fetch products and format image
        $products = $query->get()->map(function ($product) {
            $images = json_decode($product->product_images, true);
            $product->first_image = $images[0] ?? null;
            unset($product->product_images);
            return $product;
        });

        return view('pages.products', compact('products'));
    }



    public function viewProductDetails($id)
    {
        try {
            $userDetails = session('user_details');
            if (!$userDetails) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }
            // Fetch product without store and reviews details
            $product = Products::select(
                'product_id',
                'product_name',
                'product_description',
                'product_price',
                'product_brand',
                'product_discount',
                'product_discounted_price',
                'product_images',
                'product_variation',
                'product_status',
                'product_category', // Category ID
                'product_subcategory' // Subcategory ID
            )
                ->with([
                    'category:id,name'
                ])
                ->where('product_id', $id)
                ->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }

            // Decode JSON fields
            $product->product_images = json_decode($product->product_images, true);
            $product->product_variation = json_decode($product->product_variation, true);

            // Prepare response data
            $response = [
                'product_id'               => $product->product_id,
                'product_name'             => $product->product_name,
                'product_description'      => $product->product_description,
                'product_price'            => $product->product_price,
                'product_brand'            => $product->product_brand,
                'product_discount'         => $product->product_discount,
                'product_discounted_price' => $product->product_discounted_price,
                'product_images'           => $product->product_images,
                'product_variation'        => $product->product_variation,
                'category_name'            => $product->category->name ?? null, // Get category name
                'subcategory'              => $product->product_subcategory,
                'product_status'           => $product->product_status
            ];

            return response()->json([
                'success' => true,
                'message' => 'Product Fetched Successfully',
                'product' => $response
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $userDetails = session('user_details');
        if (!$userDetails) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,product_id',
            'product_status' => 'required|in:0,1'
        ]);

        $product = Products::find($request->product_id);
        if ($product) {
            $product->product_status = $request->product_status;
            $product->save();
            return response()->json(['success' => true, 'message' => 'Status updated successfully.']);
        }

        return response()->json(['success' => false, 'message' => 'Product not found.']);
    }

    public function deleteProduct($id)
    {
        $userDetails = session('user_details');
        if (!$userDetails) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $product = Products::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found!'], 404);
        }

        $product->delete();
        return response()->json(['message' => 'Product deleted successfully!']);
    }

    public function getOtherSellerProduct()
    {
        $user = session('user_details')['user_id'];
        $products = Products::Where('user_id', '!=', $user)->get();

        return view('seller.OtherSeller', compact('products'));
    }
}
