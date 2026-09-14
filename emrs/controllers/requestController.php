<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../views/login.php");
    exit();
}

require_once __DIR__ . '/../models/requestModel.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_request_btn'])) {

    $employeeId  = (int)$_SESSION['user_id'];

    $equipmentId = (int)($_POST['equipment_id'] ?? 0);
    $priority    = htmlspecialchars(trim($_POST['priority'] ?? 'Medium'));
    $description = htmlspecialchars(trim($_POST['description'] ?? ''));

    if ($equipmentId <= 0 || empty($description)) {
        header("Location: ../views/request_form.php?error=empty_fields");
        exit();
    }

    $isCreated = createRequest($employeeId, $equipmentId, $description, $priority);

    if ($isCreated) {
        header("Location: ../views/employee_dashboard.php?success=ticket_created");
    } else {
        header("Location: ../views/request_form.php?error=db_error");
    }
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['request_action'])) {
    $requestId = (int)($_POST['request_id'] ?? 0);
    $action    = $_POST['request_action'];

    if ($requestId > 0) {
        if ($action === 'cancel') {
            updateRequestStatus($requestId, 'Cancelled');
        } elseif ($action === 'confirm_done') {
            updateRequestStatus($requestId, 'Completed');
        }
    }

    header("Location: ../views/employee_dashboard.php");
    exit();
}

header("Location: ../views/employee_dashboard.php");
exit();
?>