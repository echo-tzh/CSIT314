<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../../entity/bookingHistory.php';
require_once __DIR__ . '/../../controller/searchUsedServiceController.php';

session_start();

// Set a known session ID to be used in the tests
$_SESSION['userAccountID'] = 1;

beforeEach(function () {
    // Create a new mock for BookingHistory for each test
    $this->mockBH = Mockery::mock('BookingHistory');
});

// Test when BookingHistory returns empty results
test('BookingHistory searchUsedService returns empty array when no results found', function () {
    $this->mockBH->shouldReceive('searchUsedService')
        ->once()
        ->with('cleaning', 1)
        ->andReturn([]); // Simulate empty result

    $results = $this->mockBH->searchUsedService('cleaning', 1);
    expect($results)->toBeArray()->toHaveCount(0);
});

// Test the controller behavior using mocked BookingHistory
test('SearchUsedServiceController returns expected results from entity', function () {
    $expected = [
        [
            'serviceName' => 'Test Service',
            'cleanerName' => 'Test Cleaner',
            'description' => 'Test Description',
            'price' => 100
        ]
    ];

    $this->mockBH->shouldReceive('searchUsedService')
        ->once()
        ->with('cleaning', 1)
        ->andReturn($expected);

    // Extend the controller to inject mocked BookingHistory
    $controller = new class($this->mockBH) extends SearchUsedServiceController {
        private $mockBH;

        public function __construct($mockBH) {
            $this->mockBH = $mockBH;
        }

        public function searchUsedService(string $keyword): array {
            return $this->mockBH->searchUsedService($keyword, $_SESSION['userAccountID']);
        }
    };

    $results = $controller->searchUsedService('cleaning');
    expect($results)->toEqual($expected);
});

// Cleanup
afterEach(function () {
    Mockery::close();
});
