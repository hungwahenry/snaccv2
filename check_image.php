<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

use App\Models\SnaccImage;
use Illuminate\Support\Facades\Storage;

echo "Checking Image Storage...\n";
echo "========================\n\n";

$image = SnaccImage::first();

if ($image) {
    echo "Image ID: {$image->id}\n";
    echo "Path in DB: {$image->path}\n";
    echo "Storage URL: " . Storage::url($image->path) . "\n";
    echo "Public disk exists: " . (Storage::disk('public')->exists($image->path) ? 'YES' : 'NO') . "\n";
    echo "Full file path: " . Storage::disk('public')->path($image->path) . "\n";
    echo "\nDefault disk: " . config('filesystems.default') . "\n";
    echo "Public disk root: " . config('filesystems.disks.public.root') . "\n";

    // Check if symlink exists
    echo "\nSymlink check:\n";
    $symlinkPath = public_path('storage');
    echo "Symlink path: {$symlinkPath}\n";
    echo "Symlink exists: " . (is_link($symlinkPath) || is_dir($symlinkPath) ? 'YES' : 'NO') . "\n";

    if (is_link($symlinkPath)) {
        echo "Symlink target: " . readlink($symlinkPath) . "\n";
    }
} else {
    echo "No images found in database\n";
}
