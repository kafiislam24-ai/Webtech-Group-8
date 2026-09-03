<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/RequestModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    header("Location: login.php");
    exit();
}

$data = RequestModel::getEmployeeDashboardData($conn, $_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EMRS - Employee Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <header class="top-nav">
        <h1>EMRS</h1>
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?> (Employee)</span>
            <a href="../logout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <main class="dashboard-container">
        <div class="dashboard-header">
            <h2>My Equipment Requests</h2>
            <a href="request_form.php" class="btn-primary">+ Submit New Request</a>
        </div>

        <section class="metrics-grid">
            <div class="card">
                <h3>Active Requests</h3>
                <p class="metric-number"><?php echo $data['activeCount']; ?></p>
            </div>
            <div class="card">
                <h3>Resolved Requests</h3>
                <p class="metric-number"><?php echo $data['resolvedCount']; ?></p>
            </div>
        </section>

        <section class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Request Title / Equipment</th>
                        <th>Date Submitted</th>
                        <th>Priority</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($data['requests']) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($data['requests'])): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['ItemName']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($row['Description']); ?></small>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($row['CreatedAt'])); ?></td>
                                <td><?php echo htmlspecialchars($row['Priority']); ?></td>
                                <td>
                                    <?php 
                                        $badgeClass = 'badge-pending';
                                        if ($row['Status'] === 'In Progress' || $row['Status'] === 'Assigned') $badgeClass = 'badge-progress';
                                        if ($row['Status'] === 'Resolved' || $row['Status'] === 'Completed') $badgeClass = 'badge-resolved';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($row['Status']); ?></span>
                                </td>
                                <td>
                                    <?php if ($row['Status'] === 'Pending'): ?>
                                        <form action="../controllers/RequestController.php" method="POST" class="inline-form">
                                            <input type="hidden" name="action" value="cancel_request">
                                            <input type="hidden" name="request_id" value="<?php echo $row['RequestID']; ?>">
                                            <button type="submit" class="btn-danger btn-sm">Cancel</button>
                                        </form>
                                    <?php elseif ($row['Status'] === 'Resolved'): ?>
                                        <form action="../controllers/RequestController.php" method="POST" class="inline-form">
                                            <input type="hidden" name="action" value="confirm_resolution">
                                            <input type="hidden" name="request_id" value="<?php echo $row['RequestID']; ?>">
                                            <button type="submit" class="btn-success btn-sm">Confirm Done</button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted">In Tracking</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align: center;">No requests submitted yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>