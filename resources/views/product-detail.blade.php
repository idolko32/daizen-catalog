<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $product->name }} | Daizen Hardware">
    <title>{{ $product->name }} | Daizen Hardware</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="product-detail-page">
    <header class="public-header">
        <a class="brand brand-dark" href="{{ route('catalog') }}"><img src="{{ asset('images/logo (1).png') }}" alt="Daizen Hardware logo" class="brand-logo"></a>
        <div class="header-links"><a href="{{ route('catalog') }}">Catalog</a><a href="#about">About us</a><a href="tel:09663783901">Call us</a><a class="header-cta" href="mailto:orders@daizenhardware.com?subject=Enquiry%20about%20{{ urlencode($product->name) }}">Email to order <span>↗</span></a></div>
    </header>
    <main class="detail-shell">
        <a class="back-link" href="{{ route('catalog') }}">← Back to catalog</a>
        <section class="detail-layout">
            <div class="detail-image"><img src="{{ $product->image_url }}" alt="{{ $product->name }}"></div>
            <div class="detail-copy"><span class="product-category">{{ $product->category }}</span><h1>{{ $product->name }}</h1><div class="detail-price">P{{ number_format((float) $product->price, 2) }} <small>/ {{ $product->unit }}</small></div><p class="detail-lead">Quality upholstery material from Daizen Hardware.</p><dl class="detail-specs"><div><dt>SKU</dt><dd>{{ $product->sku }}</dd></div><div><dt>Stock available</dt><dd>{{ $product->stock }} units</dd></div><div><dt>Availability</dt><dd>{{ $product->stock > 0 ? 'Available to order' : 'Please confirm availability' }}</dd></div></dl><div class="order-panel"><strong>Interested in this product?</strong><p>Contact Daizen Hardware directly to confirm availability and place your order.</p><div class="order-actions"><a class="button button-teal" href="mailto:orders@daizenhardware.com?subject=Enquiry%20about%20{{ urlencode($product->name) }}">✉ Email to order</a><a href="tel:09663783901">☎ Call 09663783901</a></div><nav class="marketplace-links" aria-label="Shop online"><a href="https://www.facebook.com/profile.php?id=100071005026361" target="_blank" rel="noopener noreferrer">Facebook ↗</a><a href="https://shopee.ph/daizenhardware?categoryId=100636&amp;entryPoint=ShopByPDP&amp;itemId=49800871209" target="_blank" rel="noopener noreferrer">Shopee ↗</a><a href="https://www.lazada.com.ph/shop/daizen-hardware?dsource=share&amp;laz_share_info=2208657405_100_3000_0_2208659405_null&amp;laz_token=310cd9ae27c99a9b9d5875fbf06c9200&amp;exlaz=e_7MiEM65zW3HGip8qo24MCZFmNCmP6gU%2FnnWa525VocjCXvTKdNAtBU7L8axQDWNERCxiM%2Fsqh17lu40WY6EhSs3Nio6kf2U4D3nWASIas1Y%3D&amp;sub_aff_id=social_share&amp;sub_id2=2208657405&amp;sub_id6=CPI_EXLAZ" target="_blank" rel="noopener noreferrer">Lazada ↗</a></nav></div></div>
        </section>
    </main>
</body>
</html>