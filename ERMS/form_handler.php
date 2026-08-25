<?php
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = isset($_POST['action']) ? sanitize_input($_POST['action']) : '';
    $errors = [];

  
    if ($action === 'create_request') {
        $equipment_name = sanitize_input($_POST['equipment_name'] ?? '');
        $priority = sanitize_input($_POST['priority'] ?? '');
        $description = sanitize_input($_POST['description'] ?? '');

        if (empty($equipment_name)) {
            $errors[] = "Equipment Name cannot be empty.";
        } elseif (strlen($equipment_name) < 2) {
            $errors[] = "Equipment Name is too short.";
        }

        $validPriorities = ['Low', 'Medium', 'High'];
        if (empty($priority) || !in_array($priority, $validPriorities)) {
            $errors[] = "Please select a valid priority level.";
        }

        if (empty($description)) {
            $errors[] = "Issue description is required.";
        } elseif (strlen($description) < 10) {
            $errors[] = "Description must be at least 10 characters long.";
        }

        if (!empty($errors)) {
            echo "<h3>Submission Failed:</h3><ul>";
            foreach ($errors as $error) {
                echo "<li style='color:red;'>$error</li>";
            }
            echo "</ul><a href='request_form.php'>Go Back to Form</a>";
            exit();
        } else {
            echo "<p style='color:green;'>Request submitted successfully!</p>";
            echo "<a href='employee_dashboard.php'>Back to Dashboard</a>";
            exit();
        }
    }


    elseif ($action === 'assign_and_approve') {
        $tech_id = sanitize_input($_POST['technician_id'] ?? '');
        if (empty($tech_id)) {
            echo "<p style='color:red;'>Error: You must assign a technician before approving.</p>";
            echo "<a href='manager_dashboard.php'>Go Back</a>";
            exit();
        }
        echo "<p style='color:green;'>Task assigned and approved successfully.</p>";
        echo "<a href='manager_dashboard.php'>Back to Dashboard</a>";
        exit();
    }
}
?>