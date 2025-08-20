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

            // Fetch only top-level categories (where parent_id is null )
            $categories = product_category::select('id', 'name', 'image')
                ->where('category_type', 'products')
                ->whereNull('parent_id')
                ->get();

            $products = null;
            $categoryIds = [];

            if ($editid !== null) {
                $products = Products::find($editid);

                if ($products) {
                    $categoryIds = DB::table('product_category_product')
                        ->where('product_id', $products->product_id)
                        ->orderBy('category_level')
                        ->pluck('category_id')
                        ->toArray();
                }
            }
            // return $categoryIds;
            return view('pages.AddProduct', compact('user', 'categories', 'products', 'categoryIds'));
        } catch (\Exception $e) {
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    public function getSubCategories($category_id)
    {
        try {
            // Find category
            $category = product_category::find($category_id);

            if (!$category) {
                return response()->json(['success' => false, 'message' => 'Category not found'], 404);
            }

            // Decode JSON sub_categories
            $subCategoriesJson = json_decode($category->sub_categories, true) ?? [];

            $subCategoryIds = array_column($subCategoriesJson, 'id');

            // Get actual subcategories from DB
            $subCategories = product_category::whereIn('id', $subCategoryIds)
                ->select('id', 'name')
                ->get();
            return response()->json([
                'success' => true,
                'sub_categories' => $subCategories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
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

            $userDetails = session('user_details');
            if (!$userDetails) {
                return response()->json(['error' => 'User not authenticated'], 401);
            }

            if ($userDetails['user_role'] !== 'admin') {

                $seller = Seller::where('user_id', $userDetails['user_id'])->first();
                if (!$seller) {
                    return response()->json(['error' => 'Seller record not found'], 404);
                }


                $store = Store::where('seller_id', $seller->seller_id)->first();
                if (!$store) {
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

            // ... (validation and update logic here)

            if (!$request->filled('product_edit_id')) {
                // -------------------------------
                // ✅ Product Creation (new product)
                // -------------------------------
                $storedImagePaths = [];
                if ($request->has('product_images')) {
                    $storedImagePaths = json_decode($request->product_images, true) ?? [];
                }

                $productVariants = $request->variants ?? [];
                foreach ($productVariants as $parentIndex => &$parentVariant) {
                    if ($request->hasFile("variants.$parentIndex.parentimage")) {
                        $parentImage = $request->file("variants.$parentIndex.parentimage");
                        $parentImagePath = $parentImage->store('variants', 'public');
                        $parentVariant['parentimage'] = "storage/" . $parentImagePath;
                    }
                    foreach ($parentVariant['children'] ?? [] as $childIndex => &$child) {
                        if ($request->hasFile("variants.$parentIndex.children.$childIndex.image")) {
                            $childImage = $request->file("variants.$parentIndex.children.$childIndex.image");
                            $childImagePath = $childImage->store('variants', 'public');
                            $child['image'] = "storage/" . $childImagePath;
                        }
                    }
                }

                $totalStock = collect($productVariants)->sum(fn($parent) => (int) ($parent['parentstock'] ?? 0));

                $productData = [
                    'user_id' => $userDetails['user_id'],
                    'store_id' => $store->store_id,
                    'product_name' => $request->title,
                    'product_description' => $request->description ?? null,
                    'product_brand' => $request->company,
                    'product_category' => $request->category_id,
                    'purchase_price' => $request->purchase_price,
                    'product_price' => $request->product_price,
                    'product_discount' => $request->discount ?? 0,
                    'product_discounted_price' => $request->discounted_price ?? 0,
                    'product_images' => json_encode($storedImagePaths),
                    'product_variation' => json_encode($productVariants),
                    'product_status' => $request->product_status ?? 0,
                    'is_boosted' => $request->has('is_boosted') ? 1 : 0,
                    'product_stock' => $totalStock,
                ];

                $newProduct = Products::create($productData);

                // ✅ Send email to Seller
                $personalInfo = json_decode($seller->personal_info, true);
                $sellerEmail = $personalInfo['email'] ?? null;
                $sellerName = $personalInfo['full_name'] ?? 'Seller';

                if ($sellerEmail) {
                    $subject = "Your product is under review";
                    $body = "
                        <h3>Hello {$sellerName},</h3>
                        <p>Your product <b>{$newProduct->product_name}</b> has been submitted and is now under review by the admin team.</p>
                        <p>You will be notified once it is approved.</p>
                        <p>Thanks,</p>
                    ";
                    (new EmailController)->sendMail($sellerEmail, $subject, $body);
                }

                // ✅ Send email to Admin
                $adminEmail = "info.arham.org@gmail.com";
                $subjectAdmin = "New product submitted for review";
                $bodyAdmin = "
                    <h3>Hello Admin,</h3>
                    <p>Seller <b>{$sellerName}</b> has added a new product for review.</p>
                    <p><b>Product:</b> {$newProduct->product_name}<br>
                       <b>Brand:</b> {$newProduct->product_brand}<br>
                    <p>Please review it in the admin panel.</p>
                ";
                (new EmailController)->sendMail($adminEmail, $subjectAdmin, $bodyAdmin);

                // ✅ Save categories in pivot table (your existing code)
                $categoryIds = [
                    1 => $request->category_id,
                    2 => $request->subcategory_id,
                    3 => $request->sub_subcategory_id,
                    4 => $request->category_level_3,
                    5 => $request->category_level_4,
                    6 => $request->category_level_5,
                ];
                foreach ($categoryIds as $level => $categoryId) {
                    if ($categoryId) {
                        DB::table('product_category_product')->insert([
                            'product_id' => $newProduct->product_id,
                            'category_id' => $categoryId,
                            'category_level' => $level,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }

                if ($userDetails['user_role'] == 'admin') {
                    return redirect()->route('hibuy_product')->with('success', 'Product added successfully');
                } else {
                    return redirect()->route('products')->with('success', 'Product added successfully');
                }
            }

        } catch (\Exception $th) {
            return redirect('/product/add')->with('error', $th->getMessage());
        }
    }




    public function categories(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:3048',
                'sub_categories' => 'nullable|json', // JSON string representing array
                'category_type' => 'required',
                'parent_id' => 'nullable|exists:categories,id' // if adding under existing parent
            ]);

            // Store image if uploaded
            $newpath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
                $newpath = "storage/" . $imagePath;
            }

            // Create new category
            $category = new product_category();
            $category->name = $request->input('name');
            $category->category_type = $request->input('category_type');
            $category->image = $newpath;
            $category->parent_id = $request->input('parent_id'); // can be null
            $category->sub_categories = json_encode([]); // start empty
            $category->save();

            // 🪄 If this is added under a parent, update parent's sub_categories JSON
            if ($category->parent_id) {
                $parent = product_category::find($category->parent_id);
                $subCategories = json_decode($parent->sub_categories, true) ?? [];
                $subCategories[] = [
                    'id' => $category->id,
                    'name' => $category->name
                ];
                $parent->sub_categories = json_encode($subCategories);
                $parent->save();
            }

            // 🪄 If request has nested subcategories, insert them recursively
            if ($request->filled('sub_categories')) {
                $subCategories = json_decode($request->input('sub_categories'), true);
                if (!is_array($subCategories)) {
                    return response()->json(['error' => 'Invalid sub_categories format'], 400);
                }
                foreach ($subCategories as $subCat) {
                    $this->createCategoryRecursive($subCat, $category->id, $category->category_type);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Category created successfully',
                'category' => $category
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    private function createCategoryRecursive(array $data, $parentId, $categoryType)
    {
        // Create this child category
        $category = new product_category();
        $category->name = $data['name'];
        $category->category_type = $categoryType;
        $category->parent_id = $parentId;
        $category->image = $data['image'] ?? null; // optional image path
        $category->sub_categories = json_encode([]); // start empty
        $category->save();

        // Update parent's sub_categories JSON
        $parent = product_category::find($parentId);
        $subCategories = json_decode($parent->sub_categories, true) ?? [];
        $subCategories[] = [
            'id' => $category->id,
            'name' => $category->name
        ];
        $parent->sub_categories = json_encode($subCategories);
        $parent->save();

        // If this child has its own children, process recursively
        if (isset($data['children']) && is_array($data['children'])) {
            foreach ($data['children'] as $child) {
                $this->createCategoryRecursive($child, $category->id, $categoryType);
            }
        }
    }




    public function showcat()
    {
        // Get all product categories
        $categories = DB::table('categories')
            ->where('category_type', 'products')
            ->orderBy('id')
            ->get();

        // Index by ID
        $categoriesById = [];
        foreach ($categories as $cat) {
            $cat->children = []; // empty array for nested children
            $categoriesById[$cat->id] = $cat;
        }

        // Build the tree
        $parentcategories = [];
        foreach ($categories as $cat) {
            if ($cat->parent_id) {
                if (isset($categoriesById[$cat->parent_id])) {
                    $categoriesById[$cat->parent_id]->children[] = $cat;
                }
            } else {
                $parentcategories[] = $cat;
            }
        }

        // Add counts and decode sub_categories if needed
        foreach ($categories as $category) {
            $category->subcategory_count = count($category->children);
            $category->product_count = DB::table('products')
                ->where('product_category', $category->id)
                ->count();
        }

        // return $parentcategories;
        // Return both to view
        return view('admin.ProductCategory', compact('parentcategories'));
    }


    public function fetchCategory($id)
    {
        $category = product_category::with('children')->find($id);

        if (!$category) {
            return response()->json([
                'status' => 'error',
                'message' => 'No record found!'
            ], 404);
        }

        // Recursive formatting function
        $formatCategory = function ($category) use (&$formatCategory) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'image' => $category->image,
                'sub_categories' => $category->children->map($formatCategory)->toArray(),
            ];
        };

        return response()->json([
            'status' => 'success',
            'data' => $formatCategory($category),
        ]);
    }
    public function deleteCategoryOrSubcategory(Request $request, $id)
    {
        try {
            $childId = $request->input('child_id');

            if ($childId) {
                // Step 1: find parent category
                $category = product_category::find($id);
                if (!$category) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Parent category not found!'
                    ], 404);
                }

                // Step 2: remove childId from parent's sub_categories json
                $subCategories = json_decode($category->sub_categories, true);
                if (!is_array($subCategories))
                    $subCategories = [];

                $filtered = array_filter($subCategories, function ($item) use ($childId) {
                    return $item['id'] != $childId;
                });
                $category->sub_categories = json_encode(array_values($filtered)); // reindex
                $category->save();

                // Step 3: delete the child and its children recursively
                $this->deleteCategoryRecursively($childId);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Subcategory and its children deleted successfully'
                ]);
            } else {
                // Delete entire parent category (and its children)
                $this->deleteCategoryRecursively($id);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Category and its children deleted successfully'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Server error: ' . $th->getMessage()
            ], 500);
        }
    }

    private function deleteCategoryRecursively($id)
    {
        $category = product_category::find($id);
        if (!$category)
            return;

        // Delete children first
        $subCategories = json_decode($category->sub_categories, true);
        if (is_array($subCategories)) {
            foreach ($subCategories as $sub) {
                $this->deleteCategoryRecursively($sub['id']);
            }
        }

        // Delete image if exists
        if ($category->image && Storage::disk('public')->exists(str_replace('storage/', '', $category->image))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $category->image));
        }

        // Finally delete the category
        $category->delete();
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
            // dd($request->all());
            // exit;
            // Validate fields; parent_id can be nullable if it's a top-level category
            $request->validate([
                'name' => 'required|string|max:255',
                'sub_categories' => 'nullable|string', // make optional if deep children aren't always updated
                'parent_id' => 'nullable|integer|exists:categories,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            ]);

            // Find category
            $category = product_category::find($id);
            if (!$category) {
                return response()->json(['error' => 'Category not found'], 404);
            }

            // Update fields
            $category->name = $request->input('name');
            $category->parent_id = $request->input('parent_id'); // can be null

            // Update image if provided
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image && Storage::disk('public')->exists(str_replace('storage/', '', $category->image))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $category->image));
                }

                $imagePath = $request->file('image')->store('categories', 'public');
                $category->image = 'storage/' . $imagePath;
            }

            // Update sub_categories if provided
            if ($request->filled('sub_categories')) {
                $subCategories = json_decode($request->input('sub_categories'), true);
                if (!is_array($subCategories)) {
                    return response()->json(['error' => 'Invalid sub_categories format'], 400);
                }
                $category->sub_categories = json_encode($subCategories);
            }

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
            ->Join('categories', 'products.product_category', '=', 'categories.id')
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


    public function getVehicleType(Request $request)
    {
        $weight = $request->weight;
        $length = $request->length;
        $width  = $request->width;

        $height = $request->height;

        // Example: match by size & weight from vehicle_types
        $vehicleTypes = DB::table('vehicle_types')
            ->where('max_weight', '>=', $weight)
            ->where('max_length', '>=', $length)
            ->where('max_width', '>=', $width)
            ->where('max_height', '>=', $height)
            ->get(['id', 'vehicle_type']);

        return response()->json($vehicleTypes);
    }

}
