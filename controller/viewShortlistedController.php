<?php
// viewShortlistedController.php
include_once '../entity/shortlist.php';
include_once '../controller/viewAllServiceController.php'; // Assuming you have this to get service details

class ViewShortlistedController {

    public function getShortlistedServices(int $homeOwnerID): array {
        $shortlist = new Shortlist();
        $serviceController = new viewAllServiceController(); // Assuming this controller can fetch service details

        // 1. Get the service IDs from the shortlist (using the entity)
        $shortlistedServiceIds = $shortlist->getShortlistedServiceIds($homeOwnerID);

        // 2. If there are no favorited services, return an empty array
        if (empty($shortlistedServiceIds)) {
            return [];
        }

        // 3. Fetch the full service details for those IDs
        $services = $serviceController->viewAllServices(); // Get all services
        $result = [];
        foreach ($services as $service) {
            if (in_array($service['serviceID'], $shortlistedServiceIds)) {
                $result[] = $service;
            }
        }

        return $result;
    }
}
?>