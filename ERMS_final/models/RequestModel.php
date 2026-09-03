<?php
require_once __DIR__ . '/../config/db.php';

class RequestModel {

    public static function getAllEquipment($conn) {
        $sql = "SELECT EquipmentID, ItemName, Category FROM equipment ORDER BY ItemName ASC";
        $result = mysqli_query($conn, $sql);
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        return $items;
    }

    public static function createRequest($conn, $employeeId, $equipmentId, $description, $priority) {
        $sql = "INSERT INTO requests (EmployeeID, EquipmentID, Description, Priority, Status, CreatedAt) 
                VALUES (?, ?, ?, ?, 'Pending', NOW())";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iiss", $employeeId, $equipmentId, $description, $priority);
        return mysqli_stmt_execute($stmt);
    }

    public static function getEmployeeDashboardData($conn, $employeeId) {
        $activeRes = mysqli_query($conn, "SELECT COUNT(*) as c FROM requests WHERE EmployeeID = $employeeId AND Status NOT IN ('Resolved', 'Completed', 'Cancelled')");
        $resolvedRes = mysqli_query($conn, "SELECT COUNT(*) as c FROM requests WHERE EmployeeID = $employeeId AND Status IN ('Resolved', 'Completed')");
        
        $active = mysqli_fetch_assoc($activeRes)['c'];
        $resolved = mysqli_fetch_assoc($resolvedRes)['c'];

        $sql = "SELECT r.RequestID, r.Description, r.Priority, r.Status, r.CreatedAt, e.ItemName 
                FROM requests r 
                JOIN equipment e ON r.EquipmentID = e.EquipmentID 
                WHERE r.EmployeeID = $employeeId 
                ORDER BY r.CreatedAt DESC";
        $requests = mysqli_query($conn, $sql);

        return ['activeCount' => $active, 'resolvedCount' => $resolved, 'requests' => $requests];
    }

    public static function getManagerDashboardData($conn) {
        $pending = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM requests WHERE Status = 'Pending'"))['c'];
        $active  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM requests WHERE Status IN ('Assigned', 'In Progress')"))['c'];
        $resolved= mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM requests WHERE Status IN ('Resolved', 'Completed')"))['c'];

        $sql = "SELECT r.RequestID, r.Description, r.Priority, r.Status, r.CreatedAt, 
                       u.Name as EmployeeName, e.ItemName, tech.Name as TechName
                FROM requests r
                JOIN users u ON r.EmployeeID = u.UserID
                JOIN equipment e ON r.EquipmentID = e.EquipmentID
                LEFT JOIN assignments a ON r.RequestID = a.RequestID
                LEFT JOIN users tech ON a.TechnicianID = tech.UserID
                ORDER BY r.CreatedAt DESC";
        $requests = mysqli_query($conn, $sql);

        return ['pendingCount' => $pending, 'activeCount' => $active, 'resolvedCount' => $resolved, 'requests' => $requests];
    }

    public static function assignAndApprove($conn, $requestId, $managerId, $technicianId) {
        $stmt1 = mysqli_prepare($conn, "UPDATE requests SET Status = 'Assigned' WHERE RequestID = ?");
        mysqli_stmt_bind_param($stmt1, "i", $requestId);
        mysqli_stmt_execute($stmt1);

        $stmt2 = mysqli_prepare($conn, "INSERT INTO assignments (RequestID, ManagerID, TechnicianID, AssignedDate) VALUES (?, ?, ?, NOW())");
        mysqli_stmt_bind_param($stmt2, "iii", $requestId, $managerId, $technicianId);
        return mysqli_stmt_execute($stmt2);
    }

    public static function getTechnicianDashboardData($conn, $techId) {
        $assigned = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM assignments a JOIN requests r ON a.RequestID = r.RequestID WHERE a.TechnicianID = $techId AND r.Status = 'Assigned'"))['c'];
        $inProgress = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM assignments a JOIN requests r ON a.RequestID = r.RequestID WHERE a.TechnicianID = $techId AND r.Status = 'In Progress'"))['c'];
        $completed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as c FROM assignments a JOIN requests r ON a.RequestID = r.RequestID WHERE a.TechnicianID = $techId AND r.Status IN ('Resolved', 'Completed')"))['c'];

        $sql = "SELECT r.RequestID, r.Description, r.Priority, r.Status, 
                       u.Name as EmployeeName, mgr.Name as ManagerName, e.ItemName 
                FROM assignments a
                JOIN requests r ON a.RequestID = r.RequestID
                JOIN users u ON r.EmployeeID = u.UserID
                JOIN users mgr ON a.ManagerID = mgr.UserID
                JOIN equipment e ON r.EquipmentID = e.EquipmentID
                WHERE a.TechnicianID = $techId
                ORDER BY a.AssignedDate DESC";
        $tasks = mysqli_query($conn, $sql);

        return ['assignedCount' => $assigned, 'inProgressCount' => $inProgress, 'completedCount' => $completed, 'tasks' => $tasks];
    }

    public static function updateStatus($conn, $requestId, $status) {
        $stmt = mysqli_prepare($conn, "UPDATE requests SET Status = ? WHERE RequestID = ?");
        mysqli_stmt_bind_param($stmt, "si", $status, $requestId);
        $res = mysqli_stmt_execute($stmt);

        if ($status === 'Resolved') {
            $stmtComp = mysqli_prepare($conn, "UPDATE assignments SET CompletionDate = NOW() WHERE RequestID = ?");
            mysqli_stmt_bind_param($stmtComp, "i", $requestId);
            mysqli_stmt_execute($stmtComp);
        }
        return $res;
    }

    public static function getEquipmentById($conn, $equipmentId) {
        $sql = "SELECT EquipmentID, ItemName, Category, StockQuantity, ConditionStatus 
                FROM equipment 
                WHERE EquipmentID = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $equipmentId);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    }
}
?>