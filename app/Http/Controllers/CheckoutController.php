<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $carts = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong!');
        }

        $subtotal = $carts->sum(fn($cart) => $cart->quantity * $cart->product->price);

        return view('checkout.index', compact('carts', 'subtotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:10',
            'shipping_province' => 'required|string|max:255',
            'notes' => 'nullable|string|max:500',
            'promo_code' => 'nullable|string|max:50',
        ]);

        $carts = Cart::with('product')
            ->where('user_id', auth()->id())
            ->get();

        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong!');
        }

        $subtotal = $carts->sum(fn($cart) => $cart->quantity * $cart->product->price);

        // Cek promo - SIMPLE (sesuai database)
        $discount = 0;
        $promoId = null;

        if ($request->filled('promo_code')) {
            $promo = Promo::where('code', strtoupper($request->promo_code))
                ->where('is_active', true)
                ->first();

            if ($promo) {
                $discount = $promo->discount;
                $promoId = $promo->id;
            }
        }

        $total = max(0, $subtotal - $discount);

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => auth()->id(),
                'invoice' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(5)),
                'promo_id' => $promoId,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => 'pending',
                'shipping_name' => $request->shipping_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_postal_code' => $request->shipping_postal_code,
                'shipping_province' => $request->shipping_province,
                'notes' => $request->notes,
            ]);

            foreach ($carts as $cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'product_name' => $cart->product->name,
                    'product_price' => $cart->product->price,
                    'quantity' => $cart->quantity,
                    'subtotal' => $cart->quantity * $cart->product->price,
                ]);

                $cart->product->decrement('stock', $cart->quantity);
            }

            Cart::where('user_id', auth()->id())->delete();

            DB::commit();

            return redirect()->route('payment.confirm', $order)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal: ' . $e->getMessage())->withInput();
        }
    }
}
