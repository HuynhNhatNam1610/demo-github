<?php
    session_start();
    require '../../model/UserModel.php';
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        $user = getUserByToken($token);
    
        if ($user && strtotime($user['reset_expires']) > time())  {
            $_SESSION['reset_token'] = $token; 
        } else {
            die("Token không hợp lệ hoặc đã hết hạn.");
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="../../controller/UserController.php" method="POST">
        <label> Mật khẩu mới</label>
        <input type="password" name="newpass" required> 
        <label>Xác nhận lại mật khẩu</label>
        <input type="password" name="reconfirm" required>
        <button type="submit" name="resetpass">Gửi</button>
    </form>
</body>
</html>