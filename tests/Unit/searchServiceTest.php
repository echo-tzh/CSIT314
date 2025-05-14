<?php

// === Entity mock ===
class TestServiceEntity {
    public function searchService($keyword): array {
        $services = [
            ['serviceID' => 1, 'name' => 'Basic Cleaning'],
            ['serviceID' => 2, 'name' => 'Window Cleaning'],
            ['serviceID' => 3, 'name' => 'Deep Cleaning']
        ];

        return array_filter($services, function ($service) use ($keyword) {
            return stripos($service['name'], $keyword) !== false;
        });
    }
}

// === Controller mock ===
class SearchServiceController {
    public function search($keyword): array {
        $entity = new TestServiceEntity();
        return array_values($entity->searchService($keyword)); // reindex result
    }
}

// ✅ Test: Entity returns matching results
test('Entity: searchService returns matching services', function () {
    $entity = new TestServiceEntity();
    $result = $entity->searchService('cleaning');
    expect($result)->toHaveCount(3);
});

// ✅ Test: Entity returns empty for no match
test('Entity: searchService returns empty if no match', function () {
    $entity = new TestServiceEntity();
    $result = $entity->searchService('massage');
    expect($result)->toBeArray();
    expect($result)->toBeEmpty();
});

// ✅ Test: Controller calls entity and returns correct result
test('Controller: search returns correct data from entity', function () {
    $controller = new SearchServiceController();
    $result = $controller->search('window');

    expect($result)->toBeArray();
    expect($result)->toHaveCount(1);
    expect($result[0]['name'])->toBe('Window Cleaning');
});
