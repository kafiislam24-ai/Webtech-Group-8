<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Manager') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMRS - Manager Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="top-nav">
        <h1>EMRS</h1>
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?> (Manager)</span>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <main class="dashboard-container">
        
        <section class="metrics-grid">
            <div class="card">
                <h3>Pending Approvals</h3>
                <p class="metric-number">3</p>
            </div>
            <div class="card">
                <h3>Active Maintenance</h3>
                <p class="metric-number">4</p>
            </div>
            <div class="card">
                <h3>Total Resolved</h3>
                <p class="metric-number">18</p>
            </div>
        </section>

        <section class="table-section">
            <div class="dashboard-header">
                <h2>Incoming Equipment Requests</h2>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Employee and Request</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Assign Technician</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Anas - Monitor Replacement</td>
                        <td>Oct 12</td>
                        <td><span class="badge badge-pending">Pending</span></td>
                        <td>
                            <form id="assignForm1" action="form_handler.php" method="POST" class="inline-form">
                                <input type="hidden" name="action" value="assign_and_approve">
                                <input type="hidden" name="request_id" value="201">
                                <select name="technician_id" required>
                                    <option value="">Select Tech</option>
                                    <option value="1">Tech: John</option>
                                    <option value="2">Tech: Alex</option>
                                    <option value="3">Tech: Sarah</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button type="submit" form="assignForm1" name="decision" value="approve" class="btn-success btn-sm">Approve</button>
                                <form action="form_handler.php" method="POST" class="inline-form">
                                    <input type="hidden" name="action" value="reject_request">
                                    <input type="hidden" name="request_id" value="201">
                                    <button type="submit" class="btn-danger btn-sm">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <td>Kafi - PC Power Supply</td>
                        <td>Oct 10</td>
                        <td><span class="badge badge-progress">Assigned</span></td>
                        <td>
                            <span class="badge badge-tech">Tech: John</span>
                        </td>
                        <td>
                            <span class="text-muted">Approved</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

    </main>

</body>
</html>