<?php

namespace App\Http\Controllers;

use App\Models\product_category;
use App\Models\Store;
use App\Models\Seller;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{


    public function index()
    {
        return view('pages.products');
    }

    public function storeProduct(Request $request)
    {
        try {
            $user = Auth::user();

            // Find the seller record for the authenticated user
            $seller = Seller::where('user_id', $user->user_id)->first();
            if (!$seller) {
                return response()->json(['error' => 'Seller record not found'], 404);
            }

            // Fetch store_id based on seller_id
            $store = Store::where('seller_id', $seller->seller_id)->first();
            if (!$store) {
                return response()->json(['error' => 'Store record not found'], 404);
            }

            // Validate request data
            $validatedData = $request->validate([
                'product_name'            => 'required|string|max:255',
                'product_description'     => 'nullable|string',
                'product_brand'           => 'required|string|max:255',
                'product_category'        => 'required|string|max:255',
                'product_stock'           => 'required|integer|min:0',
                'purchase_price'          => 'required|numeric|min:0',
                'product_price'           => 'required|numeric|min:0',
                'product_discount'        => 'nullable|numeric|min:0',
                'product_discounted_price' => 'nullable|numeric|min:0',
                'product_images'          => 'nullable|array', // Array of images
                'product_images.*'        => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'product_attributes'      => 'nullable|array',
                'product_variation'       => 'nullable|array',
                'product_status'          => 'nullable|integer|in:0,1',
            ]);

            // Handle image upload
            $imagePaths = [];
            if ($request->hasFile('product_images')) {
                foreach ($request->file('product_images') as $image) {
                    $path = $image->store('products', 'public'); // Save in storage/app/public/products
                    $imagePaths[] = "storage/{$path}"; // Store relative path
                }
            }

            // Prepare data for insertion
            $productData = [
                'user_id'                  => $user->user_id,
                'store_id'                 => $store->store_id,
                'product_name'             => $validatedData['product_name'],
                'product_description'      => $validatedData['product_description'] ?? null,
                'product_brand'            => $validatedData['product_brand'],
                'product_category'         => $validatedData['product_category'],
                'product_stock'            => $validatedData['product_stock'],
                'purchase_price'           => $validatedData['purchase_price'],
                'product_price'            => $validatedData['product_price'],
                'product_discount'         => $validatedData['product_discount'] ?? 0,
                'product_discounted_price' => $validatedData['product_discounted_price'] ?? 0,
                'product_images'           => json_encode($imagePaths), // Store image paths as JSON
                'product_attributes'       => json_encode($validatedData['product_attributes'] ?? []),
                'product_variation'        => json_encode($validatedData['product_variation'] ?? []),
                'product_status'           => $validatedData['product_status'] ?? 0, // Default to inactive (0)
            ];

            // Insert product data into the database
            $product = Products::create($productData);

            return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function categories(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'sub_categories' => 'required|string', // It comes as a JSON string
            ]);
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
            }

            // Convert JSON string to array
            $subCategories = json_decode($request->input('sub_categories'));
            if (!is_array($subCategories)) {
                return response()->json(['error' => 'Invalid sub_categories format'], 400);
            }
            $category = new product_category();
            $category->name = $request->input('name');
            $category->image = $imagePath;
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

}
