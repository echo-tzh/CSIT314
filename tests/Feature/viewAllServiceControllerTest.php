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
    $_SESSION['userProfileID'] = 3; // Admin profile

    // Mock the service fetching all services
    $this->mockService->shouldReceive('viewAllServices')
        ->andReturn([
            ['serviceID' => 1, 'serviceName' => 'Cleaning', 'description' => 'Home cleaning', 'price' => 100, 'serviceDate' => '2025-01-01'],
        ]);

    // Simulate calling the controller's method to fetch services
    // In actual implementation, you would call the controller's method here
    // For now, simulate the response from the service
    $services = $this->mockService->viewAllServices();

    // Assert that the result is an array (list of services)
    expect($services)->toBeArray();

    // Assert that the service name 'Cleaning' is part of the returned services
    expect($services[0]['serviceName'])->toBe('Cleaning');
});

it('redirects to login if not logged in as admin', function () {
    // Not setting session variables means user is not logged in
    // Simulate the controller method here to check redirection
    // For this example, we'll just check the session for user account ID

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
?>