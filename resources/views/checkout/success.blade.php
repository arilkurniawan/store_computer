<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Berhasil</title>
</head>
<body>

<h1>Pesanan Berhasil</h1>

<p><strong>Kode Pesanan:</strong> {{ $order->order_code }}</p>

<hr>

@php
    $subtotalProduk = $order->total_price + $order->discount_amount;
@endphp

<p>
    Subtotal Produk:
    Rp {{ number_format($subtotalProduk) }}
</p>

<p>
    Subtotal Diskon:
    - Rp {{ number_format($order->discount_amount) }}
</p>

<hr>

<p>
    <strong>
        Total Pesanan:
        Rp {{ number_format($order->total_price) }}
    </strong>
</p>

<p>Status: {{ ucfirst($order->status) }}</p>

<br>

<a href="/products">Kembali ke Produk</a>

</body>
</html>
