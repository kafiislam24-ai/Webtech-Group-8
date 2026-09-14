<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "unauthorized";
    exit();
}

require_once __DIR__ . '/../models/requestModel.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $requestId = (int)($_POST['request_id'] ?? 0);
    $operation = trim($_POST['operation'] ?? '');

    if ($requestId <= 0 || empty($operation)) {
        echo "invalid_input";
        exit();
    }

   
    if ($operation === 'assign_and_approve') {
        $managerId = (int)$_SESSION['user_id'];
        $techId    = (int)($_POST['tech_id'] ?? 0);

        if ($techId <= 0) {
            echo "missing_tech";
            exit();
        }

        $updated = assignTechnician($requestId, $managerId, $techId);

        echo $updated ? "success" : "db_error";
        exit();
    }

    
    if ($operation === 'start_task') {
        $updated = updateRequestStatus($requestId, 'In Progress');

        echo $updated ? "success" : "db_error";
        exit();
    }

   
    if ($operation === 'mark_resolved') {
        $updated = updateRequestStatus($requestId, 'Resolved');

        echo $updated ? "success" : "db_error";
        exit();
    }

    echo "unknown_operation";
    exit();

} else {
    echo "invalid_request_method";
    exit();
}
?>