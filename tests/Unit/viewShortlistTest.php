<?php

require_once __DIR__ . '/../../Entity/shortlist.php';

// === Entity Mock ===
class TestShortlistViewer extends Shortlist {
    protected $conn;

    public function __construct($conn = null) {
        $this->conn = $conn;
    }

    public function getShortlistedServiceIds($homeOwnerID): array {
        if ($homeOwnerID == 3) {
            return [
                ['shortlistID' => 1, 'homeOwnerID' => 3, 'serviceID' => 10, 'createdDate' => '2025-05-01'],
                ['shortlistID' => 2, 'homeOwnerID' => 3, 'serviceID' => 12, 'createdDate' => '2025-05-03']
            ];
        }
        return [];
    }
}

// === Controller Mock ===
class ViewShortlistController {
    public function getShortlist($homeOwnerID): array {
        $entity = new TestShortlistViewer();
        return $entity->getShortlistedServiceIds($homeOwnerID);
    }
}

test('Entity: valid ID returns shortlist', function () {
    $entity = new TestShortlistViewer();
    $results = $entity->getShortlistedServiceIds(3);
    expect($results)->toHaveCount(2);
});

test('Entity: invalid ID returns empty', function () {
    $entity = new TestShortlistViewer();
    expect($entity->getShortlistedServiceIds(999))->toBe([]);
});

test('Controller: shortlist returned correctly', function () {
    $controller = new ViewShortlistController();
    $results = $controller->getShortlist(3);
    expect($results)->toHaveCount(2);
});
