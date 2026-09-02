<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Dashboard - EMRS</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?></h2>
        <div class="nav-actions">
            <a href="index.php?url=employee/createRequest" class="btn">New Maintenance Request</a>
            <a href="index.php?url=logout" class="btn logout">Logout</a>
        </div>

        <h3>My Submitted Requests</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Equipment</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Submitted On</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($requests)): ?>
                    <?php foreach ($requests as $req): ?>
                        <tr>
                            <td><?= $req['id']; ?></td>
                            <td><?= htmlspecialchars($req['equipment_name']); ?></td>
                            <td><?= htmlspecialchars($req['description']); ?></td>
                            <td><span class="status-badge <?= strtolower(str_replace(' ', '-', $req['status'])); ?>"><?= htmlspecialchars($req['status']); ?></span></td>
                            <td><?= $req['created_at'] ?? 'N/A'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="5">No maintenance requests logged yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>