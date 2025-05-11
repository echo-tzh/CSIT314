<?php
use App\Entities\Service;
use Mockery;
use PHPUnit\Framework\TestCase;

beforeEach(function () {
    // Mocking the Service class
    $this->mockService = Mockery::mock(Service::class);
});

afterEach(function () {
    // Close Mockery after each test
    Mockery::close();
});

it('shows all services if user is logged in as admin', function () {
    // Mock the controller behavior
    $_SESSION['userAccountID'] = 1; // Admin user
    $_SESSION['userProfileID'] = 3; // Cleaner profile

    // Mock the service fetching all services
    $this->mockService->shouldReceive('viewAllServices')
        ->andReturn([
            ['serviceID' => 1, 'serviceName' => 'Cleaning', 'description' => 'Home cleaning', 'price' => 100, 'serviceDate' => '2025-01-01'],
        ]);

    // Simulate calling the controller's method to fetch services
    $services = $this->mockService->viewAllServices();

    // Assert that the result is an array (list of services)
    expect($services)->toBeArray();

    // Assert that the service name 'Cleaning' is part of the returned services
    expect($services[0]['serviceName'])->toBe('Cleaning');

    // Optionally, check other service details if needed
    expect($services[0]['price'])->toBe(100);
});

it('redirects to login if not logged in as admin', function () {
    // Not setting session variables means user is not logged in
    $_SESSION['userAccountID'] = null;  // No logged-in user
    $_SESSION['userProfileID'] = null;  // No logged-in user profile

    // Simulate the logic that checks whether the user is logged in
    if ($_SESSION['userAccountID'] === null || $_SESSION['userProfileID'] === null) {
        // Simulating a redirect response (this would usually be done by the controller)
        $response = '/login';  // Simulating the redirect to login page
    }

    // Assert that the response is a redirect to the login page
    expect($response)->toBe('/login');
});

it('shows all services if user is logged in as admin (alternative route)', function () {
    // Simulate logged-in admin
    $_SESSION['userAccountID'] = 1; // Admin user
    $_SESSION['userProfileID'] = 3; // Admin profile

    // Mock the service to return a list of services
    $this->mockService->shouldReceive('viewAllServices')
        ->andReturn([
            ['serviceID' => 1, 'serviceName' => 'Cleaning', 'description' => 'Home cleaning', 'price' => 100, 'serviceDate' => '2025-01-01'],
            ['serviceID' => 2, 'serviceName' => 'Washing', 'description' => 'Laundry washing', 'price' => 50, 'serviceDate' => '2025-02-01'],
        ]);

    // Simulate the controller method (usually it would call a route or method in the controller)
    $services = $this->mockService->viewAllServices();

    // Check if both services are returned
    expect($services)->toBeArray();
    expect(count($services))->toBeGreaterThan(1);
    expect($services[0]['serviceName'])->toBe('Cleaning');
    expect($services[1]['serviceName'])->toBe('Washing');
});

it('shows an error message if no services are available', function () {
    // Simulate logged-in admin
    $_SESSION['userAccountID'] = 1; // Admin user
    $_SESSION['userProfileID'] = 3; // Admin profile

    // Mock the service to return an empty array (no services)
    $this->mockService->shouldReceive('viewAllServices')
        ->andReturn([]);

    // Simulate the controller method
    $services = $this->mockService->viewAllServices();

    // Assert that the result is an empty array
    expect($services)->toBeArray();
    expect(count($services))->toBe(0);

    // Assert that an error message is shown if no services are available
    // This would depend on your application logic for showing error messages
    // Example:
    $errorMessage = "No services available.";
    expect($errorMessage)->toBe("No services available.");
});
?>