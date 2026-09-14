<?php
session_start();

// 1. Role-Based Access Control (RBAC) Guard
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Technician') {
    header("Location: login.php");
    exit();
}

// 2. Fetch assigned tasks directly from the Model
require_once __DIR__ . '/../models/requestModel.php';
$assignedTasks = getAssignedTasks($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EMRS - Technician Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header>
        <h1>Equipment & Maintenance Request System</h1>
        <div>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> (Technician)</span>
            <a href="../logout.php">Logout</a>
        </div>
    </header>

    <div class="container" style="max-width: 1050px;">
        <h2>My Assigned Tasks</h2>
        <p style="color: #666; font-size: 14px; margin-top: 4px;">Update ticket progress as you inspect and repair equipment.</p>

        <?php if (empty($assignedTasks)): ?>
            <div style="background: #ffffff; padding: 25px; border: 1px solid #e0e0e0; border-radius: 4px; text-align: center; margin-top: 20px;">
                <p style="color: #666;">You currently have no tasks assigned to you.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Equipment</th>
                        <th>Reported By</th>
                        <th>Priority</th>
                        <th>Description</th>
                        <th>Assigned Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assignedTasks as $task): ?>
                        <tr id="row_<?php echo $task['RequestID']; ?>">
                            <td>#<?php echo $task['RequestID']; ?></td>
                            <td><strong><?php echo htmlspecialchars($task['ItemName']); ?></strong></td>
                            <td><?php echo htmlspecialchars($task['EmployeeName']); ?></td>
                            <td>
                                <span style="font-weight: bold; color: <?php echo ($task['Priority'] === 'High') ? '#e74c3c' : (($task['Priority'] === 'Medium') ? '#f39c12' : '#27ae60'); ?>;">
                                    <?php echo htmlspecialchars($task['Priority']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($task['Description']); ?></td>
                            <td><?php echo date("M d, Y - h:i A", strtotime($task['AssignedDate'])); ?></td>

                            <!-- Dynamic Status Badge Cell -->
                            <td id="status_cell_<?php echo $task['RequestID']; ?>">
                                <?php
                                $badgeClass = 'badge-pending';
                                if ($task['Status'] === 'Assigned' || $task['Status'] === 'In Progress') {
                                    $badgeClass = 'badge-progress';
                                } elseif ($task['Status'] === 'Resolved' || $task['Status'] === 'Completed') {
                                    $badgeClass = 'badge-resolved';
                                } elseif ($task['Status'] === 'Cancelled') {
                                    $badgeClass = 'badge-rejected';
                                }
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>">
                                    <?php echo htmlspecialchars($task['Status']); ?>
                                </span>
                            </td>

                            <!-- Dynamic Action Button Cell -->
                            <td id="action_cell_<?php echo $task['RequestID']; ?>">
                                <?php if ($task['Status'] === 'Assigned'): ?>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="updateTaskStatus(<?php echo $task['RequestID']; ?>, 'start_task')">
                                        Start Work
                                    </button>

                                <?php elseif ($task['Status'] === 'In Progress'): ?>
                                    <button type="button" class="btn btn-success btn-sm" onclick="updateTaskStatus(<?php echo $task['RequestID']; ?>, 'mark_resolved')">
                                        Mark Resolved
                                    </button>

                                <?php elseif ($task['Status'] === 'Resolved' || $task['Status'] === 'Completed'): ?>
                                    <span style="color: #27ae60; font-weight: bold; font-size: 13px;">✓ Done</span>

                                <?php else: ?>
                                    <span style="color: #999; font-size: 13px;">N/A</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- AJAX (POST) State Transition Script -->
    <script>
    function updateTaskStatus(requestId, operation) {
        let formData = new FormData();
        formData.append("request_id", requestId);
        formData.append("operation", operation);

        // Send asynchronous POST request to ajaxStatusController.php
        fetch("../controllers/ajaxStatusController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                let statusCell = document.getElementById("status_cell_" + requestId);
                let actionCell = document.getElementById("action_cell_" + requestId);

                if (operation === "start_task") {
                    // Transition 1: Assigned -> In Progress
                    statusCell.innerHTML = '<span class="badge badge-progress">In Progress</span>';
                    actionCell.innerHTML = `<button type="button" class="btn btn-success btn-sm" onclick="updateTaskStatus(${requestId}, 'mark_resolved')">Mark Resolved</button>`;
                } else if (operation === "mark_resolved") {
                    // Transition 2: In Progress -> Resolved
                    statusCell.innerHTML = '<span class="badge badge-resolved">Resolved</span>';
                    actionCell.innerHTML = '<span style="color: #27ae60; font-weight: bold; font-size: 13px;">✓ Done</span>';
                }
            } else {
                alert("Failed to update status. Server error: " + data);
            }
        })
        .catch(error => {
            console.error("AJAX Error:", error);
            alert("A network error occurred.");
        });
    }
    </script>

</body>
</html>