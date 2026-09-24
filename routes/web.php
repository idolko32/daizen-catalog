<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('catalog');
});

Route::get('/products', [ProductController::class, 'index'])->name('catalog');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/admin/products', [ProductController::class, 'adminIndex'])->name('admin.products');
Route::post('/admin/products', [ProductController::class, 'store'])->name('admin.products.store');
Route::post('/admin/products/import-images', [ProductController::class, 'importImageLibrary'])->name('admin.products.import-images');
Route::delete('/admin/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

Route::get('/home', fn () => redirect()->route('catalog'))->name('home');

Route::get('/image-library', function () {
    $files = collect(File::files(base_path('images')));
    $supported = ['jpg', 'jpeg', 'png', 'webp', 'jfif', 'gif'];
    $images = $files
        ->filter(fn ($file) => in_array(strtolower($file->getExtension()), $supported, true))
        ->sortBy(fn ($file) => strtolower($file->getFilename()))
        ->map(fn ($file) => [
            'name' => $file->getFilename(),
            'type' => strtoupper($file->getExtension()),
            'url' => route('image.file', ['filename' => $file->getFilename()]),
        ])->values();

    $unsupported = $files
        ->reject(fn ($file) => in_array(strtolower($file->getExtension()), $supported, true))
        ->map(fn ($file) => $file->getFilename())->values();

    return view('image-library', compact('images', 'unsupported'));
})->name('image.library');

Route::get('/image-library/file/{filename}', function (string $filename) {
    $requested = basename($filename);
    $file = collect(File::files(base_path('images')))
        ->first(fn ($candidate) => $candidate->getFilename() === $requested);

    abort_unless($file, 404);
    return response()->file($file->getRealPath(), ['Cache-Control' => 'public, max-age=86400']);
})->where('filename', '.*')->name('image.file');
