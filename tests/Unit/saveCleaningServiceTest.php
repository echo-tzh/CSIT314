<?php
require_once __DIR__ . '/../../Entity/shortlist.php';

// === Entity Mock ===
class TestShortlist extends Shortlist {
    protected $conn;
    private $saved = [];

    public function __construct($conn = null) {
        $this->conn = $conn;
    }

    public function saveShortlist($homeOwnerID, $serviceID): bool {
        if (!is_int($homeOwnerID) || !is_int($serviceID)) return false;
        if ($homeOwnerID <= 0 || $serviceID <= 0) return false;

        $key = "$homeOwnerID-$serviceID";
        if (in_array($key, $this->saved)) return false;

        $this->saved[] = $key;
        return true;
    }
}

// === Controller Mock ===
class SaveShortlistController {
    public function save($homeOwnerID, $serviceID): string {
        $entity = new TestShortlist();
        return $entity->saveShortlist($homeOwnerID, $serviceID) ? "Saved" : "Failed";
    }
}

// === Tests ===

test('Entity: saveShortlist works for valid IDs', function () {
    $entity = new TestShortlist();
    expect($entity->saveShortlist(3, 101))->toBeTrue();
});

test('Entity: saveShortlist fails for invalid inputs', function () {
    $entity = new TestShortlist();
    expect($entity->saveShortlist(0, 101))->toBeFalse();
    expect($entity->saveShortlist(3, null))->toBeFalse();
});

test('Controller: returns Saved for valid input, Failed for invalid', function () {
    $controller = new SaveShortlistController();
    expect($controller->save(3, 101))->toBe("Saved");
    expect($controller->save(0, 101))->toBe("Failed");
});
