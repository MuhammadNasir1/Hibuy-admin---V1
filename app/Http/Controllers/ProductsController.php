<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            $userId = session('user_details.user_id');

            $user = User::where('user_id', $userId)->first();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User not found.'], 404);
            }

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
            // return $user;
            // Return to the Blade view with compacted data
            return view('pages.AddProduct', compact('user', 'categories', 'products'));
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

            if ($userDetails['user_role'] !== 'admin') {
                // Find the seller record for the authenticated user
                $seller = Seller::where('user_id', $userDetails['user_id'])->first();
                if (!$seller) {
                    return response()->json(['error' => 'Seller record not found'], 404);
                }

                // Fetch store_id based on seller_id
                $store = Store::where('seller_id', $seller->seller_id)->first();
                if (!$store) {
                    // return response()->json(['error' => 'Store record not found'], 404);
                    return redirect('/products')->with('error', 'Store record not found. Please Create Store First');
                }
            } else {
                $seller_id = '0';
                $store_id = '0';
                $store = (object) [
                    'store_id' => $store_id,
                    'seller_id' => $seller_id,
                ];
            }

            $productId = $request->product_edit_id;



            // Validate request data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'company' => 'required|string|max:255',
                'category' => 'required|integer|max:255',
                'sub_category' => 'required|string|max:255',
                'purchase_price' => 'required|numeric|min:0',
                'product_price' => 'required|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'discounted_price' => 'nullable|numeric|min:0',
                'variants' => 'nullable|array',
                'product_status' => 'nullable|integer|in:0,1',
                'product_edit_id' => 'nullable|integer', // Add this field for update condition
                'is_boosted' => 'nullable|in:0,1',


            ]);
            if ($request->has('is_boosted') && $userDetails['user_role'] !== 'admin') {
                $user = User::where('user_id', $userDetails['user_id'])->first();

                if (!$user) {
                    return redirect('/products')->with('error', 'User not found.');
                }

                $packageDetail = is_array($user->package_detail)
                    ? $user->package_detail
                    : json_decode($user->package_detail, true);

                if (!isset($packageDetail['package_status']) || $packageDetail['package_status'] !== 'approved') {
                    return redirect('/products')->with('error', 'You are not authorized to boost products.');
                }
            }
            $isBoosted = $request->has('is_boosted') ? 1 : 0;

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
                    'user_id' => $userDetails['user_id'],
                    'store_id' => $store->store_id,
                    'product_name' => $validatedData['title'],
                    'product_description' => $validatedData['description'] ?? null,
                    'product_brand' => $validatedData['company'],
                    'product_category' => $validatedData['category'],
                    'product_subcategory' => $validatedData['sub_category'],
                    'purchase_price' => $validatedData['purchase_price'],
                    'product_price' => $validatedData['product_price'],
                    'product_discount' => $validatedData['discount'] ?? 0,
                    'product_discounted_price' => $validatedData['discounted_price'] ?? 0,
                    // 'is_boosted' => $isBoosted,
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
                if ($userDetails['user_role'] == 'admin') {
                    return redirect()->route('hibuy_product')->with('success', 'Product updated successfully');
                } else {
                    return redirect()->route('products')->with('success', 'Product updated successfully');
                }
                // return redirect()->route('products')->with('success', 'Product updated successfully');
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
                    'user_id' => $userDetails['user_id'],
                    'store_id' => $store->store_id,
                    'product_name' => $validatedData['title'],
                    'product_description' => $validatedData['description'] ?? null,
                    'product_brand' => $validatedData['company'],
                    'product_category' => $validatedData['category'],
                    'product_subcategory' => $validatedData['sub_category'],
                    'purchase_price' => $validatedData['purchase_price'],
                    'product_price' => $validatedData['product_price'],
                    'product_discount' => $validatedData['discount'] ?? 0,
                    'product_discounted_price' => $validatedData['discounted_price'] ?? 0,
                    'product_images' => json_encode($storedImagePaths),
                    'product_variation' => json_encode($productVariants),
                    'product_status' => $validatedData['product_status'] ?? 0,
                    'is_boosted' => $isBoosted,
                ];

                Products::create($productData);
                // return $userDetails;
                if ($userDetails['user_role'] == 'admin') {
                    return redirect()->route('hibuy_product')->with('success', 'Product added successfully');
                } else {
                    return redirect()->route('products')->with('success', 'Product added successfully');
                }
                // return redirect()->route('products')->with('success', 'Product added successfully');
            }
        } catch (\Exception $th) {
            // return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
            return redirect('/product/add')->with('error', $th->getMessage());
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

            return response()->json(['success' => true, 'message' => 'Category added successfully', 'category' => $category], 201);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 500);
        }
    }

    public function showcat()
    {
        // $categories = product_category::all();
        $categories = DB::table('categories')
            ->leftJoin('products', 'categories.id', '=', 'products.product_category')
            ->select('categories.*', 'products.product_category')
            ->distinct()
            ->get();
        // Loop through each category and count subcategories
        foreach ($categories as $category) {
            $category->subcategory_count = is_array(json_decode($category->sub_categories, true))
                ? count(json_decode($category->sub_categories, true))
                : 0;
        }
        foreach ($categories as $category) {
            $category->product_count = DB::table('products')
                ->where('product_category', $category->id)
                ->count();
        }
        // return  $categories;
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
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            // Find the category by ID
            $category = product_category::find($id);
            if (!$category) {
                return response()->json(['error' => 'Category not found'], 404);
            }

            // Update category name
            $category->name = $request->input('name');

            // Handle image upload
            if ($request->hasFile('image')) {
                // Optional: Delete old image
                if ($category->image && Storage::exists(str_replace("storage/", "", $category->image))) {
                    Storage::delete(str_replace("storage/", "", $category->image));
                }

                $imagePath = $request->file('image')->store('categories', 'public');
                $category->image = "storage/" . $imagePath; // Ensure it's prefixed properly
            }

            // Convert subcategories JSON string to an array and save it
            $subCategories = json_decode($request->input('sub_categories'));
            if (!is_array($subCategories)) {
                return response()->json(['error' => 'Invalid sub_categories format'], 400);
            }
            $category->sub_categories = json_encode($subCategories); // Save as JSON

            // Save updated category
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Category updated successfully',
                'category' => $category
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    //     public function showAllProducts()
    //     {
    //         // Retrieve user details from session
    //         $userDetails = session('user_details');
    //         if (!$userDetails) {
    //             return response()->json(['error' => 'User not authenticated'], 401);
    //         }

    //         $loggedInUserId = $userDetails['user_id'];
    //         $loggedInUserRole = $userDetails['user_role']; // Get user role

    //         if ($loggedInUserRole == 'admin') {
    //             $p_id = $loggedInUserId;
    //         }

    //         // Base query
    //         $query = DB::table('products')
    //             ->leftJoin('categories', 'products.product_category', '=', 'categories.id')
    //             ->join('users', 'products.user_id', '=', 'users.user_id')
    //             ->select(
    //                 'products.product_id',
    //                 'products.user_id',
    //                 'products.product_name',
    //                 'categories.name as product_category',
    //                 'products.product_discounted_price',
    //                 'products.product_images',
    //                 'products.product_status',
    //                 'products.is_boosted',
    //                 'products.created_at',
    //                 'products.updated_at',
    //                 'users.user_name as user_name'
    //             );


    //         // If not admin, filter by logged-in user_id
    //         if ($loggedInUserRole !== 'admin') {
    //             $query->where('products.user_id', $loggedInUserId);
    //         } else {
    //             $query->where('products.user_id', '!=', $p_id);
    //         }

    //         // Fetch products and format image
    //         $products = $query->get()->map(function ($product) {
    //             $images = json_decode($product->product_images, true);
    //             $product->first_image = $images[0] ?? null;
    //             unset($product->product_images);
    //             return $product;
    //         });

    // // return $products;
    //  $user = User::where('user_id', $loggedInUserId)->first();
    //     $packageDetail = json_decode($user->package_detail, true);

    //     $packageStatus = $packageDetail['package_status'] ?? null;

    //     // return $products;
    //     return view('pages.products', compact('products', 'packageStatus'));
    // }


    public function showAllProducts(Request $request)
    {
        $userDetails = session('user_details');
        if (!$userDetails) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $loggedInUserId = $userDetails['user_id'];
        $loggedInUserRole = $userDetails['user_role'];

        if ($loggedInUserRole == 'admin') {
            $p_id = $loggedInUserId;
        }

        $query = DB::table('products')
            ->leftJoin('categories', 'products.product_category', '=', 'categories.id')
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
            )
            ->orderBy('products.product_id', 'desc'); // ⬅️ This adds DESC order


        if ($loggedInUserRole !== 'admin') {
            $query->where('products.user_id', $loggedInUserId);
        } else {
            $query->where('products.user_id', '!=', $p_id);
        }

        // Filter by boosted if passed in request
        if ($request->has('boosted') && $request->boosted == '1') {
            $query->where('products.is_boosted', 1);
        }

        $products = $query->get()->map(function ($product) {
            $images = json_decode($product->product_images, true);
            $product->first_image = $images[0] ?? null;
            unset($product->product_images);
            return $product;
        });

        $user = User::where('user_id', $loggedInUserId)->first();
        $packageDetail = json_decode($user->package_detail, true);
        $packageStatus = $packageDetail['package_status'] ?? null;
        $package_end_date = $packageDetail['package_end_date'] ?? null;

        return view('pages.products', compact('products', 'packageStatus', 'package_end_date'));
    }


    public function showHibuyProducts()
    {
        // Retrieve user details from session
        $userDetails = session('user_details');
        if (!$userDetails) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $loggedInUserId = $userDetails['user_id'];
        $loggedInUserRole = $userDetails['user_role']; // Get user role

        if ($loggedInUserRole == 'admin') {
            $p_id = $loggedInUserId;
        }

        // Base query
        $query = DB::table('products')
            ->join('categories', 'products.product_category', '=', 'categories.id')
            ->join('users', 'products.user_id', '=', 'users.user_id')
            ->where('products.user_id', '=', $p_id)
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


        return view('admin.HibuyProduct', compact('products'));
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
                'user_id',
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
                'product_id' => $product->product_id,
                'user_id' => $product->user_id,
                'product_name' => $product->product_name,
                'product_description' => $product->product_description,
                'product_price' => $product->product_price,
                'product_brand' => $product->product_brand,
                'product_discount' => $product->product_discount,
                'product_discounted_price' => $product->product_discounted_price,
                'product_images' => $product->product_images,
                'product_variation' => $product->product_variation,
                'category_name' => $product->category->name ?? null, // Get category name
                'category_id' => $product->category->id,
                'subcategory' => $product->product_subcategory,
                'product_status' => $product->product_status
            ];
            // return $response;
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
            return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
        }

        $product = Products::find($id);

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found!'], 404);
        }

        $product->delete();
        return response()->json(['success' => true, 'message' => 'Product deleted successfully!']);
    }

    // public function getOtherSellerProduct()
    // {
    //     $user = session('user_details')['user_id'];

    //     $products = Products::with('category')
    //         ->where('user_id', '!=', $user )
    //         ->get();

    //     $categories = product_category::all(); // <-- Get all categories

    //     return view('seller.OtherSeller', compact('products', 'categories'));
    // }


    public function getOtherSellerProduct()
    {
        $userId = session('user_details')['user_id'];

        $products = Products::with('category')
            ->where('user_id', '!=', $userId)
            ->whereHas('user', function ($query) {
                $query->where('user_role', '!=', 'admin');
            })
            ->whereHas('category', function ($query) {
                // Ensure the product has a valid category in the product_category table
                $query->whereNotNull('id');
            })
            ->get();

        $categories = product_category::all();

        return view('seller.OtherSeller', compact('products', 'categories'));
    }


    public function boost($id)
    {
        $user = session('user_details');

        $product = Products::findOrFail($id);

        // Only allow the owner to boost their own products
        if ($product->user_id != $user['user_id']) {
            return redirect()->back()->with('error', 'You are not authorized to boost this product.');
        }

        $fullUser = User::where('user_id', $user['user_id'])->first();

        $packageDetail = json_decode($fullUser->package_detail, true);

        // Check if package is approved
        $packageStatus = strtolower($packageDetail['package_status'] ?? '');
        if ($packageStatus !== 'approved') {
            return redirect()->back()->with('error', 'Your package is not approved. You cannot boost products.');
        }

        $packageType = strtolower($packageDetail['package_type'] ?? '');

        // Determine max boostable product count
        $maxBoosts = match ($packageType) {
            'silver' => 3,
            'gold' => 6,
            'platinum' => 10,
            default => 0
        };

        // Get start and end date
        $boostStartDate = $packageDetail['package_start_date'] ?? null;
        $boostEndDate = $packageDetail['package_end_date'] ?? null;

        // Toggle boosting
        if ($product->is_boosted == 0) {
            // Count current boosted products
            $boostedCount = Products::where('user_id', $user['user_id'])
                ->where('is_boosted', 1)
                ->count();

            // Check if boosting limit reached
            if ($boostedCount >= $maxBoosts) {
                return redirect()->back()->with('error', "You can only boost up to {$maxBoosts} products with your {$packageType} package.");
            }

            $product->is_boosted = 1;
            $product->boost_start_date = $boostStartDate;
            $product->boost_end_date = $boostEndDate;

            $message = 'Product boosted successfully!';
        } else {
            $product->is_boosted = 0;
            $product->boost_start_date = null;
            $product->boost_end_date = null;

            $message = 'Product unboosted successfully!';
        }

        $product->save();

        return redirect()->back()->with('success', $message);
    }
}
