<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manager Dashboard - EMRS</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="dashboard-container">
        <h2>Manager Portal</h2>
        <p>Logged in as: <?= htmlspecialchars($_SESSION['user_name']); ?></p>
        <a href="index.php?url=logout">Logout</a>

        <h3>All System Requests & Technician Assignment</h3>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Employee</th>
                    <th>Equipment</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Assign Technician</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($requests)): ?>
                    <?php foreach ($requests as $req): ?>
                        <tr>
                            <td><?= $req['id']; ?></td>
                            <td><?= htmlspecialchars($req['requester_name'] ?? 'N/A'); ?></td>
                            <td><?= htmlspecialchars($req['equipment_name']); ?></td>
                            <td><?= htmlspecialchars($req['description']); ?></td>
                            <td><?= htmlspecialchars($req['status']); ?></td>
                            <td>
                                <form action="index.php?url=manager/assign" method="POST" style="display:flex; gap: 5px;">
                                    <input type="hidden" name="request_id" value="<?= $req['id']; ?>">
                                    <select name="technician_id" required style="width: auto;">
                                        <option value="">Select Technician</option>
                                        <?php if (!empty($technicians)): ?>
                                            <?php foreach ($technicians as $tech): ?>
                                                <option value="<?= $tech['id']; ?>" <?= ($req['technician_id'] == $tech['id']) ? 'selected' : ''; ?>>
                                                    <?= htmlspecialchars($tech['full_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <button type="submit" style="margin-top:0;">Assign</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6">No requests found in system.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>