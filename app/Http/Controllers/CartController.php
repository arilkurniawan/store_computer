<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
        {
            $cart = session()->get('cart', []);

            return view('cart.index', compact('cart'));
        }

    public function add(Product $product)
        {
            $cart = session()->get('cart', []);

            if (isset($cart[$product->id])) {
                // Jika produk sudah ada → tambah qty
                $cart[$product->id]['quantity'] += 1;
                $cart[$product->id]['subtotal'] =
                    $cart[$product->id]['quantity'] * $cart[$product->id]['price'];
            } else {
                // Jika produk baru
                $cart[$product->id] = [
                    'product_id' => $product->id,
                    'name'       => $product->name,
                    'price'      => $product->price,
                    'quantity'   => 1,
                    'subtotal'   => $product->price,
                    'image'      => $product->image,
                ];
            }

            session()->put('cart', $cart);

            return redirect()->back()->with('success', 'Produk ditambahkan ke keranjang');
        }

    public function update(Request $request, Product $product)
        {
            $cart = session()->get('cart', []);

            if (isset($cart[$product->id])) {
                $quantity = max(1, (int) $request->quantity);

                $cart[$product->id]['quantity'] = $quantity;
                $cart[$product->id]['subtotal'] =
                    $quantity * $cart[$product->id]['price'];

                session()->put('cart', $cart);
            }

            return redirect()->route('cart.index')
                ->with('success', 'Keranjang diperbarui');
        }

    public function remove(Product $product)
        {
            $cart = session()->get('cart', []);

            if (isset($cart[$product->id])) {
                unset($cart[$product->id]);
                session()->put('cart', $cart);
            }

            return redirect()->route('cart.index')
                ->with('success', 'Produk dihapus dari keranjang');
        }

    public function clear()
        {
            session()->forget('cart');

            return redirect()->route('cart.index')
                ->with('success', 'Keranjang dikosongkan');
        }

}
