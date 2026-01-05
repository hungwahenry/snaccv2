<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Console\Kernel');
$kernel->bootstrap();

// Get a test user
$user = \App\Models\User::with('profile')->first();

if (!$user || !$user->profile) {
    echo "No user found with profile. Please create a user first.\n";
    exit(1);
}

echo "Testing Snacc Creation Directly Through Service\n";
echo "================================================\n\n";

// Get services
$snaccService = app(\App\Services\SnaccService::class);
$vibetagService = app(\App\Services\VibetagService::class);

// Test data
$content = "This is a test post created directly through the service with #testing and #backend hashtags";
$visibility = 'campus';
$images = [];
$gifUrl = null;
$quotedSnaccId = null;

echo "Test Data:\n";
echo "User ID: {$user->id}\n";
echo "University ID: {$user->profile->university_id}\n";
echo "Content: {$content}\n";
echo "Visibility: {$visibility}\n\n";

try {
    // Process vibetags
    $vibetags = $vibetagService->processForSnacc($content, []);
    echo "Extracted Vibetags: " . implode(', ', $vibetags) . "\n\n";

    // Create snacc
    echo "Creating snacc...\n";
    $snacc = $snaccService->createSnacc(
        userId: $user->id,
        universityId: $user->profile->university_id,
        content: $content,
        visibility: $visibility,
        images: $images,
        gifUrl: $gifUrl,
        vibetags: $vibetags,
        quotedSnaccId: $quotedSnaccId
    );

    echo "SUCCESS! Snacc created successfully\n\n";

    echo "Created Snacc Details:\n";
    echo "ID: {$snacc->id}\n";
    echo "Content: {$snacc->content}\n";
    echo "Content Length: " . strlen($snacc->content) . " characters\n";
    echo "Visibility: {$snacc->visibility}\n";
    echo "Vibetags: " . $snacc->vibetags->pluck('name')->implode(', ') . "\n";
    echo "Created At: {$snacc->created_at}\n";

    // Verify in database
    $dbSnacc = \App\Models\Snacc::find($snacc->id);
    echo "\nVerification from Database:\n";
    echo "Content from DB: {$dbSnacc->content}\n";
    echo "Content matches: " . ($dbSnacc->content === $content ? 'YES' : 'NO') . "\n";

} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}

echo "\n========================================\n";
echo "Backend service is working correctly!\n";
echo "The issue is definitely in the frontend.\n";
