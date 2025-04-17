<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $products = Item::with('category')->latest()->get();
        return view('product', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'item_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        $imagePath = $request->file('item_image')->store('items', 'public');

        Item::create([
            'name' => $request->name,
            'item_image' => $imagePath,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
        ]);

        return redirect()->back()->with('status', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'item_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($request->hasFile('item_image')) {
            if ($item->item_image) {
                Storage::disk('public')->delete($item->item_image);
            }
            $imagePath = $request->file('item_image')->store('items', 'public');
        } else {
            $imagePath = $item->item_image;
        }

        $item->update([
            'name' => $request->name,
            'item_image' => $imagePath,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
        ]);

        return redirect()->back()->with('status', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        if ($item->item_image) {
            Storage::disk('public')->delete($item->item_image);
        }
        $item->delete();

        return redirect()->route('products.index')->with('status', 'Produk berhasil dihapus.');
    }
}