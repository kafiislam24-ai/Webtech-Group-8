<?php
require_once __DIR__ . '/../config/db.php';

class UserModel {
    
    public static function findByEmailAndRole($conn, $email, $roleName) {
        $sql = "SELECT u.UserID, u.Name, u.Email, u.Password, r.RoleName 
                FROM users u 
                JOIN roles r ON u.RoleID = r.RoleID 
                WHERE u.Email = ? AND r.RoleName = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $email, $roleName);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    }

    public static function getRoleIdByName($conn, $roleName) {
        $sql = "SELECT RoleID FROM roles WHERE RoleName = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $roleName);
        mysqli_stmt_execute($stmt);
        $result = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        return $result ? $result['RoleID'] : null;
    }

    public static function create($conn, $name, $email, $password, $roleId) {
        $sql = "INSERT INTO users (Name, Email, Password, RoleID) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssi", $name, $email, $password, $roleId);
        return mysqli_stmt_execute($stmt);
    }

    public static function getTechnicians($conn) {
        $sql = "SELECT UserID, Name FROM users WHERE RoleID = 3";
        $result = mysqli_query($conn, $sql);
        $techs = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $techs[] = $row;
        }
        return $techs;
    }

    public static function emailExists($conn, $email) {
        $sql = "SELECT UserID FROM users WHERE Email = ? LIMIT 1";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_num_rows($result) > 0;
    }
}
?>