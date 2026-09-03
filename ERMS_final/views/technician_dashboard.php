<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/RequestModel.php';

// Access Control check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Technician') {
    header("Location: login.php");
    exit();
}

$data = RequestModel::getTechnicianDashboardData($conn, $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMRS - Technician Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <header class="top-nav">
        <h1>EMRS</h1>
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?> (Technician)</span>
            <a href="../logout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <main class="dashboard-container">
        <!-- Metric Summary Cards -->
        <section class="metrics-grid">
            <div class="card">
                <h3>Assigned Tasks</h3>
                <p class="metric-number"><?php echo $data['assignedCount']; ?></p>
            </div>
            <div class="card">
                <h3>In Progress</h3>
                <p class="metric-number"><?php echo $data['inProgressCount']; ?></p>
            </div>
            <div class="card">
                <h3>Completed</h3>
                <p class="metric-number"><?php echo $data['completedCount']; ?></p>
            </div>
        </section>

        <section class="table-section">
            <div class="dashboard-header">
                <h2>My Assigned Work Queue</h2>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Task & Equipment</th>
                        <th>Assigned By</th>
                        <th>Work Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($data['tasks']) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($data['tasks'])): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['EmployeeName']); ?></strong> - <?php echo htmlspecialchars($row['ItemName']); ?><br>
                                    <small><?php echo htmlspecialchars($row['Description']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['ManagerName']); ?></td>
                                
                                <!-- Targeted by AJAX to dynamically update status badge -->
                                <td class="tech-status-cell">
                                    <span class="badge <?php echo ($row['Status'] === 'Assigned') ? 'badge-pending' : (($row['Status'] === 'Resolved' || $row['Status'] === 'Completed') ? 'badge-resolved' : 'badge-progress'); ?>">
                                        <?php echo htmlspecialchars($row['Status']); ?>
                                    </span>
                                </td>

                                <td class="tech-action-cell">
                                    <?php if ($row['Status'] === 'Assigned'): ?>
                                        <button type="button" class="btn-info btn-sm tech-action-btn" data-request-id="<?php echo $row['RequestID']; ?>" data-operation="start_task">Start Work</button>
                                    <?php elseif ($row['Status'] === 'In Progress'): ?>
                                        <button type="button" class="btn-success btn-sm tech-action-btn" data-request-id="<?php echo $row['RequestID']; ?>" data-operation="mark_resolved">Mark Resolved</button>
                                    <?php else: ?>
                                        <span class="text-muted">Task Finished</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" style="text-align: center;">No tasks assigned currently.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <script src="../assets/js/ajax_handler.js"></script>
</body>
</html>