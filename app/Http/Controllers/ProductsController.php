<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::with('user')->get();
        return view('dashboard', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string',
            'kode_produk' => 'required|string|unique:products',
            'stok' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        Product::create([
            'user_id' => Auth::id(),
            'nama_produk' => $request->nama_produk,
            'kode_produk' => $request->kode_produk,
            'stok' => $request->stok,
            'harga' => $request->harga,
        ]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string',
            'kode_produk' => 'required|string|unique:products,kode_produk,' . $id,
            'stok' => 'required|integer',
            'harga' => 'required|numeric',
        ]);

        $product->update([
            'nama_produk' => $request->nama_produk,
            'kode_produk' => $request->kode_produk,
            'stok' => $request->stok,
            'harga' => $request->harga,
        ]);

        return redirect()->back()->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Produk berhasil dihapus!');
    }
}
