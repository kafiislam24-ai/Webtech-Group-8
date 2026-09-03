<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/RequestModel.php';

function clean($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = clean($_POST['action'] ?? '');

    if ($action === 'create_request') {
        $employeeId = $_SESSION['user_id'];
        $equipmentId = (int)$_POST['equipment_id'];
        $priority = clean($_POST['priority'] ?? 'Medium');
        $description = clean($_POST['description'] ?? '');

        RequestModel::createRequest($conn, $employeeId, $equipmentId, $description, $priority);
        header("Location: ../views/employee_dashboard.php");
        exit();
    }

    elseif ($action === 'cancel_request') {
        RequestModel::updateStatus($conn, (int)$_POST['request_id'], 'Cancelled');
        header("Location: ../views/employee_dashboard.php");
        exit();
    }
    elseif ($action === 'confirm_resolution') {
        RequestModel::updateStatus($conn, (int)$_POST['request_id'], 'Completed');
        header("Location: ../views/employee_dashboard.php");
        exit();
    }

    elseif ($action === 'assign_and_approve') {
        $requestId = (int)$_POST['request_id'];
        $managerId = $_SESSION['user_id'];
        $techId    = (int)$_POST['technician_id'];

        RequestModel::assignAndApprove($conn, $requestId, $managerId, $techId);
        header("Location: ../views/manager_dashboard.php");
        exit();
    }
    elseif ($action === 'reject_request') {
        RequestModel::updateStatus($conn, (int)$_POST['request_id'], 'Rejected');
        header("Location: ../views/manager_dashboard.php");
        exit();
    }

    elseif ($action === 'start_task') {
        RequestModel::updateStatus($conn, (int)$_POST['request_id'], 'In Progress');
        header("Location: ../views/technician_dashboard.php");
        exit();
    }
    elseif ($action === 'update_task_status') {
        $status = clean($_POST['work_status']);
        RequestModel::updateStatus($conn, (int)$_POST['request_id'], $status);
        header("Location: ../views/technician_dashboard.php");
        exit();
    }
}
?>