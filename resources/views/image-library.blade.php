<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Daizen Hardware image library">
    <title>Image library | Daizen Hardware</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="image-library-page">
    <main class="image-library">
        <header class="library-header">
            <div>
                <span class="eyebrow">Daizen hardware</span>
                <h1>Image library</h1>
                <p>{{ $images->count() }} images available from <strong>images</strong></p>
            </div>
            <a class="outline-button" href="{{ route('catalog') }}">← Back to catalog</a>
        </header>
        <div class="library-toolbar">
            <label class="search"><span>⌕</span><input id="image-search" type="search" placeholder="Search image names..." autocomplete="off"></label>
            <span id="image-count" class="library-count">{{ $images->count() }} images</span>
        </div>
        <section class="image-grid" id="image-grid" aria-label="Image library">
            @foreach($images as $image)
                <figure class="image-tile" data-name="{{ strtolower($image['name']) }}">
                    <a href="{{ $image['url'] }}" target="_blank" rel="noreferrer"><img src="{{ $image['url'] }}" alt="{{ $image['name'] }}" loading="lazy"></a>
                    <figcaption><span>{{ $image['type'] }}</span>{{ $image['name'] }}</figcaption>
                </figure>
            @endforeach
        </section>
        <p class="library-empty" id="image-empty" hidden>No images match that search.</p>
        @if($unsupported->isNotEmpty())
            <aside class="unsupported-files"><strong>Not previewed in browser:</strong> {{ $unsupported->implode(', ') }}</aside>
        @endif
    </main>
    <script>
        const imageSearch = document.querySelector('#image-search');
        const imageTiles = [...document.querySelectorAll('.image-tile')];
        const imageCount = document.querySelector('#image-count');
        const imageEmpty = document.querySelector('#image-empty');
        imageSearch?.addEventListener('input', () => {
            const query = imageSearch.value.trim().toLowerCase();
            const visible = imageTiles.filter((tile) => {
                tile.hidden = !tile.dataset.name.includes(query);
                return !tile.hidden;
            });
            imageCount.textContent = `${visible.length} images`;
            imageEmpty.hidden = visible.length > 0;
        });
    </script>
</body>
</html>