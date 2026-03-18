<?php
header('Content-Type: application/json');
require_once('../../database/db_connect.php');

$budget     = isset($_GET['budget'])     ? (int) $_GET['budget']     : 50;
$preference = isset($_GET['preference']) ? trim($_GET['preference'])  : 'unknown';
$experience = isset($_GET['experience']) ? trim($_GET['experience'])  : 'beginner';
$recipient  = isset($_GET['recipient'])  ? trim($_GET['recipient'])   : 'friend';
$occasion   = isset($_GET['occasion'])   ? trim($_GET['occasion'])    : 'birthday';

// ══════════════════════════════════════════════════════════════════════════════
//  YOUR WINE STOCK (30 wines across 6 categories):
//
//  Red Wine      → IDs: 1,2,3,4,25          (£155–£980)
//  White Wine    → IDs: 5,6,7,8,26           (£35–£62)
//  Rosé Wine     → IDs: 9,10,11,12,27        (£25–£99)
//  Sparkling     → IDs: 17,18,19,20,29       (£120–£410)
//  Dessert Wine  → IDs: 13,14,15,16,28       (£65–£12000)
//  Fortified     → IDs: 21,22,23,24,30       (£105–£476)
//
//  SMART MATCHING LOGIC:
//  Priority list of categories is built from all 4 answers.
//  Categories are tried in order — wines collected until we have 6.
// ══════════════════════════════════════════════════════════════════════════════

// ── Step 1: Base categories from wine preference ──────────────────────────────
$preferenceMap = [
    'red'       => ['Red Wine'],
    'white'     => ['White Wine'],
    'sparkling' => ['Sparkling Wine'],
    'rose'      => ['Rosé Wine'],
    'rosé'      => ['Rosé Wine'],
    'unknown'   => [],
];
$categories = $preferenceMap[$preference] ?? [];

// ── Step 2: Occasion-driven category suggestions ──────────────────────────────
// Based on your actual stock — what makes sense for each occasion
$occasionMap = [
    // Anniversary: romantic — Sparkling (Dom Pérignon, Bollinger) or bold Red
    'anniversary' => ['Sparkling Wine', 'Red Wine', 'Rosé Wine'],
    // Christmas: warming & festive — Red, Fortified Port, Sparkling
    'christmas'   => ['Red Wine', 'Fortified Wine', 'Sparkling Wine', 'Dessert Wine'],
    // Birthday: celebratory — Sparkling, Rosé, then Red
    'birthday'    => ['Sparkling Wine', 'Rosé Wine', 'Red Wine', 'White Wine'],
    // Thank you: elegant & thoughtful — White, Rosé, Dessert
    'thankyou'    => ['White Wine', 'Rosé Wine', 'Dessert Wine', 'Sparkling Wine'],
];

if ($preference === 'unknown') {
    // No preference given — let occasion decide entirely
    $categories = $occasionMap[$occasion] ?? ['Red Wine', 'White Wine', 'Sparkling Wine'];
} else {
    // Has preference — add occasion suggestions as supplementary fallbacks
    foreach (($occasionMap[$occasion] ?? []) as $cat) {
        if (!in_array($cat, $categories)) $categories[] = $cat;
    }
}

// ── Step 3: Recipient nudges ──────────────────────────────────────────────────
// Push relevant categories for each recipient type
$recipientMap = [
    // Partner: romantic — Sparkling Champagne, bold Red, Rosé
    'partner'   => ['Sparkling Wine', 'Red Wine', 'Rosé Wine'],
    // Friend: fun & approachable — Rosé (Whispering Angel), White, Sparkling
    'friend'    => ['Rosé Wine', 'White Wine', 'Sparkling Wine'],
    // Family: classic & warming — Red, White, Fortified Port at Christmas
    'family'    => ['Red Wine', 'White Wine', 'Fortified Wine'],
    // Colleague/Boss: refined & safe — White, Sparkling, Red
    'colleague' => ['White Wine', 'Sparkling Wine', 'Red Wine'],
];
foreach (($recipientMap[$recipient] ?? []) as $cat) {
    if (!in_array($cat, $categories)) $categories[] = $cat;
}

