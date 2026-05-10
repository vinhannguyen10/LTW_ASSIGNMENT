<?php
// 1. Lấy đường dẫn URL hiện tại
$current_uri = $_SERVER['REQUEST_URI'];

// 2. Kiểm tra xem người dùng đang ở mục nào dựa vào URL
$is_post = strpos($current_uri, '/new_posts/') !== false;
$is_dashboard = strpos($current_uri, '/index2.php') !== false && !$is_post;
$is_comments = strpos($current_uri, '/comments/') !== false;
// 3. Khai báo đoạn CSS làm nổi bật (màu tím)
$active_style = 'style="background-color: purple; color: white; border-radius: 5px;"';
?>
<!-- sidebar menu area start -->
<div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="<?php echo $rootPath; ?>/index2.php" style="font-size: 28px; font-weight: 800; text-transform: uppercase; color: white; text-decoration: none; letter-spacing: 2px;">OLIVIA</a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">
                    <!-- Nút Dashboard -->
                    <li class="<?php echo $is_dashboard ? 'active' : ''; ?>">
                        <a href="<?php echo $rootPath; ?>/index2.php" aria-expanded="true" <?php echo $is_dashboard ? $active_style : ''; ?>>
                            <i class="ti-dashboard"></i><span>Dashboard</span>
                        </a>
                    </li>
                    
                    <!-- Nút Products (Chưa làm, để trống) -->
                    <!-- Nút Products -->
<li>
    <a href="<?php echo $rootPath; ?>/products/index.php" aria-expanded="true">
        <i class="fa-solid fa-shop"></i>
        <span>Products</span>
    </a>
</li>
                    
                    <!-- Nút Comments (Chưa làm, để trống) -->
                    <li class="<?php echo $is_comments ? 'active' : ''; ?>">
                        <a href="<?php echo $rootPath; ?>/comments/index.php" aria-expanded="true" <?php echo $is_comments ? $active_style : ''; ?>>
                            <i class="ti-layers-alt"></i> <span>Comments</span>
                        </a>
                    </li>
                    
                    <!-- Nút Posts -->
                    <li class="<?php echo $is_post ? 'active' : ''; ?>">
                        <a href="<?php echo $rootPath; ?>/new_posts/index.php" aria-expanded="true" <?php echo $is_post ? $active_style : ''; ?>>
                            <i class="ti-layers-alt"></i> <span>Posts</span>
                        </a>
                    </li>
                    
                    <!-- Nút Orders (Chưa làm, để trống) -->
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa-solid fa-cart-shopping"></i> <span>Orders</span></a>
                    </li>
                    
                    <!-- Nút Contact (Chưa làm, để trống) -->
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa-solid fa-phone"></i> <span>Contact</span></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- sidebar menu area end -->
