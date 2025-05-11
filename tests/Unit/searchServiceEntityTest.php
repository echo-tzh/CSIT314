<?php

use PHPUnit\Framework\TestCase;

// Include required files
require_once __DIR__ . '/../../entity/service.php';

class ServiceEntityTest extends TestCase
{
    private $service;
    private $connMock;
    private $stmtMock;
    private $resultMock;
    
    protected function setUp(): void
    {
        // Create mock objects
        $this->connMock = $this->createMock(mysqli::class);
        $this->stmtMock = $this->createMock(mysqli_stmt::class);
        $this->resultMock = $this->createMock(mysqli_result::class);
        
        // Create instance of Service
        $this->service = new Service();
        
        // Use Reflection to set the private conn property
        $reflectionClass = new ReflectionClass(Service::class);
        $connProperty = $reflectionClass->getProperty('conn');
        $connProperty->setAccessible(true);
        $connProperty->setValue($this->service, $this->connMock);
    }
    
    public function testSearchServiceWithResults(): void
    {
        // Sample data that would be returned from the database
        $expectedRow1 = [
            'serviceID' => 1,
            'serviceName' => 'House Cleaning',
            'description' => 'Complete house cleaning service',
            'price' => 150.00,
            'serviceDate' => '2023-05-15',
            'cleanerID' => 5,
            'categoryID' => 2
        ];
        
        $expectedRow2 = [
            'serviceID' => 3,
            'serviceName' => 'Deep Cleaning',
            'description' => 'Deep house cleaning service',
            'price' => 250.00,
            'serviceDate' => '2023-05-20',
            'cleanerID' => 7,
            'categoryID' => 2
        ];
        
        // Set up expectation for prepare method
        $this->connMock->expects($this->once())
            ->method('prepare')
            ->with($this->stringContains('SELECT serviceID, serviceName, description, price, serviceDate, cleanerID, categoryID FROM service WHERE serviceName LIKE ? OR description LIKE ?'))
            ->willReturn($this->stmtMock);
        
        // Set up expectation for bind_param method
        $this->stmtMock->expects($this->once())
            ->method('bind_param')
            ->with('ss', $this->anything(), $this->anything());
        
        // Set up expectation for execute method
        $this->stmtMock->expects($this->once())
            ->method('execute');
        
        // Set up expectation for get_result method
        $this->stmtMock->expects($this->once())
            ->method('get_result')
            ->willReturn($this->resultMock);
        
        // Set up num_rows property on the result mock
        $this->resultMock->expects($this->once())
            ->method('num_rows')
            ->willReturn(2);
        
        // Set up fetch_assoc method to return our expected rows in sequence
        $this->resultMock->expects($this->exactly(3))
            ->method('fetch_assoc')
            ->willReturnOnConsecutiveCalls($expectedRow1, $expectedRow2, null);
        
        // Set up close method expectation
        $this->stmtMock->expects($this->once())
            ->method('close');
        
        // Call the method under test
        $result = $this->service->searchService('Clean');
        
        // Assert the results
        $this->assertCount(2, $result);
        $this->assertEquals($expectedRow1, $result[0]);
        $this->assertEquals($expectedRow2, $result[1]);
    }
    
    public function testSearchServiceWithNoResults(): void
    {
        // Set up expectation for prepare method
        $this->connMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmtMock);
        
        // Set up expectation for bind_param method
        $this->stmtMock->expects($this->once())
            ->method('bind_param')
            ->with('ss', $this->anything(), $this->anything());
        
        // Set up expectation for execute method
        $this->stmtMock->expects($this->once())
            ->method('execute');
        
        // Set up expectation for get_result method
        $this->stmtMock->expects($this->once())
            ->method('get_result')
            ->willReturn($this->resultMock);
        
        // Set up num_rows property on the result mock to return 0
        $this->resultMock->expects($this->once())
            ->method('num_rows')
            ->willReturn(0);
        
        // No calls to fetch_assoc are expected
        
        // Set up close method expectation
        $this->stmtMock->expects($this->once())
            ->method('close');
        
        // Call the method under test
        $result = $this->service->searchService('NonExistentService');
        
        // Assert the results
        $this->assertEmpty($result);
        $this->assertEquals([], $result);
    }
    
    public function testSearchServiceWithNullResult(): void
    {
        // Set up expectation for prepare method
        $this->connMock->expects($this->once())
            ->method('prepare')
            ->willReturn($this->stmtMock);
        
        // Set up expectation for bind_param method
        $this->stmtMock->expects($this->once())
            ->method('bind_param')
            ->with('ss', $this->anything(), $this->anything());
        
        // Set up expectation for execute method
        $this->stmtMock->expects($this->once())
            ->method('execute');
        
        // Set up expectation for get_result method to return null
        $this->stmtMock->expects($this->once())
            ->method('get_result')
            ->willReturn(null);
        
        // Set up close method expectation
        $this->stmtMock->expects($this->once())
            ->method('close');
        
        // Call the method under test
        $result = $this->service->searchService('Clean');
        
        // Assert the results
        $this->assertEmpty($result);
        $this->assertEquals([], $result);
    }
    
    public function testSearchServiceWildcardHandling(): void
    {
        // Create a mock for the statement that will capture the search parameter
        $stmtMock = $this->getMockBuilder(mysqli_stmt::class)
            ->disableOriginalConstructor()
            ->getMock();
            
        $this->connMock->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtMock);
            
        // This test will verify that wildcards are properly added to the search term
        $capturedSearchTerm = null;
        
        // Use a callback to capture the bound parameters
        $stmtMock->expects($this->once())
            ->method('bind_param')
            ->with('ss', $this->anything(), $this->anything())
            ->will($this->returnCallback(function($types, &$searchTerm1, &$searchTerm2) use (&$capturedSearchTerm) {
                $capturedSearchTerm = $searchTerm1;
            }));
        
        $stmtMock->expects($this->once())
            ->method('execute');
            
        $stmtMock->expects($this->once())
            ->method('get_result')
            ->willReturn($this->resultMock);
            
        $this->resultMock->expects($this->once())
            ->method('num_rows')
            ->willReturn(0);
            
        $stmtMock->expects($this->once())
            ->method('close');
        
        // Call the method with "test" as the search term
        $this->service->searchService('test');
        
        // Verify the search term had wildcards added
        $this->assertEquals('%test%', $capturedSearchTerm);
    }
}