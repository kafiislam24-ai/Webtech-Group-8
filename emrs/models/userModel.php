<?php
require_once __DIR__ . '/../dbConnect.php';


function validateUser($email, $password, $role) {
    global $conn;

    $email    = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    $role     = mysqli_real_escape_string($conn, $role);

    $sql = "SELECT u.UserID, u.Name, u.Email, u.Password, r.RoleName 
            FROM users u 
            JOIN roles r ON u.RoleID = r.RoleID 
            WHERE u.Email = '$email' 
              AND u.Password = '$password' 
              AND r.RoleName = '$role' 
            LIMIT 1";

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }
    return false;
}


function registerUser($name, $email, $password, $role) {
    global $conn;

    $name     = mysqli_real_escape_string($conn, $name);
    $email    = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);
    $role     = mysqli_real_escape_string($conn, $role);

    $roleSql    = "SELECT RoleID FROM roles WHERE RoleName = '$role' LIMIT 1";
    $roleResult = mysqli_query($conn, $roleSql);
    $roleRow    = mysqli_fetch_assoc($roleResult);

    if (!$roleRow) {
        return false;
    }

    $roleId = (int)$roleRow['RoleID'];

    $insertSql = "INSERT INTO users (Name, Email, Password, RoleID) 
                  VALUES ('$name', '$email', '$password', $roleId)";

    return mysqli_query($conn, $insertSql);
}


function isEmailTaken($email) {
    global $conn;

    $email = mysqli_real_escape_string($conn, $email);

    $sql    = "SELECT UserID FROM users WHERE Email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    return ($result && mysqli_num_rows($result) > 0);
}


function getTechnicians() {
    global $conn;

    $sql    = "SELECT UserID, Name, Email FROM users WHERE RoleID = 3";
    $result = mysqli_query($conn, $sql);

    $technicians = [];
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $technicians[] = $row;
        }
    }
    return $technicians;
}
?>