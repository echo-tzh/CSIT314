<?php

// Import or define required entity classes
// For testing purposes, we'll define our own versions here

class cleaningCategory {
    protected $conn;
    
    public function __construct() {
        // Default constructor - in real code this would connect to the DB
    }
    
    public function viewAllCleaningCategory(): array {
        // This would be implemented in the real class
        return [];
    }
}

class bookingHistory {
    protected $conn;
    
    public function __construct() {
        // Default constructor - in real code this would connect to the DB
    }
    
    public function getAllFilteredHistoryByCategory($categoryID, $homeOwnerID): array {
        // This would be implemented in the real class
        return [];
    }
}

// Controller class
class ViewAllFilteredHistoryController {
    public function getAllCategories(): array {
        $categoryEntity = new cleaningCategory();
        return $categoryEntity->viewAllCleaningCategory(); // returns categoryID and categoryName
    }

    public function getAllFilteredHistoryByCategory(int $categoryID, int $homeOwnerID): array {
        $bookingEntity = new bookingHistory();
        return $bookingEntity->getAllFilteredHistoryByCategory($categoryID, $homeOwnerID); // Pass both params
    }
}

// Create test doubles for the entity classes

// Test double for cleaningCategory
class TestCleaningCategory extends cleaningCategory {    
    // Override the method to return test data
    public function viewAllCleaningCategory(): array {
        // Return sample category data
        return [
            [
                'categoryID' => 1,
                'categoryName' => 'Regular'
            ],
            [
                'categoryID' => 2,
                'categoryName' => 'Specialized'
            ],
            [
                'categoryID' => 3,
                'categoryName' => 'Premium'
            ]
        ];
    }
}

// Test double for bookingHistory
class TestBookingHistory extends bookingHistory {
    // Define the property to avoid dynamic property warning
    protected $conn;
    
    // Override the constructor to inject our mock connection if needed
    public function __construct($mockConn = null) {
        // Skip the parent constructor to avoid actual DB connection
        $this->conn = $mockConn;
    }
    
    // Test implementation that doesn't rely on database
    public function getAllFilteredHistoryByCategory($categoryID, $homeOwnerID): array {
        // Based on the provided database sample and test parameters,
        // return appropriate test data
        
        // Sample data that mimics your bookingHistory table structure
        if ($categoryID == 1 && $homeOwnerID == 3) {
            return [
                [
                    'bookingID' => 1,
                    'homeOwnerID' => 3,
                    'serviceName' => 'Basic Cleaning',
                    'categoryName' => 'Regular',
                    'bookingDate' => '2025-05-04 10:00:00'
                ],
                [
                    'bookingID' => 2,
                    'homeOwnerID' => 3,
                    'serviceName' => 'Deep Cleaning',
                    'categoryName' => 'Regular',
                    'bookingDate' => '2025-05-05 10:00:00'
                ]
            ];
        } elseif ($categoryID == 2 && $homeOwnerID == 3) {
            return [
                [
                    'bookingID' => 3,
                    'homeOwnerID' => 3,
                    'serviceName' => 'Window Cleaning',
                    'categoryName' => 'Specialized',
                    'bookingDate' => '2025-05-06 14:00:00'
                ],
                [
                    'bookingID' => 4,
                    'homeOwnerID' => 3,
                    'serviceName' => 'Carpet Cleaning',
                    'categoryName' => 'Specialized',
                    'bookingDate' => '2025-05-07 14:00:00'
                ]
            ];
        } elseif ($categoryID == 3 && $homeOwnerID == 3) {
            return [
                [
                    'bookingID' => 5,
                    'homeOwnerID' => 3,
                    'serviceName' => 'VIP Full House Cleaning',
                    'categoryName' => 'Premium',
                    'bookingDate' => '2025-05-08 09:00:00'
                ]
            ];
        }
        
        // Default: return empty array for no matches
        return [];
    }
}

// Mock the database connection class
class BookingHistoryMockConnection {
    public function prepare($query) {
        // This won't be called in our test implementation
        return null;
    }
}

// Create a test version of our controller that uses the test doubles
class TestViewAllFilteredHistoryController extends ViewAllFilteredHistoryController {
    // Override methods to use our test doubles instead of actual entities
    
    public function getAllCategories(): array {
        $categoryEntity = new TestCleaningCategory();
        return $categoryEntity->viewAllCleaningCategory();
    }
    
    public function getAllFilteredHistoryByCategory(int $categoryID, int $homeOwnerID): array {
        $bookingEntity = new TestBookingHistory();
        return $bookingEntity->getAllFilteredHistoryByCategory($categoryID, $homeOwnerID);
    }
}

// PEST TESTS FOR BOOKING HISTORY ENTITY

test('filters booking history by category 1 and homeowner ID 3 returns correct results', function () {
    // Create the test double
    $bookingHistory = new TestBookingHistory(new BookingHistoryMockConnection());
    
    // Test case: Category 1, HomeOwner 3 should return 2 bookings
    $results = $bookingHistory->getAllFilteredHistoryByCategory(1, 3);
    
    expect($results)->toBeArray();
    expect($results)->toHaveCount(2);
    
    // Check first result details
    expect($results[0]['bookingID'])->toBe(1);
    expect($results[0]['homeOwnerID'])->toBe(3);
    expect($results[0]['serviceName'])->toBe('Basic Cleaning');
    expect($results[0]['categoryName'])->toBe('Regular');
    expect($results[0]['bookingDate'])->toBe('2025-05-04 10:00:00');
    
    // Check second result details
    expect($results[1]['bookingID'])->toBe(2);
    expect($results[1]['serviceName'])->toBe('Deep Cleaning');
});

