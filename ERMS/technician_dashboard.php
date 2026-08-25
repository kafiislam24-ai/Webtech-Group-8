<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Technician') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMRS - Technician Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header class="top-nav">
        <h1>EMRS</h1>
        <div class="user-info">
            <span><?php echo htmlspecialchars($_SESSION['user_name']); ?> (Technician)</span>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </header>

    <main class="dashboard-container">
        
        <section class="metrics-grid">
            <div class="card">
                <h3>Assigned Tasks</h3>
                <p class="metric-number">3</p>
            </div>
            <div class="card">
                <h3>In Progress</h3>
                <p class="metric-number">1</p>
            </div>
            <div class="card">
                <h3>Completed Today</h3>
                <p class="metric-number">4</p>
            </div>
        </section>

        <section class="table-section">
            <div class="dashboard-header">
                <h2>My Assigned Work Queue</h2>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Task and Equipment</th>
                        <th>Assigned By</th>
                        <th>Work Status</th>
                        <th>Spare Parts Used</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Anas - Monitor Replacement</td>
                        <td>Manager</td>
                        <td>
                            <form id="techTaskForm1" action="form_handler.php" method="POST" class="inline-form">
                                <input type="hidden" name="action" value="update_task">
                                <input type="hidden" name="task_id" value="301">
                                <select name="work_status">
                                    <option value="In Progress" selected>In Progress</option>
                                    <option value="Awaiting Parts">Awaiting Parts</option>
                                    <option value="Resolved">Resolved</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <span class="badge badge-part">1X DisplayPort cable</span>
                        </td>
                        <td>
                            <button type="submit" form="techTaskForm1" class="btn-success btn-sm">Mark Resolved</button>
                        </td>
                    </tr>

                    <tr>
                        <td>Kafi - PC Power Supply</td>
                        <td>Manager</td>
                        <td>
                            <span class="badge badge-pending">Assigned</span>
                        </td>
                        <td>
                            <form id="techTaskForm2" action="form_handler.php" method="POST" class="inline-form">
                                <input type="hidden" name="action" value="start_task">
                                <input type="hidden" name="task_id" value="302">
                                <select name="part_used">
                                    <option value="">Select Parts...</option>
                                    <option value="Power Supply Unit (550W)">Power Supply Unit (550W)</option>
                                    <option value="RAM 8GB DDR4">RAM 8GB DDR4</option>
                                    <option value="SATA Cable">SATA Cable</option>
                                </select>
                            </form>
                        </td>
                        <td>
                            <button type="submit" form="techTaskForm2" class="btn-info btn-sm">Start Work</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

    </main>

</body>
</html>