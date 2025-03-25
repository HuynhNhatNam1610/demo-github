<?php 
  session_start();
  if(isset($_SESSION['mySession'])){
    // header("location: thoitrangnam.php");
    // exit();
  }
?>

<!DOCTYPE html>
<html lang="vi">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Đăng Nhập / Đăng Ký</title>
    <link rel="stylesheet" href="../css/login.css" />
  </head>
  <body>
    <header> <?php include "header.php";?> </header>
    <div class="container">
      <!-- Login Form -->
       <form action="../../controller/UserController.php" method="POST">
          <div id="login-form" class="active">
            <h1 class="title">ĐĂNG NHẬP</h1>

            <div class="progress-bar">
              <div class="progress-bar-gray"></div>
              <div class="progress-bar-red"></div>
              <div class="progress-bar-gray"></div>
            </div>

            <input type="text" name="username" class="input-field" placeholder="Email/SĐT" />
            <input type="password" name="password" class="input-field" placeholder="Mật khẩu" />

            <button class="button" type="submit" name="dangnhap">ĐĂNG NHẬP</button>

            <div class="login-links">
              <a id="forgot-password-link">Quên mật khẩu?</a>
              <a id="register-link">Đăng ký tại đây</a>
            </div>

            <div class="alt-login-text">hoặc đăng nhập qua</div>

            <div class="social-buttons">
              <a
                href="https://www.facebook.com/hao23nhat/"
                class="social-button facebook-button"
              >
                <i>f</i> Facebook
              </a>
              <a
                href="https://www.google.com.vn/"
                class="social-button google-button"
              >
                <i>G+</i> Google
              </a>
            </div>
          </div>
       </form>


      <form action="../../controller/UserController.php" method="POST">
        <div id="register-form">
          <h1 class="title">ĐĂNG KÝ</h1>
          <p class="description">
            Đã có tài khoản, <a id="login-link">đăng nhập tại đây</a>
          </p>
          <input type="text" name="fullname" class="input-field" placeholder="Họ và tên" required />
          <input type="text" name="username" class="input-field" placeholder="Tài khoản" required />
          <input type="password" name="password" class="input-field" placeholder="Mật khẩu" required/>
          <input type="email" name="email" class="input-field" placeholder="Email" required />
          <input type="tel" name="phone" class="input-field" placeholder="Số điện thoại" required/>
          <input type="text" name="address" class="input-field" placeholder="Địa chỉ" required />

          <button class="button" type="submit" name="dangky">ĐĂNG KÝ</button>

          <div class="alt-login-text">Hoặc đăng nhập bằng</div>

          <div class="social-buttons">
            <a
              href="https://www.facebook.com/hao23nhat/"
              class="social-button facebook-button"
            >
              <i>f</i> Facebook
            </a>
            <a
              href="https://www.google.com.vn/"
              class="social-button google-button"
            >
              <i>G+</i> Google
            </a>
          </div>
        </div>
      </form>

      <form action="../../controller/UserController.php" method="POST">
        <div id="forgot-password-form">
          <h1 class="title">QUÊN MẬT KHẨU</h1>
          <p class="description">Nhập email của bạn để lấy lại mật khẩu</p>

          <input type="email" class="input-field" placeholder="Email" name="email" />

          <button class="button" type="submit" name="forgot">LẤY LẠI MẬT KHẨU</button>

          <p class="description">
            <a id="back-to-login-link">Quay lại đăng nhập</a>
          </p>
        </div>
      </form>
    </div>

    <footer> <?php include "footer.php"; ?></footer>
    <script src="../js/login.js"></script>
  </body>
</html>
