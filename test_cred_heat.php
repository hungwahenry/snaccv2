<?php

use App\Models\User;
use App\Models\Snacc;
use App\Models\SnaccLike;
use App\Models\ScoringRule;

echo "=== CRED & HEAT SYSTEM TEST ===\n\n";

// Test 1: Check Scoring Rules
echo "1. Scoring Rules Check:\n";
echo "   - Post Created Cred: " . ScoringRule::getValue('cred.action.post_created') . "\n";
echo "   - Like Received Cred: " . ScoringRule::getValue('cred.action.like_received') . "\n";
echo "   - Heat Like Weight: " . ScoringRule::getValue('heat.weight.like') . "\n";
echo "   - Daily Cap: " . ScoringRule::getValue('cred.limit.daily_cap') . "\n\n";

// Test 2: Get test users with profiles
$user1 = User::whereHas('profile')->first();
$user2 = User::whereHas('profile')->where('id', '!=', optional($user1)->id)->first();

if (!$user1 || !$user2) {
    echo "ERROR: Need at least 2 users with completed profiles.\n";
    echo "Please complete onboarding for at least 2 users first.\n";
    exit(1);
}

echo "2. Test Users:\n";
echo "   - User 1: {$user1->name} (ID: {$user1->id}, Cred: {$user1->cred_score})\n";
echo "   - User 2: {$user2->name} (ID: {$user2->id}, Cred: {$user2->cred_score})\n";
echo "   - University: {$user1->profile->university->name}\n\n";

// Test 3: Create a test post (triggers SnaccObserver)
echo "3. Creating test post by User 1...\n";
$initialCred = $user1->cred_score;

$snacc = Snacc::create([
    'user_id' => $user1->id,
    'university_id' => $user1->profile->university_id,
    'content' => 'Test post for Cred/Heat system - ' . now(),
    'visibility' => 'campus',
    'slug' => \Illuminate\Support\Str::ulid(),
]);

echo "   - Post created (ID: {$snacc->id})\n";
echo "   - Waiting for Observer + Queue to process...\n";
sleep(3);

$user1->refresh();
$snacc->refresh();

$credGained = $user1->cred_score - $initialCred;
echo "   - User 1 Cred gained: +{$credGained} (Expected: +1)\n";
echo "   - Post Heat Score: {$snacc->heat_score}\n\n";

// Test 4: User 2 likes the post (triggers SnaccLikeObserver)
echo "4. User 2 likes the post...\n";
$user1InitialCred = $user1->cred_score;

$like = SnaccLike::create([
    'user_id' => $user2->id,
    'snacc_id' => $snacc->id,
]);

echo "   - Like created\n";
echo "   - Waiting for Observer + Queue...\n";
sleep(3);

$user1->refresh();
$snacc->refresh();

$credGained = $user1->cred_score - $user1InitialCred;
echo "   - User 1 Cred gained: +{$credGained} (Expected: +2 for like_received)\n";
echo "   - Post likes_count: {$snacc->likes_count}\n";
echo "   - Post Heat Score: {$snacc->heat_score} (Should be > 0)\n\n";

// Test 5: Check Cred Transactions
echo "5. Recent Cred Transactions for User 1:\n";
$transactions = \App\Models\CredTransaction::where('user_id', $user1->id)
    ->latest()
    ->take(5)
    ->get();

if ($transactions->isEmpty()) {
    echo "   - No transactions found (this might indicate an issue)\n";
} else {
    foreach ($transactions as $tx) {
        echo "   - {$tx->action}: {$tx->amount} cred ({$tx->description})\n";
    }
}

echo "\n=== TEST COMPLETE ===\n";
echo "\nCheck your queue:work terminal to see the UpdateHeatScore jobs processing!\n";
