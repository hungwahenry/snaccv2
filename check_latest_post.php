<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get the most recent post
$snacc = \App\Models\Snacc::with(['user.profile', 'images', 'vibetags'])
    ->latest()
    ->first();

if ($snacc) {
    echo "Latest Post:\n";
    echo "============\n";
    echo "ID: {$snacc->id}\n";
    echo "Content: '" . ($snacc->content ?? '[NULL]') . "'\n";
    echo "Content Length: " . strlen($snacc->content ?? '') . " characters\n";
    echo "GIF URL: " . ($snacc->gif_url ?? '[NULL]') . "\n";
    echo "Visibility: {$snacc->visibility}\n";
    echo "Images: " . $snacc->images->count() . "\n";
    echo "Vibetags: " . $snacc->vibetags->pluck('name')->implode(', ') . "\n";
    echo "Created: {$snacc->created_at}\n\n";

    // Check if it contains "it should work now"
    if ($snacc->content && str_contains(strtolower($snacc->content), 'it should work now')) {
        echo "✓ SUCCESS! Found the post with 'it should work now'\n";
    } else {
        echo "Content received: " . var_export($snacc->content, true) . "\n";
    }
} else {
    echo "No posts found\n";
}

// Show last 3 posts
echo "\n\nLast 3 Posts:\n";
echo "=============\n";
$recent = \App\Models\Snacc::orderBy('created_at', 'desc')->limit(3)->get();
foreach ($recent as $s) {
    echo "ID {$s->id}: '" . ($s->content ?? '[NULL]') . "' (Created: {$s->created_at})\n";
}
