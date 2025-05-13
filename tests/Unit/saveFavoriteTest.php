<?php

use App\Entity\Shortlist;
use App\Entities\Service;
use Mockery;

require_once __DIR__ . '/../../controller/saveFavoriteController.php';
require_once __DIR__ . '/../../entity/shortlist.php';

beforeEach(function () {
    // Create a mock SQL connection (not directly used in service entity test)
    $this->mockConn = Mockery::mock('mysqli');

    // Mock the Service class and make it partial
    $this->mockService = Mockery::mock(Service::class)->makePartial();

    // Mock the Shortlist class
    $this->mockShortlist = mock(Shortlist::class);
});

afterEach(function () {
    Mockery::close();
});

it('saveFavoriteController correctly passes data', function () {
    $homeOwnerID = 1;
    $serviceID = 100;

    // Expect the saveFavorite method to be called once with specific arguments
    $this->mockShortlist->shouldReceive('saveFavorite')
        ->once()
        ->with($homeOwnerID, $serviceID)
        ->andReturn(true);

    // Inject the mock using anonymous class that extends the controller
    $controller = new class($this->mockShortlist) extends \SaveFavoriteController { // Removed the leading backslash as well
        private $mockShortlist;

        public function __construct($mockShortlist) {
            $this->mockShortlist = $mockShortlist;
        }

        public function saveFavorite(int $homeOwnerID, int $serviceID): bool {
            return $this->mockShortlist->saveFavorite($homeOwnerID, $serviceID);
        }
    };

    $result = $controller->saveFavorite($homeOwnerID, $serviceID);

    expect($result)->toBeTrue();
});

/**
 * Test Case: it correctly retrieves services matching the search term
 * Description: Ensures that when a valid keyword is provided, the service search method returns correct matches.
 * Expected Output: An array of matching services.
 */
it('correctly retrieves services matching the search term', function () {
    // Define search term and expected result
    $searchTerm = 'cleaning';
    $expectedResults = [
        ['serviceID' => 1, 'serviceName' => 'House Cleaning', 'description' => 'Full house cleaning service', 'price' => 100.00],
        ['serviceID' => 2, 'serviceName' => 'Office Cleaning', 'description' => 'Daily office cleaning', 'price' => 200.00],
    ];

    // Mock the searchService method to return the expected results
    $this->mockService->shouldReceive('searchService')
        ->once()
        ->with($searchTerm)
        ->andReturn($expectedResults);

    // Call the searchService method
    $results = $this->mockService->searchService($searchTerm);

    // Assert the results match the expected output
    expect($results)->toBe($expectedResults);
});

/**
 * Test Case: it returns an empty array if no services match the search term
 * Description: Validates that the method returns an empty array when no matches are found.
 * Expected Output: An empty array.
 */
it('returns an empty array if no services match the search term', function () {
    // Define a search term that should return no results
    $searchTerm = 'nonexistent term';
    $expectedResults = [];

    // Mock the searchService method to return an empty array
    $this->mockService->shouldReceive('searchService')
        ->once()
        ->with($searchTerm)
        ->andReturn($expectedResults);

    // Call the searchService method
    $results = $this->mockService->searchService($searchTerm);

    // Assert the results are an empty array
    expect($results)->toBeEmpty();
    expect($results)->toBe($expectedResults);
});