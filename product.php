<?php
session_start();
ob_start();
$rootPath = '/LTW_ASSIGNMENT';
require_once './database/DB.php';

$categoryId = isset($_GET['categoryId']) ? (int)$_GET['categoryId'] : 0;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 8;
$totalProducts = 0;
$totalPage = 0;

$sqlShowProducts = "SELECT product_id, name, quantity, images, price, price_sale FROM product";
if ($categoryId > 0) {
    $sqlShowProducts .= " WHERE category_id = $categoryId";
}

$productsCount = $conn->query($sqlShowProducts);
if ($productsCount) {
    $totalProducts = $productsCount->num_rows;
}

if ($totalProducts > 0) {
    $totalPage = ceil($totalProducts / $limit);

    if ($currentPage > $totalPage) {
        $currentPage = $totalPage;
    } elseif ($currentPage < 1) {
        $currentPage = 1;
    }

    $start = ($currentPage - 1) * $limit;
    $products = $conn->query($sqlShowProducts . " LIMIT $start, $limit");
} else {
    $products = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sản Phẩm</title>
    <link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="./public/css/base.css">
    <link rel="stylesheet" href="./public/css/product.css">

    <style>
        .paging nav {
            width: 100%;
        }

        .paging .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 0;
            margin: 30px 0 0;
            padding: 0;
        }

        .paging .page-item {
            margin: 0;
            padding: 0;
        }

        .paging .page-link {
            min-width: 58px;
            height: 58px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #000;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 0 !important;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.12);
        }

        .paging .page-link:hover {
            background-color: #e9ecef;
            color: #000;
        }

        .paging .page-item.active .page-link {
            background-color: #C7C8C9;
            border-color: #C7C8C9;
            color: #000;
        }
    </style>
</head>
<body>
<?php
    require './includes/header.php';
    require './includes/navbar.php';
?>

