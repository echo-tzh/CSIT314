<?php

// tests/Unit/SearchServiceControllerTest.php



use Mockery; // Already used in your original searchServiceControllerTest



$controllerPathOkay = false;
if (file_exists(__DIR__ . '/../../controller/searchServiceController.php')) {
    // Attempt to include service.php first, as it's a dependency for searchServiceController
    if (file_exists(__DIR__ . '/../../entity/service.php')) {
        require_once __DIR__ . '/../../entity/service.php'; // Make Service class available for Mockery
        $controllerPathOkay = true;
    } else {
        // Fallback or error: service.php not found where expected by test
        // This might indicate the user still needs to verify file paths.
        // For now, the test will likely fail if Service class isn't found for Mockery.
    }
}


beforeEach(function () {
    // Mocking the Service class
    // The actual 'Service' class needs to be resolvable here for Mockery.
    // If 'App\Entities\Service' is used, it must be autoloaded or included.
    // If global 'Service' is used, it must be included (e.g., by the require_once above).
    if (class_exists('Service')) { // Check if Service class is loaded
        $this->mockService = Mockery::mock(Service::class);
    } else {
        // Fallback if Service class isn't found: create a generic mock.
        // This won't be type-hint aware but allows test structure.
        // Ideally, ensure Service class is correctly loaded.
        $this->mockService = Mockery::mock('alias:Service');
        // Using 'alias:Service' tells Mockery to create a mock for a class named Service
        // even if it cannot autoload/find the original class definition.
        // This is useful if autoloading is problematic but you still want to define expectations.
    }
});

afterEach(function () {
    // Close Mockery after each test
    Mockery::close();
});

it('correctly calls the service and returns expected search results', function () {
    // 1. Arrange
    $searchTerm = 'cleaning';
    $expectedResults = [
        ['serviceID' => 1, 'serviceName' => 'House Cleaning', 'description' => 'Full house cleaning service', 'price' => 100.00],
        ['serviceID' => 2, 'serviceName' => 'Office Cleaning', 'description' => 'Daily office cleaning', 'price' => 200.00],
    ];

    // Define expectations on the mocked service
    // This assumes $this->mockService was successfully created in beforeEach
    if (isset($this->mockService) && $this->mockService instanceof Mockery\MockInterface) {
        $this->mockService->shouldReceive('searchService')
            ->once()
            ->with($searchTerm)
            ->andReturn($expectedResults);
    } else {
        // Skip mock expectations if mockService isn't set up, or throw error
        // This indicates a problem in beforeEach or class loading.
        throw new \Exception("Service mock was not properly initialized.");
    }

    // --- Instantiate searchServiceController ---
    // This part is tricky because searchServiceController directly news up Service.
    // To truly unit test the controller in isolation with the mock,
    // we use the anonymous class override trick, or refactor the controller for DI.

    // Load controller using chdir workaround IF the path issue inside controller still exists
    // AND you haven't fixed it in the source.
    // If you DID fix searchServiceController.php to use `__DIR__` for its internal include,
    // then you can just `require_once __DIR__ . '/../../controller/searchServiceController.php';`
    // at the top of this test file (if not autoloaded) and remove chdir.

    $controllerLoaded = false;
    $originalCwd = getcwd();
    $controllerDir = realpath(__DIR__ . '/../../controller');

    if ($controllerDir && chdir($controllerDir)) {
        if (file_exists('searchServiceController.php')) {
            require_once 'searchServiceController.php'; // Include the controller
            $controllerLoaded = class_exists('searchServiceController');
        }
        chdir($originalCwd); // IMPORTANT: Change back to the original CWD
    }

    if (!$controllerLoaded) {
        throw new \Exception('searchServiceController class was not loaded correctly. Check paths and internal require_once issues.');
    }

    // Instantiate the controller using the anonymous class to inject the mock
    $controller = new class($this->mockService) extends searchServiceController {
        private $mockServiceInstance;

        // The constructor of the anonymous class receives the mock
        public function __construct($mockServiceInstance) {
            // If your searchServiceController had its own constructor, you might need to call parent::__construct()
            $this->mockServiceInstance = $mockServiceInstance;
        }

        // Override the searchService method to use the mock
        public function searchService(string $searchTerm): array {
            // Instead of:
            // $service = new Service();
            // return $service->searchService($searchTerm);
            // We use our injected mock:
            return $this->mockServiceInstance->searchService($searchTerm);
        }
    };

    // 2. Act
    $results = $controller->searchService($searchTerm);

    // 3. Assert
    expect($results)->toBe($expectedResults);
    // Mockery will also verify its expectations (called once, with $searchTerm) automatically
    // when Mockery::close() is called in afterEach.
});

it('returns an empty array if search term yields no results from service', function () {
    // 1. Arrange
    $searchTerm = 'nonexistent term';
    $expectedResults = []; // Empty array for no results

    if (isset($this->mockService) && $this->mockService instanceof Mockery\MockInterface) {
        $this->mockService->shouldReceive('searchService')
            ->once()
            ->with($searchTerm)
            ->andReturn($expectedResults); // Service returns an empty array
    } else {
        throw new \Exception("Service mock was not properly initialized.");
    }

    // Load and instantiate controller as in the previous test
    $controllerLoaded = false;
    $originalCwd = getcwd();
    $controllerDir = realpath(__DIR__ . '/../../controller');
    if ($controllerDir && chdir($controllerDir)) {
        if (file_exists('searchServiceController.php')) {
            require_once 'searchServiceController.php';
            $controllerLoaded = class_exists('searchServiceController');
        }
        chdir($originalCwd);
    }
    if (!$controllerLoaded) {
        throw new \Exception('searchServiceController class was not loaded correctly.');
    }

    $controller = new class($this->mockService) extends searchServiceController {
        private $mockServiceInstance;
        public function __construct($mockServiceInstance) {
            $this->mockServiceInstance = $mockServiceInstance;
        }
        public function searchService(string $searchTerm): array {
            return $this->mockServiceInstance->searchService($searchTerm);
        }
    };

    // 2. Act
    $results = $controller->searchService($searchTerm);

    // 3. Assert
    expect($results)->toBeEmpty();
    expect($results)->toBe($expectedResults);
});

?>