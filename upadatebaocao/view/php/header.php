<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebTheThaovn Shop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/header.css">
</head>
<body>
    <header>
        <div class="header">
            <div class="logo">
                <img src="../img/logo3.webp" alt="Logo WebTheThaovn">
                <span class="logo-text">NHL SPORTS</span>
            </div>
            
            <div class="search-container">
                <button><i class="bi bi-search"></i></button>
                <input type="text" class="search-input" placeholder="Tìm sản phẩm...">
            </div>
            
            <div class="contact-info">
                    <i class="bi bi-telephone-fill"></i>
                    <div class="contact-text">
                        <div >Tư vấn mua hàng</div>
                        <a href="tel:0823885888" class="tel">
                            <div >0823 885 888</div>
                        </a>
                    </div>
            </div>
            
            <div class="header-icons">
                <a href="#"><i class="bi bi-house-fill"></i></a>
                <!-- <a href="#"><i class="bi bi-heart"></i></a> -->
                <a href="#"><i class="bi bi-cart"></i></a>
                <div class="user-menu">
                    <a href="#"><i class="bi bi-person"></i></a>
                    <i class="bi bi-caret-down-fill dropdown-toggle"></i>
                    <ul class="dropdown-menu">
                        <li><a href="login.php"> <img src="../img/loginicon.png">Đăng nhập</a></li>
                        <li>
                            <form action="../../controller/UserController.php" method="POST">
                                <button type="submit" name="logout">
                                    <img src="../img/dangxuaticon.jpg"> Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="category-nav">
            <a href="#" class="list">
                <i class="bi bi-list"></i>
                <span> Danh mục sản phẩm</span>
            </a>
            <div class="search-container1">
                <button><i class="bi bi-search"></i></button>
                <input type="text" class="search-input1" placeholder="Tìm sản phẩm...">
            </div>
            <a href="#" class="hidden">Sale off bóng đá</a>
            <a href="#" class="hidden">Đồ bóng chuyền SALE OFF!</a>
            <a href="#" class="hidden">Đồ Bi-a Chính Hãng</a>
            <a href="#" class="hidden">Giầy bóng chuyền Sao Vàng</a>
            <a href="#" class="hidden">SALE OUTLET 40%</a>
        </div>
    </header>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="../js/header.js"></script>
</body>
</html>