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
        
        <form action="{{ route('cart.store') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn btn-primary">
                Tambah ke Keranjang
            </button>
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
