<?php 
    include "config/connect.php";
    
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
?>