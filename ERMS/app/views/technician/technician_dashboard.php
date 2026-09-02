<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Technician Dashboard - EMRS</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, Technician <?= htmlspecialchars($_SESSION['user_name']); ?></h2>
        <a href="index.php?url=logout">Logout</a>
        <h3>Assigned Maintenance Tasks</h3>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Equipment</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tasks)): ?>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td><?= $task['id']; ?></td>
                            <td><?= htmlspecialchars($task['equipment_name']); ?></td>
                            <td><?= htmlspecialchars($task['description']); ?></td>
                            <td><?= htmlspecialchars($task['status']); ?></td>
                            <td>
                                <form action="index.php?url=technician/updateStatus" method="POST">
                                    <input type="hidden" name="request_id" value="<?= $task['id']; ?>">
                                    <select name="status">
                                        <option value="In Progress">In Progress</option>
                                        <option value="Resolved">Resolved</option>
                                    </select>
                                    <button type="submit">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No assigned tasks found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>