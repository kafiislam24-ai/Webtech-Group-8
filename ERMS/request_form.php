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
    <title>EMRS - Submit New Request</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <h1>Equipment & Maintenance Request System</h1>
        <nav>
            <a href="employee_dashboard.php" class="nav-link">Back to Dashboard</a>
            <div class="user-info">
                <span><?php echo htmlspecialchars($_SESSION['user_name']); ?> (Employee)</span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </nav>
    </header>

    <main class="auth-wrapper">
        <section class="form-card">
            <h2>Submit New Request</h2>
            
            <form id="requestForm" action="form_handler.php" method="POST">
                <input type="hidden" name="action" value="create_request">

                <div class="form-group">
                    <label for="reqEquipmentName">Equipment Name:</label>
                    <input type="text" id="reqEquipmentName" name="equipment_name" placeholder="e.g., Monitor, Power Supply, Printer">
                    <span class="error-msg" id="reqEquipmentError"></span>
                </div>

                <div class="form-group">
                    <label for="reqPriority">Priority Level:</label>
                    <select id="reqPriority" name="priority">
                        <option value="">-- Select Priority --</option>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                    <span class="error-msg" id="reqPriorityError"></span>
                </div>

                <div class="form-group">
                    <label for="reqDescription">Issue Description:</label>
                    <textarea id="reqDescription" name="description" rows="5" placeholder="Describe the hardware fault or maintenance issue in detail..."></textarea>
                    <span class="error-msg" id="reqDescriptionError"></span>
                </div>

                <div class="button-group">
                    <button type="submit" name="submit_request_btn" class="btn-primary">Submit Request</button>
                    <a href="employee_dashboard.php" class="btn-secondary">Cancel</a>
                </div>
            </form>
        </section>
    </main>

    <script src="validation.js"></script>
</body>
</html>