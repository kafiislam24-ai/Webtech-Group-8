<?php
require_once __DIR__ . '/../dbConnect.php';


function getAllEquipment() {
    global $conn;

    $sql = "SELECT EquipmentID, ItemName FROM equipment ORDER BY ItemName ASC";
    $result = mysqli_query($conn, $sql);

    $items = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
    }
    return $items;
}


function getEquipmentDetails($id) {
    global $conn;

    $id = (int)$id;
    $sql = "SELECT ItemName, Category, StockQuantity, ConditionStatus 
            FROM equipment 
            WHERE EquipmentID = $id 
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return false;
}


function createRequest($empId, $equipId, $desc, $priority) {
    global $conn;

    $empId    = (int)$empId;
    $equipId  = (int)$equipId;
    $desc     = mysqli_real_escape_string($conn, $desc);
    $priority = mysqli_real_escape_string($conn, $priority);

    $sql = "INSERT INTO requests (EmployeeID, EquipmentID, Description, Priority, Status) 
            VALUES ($empId, $equipId, '$desc', '$priority', 'Pending')";

    return mysqli_query($conn, $sql);
}


function getRequestsByEmployee($empId) {
    global $conn;

    $empId = (int)$empId;
    $sql = "SELECT r.RequestID, r.Description, r.Priority, r.Status, r.CreatedAt, 
                   e.ItemName, e.Category 
            FROM requests r 
            JOIN equipment e ON r.EquipmentID = e.EquipmentID 
            WHERE r.EmployeeID = $empId 
            ORDER BY r.CreatedAt DESC";

    $result = mysqli_query($conn, $sql);

    $requests = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $requests[] = $row;
        }
    }
    return $requests;
}


function getAllRequestsWithDetails() {
    global $conn;

    $sql = "SELECT r.RequestID, r.Description, r.Priority, r.Status, r.CreatedAt, 
                   e.ItemName, 
                   u.Name AS EmployeeName, 
                   t.Name AS TechName 
            FROM requests r 
            JOIN equipment e ON r.EquipmentID = e.EquipmentID 
            JOIN users u ON r.EmployeeID = u.UserID 
            LEFT JOIN assignments a ON r.RequestID = a.RequestID 
            LEFT JOIN users t ON a.TechnicianID = t.UserID 
            ORDER BY r.CreatedAt DESC";

    $result = mysqli_query($conn, $sql);

    $requests = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $requests[] = $row;
        }
    }
    return $requests;
}


function getAssignedTasks($techId) {
    global $conn;

    $techId = (int)$techId;
    $sql = "SELECT a.AssignmentID, a.AssignedDate, a.CompletionDate, 
                   r.RequestID, r.Description, r.Priority, r.Status, 
                   e.ItemName, 
                   u.Name AS EmployeeName 
            FROM assignments a 
            JOIN requests r ON a.RequestID = r.RequestID 
            JOIN equipment e ON r.EquipmentID = e.EquipmentID 
            JOIN users u ON r.EmployeeID = u.UserID 
            WHERE a.TechnicianID = $techId 
            ORDER BY a.AssignedDate DESC";

    $result = mysqli_query($conn, $sql);

    $tasks = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $tasks[] = $row;
        }
    }
    return $tasks;
}


function updateRequestStatus($requestId, $newStatus) {
    global $conn;

    $requestId = (int)$requestId;
    $newStatus = mysqli_real_escape_string($conn, $newStatus);

    $sql = "UPDATE requests SET Status = '$newStatus' WHERE RequestID = $requestId";
    $updated = mysqli_query($conn, $sql);

    if ($updated && ($newStatus === 'Resolved' || $newStatus === 'Completed')) {
        $dateSql = "UPDATE assignments SET CompletionDate = NOW() WHERE RequestID = $requestId";
        mysqli_query($conn, $dateSql);
    }

    return $updated;
}


function assignTechnician($requestId, $managerId, $techId) {
    global $conn;

    $requestId = (int)$requestId;
    $managerId = (int)$managerId;
    $techId    = (int)$techId;

    $updateSql = "UPDATE requests SET Status = 'Assigned' WHERE RequestID = $requestId";
    $statusUpdated = mysqli_query($conn, $updateSql);

    if (!$statusUpdated) {
        return false;
    }

    $assignSql = "INSERT INTO assignments (RequestID, ManagerID, TechnicianID) 
                  VALUES ($requestId, $managerId, $techId)";

    return mysqli_query($conn, $assignSql);
}
?>