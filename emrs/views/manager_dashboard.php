<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Manager') {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../models/requestModel.php';
require_once __DIR__ . '/../models/userModel.php';

$allRequests = getAllRequestsWithDetails();
$technicians = getTechnicians();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EMRS - Manager Dashboard</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header>
        <h1>Equipment & Maintenance Request System</h1>
        <div>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> (Manager)</span>
            <a href="../logout.php">Logout</a>
        </div>
    </header>

    <div class="container" style="max-width: 1050px;">
        <h2>Incoming Maintenance Requests</h2>
        <p style="color: #666; font-size: 14px; margin-top: 4px;">Assign a technician and approve pending requests.</p>

        <?php if (empty($allRequests)): ?>
            <div style="background: #ffffff; padding: 25px; border: 1px solid #e0e0e0; border-radius: 4px; text-align: center; margin-top: 20px;">
                <p style="color: #666;">No maintenance requests found in the system.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Employee</th>
                        <th>Equipment</th>
                        <th>Priority</th>
                        <th>Description</th>
                        <th>Assign Technician</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allRequests as $row): ?>
                        <tr id="row_<?php echo $row['RequestID']; ?>">
                            <td>#<?php echo $row['RequestID']; ?></td>
                            <td><?php echo htmlspecialchars($row['EmployeeName']); ?></td>
                            <td><strong><?php echo htmlspecialchars($row['ItemName']); ?></strong></td>
                            <td>
                                <span style="font-weight: bold; color: <?php echo ($row['Priority'] === 'High') ? '#e74c3c' : (($row['Priority'] === 'Medium') ? '#f39c12' : '#27ae60'); ?>;">
                                    <?php echo htmlspecialchars($row['Priority']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($row['Description']); ?></td>

                            <td id="tech_cell_<?php echo $row['RequestID']; ?>">
                                <?php if ($row['Status'] === 'Pending'): ?>
                                    <select id="tech_select_<?php echo $row['RequestID']; ?>" style="padding: 4px 6px; font-size: 13px;">
                                        <option value="">-- Choose Tech --</option>
                                        <?php foreach ($technicians as $tech): ?>
                                            <option value="<?php echo $tech['UserID']; ?>">
                                                <?php echo htmlspecialchars($tech['Name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php else: ?>
                                    <span><?php echo htmlspecialchars($row['TechName'] ?? 'Assigned'); ?></span>
                                <?php endif; ?>
                            </td>

                            <td id="status_cell_<?php echo $row['RequestID']; ?>">
                                <?php
                                $badgeClass = 'badge-pending';
                                if ($row['Status'] === 'Assigned' || $row['Status'] === 'In Progress') {
                                    $badgeClass = 'badge-progress';
                                } elseif ($row['Status'] === 'Resolved' || $row['Status'] === 'Completed') {
                                    $badgeClass = 'badge-resolved';
                                } elseif ($row['Status'] === 'Cancelled') {
                                    $badgeClass = 'badge-rejected';
                                }
                                ?>
                                <span class="badge <?php echo $badgeClass; ?>">
                                    <?php echo htmlspecialchars($row['Status']); ?>
                                </span>
                            </td>

                            <td id="action_cell_<?php echo $row['RequestID']; ?>">
                                <?php if ($row['Status'] === 'Pending'): ?>
                                    <button type="button" class="btn btn-success btn-sm" onclick="approveTicket(<?php echo $row['RequestID']; ?>)">
                                        Approve
                                    </button>
                                <?php else: ?>
                                    <span style="color: #999; font-size: 13px;">Processed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <script>
    function approveTicket(requestId) {
        let selectElement = document.getElementById("tech_select_" + requestId);
        let techId = selectElement.value;

        if (techId === "") {
            alert("Please select a technician before approving the ticket.");
            return;
        }

        let techName = selectElement.options[selectElement.selectedIndex].text;

        let formData = new FormData();
        formData.append("request_id", requestId);
        formData.append("operation", "assign_and_approve");
        formData.append("tech_id", techId);

        fetch("../controllers/ajaxStatusController.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === "success") {
                document.getElementById("tech_cell_" + requestId).innerText = techName;

                document.getElementById("status_cell_" + requestId).innerHTML = 
                    '<span class="badge badge-progress">Assigned</span>';

                document.getElementById("action_cell_" + requestId).innerHTML = 
                    '<span style="color: #999; font-size: 13px;">Processed</span>';
            } else {
                alert("Failed to update status. Server response: " + data);
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