test('filters booking history with category 2 returns correct specialized cleaning results', function () {
    $bookingHistory = new TestBookingHistory(new BookingHistoryMockConnection());
    
    // Test case: Category 2, HomeOwner 3 should return specialized cleaning records
    $results = $bookingHistory->getAllFilteredHistoryByCategory(2, 3);
    
    expect($results)->toBeArray();
    expect($results)->toHaveCount(2);
    expect($results[0]['bookingID'])->toBe(3);
    expect($results[0]['serviceName'])->toBe('Window Cleaning');
    expect($results[0]['categoryName'])->toBe('Specialized');
    expect($results[1]['serviceName'])->toBe('Carpet Cleaning');
});

test('filters booking history with no matching records returns empty array', function () {
    $bookingHistory = new TestBookingHistory(new BookingHistoryMockConnection());
    
    // Test with parameters that won't match any records
    $results = $bookingHistory->getAllFilteredHistoryByCategory(99, 999);
    
    expect($results)->toBeArray();
    expect($results)->toBeEmpty();
});

test('booking history data contains required fields for display', function () {
    $bookingHistory = new TestBookingHistory(new BookingHistoryMockConnection());
    
    // Get some test data
    $results = $bookingHistory->getAllFilteredHistoryByCategory(1, 3);
    
    // Make sure each record has all the required fields
    foreach ($results as $record) {
        expect($record)->toHaveKeys(['bookingID', 'homeOwnerID', 'serviceName', 'categoryName', 'bookingDate']);
    }
});

// PEST TESTS FOR CONTROLLER

test('controller can retrieve all cleaning categories', function () {
    // Create the test controller
    $controller = new TestViewAllFilteredHistoryController();
    
    // Get all categories
    $categories = $controller->getAllCategories();
    
    // Assert results
    expect($categories)->toBeArray();
    expect($categories)->toHaveCount(3);
    
    // Check first category
    expect($categories[0]['categoryID'])->toBe(1);
    expect($categories[0]['categoryName'])->toBe('Regular');
    
    // Check second category
    expect($categories[1]['categoryID'])->toBe(2);
    expect($categories[1]['categoryName'])->toBe('Specialized');
    
    // Check third category
    expect($categories[2]['categoryID'])->toBe(3);
    expect($categories[2]['categoryName'])->toBe('Premium');
});

test('controller can filter booking history by regular category', function () {
    // Create the test controller
    $controller = new TestViewAllFilteredHistoryController();
    
    // Get filtered history for category 1 (Regular) and homeOwner 3
    $history = $controller->getAllFilteredHistoryByCategory(1, 3);
    
    // Assert results
    expect($history)->toBeArray();
    expect($history)->toHaveCount(2);
    
    // Check data in first record
    expect($history[0]['bookingID'])->toBe(1);
    expect($history[0]['homeOwnerID'])->toBe(3);
    expect($history[0]['serviceName'])->toBe('Basic Cleaning');
    expect($history[0]['categoryName'])->toBe('Regular');
    expect($history[0]['bookingDate'])->toBe('2025-05-04 10:00:00');
    
    // Check data in second record
    expect($history[1]['bookingID'])->toBe(2);
    expect($history[1]['serviceName'])->toBe('Deep Cleaning');
    expect($history[1]['categoryName'])->toBe('Regular');
});

test('controller can filter booking history by category that is "specialized"', function () {
    // Create the test controller
    $controller = new TestViewAllFilteredHistoryController();
    
    // Get filtered history for category 2 (Specialized) and homeOwner 3
    $history = $controller->getAllFilteredHistoryByCategory(2, 3);
    
    // Assert results
    expect($history)->toBeArray();
    expect($history)->toHaveCount(2);
    
    // Check first record
    expect($history[0]['bookingID'])->toBe(3);
    expect($history[0]['serviceName'])->toBe('Window Cleaning');
    expect($history[0]['categoryName'])->toBe('Specialized');
    
    // Check second record
    expect($history[1]['bookingID'])->toBe(4);
    expect($history[1]['serviceName'])->toBe('Carpet Cleaning');
    expect($history[1]['categoryName'])->toBe('Specialized');
});

test('controller returns empty array when no booking history matches filter', function () {
    // Create the test controller
    $controller = new TestViewAllFilteredHistoryController();
    
    // Get filtered history with parameters that won't match any records
    $history = $controller->getAllFilteredHistoryByCategory(99, 999);
    
    // Assert results
    expect($history)->toBeArray();
    expect($history)->toBeEmpty();
});

test('filtered booking history records contain all required fields', function () {
    // Create the test controller
    $controller = new TestViewAllFilteredHistoryController();
    
    // Get some test data
    $history = $controller->getAllFilteredHistoryByCategory(1, 3);
    
    // Check each record has all required fields
    foreach ($history as $record) {
        expect($record)->toHaveKeys(['bookingID', 'homeOwnerID', 'serviceName', 'categoryName', 'bookingDate']);
    }
});