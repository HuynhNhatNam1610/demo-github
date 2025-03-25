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

    //////////////////////////////////
    if(isset($_POST['forgot'])){
        $email=$_POST['email'];
        $user = checkMailInDatabase($email);
        if($user){
            $token = bin2hex(random_bytes(32));
            storeResetToken($email,$token);
            $resetLink = "http://localhost/baocao/view/php/reset_password.php?token=$token";
            $subject = "Yêu cầu đặt lại mật khẩu tại shop NHL SPORTS";
            $message = "Nhấp vào link sau để đặt lại mật khẩu: <a href='$resetLink'>Đặt lại mật khẩu</a>";
            if(sendMail($email, $subject, $message)) {
                echo "Email đặt lại mật khẩu đã được gửi!";
            } else {
                echo "Lỗi khi gửi email.";
            }
        }else{
            echo "Email không tồn tại";
        }
    }

    if(isset($_POST['resetpass'])){
        $newpass=$_POST['newpass'];
        $reconfirm=$_POST['reconfirm'];
        if($newpass==$reconfirm){
            if (!isset($_SESSION['reset_token'])) {
                die("Lỗi: Token không hợp lệ.");
            }
            $token=$_SESSION['reset_token'];
            unset($_SESSION['reset_token']);
            $result = changePassword($newpass,$token);
            if ($result) {
                echo "Mật khẩu đã được cập nhật thành công!";
                header("location: ../view/php/thoitrangnam.php");
            } else {
                echo "Lỗi: Token không hợp lệ.";
            }
        }else{
            echo "Mật khẩu không khớp!";
        }
    }

?>