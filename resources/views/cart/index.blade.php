<!DOCTYPE html>
<html>
<head>
    <title>Keranjang Belanja</title>
</head>
<body>

<h1>Keranjang Belanja</h1>

{{-- Flash message --}}
@if (session('success'))
    <p style="color: green;">
        {{ session('success') }}
    </p>
@endif

@if (empty($cart))
    <p>Keranjang masih kosong.</p>
    <a href="/products">Kembali ke Produk</a>
@else
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp

            @foreach ($cart as $item)
                @php $total += $item['subtotal']; @endphp
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>Rp {{ number_format($item['price']) }}</td>
                    <td>
                        <form action="{{ route('cart.update', $item['product_id']) }}" method="POST">
                            @csrf
                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1">
                            <button type="submit">Update</button>
                        </form>
                    </td>
                    <td>Rp {{ number_format($item['subtotal']) }}</td>
                    <td>
                        <form action="{{ route('cart.remove', $item['product_id']) }}" method="POST">
                            @csrf
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach

            <tr>
                <td colspan="3"><strong>Total</strong></td>
                <td colspan="2">
                    <strong>Rp {{ number_format($total) }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <br>

    <a href="/products">← Lanjut Belanja</a>
@endif

<br>
<a href="{{ route('checkout.index') }}">
    <button>Checkout</button>
</a>

</body>
</html>