<div class="container-fluid mt-5 mb-5">
    <div class="row">
        <div class="col-xl-2 col-md-4 col-sm-6">
            <div class="list-group mb-5">
                <span class="list-group-item bg-dark text-light" aria-current="true">
                    <b class="user-select-none nav-link">Category</b>
                </span>

                <?php
                    $sqlShowCategory = "SELECT * FROM category";
                    $category = $conn->query($sqlShowCategory);
                    while ($row = $category->fetch_assoc()) {
                        $activeCategory = ($categoryId == (int)$row['category_id']) ? 'bg-dark text-dark bg-opacity-25' : '';
                ?>
                    <a href="<?php echo $rootPath ?>/product.php?categoryId=<?php echo $row['category_id'] ?>" class="list-group-item list-group-item-action <?php echo $activeCategory ?>">
                        <?php echo $row['category_name'] ?>
                    </a>
                <?php
                    }
                ?>

                <a href="<?php echo $rootPath ?>/product.php?categoryId=0" class="list-group-item list-group-item-action <?php if ($categoryId == 0) echo 'bg-dark text-dark bg-opacity-25' ?>">
                    All
                </a>
            </div>
        </div>

        <div class="col-xl-10 col-md-8 col-sm-6">
            <div class="container mb-5">
                <div class="row">
                    <?php
                    if ($products && $products->num_rows > 0) {
                        while ($row = $products->fetch_assoc()) {
                    ?>
                    <div class="col-xl-3 col-md-6 col-sm-12 mb-3">
                        <div class="card h-100">
                            <img src="<?php echo $rootPath ?>/public/img/products/<?php echo $row['images']; ?>" class="img-fluid" alt="<?php echo $row['name']; ?>">

                            <div class="card-body d-flex flex-column justify-content-between">
                                <div class="d-flex flex-column justify-content-start">
                                    <h6 class="card-title"><?php echo $row['name']; ?></h6>
                                </div>

                                <div class="card-text">
                                    <div class="d-flex justify-content-between align-items-center" style="text-align:center;">
                                        <div>
                                            <?php if ($row['quantity'] > 0) { ?>
                                                <span class="badge text-dark" style="background-color:#C7C8C9;">In Stock</span>
                                            <?php } else { ?>
                                                <span class="badge bg-danger">Out of stock</span>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <p>
                                        <?php
                                        if ($row['price_sale'] != 0) {
                                            echo '<del class="text-secondary">' . number_format($row['price']) . '</del><sup>đ</sup>';
                                            echo '<strong><span class="text-dark ms-3">' . number_format($row['price_sale']) . '<sup>đ</sup></span></strong>';
                                        } else {
                                            echo '<strong>' . number_format($row['price']) . '<sup>đ</sup></strong>';
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>

                            <div class="card-footer d-flex flex-column">
                                <a href="<?php echo $rootPath ?>/product_detail.php?productId=<?php echo $row['product_id'] ?>"
                                   class="btn btn-secondary mt-1"
                                   style="background-color:#C7C8C9; color:black; border:0; transition:background-color 0.3s ease, color 0.3s ease;"
                                   onmouseover="this.style.backgroundColor='#A0A1A2'; this.style.color='white';"
                                   onmouseout="this.style.backgroundColor='#C7C8C9'; this.style.color='black';">
                                    Detail
                                </a>

                                <button onclick="addCartItem(<?php echo $row['product_id'] ?>)"
                                        class="btn btn-secondary text-dark mt-1 <?php if ($row['quantity'] == 0) echo 'disabled' ?>"
                                        style="background-color:#C7C8C9; color:black; border:0; transition:background-color 0.3s ease, color 0.3s ease;"
                                        onmouseover="this.style.backgroundColor='#A0A1A2'; this.style.color='white'; this.querySelector('i').style.color='white';"
                                        onmouseout="this.style.backgroundColor='#C7C8C9'; this.style.color='black'; this.querySelector('i').style.color='black';">
                                    <i class="fa-solid fa-cart-plus"></i> Add to cart
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                        }
                    } else {
                        echo '<div class="alert alert-warning" role="alert"><i class="fa-light fa-circle-exclamation"></i> Không tìm thấy sản phẩm nào</div>';
                    }
                    ?>
                </div>

                <?php if ($totalProducts > 0 && $totalPage > 1) { ?>
                <div class="row paging">
                    <nav class="mt-3 d-flex justify-content-center">
                        <ul class="pagination pagination-lg">
                            <?php if ($currentPage > 1) { ?>
                                <li class="page-item">
                                    <a href="<?php echo $rootPath ?>/product.php?categoryId=<?php echo $categoryId ?>&page=<?php echo $currentPage - 1; ?>" class="page-link" data-remote="true">
                                        <i class="fa-solid fa-arrow-left"></i>
                                    </a>
                                </li>
                            <?php } ?>

                            <?php for ($i = 1; $i <= $totalPage; $i++) { ?>
                                <?php if ($i == $currentPage) { ?>
                                    <li class="page-item active">
                                        <span class="page-link" data-remote="true"><?php echo $i ?></span>
                                    </li>
                                <?php } else { ?>
                                    <li class="page-item">
                                        <a data-remote="true" class="page-link" href="<?php echo $rootPath ?>/product.php?categoryId=<?php echo $categoryId ?>&page=<?php echo $i ?>">
                                            <?php echo $i ?>
                                        </a>
                                    </li>
                                <?php } ?>
                            <?php } ?>

                            <?php if ($currentPage < $totalPage) { ?>
                                <li class="page-item">
                                    <a href="<?php echo $rootPath ?>/product.php?categoryId=<?php echo $categoryId ?>&page=<?php echo $currentPage + 1 ?>" class="page-link" data-remote="true">
                                        <i class="fa-solid fa-arrow-right"></i>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<?php require './includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.3/jquery.min.js" integrity="sha512-STof4xm1wgkfm7heWqFJVn58Hm3EtS31XFaagaa8VMReCXAkQnJZ+jEy8PCC/iT18dFy95WcExNHFTqLyp72eQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="./public/javascripts/loadCartHeader.js"></script>

<script>
    function addCartItem(pId) {
        $.ajax({
            url: "<?php echo $rootPath ?>/ajax/addCartItem.php",
            type: "POST",
            data: {
                productId: pId,
            },
            success: function () {
                alert("Thêm sản phẩm thành công");
                loadCartAjax();
            },
            error: function () {
                alert("Lỗi thao tác");
            }
        });
    }

    $(document).ready(function() {
        loadCartAjax();

        $(window).scroll(function() {
            if ($(this).scrollTop() > 114) {
                $("#navbar-top").addClass('fix-nav');
            } else {
                $("#navbar-top").removeClass('fix-nav');
            }
        });
    });
</script>
<script src="./public/javascripts/liveSearch.js"></script>
</body>
</html>
<?php
if (isset($conn) && $conn instanceof mysqli) {
    $conn->close();
}
?>
