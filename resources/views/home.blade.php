@php($isAdmin = $isAdmin ?? false)
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Daizen Hardware upholstery materials catalog">
    <title>{{ $isAdmin ? 'Products | Daizen Hardware' : 'Daizen Hardware | Upholstery materials' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="{{ $isAdmin ? 'is-admin' : 'is-catalog' }}">
    @if($isAdmin)
        <aside class="sidebar" aria-label="Admin navigation">
            <a class="brand" href="{{ route('admin.products') }}" aria-label="Daizen Hardware admin home">
                <span class="brand-mark">D</span><span>DAIZEN <small>HARDWARE</small></span>
            </a>
            <nav class="side-nav">
                <a href="#"><span class="nav-icon">⌂</span> Dashboard</a>
                <a class="active" href="{{ route('admin.products') }}"><span class="nav-icon">▦</span> Products</a>
                <a href="#"><span class="nav-icon">↗</span> Public catalog</a>
                <a href="#"><span class="nav-icon">♧</span> Customers</a>
                <a href="#"><span class="nav-icon">◈</span> Projects</a>
                <a href="#"><span class="nav-icon">⌑</span> Shopping lists</a>
                <a href="#"><span class="nav-icon">▤</span> Purchase orders</a>
                <a href="#"><span class="nav-icon">◇</span> Categories</a>
                <a href="#"><span class="nav-icon">♙</span> Users</a>
                <a href="#"><span class="nav-icon">⚙</span> Settings</a>
            </nav>
            <div class="side-footer"><a href="#">◉ &nbsp; Profile</a><a href="#">↪ &nbsp; Logout</a></div>
        </aside>
    @endif
    <main class="page-shell">
        @if(!$isAdmin)
            <header class="public-header">
                <a class="brand brand-dark" href="{{ route('catalog') }}"><span class="brand-mark">D</span><span>DAIZEN <small>HARDWARE</small></span></a>
                <div class="header-links"><a href="#catalog">Catalog</a><a href="tel:09663783901">☎ 09663783901</a><a href="https://www.facebook.com/profile.php?id=100071005026361" target="_blank" rel="noopener noreferrer">Facebook ↗</a><a href="https://shopee.ph/daizenhardware?categoryId=100636&amp;entryPoint=ShopByPDP&amp;itemId=49800871209" target="_blank" rel="noopener noreferrer">Shopee ↗</a><a href="https://www.lazada.com.ph/shop/daizen-hardware" target="_blank" rel="noopener noreferrer">Lazada ↗</a></div>
            </header>
        @endif
        <div class="content">
            @if($isAdmin)
                <div class="admin-topbar"><div><span class="eyebrow">Inventory</span><h1>Products</h1></div><div class="admin-actions"><form action="{{ route('admin.products.import-images') }}" method="POST">@csrf<button class="outline-button" type="submit">Import image library</button></form><a class="button button-primary" href="#new-product"><span>＋</span> New product</a></div></div>
                @if(session('status'))<div class="flash-message">{{ session('status') }}</div>@endif
                @if($errors->any())<div class="flash-message flash-error">Please correct the highlighted fields and try again.</div>@endif
                <div class="product-modal" id="new-product">
                    <div class="modal-card"><a class="modal-close" href="#" aria-label="Close new product form">×</a><span class="eyebrow">Inventory</span><h2>Add new product</h2><p class="modal-intro">Add a product to the live catalog with a clear image and stock details.</p>
                        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="product-form">
                            @csrf
                            <label>Product name<input name="name" type="text" value="{{ old('name') }}" required maxlength="160" placeholder="e.g. Gold reflective upholstery leather">@error('name')<small>{{ $message }}</small>@enderror</label>
                            <div class="form-row"><label>Category<input name="category" type="text" value="{{ old('category') }}" required maxlength="80" placeholder="e.g. Upholstery fabric">@error('category')<small>{{ $message }}</small>@enderror</label><label>SKU<input name="sku" type="text" value="{{ old('sku') }}" required maxlength="60" placeholder="DAI-FAB-005">@error('sku')<small>{{ $message }}</small>@enderror</label></div>
                            <div class="form-row"><label>Price<input name="price" type="number" value="{{ old('price') }}" required min="0" step="0.01" placeholder="100.00">@error('price')<small>{{ $message }}</small>@enderror</label><label>Unit<input name="unit" type="text" value="{{ old('unit', 'piece') }}" required maxlength="40" placeholder="metre, piece, set"></label><label>Stock<input name="stock" type="number" value="{{ old('stock', 0) }}" required min="0" step="1"></label></div>
                            <label>Product image <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp,.gif"><small>Optional. JPG, PNG, WEBP or GIF up to 5 MB.</small>@error('image')<small>{{ $message }}</small>@enderror</label>
                            <div class="form-actions"><a class="outline-button" href="#">Cancel</a><button class="button button-primary" type="submit">Save product</button></div>
                        </form>
                    </div>
                </div>
            @endif
            <section class="hero" aria-labelledby="hero-title">
                <div class="hero-copy"><span class="eyebrow">{{ $isAdmin ? 'Daizen upholstery marketplace' : 'Daizen hardware' }}</span><h2 id="hero-title">{{ $isAdmin ? 'Materials that make every piece feel finished.' : 'Upholstery materials for better finished pieces.' }}</h2><p>{{ $isAdmin ? 'Browse reflective leather, sofa mechanisms, PVC piping, foam, fabrics, and other upholstery essentials in one focused catalog.' : 'Browse our current collection of reflective leather, fabrics, sofa mechanisms, PVC piping, foam, and upholstery hardware.' }}</p></div><div class="hero-stat"><span class="cube">◇</span><strong>{{ count($products) }}</strong><span>available products</span></div>
            </section>
            <section class="catalog" id="catalog">
                <div class="toolbar"><label class="search"><span>⌕</span><input id="product-search" type="search" placeholder="Search products, SKUs, or brands..." autocomplete="off"></label><select id="category-filter"><option value="all">All categories</option>@foreach($products->pluck('category')->unique() as $category)<option value="{{ strtolower($category) }}">{{ $category }}</option>@endforeach</select><select id="sort-products"><option value="recent">Recently added</option><option value="price-low">Price: low to high</option><option value="stock">Stock available</option></select><button class="button button-teal" id="filter-button" type="button">☷ <span>Filter</span></button></div>
                <div class="catalog-heading"><div><strong id="result-count">{{ count($products) }} products</strong><span> · Updated just now</span></div>@if(!$isAdmin)<a href="mailto:orders@daizenhardware.com">Contact us directly to purchase <span>↗</span></a>@endif</div>
                <div class="product-grid" id="product-grid">
                    @foreach($products as $product)
                        <article class="product-card" data-name="{{ strtolower($product->name) }}" data-category="{{ strtolower($product->category) }}" data-price="{{ $product->price }}" data-stock="{{ $product->stock }}">
                            <a class="product-image" href="{{ $isAdmin ? '#product-' . $product->id : route('products.show', $product) }}"><img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy"><span class="image-label">{{ $isAdmin ? 'LIVE' : 'DAIZEN' }}</span></a>
                            <div class="product-info"><span class="product-category">{{ $product->category }}</span><h3>{{ $product->name }}</h3><p class="sku">{{ $product->sku }}</p><div class="product-rule"></div><div class="product-meta"><strong>P{{ number_format((float) $product->price, 2) }} <small>/ {{ $product->unit }}</small></strong><span class="stock">▧ {{ $product->stock }} in stock</span></div><div class="card-actions">@if($isAdmin)<a class="text-button" href="#edit-{{ $product->id }}">♢ Edit</a><form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Remove this product?')">@csrf @method('DELETE')<button class="text-button danger-button" type="submit">Delete</button></form><a class="outline-button" href="#details-{{ $product->id }}">View details ↗</a>@else<a class="outline-button full" href="{{ route('products.show', $product) }}">View product&nbsp; →</a>@endif</div></div>
                        </article>
                    @endforeach
                </div>
                <div class="empty-state" id="empty-state" hidden>No products match your search.</div>
            </section>
        </div>
        <footer class="site-footer"><span>© {{ date('Y') }} Daizen Hardware</span><span>Quality materials for considered upholstery.</span><nav class="contact-links" aria-label="Contact Daizen Hardware"><a href="tel:09663783901">☎ 09663783901</a><a href="https://www.facebook.com/profile.php?id=100071005026361" target="_blank" rel="noopener noreferrer">Facebook ↗</a><a href="https://shopee.ph/daizenhardware?categoryId=100636&amp;entryPoint=ShopByPDP&amp;itemId=49800871209" target="_blank" rel="noopener noreferrer">Shopee ↗</a><a href="https://www.lazada.com.ph/shop/daizen-hardware?dsource=share&amp;laz_share_info=2208657405_100_3000_0_2208659405_null&amp;laz_token=310cd9ae27c99a9b9d5875fbf06c9200&amp;exlaz=e_7MiEM65zW3HGip8qo24MCZFmNCmP6gU%2FnnWa525VocjCXvTKdNAtBU7L8axQDWNERCxiM%2Fsqh17lu40WY6EhSs3Nio6kf2U4D3nWASIas1Y%3D&amp;sub_aff_id=social_share&amp;sub_id2=2208657405&amp;sub_id6=CPI_EXLAZ" target="_blank" rel="noopener noreferrer">Lazada ↗</a></nav></footer>
    </main>
</body>
</html>
