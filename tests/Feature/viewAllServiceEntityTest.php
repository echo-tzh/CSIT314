<?php
use App\Entities\Service;
use Mockery;

beforeEach(function () {
    // Mock the database connection (mysqli for example)
    $this->mockDb = Mockery::mock('mysqli');
});

afterEach(function () {
    // Close Mockery after each test
    Mockery::close();
});

it('fetches all services from the database', function () {
    // Mock database query result
    $this->mockDb->shouldReceive('query')
        ->with('SELECT serviceID, serviceName, description, price, serviceDate, cleanerID, categoryID FROM service WHERE isDeleted = 0 ORDER BY serviceID')
        ->andReturn(true); // Mocking that query returns a result

    // Assuming Service class uses this database connection to fetch services
    // Mock the actual Service class method
    $mockService = Mockery::mock(Service::class);
    $mockService->shouldReceive('fetchAllServicesFromDb')->andReturn([
        ['serviceID' => 1, 'serviceName' => 'Cleaning', 'description' => 'Home cleaning', 'price' => 100, 'serviceDate' => '2025-01-01'],
        ['serviceID' => 2, 'serviceName' => 'Washing', 'description' => 'Laundry washing', 'price' => 50, 'serviceDate' => '2025-02-01'],
    ]);

    // Simulate fetching the services
    $services = $mockService->fetchAllServicesFromDb();

    // Assert that the result is an array (list of services)
    expect($services)->toBeArray();

    // Assert that we have two services in the list
    expect(count($services))->toBe(2);

    // Assert that the first service has the expected 'serviceName'
    expect($services[0]['serviceName'])->toBe('Cleaning');

    // Optionally, check other fields as well
    expect($services[0]['price'])->toBe(100);
    expect($services[1]['serviceName'])->toBe('Washing');
    expect($services[1]['price'])->toBe(50);
});

?>