<?php

echo "=== OBSERVER DIAGNOSTIC ===\n\n";

// Test if SnaccObserver::creating fires
echo "1. Testing SnaccObserver::creating (slug generation)...\n";

$snacc = new \App\Models\Snacc();
$snacc->user_id = 3;
$snacc->university_id = 55;
$snacc->content = 'Test';
$snacc->visibility = 'campus';

// Don't set slug - let observer handle it
echo "   - Slug before save: " . ($snacc->slug ?? 'NULL') . "\n";

try {
    $snacc->save();
    echo "   - Slug after save: " . $snacc->slug . "\n";
    echo "   - ✅ SnaccObserver::creating FIRED!\n\n";
    
    // Clean up
    $snacc->delete();
} catch (\Exception $e) {
    echo "   - ❌ ERROR: " . $e->getMessage() . "\n";
    echo "   - SnaccObserver::creating DID NOT FIRE\n\n";
}

// Check if observers are registered
echo "2. Checking observer registration...\n";
$observers = \Illuminate\Support\Facades\Event::getListeners('eloquent.creating: App\Models\Snacc');
echo "   - Registered observers for Snacc::creating: " . count($observers) . "\n";

if (count($observers) > 0) {
    echo "   - ✅ Observers are registered\n";
} else {
    echo "   - ❌ NO observers registered!\n";
    echo "   - Did you restart the server after updating AppServiceProvider?\n";
}

echo "\n=== DIAGNOSTIC COMPLETE ===\n";
