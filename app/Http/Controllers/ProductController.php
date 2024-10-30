<?php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        return Product::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $data = $request->all();
    
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
            $data['image'] = $imagePath;
        }
    
        $product = Product::create($data);
        return response()->json($product, 201);
    }
    

    public function show($id)
    {
        return Product::findOrFail($id);
    }

    
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'quantity' => 'required|integer',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $product = Product::findOrFail($id);
    $data = $request->all();

    if ($request->hasFile('image')) {
        // Delete the old image if it exists
        if ($product->image && Storage::exists('public/' . $product->image)) {
            Storage::delete('public/' . $product->image);
        }

        $imagePath = $request->file('image')->store('product_images', 'public');
        $data['image'] = $imagePath;
    }

    $product->update($data);
    return response()->json($product, 200);
}
    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(null, 204);
    }
}
