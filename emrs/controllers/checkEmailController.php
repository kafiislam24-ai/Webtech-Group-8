<?php
// Step 1: Include the Model containing the SQL query
require_once __DIR__ . '/../models/userModel.php';

// Step 2: Verify that an email was passed via the GET method
if (isset($_GET['email'])) {
    $email = trim($_GET['email']);

    // Guard against empty query values
    if (empty($email)) {
        echo "invalid";
        exit();
    }

    // Step 3: Call the Model function to query the database
    $taken = isEmailTaken($email);

    // Step 4: Return plain text status directly back to the JavaScript fetch()
    if ($taken) {
        echo "taken";
    } else {
        echo "available";
    }
}
?>