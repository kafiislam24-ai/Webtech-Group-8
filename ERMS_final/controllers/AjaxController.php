<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/RequestModel.php';

function clean($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $action = clean($_GET['action'] ?? '');

    if ($action === 'check_email') {
        $email = clean($_GET['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(['status' => 'invalid', 'message' => 'Please enter a valid email format.']);
            exit();
        }

        $exists = UserModel::emailExists($conn, $email);
        if ($exists) {
            echo json_encode(['status' => 'taken', 'message' => 'Email is already registered.']);
        } else {
            echo json_encode(['status' => 'available', 'message' => 'Email is available.']);
        }
        exit();
    }

    if ($action === 'get_equipment_info') {
        $equipmentId = (int)($_GET['equipment_id'] ?? 0);

        if ($equipmentId <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid equipment ID.']);
            exit();
        }

        $item = RequestModel::getEquipmentById($conn, $equipmentId);
        if ($item) {
            echo json_encode([
                'status'    => 'success',
                'category'  => $item['Category'],
                'stock'     => $item['StockQuantity'],
                'condition' => $item['ConditionStatus']
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Equipment details not found.']);
        }
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = clean($_POST['action'] ?? '');

    if ($action === 'create_request') {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
            echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
            exit();
        }

        $employeeId  = $_SESSION['user_id'];
        $equipmentId = (int)$_POST['equipment_id'];
        $priority    = clean($_POST['priority'] ?? 'Medium');
        $description = clean($_POST['description'] ?? '');

        if (empty($equipmentId) || empty($description)) {
            echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
            exit();
        }

        $created = RequestModel::createRequest($conn, $employeeId, $equipmentId, $description, $priority);
        if ($created) {
            echo json_encode(['status' => 'success', 'message' => 'Request submitted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save request.']);
        }
        exit();
    }

    if ($action === 'update_status_ajax') {
        $requestId = (int)$_POST['request_id'];
        $operation = clean($_POST['operation'] ?? '');

        if ($operation === 'assign_and_approve') {
            $managerId = $_SESSION['user_id'];
            $techId    = (int)$_POST['technician_id'];

            if ($techId <= 0) {
                echo json_encode(['status' => 'error', 'message' => 'Please select a technician.']);
                exit();
            }

            RequestModel::assignAndApprove($conn, $requestId, $managerId, $techId);
            $techRes = mysqli_query($conn, "SELECT Name FROM users WHERE UserID = $techId LIMIT 1");
            $tech = mysqli_fetch_assoc($techRes);

            echo json_encode([
                'status'     => 'success',
                'new_status' => 'Assigned',
                'tech_name'  => $tech['Name'] ?? 'Assigned'
            ]);
            exit();
        } elseif ($operation === 'reject_request') {
            RequestModel::updateStatus($conn, $requestId, 'Rejected');
            echo json_encode(['status' => 'success', 'new_status' => 'Rejected']);
            exit();
        } elseif ($operation === 'start_task') {
            RequestModel::updateStatus($conn, $requestId, 'In Progress');
            echo json_encode(['status' => 'success', 'new_status' => 'In Progress']);
            exit();
        } elseif ($operation === 'mark_resolved') {
            RequestModel::updateStatus($conn, $requestId, 'Resolved');
            echo json_encode(['status' => 'success', 'new_status' => 'Resolved']);
            exit();
        }
    }
}

echo json_encode(['status' => 'error', 'message' => 'Invalid endpoint.']);
exit();
?>