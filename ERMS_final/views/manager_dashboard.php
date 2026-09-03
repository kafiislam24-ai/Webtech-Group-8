<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/RequestModel.php';

// Access Control check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Manager') {
    header("Location: login.php");
    exit();
}

$data = RequestModel::getManagerDashboardData($conn);
$technicians = UserModel::getTechnicians($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMRS - Manager Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <header class="top-nav">
        <h1>EMRS</h1>
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?> (Manager)</span>
            <a href="../logout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <main class="dashboard-container">
        <!-- Metric Summary Cards -->
        <section class="metrics-grid">
            <div class="card">
                <h3>Pending Approvals</h3>
                <p class="metric-number"><?php echo $data['pendingCount']; ?></p>
            </div>
            <div class="card">
                <h3>Active Maintenance</h3>
                <p class="metric-number"><?php echo $data['activeCount']; ?></p>
            </div>
            <div class="card">
                <h3>Total Resolved</h3>
                <p class="metric-number"><?php echo $data['resolvedCount']; ?></p>
            </div>
        </section>

        <!-- Requests Table with AJAX Hooks -->
        <section class="table-section">
            <div class="dashboard-header">
                <h2>Incoming Equipment Requests</h2>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Employee & Request</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Assign Technician</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($data['requests']) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($data['requests'])): ?>
                            <tr>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['EmployeeName']); ?></strong> - <?php echo htmlspecialchars($row['ItemName']); ?><br>
                                    <small><?php echo htmlspecialchars($row['Description']); ?></small>
                                </td>
                                <td><?php echo date('M d', strtotime($row['CreatedAt'])); ?></td>
                                
                                <td class="status-cell">
                                    <span class="badge <?php echo ($row['Status'] === 'Pending') ? 'badge-pending' : (($row['Status'] === 'Resolved' || $row['Status'] === 'Completed') ? 'badge-resolved' : 'badge-progress'); ?>">
                                        <?php echo htmlspecialchars($row['Status']); ?>
                                    </span>
                                </td>

                                <td class="assign-cell">
                                    <?php if ($row['Status'] === 'Pending'): ?>
                                        <select name="technician_id">
                                            <option value="">Select Tech</option>
                                            <?php foreach ($technicians as $tech): ?>
                                                <option value="<?php echo $tech['UserID']; ?>"><?php echo htmlspecialchars($tech['Name']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else: ?>
                                        <span class="badge badge-tech"><?php echo htmlspecialchars($row['TechName'] ?? 'Unassigned'); ?></span>
                                    <?php endif; ?>
                                </td>

                                <td class="action-cell">
                                    <?php if ($row['Status'] === 'Pending'): ?>
                                        <div class="btn-group">
                                            <button type="button" class="btn-success btn-sm manager-action-btn" data-request-id="<?php echo $row['RequestID']; ?>" data-operation="assign_and_approve">Approve</button>
                                            <button type="button" class="btn-danger btn-sm manager-action-btn" data-request-id="<?php echo $row['RequestID']; ?>" data-operation="reject_request">Reject</button>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted">Processed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" style="text-align: center;">No incoming requests.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <script src="../assets/js/ajax_handler.js"></script>
</body>
</html>