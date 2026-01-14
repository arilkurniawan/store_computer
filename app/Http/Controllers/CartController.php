<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    /**
     * Tampilkan keranjang
     */
    public function index()
    {
        $carts = Cart::with('product.category')
            ->where('user_id', Auth::id())
            ->get();

        $subtotal = $carts->sum(fn($cart) => $cart->subtotal);

        return view('cart.index', compact('carts', 'subtotal'));
    }

    /**
     * Tambah produk ke keranjang
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Cek apakah produk available
        if (!$product->isAvailable()) {
            return back()->with('error', 'Produk tidak tersedia.');
        }

        // Cek stok
        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Stok tidak mencukupi. Tersisa ' . $product->stock . ' item.');
        }

        // Cek apakah sudah ada di cart
        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            // Update quantity
            $newQuantity = $cart->quantity + $request->quantity;

            // Cek stok untuk total quantity
            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Stok tidak mencukupi. Tersisa ' . $product->stock . ' item.');
            }

            $cart->update(['quantity' => $newQuantity]);
        } else {
            // Buat baru
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $request->quantity,
            ]);
        }

        // Cek apakah request AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'cart_count' => Auth::user()->cart_count,
            ]);
        }

        return back()->with('success');
    }

    /**
     * Update quantity
     */
    public function update(Request $request, Cart $cart)
    {
        // Pastikan cart milik user
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Cek stok
        if ($cart->product->stock < $request->quantity) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Tersisa ' . $cart->product->stock . ' item.',
                ], 422);
            }
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        $cart->update(['quantity' => $request->quantity]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'subtotal' => $cart->subtotal,
                'formatted_subtotal' => $cart->formatted_subtotal,
                'cart_count' => Auth::user()->fresh()->cart_count,
            ]);
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    /**
     * Hapus item dari keranjang
     */
    public function destroy(Cart $cart)
    {
        // Pastikan cart milik user
        if ($cart->user_id !== Auth::id()) {
            abort(403);
        }

        $cart->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produk dihapus dari keranjang.',
                'cart_count' => Auth::user()->fresh()->cart_count,
            ]);
        }

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    /**
     * Kosongkan keranjang
     */
    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Keranjang dikosongkan.',
                'cart_count' => 0,
            ]);
        }

        return back()->with('success', 'Keranjang dikosongkan.');
    }

    /**
     * Get cart count (AJAX)
     */
    public function count()
    {
        return response()->json([
            'count' => Auth::user()->cart_count,
        ]);
    }
}
