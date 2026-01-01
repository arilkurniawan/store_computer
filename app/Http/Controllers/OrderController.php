<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    // ❌ HAPUS CONSTRUCTOR INI!
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Daftar pesanan user
     */
    public function index()
    {
        $orders = Order::with('items')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Detail pesanan
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items');

        return view('payment.confirm', compact('order'));
    }

    /**
     * Batalkan pesanan
     */
    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'waiting_confirmation'])) {
            return back()->with('error', 'Pesanan ini tidak dapat dibatalkan.');
        }

        // Kembalikan stok
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        $order->update(['status' => 'cancelled']);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Konfirmasi pesanan diterima
     */
    public function confirmReceived(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'shipped') {
            return back()->with('error', 'Pesanan belum dikirim.');
        }

        $order->update(['status' => 'completed']);

        return back()->with('success', 'Pesanan telah selesai. Terima kasih! 🎉');
    }
}
