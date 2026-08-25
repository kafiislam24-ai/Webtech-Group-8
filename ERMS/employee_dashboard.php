<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMRS - Employee Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="top-nav">
        <h1>EMRS</h1>
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?> (Employee)</span>
            <a href="logout.php" class="btn-logout">Logout</a>
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
                <p class="metric-number">2</p>
            </div>
            <div class="card">
                <h3>Resolved Requests</h3>
                <p class="metric-number">5</p>
            </div>
        </section>

        <section class="table-section">
            <table>
                <thead>
                    <tr>
                        <th>Request Title</th>
                        <th>Date Submitted</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Monitor Replacement</td>
                        <td>Oct 12</td>
                        <td><span class="badge badge-pending">Pending</span></td>
                        <td>
                            <form action="form_handler.php" method="POST" class="inline-form">
                                <input type="hidden" name="action" value="cancel_request">
                                <input type="hidden" name="request_id" value="101">
                                <button type="submit" class="btn-danger btn-sm">Cancel</button>
                            </form>
                        </td>
                    </tr>

                    <tr>
                        <td>PC Power Supply Unit</td>
                        <td>Oct 10</td>
                        <td><span class="badge badge-progress">In Progress</span></td>
                        <td>
                            <a href="#" class="btn-info btn-sm">View Details</a>
                        </td>
                    </tr>

                    <tr>
                        <td>Chair Repair</td>
                        <td>Oct 8</td>
                        <td><span class="badge badge-resolved">Resolved</span></td>
                        <td>
                            <form action="form_handler.php" method="POST" class="inline-form">
                                <input type="hidden" name="action" value="confirm_resolution">
                                <input type="hidden" name="request_id" value="103">
                                <button type="submit" class="btn-success btn-sm">Confirm Done</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

    </main>

</body>
</html>