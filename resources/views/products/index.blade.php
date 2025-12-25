<!DOCTYPE html>
<html>
<head>
    <title>Produk</title>
</head>
<body>

<h1>Daftar Produk</h1>

@foreach ($products as $product)
    <div>
        <p>{{ $product->name }}</p>
        <p>Rp {{ number_format($product->price) }}</p>

        <form action="{{ route('cart.add', $product) }}" method="POST">
            @csrf
            <button type="submit">Tambah ke Keranjang</button>
        </form>
    </div>
@endforeach

    @if (session('success'))
    <p style="color: green;">
        {{ session('success') }}
    </p>
@endif

</body>
</html>
