<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Request - EMRS</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Create Maintenance Request</h2>
        <form action="index.php?url=employee/storeRequest" method="POST">
            <label for="equipment_name">Equipment Name / Asset ID:</label>
            <input type="text" id="equipment_name" name="equipment_name" required>

            <label for="description">Issue Description:</label>
            <textarea id="description" name="description" rows="5" required></textarea>

            <button type="submit">Submit Request</button>
            <a href="index.php?url=employee/dashboard">Cancel</a>
        </form>
    </div>
</body>
</html>