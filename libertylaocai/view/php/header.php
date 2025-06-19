<?php
require_once "session.php";
require_once "../../model/UserModel.php";

// Kiểm tra ngôn ngữ từ session, mặc định là 1 (tiếng Việt)
$languageId = isset($_SESSION['language_id']) ? $_SESSION['language_id'] : 1;

// Lấy thông tin khách sạn và danh mục header theo ngôn ngữ
$informationHotel = getHotelInfoWithLanguage($languageId);
$category_header = getHeaderCategories($languageId);
$getSubsectionHeader = getSubsectionHeaderCategories($languageId);
?>

<!DOCTYPE html>
<html lang="<?php echo $languageId == 1 ? 'vi' : 'en'; ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header Liberty Hotel</title>
    <link rel="icon" type="image/png" href="/libertylaocai/view/img/logoliberty.jpg">
    <meta name="description" content="<?php echo htmlspecialchars($informationHotel[0]['description'] ?? 'Liberty Hotel & Events Khách sạn Liberty Lào Cai'); ?>">
    <link rel="stylesheet" href="/libertylaocai/view/css/header.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <base href="/libertylaocai/">
</head>

<body>
    <header>
        <div class="header">
            <?php if (!empty($informationHotel)): ?>
                <?php foreach ($informationHotel as $info): ?>
                    <div class="contact-info">
                        <div class="head-info">
                            <span class="phone"><i class="bi bi-telephone-fill"></i> <a href="tel:<?php echo htmlspecialchars($info['phone']); ?>"><?php echo htmlspecialchars($info['phone']); ?></a></span>
                            <span class="address"><i class="bi bi-geo-alt-fill"></i> <a href="<?php echo htmlspecialchars($info['position']); ?>"
                                    target="_blank" style="text-decoration: none; color: inherit;">
                                    <?php echo htmlspecialchars($info['address']); ?>
                                </a></span>
                        </div>
                        <div class="right-align">
                            <div class="language">
                                <img src="/view/img/<?php echo $languageId == 1 ? 'vi.jpg' : 'en.jpg'; ?>" alt="<?php echo $languageId == 1 ? 'Vietnam Flag' : 'English Flag'; ?>" class="flag language-toggle" data-lang="<?php echo $languageId == 1 ? 2 : 1; ?>">
                            </div>
                            <div class="login"><a href="/libertylaocai/dang-nhap" class="login-icon"><i class="fas fa-user"></i></a></div>
                        </div>
                    </div>
                    <div class="second-container">
                        <div class="category-nav">
                            <a class="home" href="/libertylaocai/trangchu">
                                <div class="logo">
                                    <div class="logo-img">
                                        <img src="/view/img/uploads/<?php echo htmlspecialchars($info['logo']); ?>" alt="<?php echo htmlspecialchars($info['name']); ?>">
                                    </div>
                                    <div class="logo-text">
                                        Liberty
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                        <div class="header-respon">
                            <div class="language1">
                                <img src="/view/img/<?php echo $languageId == 1 ? 'vi.jpg' : 'en.jpg'; ?>" alt="<?php echo $languageId == 1 ? 'Vietnam Flag' : 'English Flag'; ?>" class="flag language-toggle" data-lang="<?php echo $languageId == 1 ? 2 : 1; ?>">
                            </div>
                            <div class="header-list">
                                <button class="menu-toggle"> <i class="bi bi-list"></i></button>
                            </div>
                        </div>
                        <!-- <form action="/libertylaocai/controller" method="POST"></form> -->
                        <div class="category-menu">
                            <?php if (!empty($category_header)): ?>
                                <?php foreach ($category_header as $category): ?>
                                    <?php
                                    // Gọi hàm để lấy các tiểu mục dựa trên id_danhmucheader và languageId
                                    $subcategories = getSubsectionHeaderCategories($category['id'], $languageId);
                                    $hasDropdown = !empty($subcategories); // Kiểm tra nếu có tiểu mục
                                    ?>
                                    <form action="/libertylaocai/user/submit" method="POST">
                                        <input type="hidden" name="category_code" value="<?php echo htmlspecialchars($category['code']); ?>">
                                        <div class="category-option<?php echo $hasDropdown ? ' dropdown' : ''; ?>">
                                            <?php echo htmlspecialchars($category['name']); ?>
                                    </form>
                                    <?php if ($hasDropdown): ?>
                                        <div class="dropdown-content">
                                            <?php foreach ($subcategories as $subcategory): ?>
                                                <form action="/libertylaocai/user/submit" method="POST" style="display:inline;">
                                                    <input type="hidden" name="subcategory_code" value="<?php echo htmlspecialchars($subcategory['code']); ?>">
                                                    <div class="dropdown-item" onclick="this.parentElement.submit()"><?php echo htmlspecialchars($subcategory['name']); ?></div>
                                                </form>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="category-option"><?php echo $languageId == 1 ? 'Không có danh mục nào.' : 'No categories available.'; ?></div>
                <?php endif; ?>
                        </div>
                    </div>
        </div>
    <?php else: ?>
        <div class="contact-info">
            <div class="head-info">
                <span class="phone"><?php echo $languageId == 1 ? 'Không có thông tin liên hệ.' : 'No contact information.'; ?></span>
                <span class="address"><?php echo $languageId == 1 ? 'Không có địa chỉ.' : 'No address.'; ?></span>
            </div>
        </div>
    <?php endif; ?>
    </div>
    </header>


    <div class="mobile-dropdown">
        <button class="close-menu"><i class="bi bi-x-lg"></i></button>

        <?php if (!empty($category_header)): ?>
            <?php foreach ($category_header as $category): ?>
                <?php
                $subcategories = getSubsectionHeaderCategories($category['id'], $languageId);
                $hasDropdown = !empty($subcategories);
                ?>

                <div class="mobile-category-option<?php echo $hasDropdown ? ' has-dropdown' : ''; ?>">
                    <form action="/libertylaocai/user/submit" method="POST" style="display: inline;">
                        <input type="hidden" name="category_code" value="<?php echo htmlspecialchars($category['code']); ?>">
                        <?php if ($category['name'] === ($languageId == 1 ? 'Trang chủ' : 'Home')): ?>
                            <a href="/libertylaocai/header" style="color: inherit; text-decoration: none;">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </a>
                        <?php else: ?>
                            <span onclick="this.parentElement.submit()" style="cursor: pointer; width: 100%; display: block;">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </span>
                        <?php endif; ?>
                    </form>

                    <?php if ($hasDropdown): ?>
                        <div class="mobile-dropdown-content">
                            <?php foreach ($subcategories as $subcategory): ?>
                                <form action="/libertylaocai/user/submit" method="POST" style="display: inline;">
                                    <input type="hidden" name="subcategory_code" value="<?php echo htmlspecialchars($subcategory['code']); ?>">
                                    <div class="mobile-dropdown-item" onclick="this.parentElement.submit()">
                                        <?php echo htmlspecialchars($subcategory['name']); ?>
                                    </div>
                                </form>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

            <?php endforeach; ?>
        <?php else: ?>
            <div class="mobile-category-option"><?php echo $languageId == 1 ? 'Không có danh mục nào.' : 'No categories available.'; ?></div>
        <?php endif; ?>

        <!-- Dòng Đăng nhập -->
        <div class="mobile-category-option">
            <a href="/libertylaocai/dang-nhap" style="color: #e6faf0; text-decoration: none; display: flex; align-items: center;">
                <?php echo $languageId == 1 ? 'Đăng nhập' : 'Login'; ?>
            </a>
        </div>

        <!-- Thông tin liên hệ -->
        <div style="margin-top: 30px; padding: 20px; border-top: 1px solid #006d5b;">
            <?php if (!empty($informationHotel)): ?>
                <?php foreach ($informationHotel as $info): ?>
                    <div style="color: #e6faf0; font-size: 14px; margin-bottom: 10px;">
                        <i class="bi bi-telephone-fill"></i>
                        <a href="tel:<?php echo htmlspecialchars($info['phone']); ?>" style="color: #e6faf0; text-decoration: none;">
                            <?php echo htmlspecialchars($info['phone']); ?>
                        </a>
                    </div>
                    <div style="color: #e6faf0; font-size: 14px;">
                        <i class="bi bi-geo-alt-fill"></i>
                        <a href="https://maps.app.goo.gl/845QqSa2ZhW6GinT7" target="_blank" style="color: #e6faf0; text-decoration: none;">
                            <?php echo htmlspecialchars($info['address']); ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="/libertylaocai/view/js/header.js"></script>
</body>

</html>
