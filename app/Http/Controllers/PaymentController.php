<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    /**
     * Halaman konfirmasi pembayaran
     */
    public function confirm(Order $order)
    {
        // Pastikan user hanya bisa akses ordernya sendiri
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items');

        // Cek apakah baru saja membuat pesanan (untuk popup)
        $showPopup = session('order_created', false);

        return view('payment.confirm', compact('order', 'showPopup'));
    }

    /**
     * Upload bukti pembayaran
     */
    public function uploadProof(Request $request, Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'notes' => 'nullable|string|max:500',
        ]);

        // Hapus bukti lama jika ada
        if ($order->payment_proof) {
            Storage::disk('public')->delete($order->payment_proof);
        }

        // Upload bukti baru
        $path = $request->file('payment_proof')->store('payment_proofs', 'public');

        // Update order
        $order->update([
            'payment_proof' => $path,
            'notes' => $request->notes,
            'status' => 'waiting_confirmation', // Status menunggu konfirmasi admin
        ]);

        return redirect()->route('payment.confirm', $order)
            ->with('success', 'Bukti pembayaran berhasil diupload! Menunggu konfirmasi admin.');
    }
}
