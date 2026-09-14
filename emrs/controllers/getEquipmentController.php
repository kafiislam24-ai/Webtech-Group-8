<?php
// Step 1: Tell the browser that the response body is formatted as JSON
header('Content-Type: application/json');

// Step 2: Include the Model to access equipment database queries
require_once __DIR__ . '/../models/requestModel.php';

// Step 3: Check if equipment_id is passed via GET
if (isset($_GET['equipment_id'])) {
    $equipmentId = (int)$_GET['equipment_id'];

    if ($equipmentId <= 0) {
        echo json_encode(['error' => 'Invalid equipment selection']);
        exit();
    }

    // Step 4: Call Model function to fetch ItemName, Category, StockQuantity, ConditionStatus
    $equipment = getEquipmentDetails($equipmentId);

    // Step 5: Output the retrieved database array as a JSON string
    if ($equipment) {
        echo json_encode($equipment);
    } else {
        echo json_encode(['error' => 'Equipment not found']);
    }
    exit();
}
?>