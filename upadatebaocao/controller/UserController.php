<?php 
    include "../model/UserModel.php";

    session_start();

    if(isset($_POST['dangnhap'])){
        $username=$_POST['username'];
        $password=$_POST['password'];
        $user = checkUserLogin($username, $password);
        if ($user) {
            $_SESSION['mySession'] = $user['username']; 
            header('location: ../view/php/thoitrangnam.php');
            exit();
        } else {
            echo "Sai thông tin đăng nhập";
        }
    }
    if(isset($_POST['logout'])){
        if(isset($_SESSION['mySession'])){
            unset($_SESSION['mySession']);
            header("location: ../view/php/thoitrangnam.php");
            exit();
        }else{
            header("location: ../view/php/thoitrangnam.php");
            exit();
        }
    }

    if(isset($_POST['dangky'])){
        $username=$_POST['username'];
        $password=$_POST['password'];
        $fullname=$_POST['fullname'];
        $email=$_POST['email'];
        $phone=$_POST['phone'];
        $address=$_POST['address'];
        $user = dangky($username, $password,$email,$phone,$fullname,$address);
        if ($user) {
            $_SESSION['mySession'] = $user['username']; 
            header('location: ../view/php/thoitrangnam.php');
            exit();
        } else {
            echo "Lỗi: Không thể đăng ký!";
        }
    }

?>