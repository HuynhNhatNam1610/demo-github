<?php 
    include "config/connect.php";
    require 'mail/sendmail.php';
    function checkUserLogin($username, $password) {
        global $conn;
        $sql = "SELECT * FROM khachhang WHERE username = ? AND password = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
    
        return mysqli_fetch_assoc($result); 
    }

    function dangky($username,$password,$email,$phone,$fullname){
        global $conn;
        if (!$conn) {
            die("Lỗi kết nối database: " . mysqli_connect_error());
        }
        $sql="INSERT INTO khachhang(username, password, email, phone, fullname, address) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            die("Lỗi chuẩn bị truy vấn: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($stmt, "ssssss",$username,$password,$email,$phone,$fullname,$address);
        $success = mysqli_stmt_execute($stmt);
        if (!$success) {
            die("Lỗi khi thực thi truy vấn: " . mysqli_error($conn));
        }
        mysqli_stmt_close($stmt);
        return $success;
    }

    function checkMailInDatabase($email){
        global $conn;
        $sql="SELECT * FROM khachhang WHERE email= ?";
        $stmt = mysqli_prepare($conn,$sql);
        mysqli_stmt_bind_param($stmt,"s",$email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result); 
    }

    function storeResetToken($email, $token) {
        global $conn;
        $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour")); 
        $sql="UPDATE khachhang SET reset_token = ?, reset_expires = ? WHERE email = ?";
        $stmt = mysqli_prepare($conn,$sql);
        $stmt->bind_param("sss", $token, $expires_at, $email);
        mysqli_stmt_execute($stmt);
    }

    function getUserByToken($token){
        global $conn;
        $sql="SELECT * FROM khachhang WHERE reset_token= ?";
        $stmt = mysqli_prepare($conn,$sql);
        mysqli_stmt_bind_param($stmt,"s",$token);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result); 
    }

    function changePassword($newpass, $token) {
        global $conn;
        $sql = "SELECT email FROM khachhang WHERE reset_token = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "s", $token);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($result);
    
        if ($user) {
            $email = $user['email'];
            // $hashedPassword = password_hash($newpass, PASSWORD_BCRYPT);
            $sql = "UPDATE khachhang SET password = ?, reset_token = NULL, reset_expires = NULL WHERE email = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "ss", $newpass, $email);
            mysqli_stmt_execute($stmt);
            return true;
        }
        return false; 
    }





    
?>