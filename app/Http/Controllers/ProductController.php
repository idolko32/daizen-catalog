<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'products' => Product::query()->where('is_active', true)->latest()->get(),
            'isAdmin' => false,
        ]);
    }

    public function adminIndex(): View
    {
        return view('home', [
            'products' => Product::query()->latest()->get(),
            'isAdmin' => true,
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        return view('product-detail', compact('product'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'category' => ['required', 'string', 'max:80'],
            'sku' => ['required', 'string', 'max:60', 'unique:products,sku'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'unit' => ['required', 'string', 'max:40'],
            'stock' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,gif', 'max:5120'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('products', 'public');
        }
        unset($validated['image']);

        Product::create($validated);

        return redirect()->route('admin.products')->with('status', 'Product added successfully.');
    }

    public function importImageLibrary(): RedirectResponse
    {
        $supported = ['jpg', 'jpeg', 'png', 'webp', 'jfif', 'gif'];
        $created = 0;

        foreach (File::files(base_path('images')) as $file) {
            if (!in_array(strtolower($file->getExtension()), $supported, true)) {
                continue;
            }

            $filename = $file->getFilename();
            $sku = 'IMG-' . strtoupper(substr(sha1($filename), 0, 10));
            $product = Product::firstOrCreate(['sku' => $sku], [
                'name' => $this->nameFromFilename($filename),
                'category' => $this->categoryFromFilename($filename),
                'price' => 0,
                'unit' => 'piece',
                'stock' => 0,
                'image_path' => $filename,
                'is_active' => true,
            ]);

            $created += (int) $product->wasRecentlyCreated;
        }

        return redirect()->route('admin.products')->with('status', "$created image products imported. Existing images were skipped.");
    }

    private function nameFromFilename(string $filename): string
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $name = preg_replace('/\s*\(1\)$/i', '', $name);
        $name = preg_replace('/[_-]+/', ' ', $name);
        return trim(ucwords($name ?: 'Imported product'));
    }

    private function categoryFromFilename(string $filename): string
    {
        $name = strtolower($filename);
        foreach ([
            'pvc' => 'PVC piping', 'spring' => 'Springs', 'leg' => 'Sofa legs',
            'sofa' => 'Sofa hardware', 'strap' => 'Straps and webbing',
            'foam' => 'Foam', 'fabric' => 'Upholstery fabric', 'leather' => 'Leather',
        ] as $keyword => $category) {
            if (str_contains($name, $keyword)) {
                return $category;
            }
        }

        return 'Imported catalog item';
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->image_path && str_starts_with($product->image_path, 'products/')) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('admin.products')->with('status', 'Product removed.');
    }
}
