<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
</head>
<body>

<h1>Checkout</h1>

{{-- ================= PROMO FORM ================= --}}
<h3>Gunakan Kode Promo</h3>

@if (session('promo_error'))
    <p style="color:red">{{ session('promo_error') }}</p>
@endif

@if (session('promo_success'))
    <p style="color:green">{{ session('promo_success') }}</p>
@endif

<form action="{{ route('checkout.applyPromo') }}" method="POST">
    @csrf
    <input type="text" name="promo_code" placeholder="Masukkan kode promo">
    <button type="submit">Gunakan</button>
</form>

<hr>

{{-- ================= CHECKOUT FORM ================= --}}

<form action="{{ route('checkout.process') }}" method="POST">
    @csrf

    <p>
        Nama:<br>
        <input type="text" name="name" required>
    </p>

    <p>
        No HP:<br>
        <input type="text" name="phone" required>
    </p>

    <p>
        Alamat:<br>
        <textarea name="address" required></textarea>
    </p>

    <p>
        Kota:<br>
        <input type="text" name="city" required>
    </p>

    <p>
        Kode Pos:<br>
        <input type="text" name="post_code" required>
    </p>

    <h3>Ringkasan</h3>

<p>Total: Rp {{ number_format(collect(session('cart'))->sum('subtotal')) }}</p>

@if (session('promo'))
    <p>Diskon ({{ session('promo.code') }}):
        - Rp {{ number_format(session('promo.discount')) }}
    </p>

    <p><strong>
        Total Bayar:
        Rp {{ number_format(
            collect(session('cart'))->sum('subtotal') - session('promo.discount')
        ) }}
    </strong></p>
@endif


    <button type="submit">Proses Pesanan</button>
</form>

</body>
</html>
