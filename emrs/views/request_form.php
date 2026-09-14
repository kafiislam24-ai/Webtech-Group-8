<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Employee') {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../models/requestModel.php';
$equipmentList = getAllEquipment();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>EMRS - Submit Maintenance Request</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <header>
        <h1>Equipment & Maintenance Request System</h1>
        <div>
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
            <a href="employee_dashboard.php">Dashboard</a>
            <a href="../logout.php">Logout</a>
        </div>
    </header>

    <div class="container">
        <div class="form-card" style="max-width: 550px;">
            <h2>Submit Maintenance Request</h2>

            <?php if (isset($_GET['error'])): ?>
                <p class="error-msg" style="margin-bottom: 15px;">
                    Please fill out all fields before submitting.
                </p>
            <?php endif; ?>

            <form action="../controllers/requestController.php" method="POST" onsubmit="return validateRequestForm()">
                
                <div class="form-group">
                    <label for="equipmentSelect">Select Equipment:</label>
                    <select id="equipmentSelect" name="equipment_id" onchange="fetchEquipmentInfo()">
                        <option value="">-- Choose Equipment --</option>
                        <?php foreach ($equipmentList as $item): ?>
                            <option value="<?php echo $item['EquipmentID']; ?>">
                                <?php echo htmlspecialchars($item['ItemName']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="error-msg" id="equipmentError"></span>
                </div>

                <div id="equipmentInfoBox" style="display: none; background: #eef2f7; border: 1px solid #ccd6e0; border-radius: 4px; padding: 12px; margin-bottom: 15px; font-size: 13px;">
                    <strong>Item Details (Live Inventory):</strong>
                    <div style="margin-top: 5px;">
                        <span><strong>Category:</strong> <span id="infoCategory">-</span></span> | 
                        <span><strong>Available Stock:</strong> <span id="infoStock">-</span></span> | 
                        <span><strong>Condition:</strong> <span id="infoCondition">-</span></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="priority">Priority Level:</label>
                    <select id="priority" name="priority">
                        <option value="Low">Low</option>
                        <option value="Medium" selected>Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Issue Description:</label>
                    <textarea id="description" name="description" rows="4" placeholder="Describe the fault or malfunction..."></textarea>
                    <span class="error-msg" id="descriptionError"></span>
                </div>

                <button type="submit" name="submit_request_btn" class="btn btn-primary">Submit Ticket</button>
            </form>
        </div>
    </div>

    <script>
    function validateRequestForm() {
        let isValid = true;
        let equip = document.getElementById("equipmentSelect").value;
        let desc = document.getElementById("description").value.trim();

        document.getElementById("equipmentError").innerText = "";
        document.getElementById("descriptionError").innerText = "";

        if (equip === "") {
            document.getElementById("equipmentError").innerText = "Please choose an equipment item.";
            isValid = false;
        }

        if (desc === "") {
            document.getElementById("descriptionError").innerText = "Description cannot be empty.";
            isValid = false;
        } else if (desc.length < 10) {
            document.getElementById("descriptionError").innerText = "Please provide at least 10 characters describing the issue.";
            isValid = false;
        }

        return isValid;
    }
    </script>

    <script>
    function fetchEquipmentInfo() {
        let equipmentId = document.getElementById("equipmentSelect").value;
        let infoBox = document.getElementById("equipmentInfoBox");

        if (equipmentId === "") {
            infoBox.style.display = "none";
            return;
        }

        fetch("../controllers/getEquipmentController.php?equipment_id=" + encodeURIComponent(equipmentId))
            .then(response => response.json())
            .then(data => {
                if (!data.error) {
                    document.getElementById("infoCategory").innerText = data.Category;
                    document.getElementById("infoStock").innerText = data.StockQuantity;
                    document.getElementById("infoCondition").innerText = data.ConditionStatus;
                    
                    infoBox.style.display = "block";
                } else {
                    infoBox.style.display = "none";
                }
            })
            .catch(error => {
                console.error("AJAX Error:", error);
                infoBox.style.display = "none";
            });
    }
    </script>

</body>
</html>