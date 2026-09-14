<?php
header('Content-Type: application/json');

require_once __DIR__ . '/../models/requestModel.php';

if (isset($_GET['equipment_id'])) {
    $equipmentId = (int)$_GET['equipment_id'];

    if ($equipmentId <= 0) {
        echo json_encode(['error' => 'Invalid equipment selection']);
        exit();
    }

    $equipment = getEquipmentDetails($equipmentId);

    if ($equipment) {
        echo json_encode($equipment);
    } else {
        echo json_encode(['error' => 'Equipment not found']);
    }
    exit();
}
?>