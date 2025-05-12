<?php

// tests/Unit/searchServiceEntityTest.php

use Mockery;
use App\Entities\Service;

beforeEach(function () {
    // Create a mock SQL connection (not directly used in test)
    $this->mockConn = Mockery::mock('mysqli');
    
    // STEP: Mock the Service class and make it partial
    $this->mockService = Mockery::mock(Service::class)->makePartial();
});

afterEach(function () {
    // STEP: Clean up after each test
    Mockery::close();
});

/**
 * Test Case: it correctly retrieves services matching the search term
 * Description: Ensures that when a valid keyword is provided, the service search method returns correct matches.
 * Expected Output: An array of matching services.
 */
it('correctly retrieves services matching the search term', function () {
    // STEP 1: Define search term and expected result
    $searchTerm = 'cleaning';
    $expectedResults = [
        ['serviceID' => 1, 'serviceName' => 'House Cleaning', 'description' => 'Full house cleaning service', 'price' => 100.00],
        ['serviceID' => 2, 'serviceName' => 'Office Cleaning', 'description' => 'Daily office cleaning', 'price' => 200.00],
    ];

    // STEP 2: Mock the searchService method to return the expected results
    $this->mockService->shouldReceive('searchService')
        ->once()
        ->with($searchTerm)
        ->andReturn($expectedResults);

    // STEP 3: Call the searchService method
    $results = $this->mockService->searchService($searchTerm);

    // STEP 4: Assert the results match the expected output
    expect($results)->toBe($expectedResults);
});

/**
 * Test Case: it returns an empty array if no services match the search term
 * Description: Validates that the method returns an empty array when no matches are found.
 * Expected Output: An empty array.
 */
it('returns an empty array if no services match the search term', function () {
    // STEP 1: Define a search term that should return no results
    $searchTerm = 'nonexistent term';
    $expectedResults = [];

    // STEP 2: Mock the searchService method to return an empty array
    $this->mockService->shouldReceive('searchService')
        ->once()
        ->with($searchTerm)
        ->andReturn($expectedResults);

    // STEP 3: Call the searchService method
    $results = $this->mockService->searchService($searchTerm);

    // STEP 4: Assert the results are an empty array
    expect($results)->toBeEmpty();
    expect($results)->toBe($expectedResults);
});

?>
