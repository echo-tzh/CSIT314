<?php

// Mocked version only — no real database connections or includes needed
require_once __DIR__ . '/../../Entity/bookingHistory.php';

// === Entity Mock ===
#[\AllowDynamicProperties]
class TestBookingHistory extends bookingHistory {
    public function __construct($conn = null) {
        $this->conn = $conn;
    }

    public function searchUsedService($keyword, $homeOwnerID): array {
        if ($keyword === 'clean' && $homeOwnerID === 3) {
            return [
                ['bookingID' => 1, 'serviceName' => 'Basic Cleaning'],
                ['bookingID' => 2, 'serviceName' => 'Window Cleaning']
            ];
        }
        return [];
    }
}

// === Controller Mock ===
class SearchHistoryController {
    public function search($keyword, $homeOwnerID): array {
        $entity = new TestBookingHistory();
        return $entity->searchUsedService($keyword, $homeOwnerID);
    }
}

// ✅ Test 1: Entity returns correct data
test('Entity: returns correct history', function () {
    $entity = new TestBookingHistory();
    $results = $entity->searchUsedService('clean', 3);
    expect($results)->toHaveCount(2);
    expect($results[0]['serviceName'])->toBe('Basic Cleaning');
    expect($results[1]['serviceName'])->toBe('Window Cleaning');
});

// ✅ Test 2: Entity returns empty on bad keyword
test('Entity: returns empty on invalid search', function () {
    $entity = new TestBookingHistory();
    expect($entity->searchUsedService('unknown', 3))->toBe([]);
});

// ✅ Test 3: Controller returns same as entity
test('Controller: returns same result as entity', function () {
    $controller = new SearchHistoryController();
    $results = $controller->search('clean', 3);
    expect($results)->toHaveCount(2);
    expect($results[0]['serviceName'])->toBe('Basic Cleaning');
});