// ── Step 4: Experience adjusts price floor ────────────────────────────────────
// Your cheapest wines: Mulderbosch Rosé £25, Château Minuty £33, Pinot Grigio £35
// Your mid-range: Sauvignon Blanc £51, Chablis £62, Whispering Angel £99
// Your premium: Sparkling from £120, Ports from £105, Reds from £155
$priceFloor = 0;
if ($experience === 'intermediate') $priceFloor = 30;  // skip very cheap bottles
if ($experience === 'expert')       $priceFloor = 100; // serious bottles only

// Experts: also suggest premium categories they'd appreciate
if ($experience === 'expert') {
    foreach (['Fortified Wine', 'Dessert Wine', 'Red Wine', 'Sparkling Wine'] as $cat) {
        if (!in_array($cat, $categories)) $categories[] = $cat;
    }
}

// ── Step 5: Always end with a catch-all so we never return empty ──────────────
$categories[] = '__any__';

// ══════════════════════════════════════════════════════════════════════════════
//  QUERY ENGINE — collect up to 6 wines across priority categories
// ══════════════════════════════════════════════════════════════════════════════
$wines   = [];
$wineIds = [];

foreach ($categories as $cat) {
    if (count($wines) >= 6) break;
    $limit = 6 - count($wines);

    if ($cat === '__any__') {
        // Catch-all: any wine in budget not already shown
        if (empty($wineIds)) {
            $stmt = $conn->prepare(
                "SELECT wineId, wineName, category AS wineType, price, imageUrl, wineRegion AS region, country
                 FROM wines
                 WHERE active = TRUE AND price <= ? AND price >= ?
                 ORDER BY price DESC LIMIT ?"
            );
            $stmt->bind_param('iii', $budget, $priceFloor, $limit);
        } else {
            $ph   = implode(',', array_fill(0, count($wineIds), '?'));
            $stmt = $conn->prepare(
                "SELECT wineId, wineName, category AS wineType, price, imageUrl, wineRegion AS region, country
                 FROM wines
                 WHERE active = TRUE AND price <= ? AND price >= ?
                   AND wineId NOT IN ($ph)
                 ORDER BY price DESC LIMIT ?"
            );
            $types  = 'ii' . str_repeat('i', count($wineIds)) . 'i';
            $params = array_merge([$budget, $priceFloor], $wineIds, [$limit]);
            $stmt->bind_param($types, ...$params);
        }
    } else {
        // Category-specific query
        if (empty($wineIds)) {
            $stmt = $conn->prepare(
                "SELECT wineId, wineName, category AS wineType, price, imageUrl, wineRegion AS region, country
                 FROM wines
                 WHERE active = TRUE AND price <= ? AND price >= ? AND category = ?
                 ORDER BY price DESC LIMIT ?"
            );
            $stmt->bind_param('iisi', $budget, $priceFloor, $cat, $limit);
        } else {
            $ph   = implode(',', array_fill(0, count($wineIds), '?'));
            $stmt = $conn->prepare(
                "SELECT wineId, wineName, category AS wineType, price, imageUrl, wineRegion AS region, country
                 FROM wines
                 WHERE active = TRUE AND price <= ? AND price >= ? AND category = ?
                   AND wineId NOT IN ($ph)
                 ORDER BY price DESC LIMIT ?"
            );
            $types  = 'iis' . str_repeat('i', count($wineIds)) . 'i';
            $params = array_merge([$budget, $priceFloor, $cat], $wineIds, [$limit]);
            $stmt->bind_param($types, ...$params);
        }
    }

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        if (!in_array($row['wineId'], $wineIds)) {
            $wines[]   = $row;
            $wineIds[] = (int)$row['wineId'];
        }
    }
    $stmt->close();
}

// ── Fallback: budget too low for price floor — drop floor ─────────────────────
if (empty($wines)) {
    $stmt = $conn->prepare(
        "SELECT wineId, wineName, category AS wineType, price, imageUrl, wineRegion AS region, country
         FROM wines WHERE active = TRUE AND price <= ?
         ORDER BY price DESC LIMIT 6"
    );
    $stmt->bind_param('i', $budget);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) { $wines[] = $row; }
    $stmt->close();
}

// ── Absolute last resort — cheapest wines available ───────────────────────────
if (empty($wines)) {
    $result = $conn->query(
        "SELECT wineId, wineName, category AS wineType, price, imageUrl, wineRegion AS region, country
         FROM wines WHERE active = TRUE ORDER BY price ASC LIMIT 6"
    );
    while ($row = $result->fetch_assoc()) { $wines[] = $row; }
}

echo json_encode($wines);
?>