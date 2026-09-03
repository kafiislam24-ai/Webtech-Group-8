<?php
session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../models/RequestModel.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    header("Location: login.php");
    exit();
}

$equipment = RequestModel::getAllEquipment($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EMRS - Submit New Request</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

    <header>
        <h1>Equipment & Maintenance Request System</h1>
        <nav><a href="employee_dashboard.php" class="nav-link">Back to Dashboard</a></nav>
    </header>

    <main class="auth-wrapper">
        <section class="form-card">
            <h2>Submit New Request</h2>
            <form id="requestForm">
                <div class="form-group">
                    <label for="reqEquipment">Select Equipment:</label>
                    <select id="reqEquipment" name="equipment_id" required>
                        <option value="">-- Choose Equipment --</option>
                        <?php foreach ($equipment as $eq): ?>
                            <option value="<?php echo $eq['EquipmentID']; ?>">
                                <?php echo htmlspecialchars($eq['ItemName']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="equipmentInfoBox" style="margin-bottom: 15px; display: none;"></div>

                <div class="form-group">
                    <label for="reqPriority">Priority Level:</label>
                    <select id="reqPriority" name="priority" required>
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="reqDescription">Issue Description:</label>
                    <textarea id="reqDescription" name="description" rows="4" placeholder="Describe the hardware fault..." required></textarea>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-primary">Submit Request</button>
                    <a href="employee_dashboard.php" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </section>
    </main>

    <script src="../assets/js/ajax_handler.js"></script>
</body>
</html>