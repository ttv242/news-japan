<?php
session_start();
ob_start();

if (!isset($_SESSION['giohang']))
    $_SESSION['giohang'] = [];

include "model/category.php";
include "model/product.php";
include "model/account.php";
include "model/connectdb.php";


// $dsdm = getAll_dm();
// $sphome1 = getall_sp(0, "");
// $spdacbiet = get_product_special();
$all_cat = get_all_cat();
$all_pro = get_all_pro();


if (isset($_GET['action'])) {
    $action = $_GET['action'];

    switch ($action) {
        case 'login':
            include "view/modules/login.php";
            break;

        case 'registered':
            if (isset($_POST['registered'])) {
                $name = $_POST['name'];
                $password = $_POST['password'];
                $user_name = $_POST['user_name'];
                $email = $_POST['email'];
                $address = $_POST['address'];
                $number_phone = $_POST['number_phone'];
                $confirm_pass = $_POST['confirm_pass'];
                echo $user_name . ' ' . $password . ' ' . $email;
                add_user($user_name, $email, $address, $number_phone, $name, $password);
            }
            header('location: index.php?action=login');
            break;

        case 'logined':
            if (isset($_POST['logined']) && ($_POST['logined'])) {
                $user_name = $_POST['user_name'];
                $password = $_POST['password'];
                $kq = getcheckuserinfo($user_name, $password);
                $role = $kq[0]['role'];
                if ($role == 1) {
                    $_SESSION['id'] = $kq[0]['id'];
                    $_SESSION['name'] = $kq[0]['name'];
                    $_SESSION['email'] = $kq[0]['email'];
                    $_SESSION['numberphone'] = $kq[0]['numberphone'];
                    $_SESSION['pass'] = $kq[0]['pass'];
                    $_SESSION['role'] = $role;
                    header('location: admin/index.php');
                } elseif ($role == 0) {
                    $_SESSION['role'] = $role;
                    $_SESSION['id'] = $kq[0]['id'];
                    $_SESSION['name'] = $kq[0]['name'];
                    if ($_SESSION['name'] == '') {
                        header('location: index.php?action=login&erro');
                    } else
                        header('location: index.php');
                }
            }
            break;

        case 'logout':
            unset($_SESSION['role']);
            unset($_SESSION['id']);
            unset($_SESSION['name']);
            header('location: login.html');
            break;

        case 'blog':
            include "view/header.php";
            include "view/modules/blog.php";
            include "view/footer.php";
            break;

        case 'about':
            include "view/header.php";
            include "view/modules/about.php";
            include "view/footer.php";
            break;
        
        case 'news':
            include "view/header.php";
            include "view/modules/news.php";
            include "view/footer.php";
            break;

        case 'news-single':
            include "view/header.php";
            include "view/modules/news_single.php";
            include "view/footer.php";
            break;

        case 'news-regular':
            include "view/header.php";
            include "view/modules/news_regular.php";
            include "view/footer.php";
            break;

        case 'contact':
            include "view/header.php";
            include "view/modules/contact.php";
            include "view/footer.php";
            break;

        case 'product-detail':
            include "view/header.php";
            if (isset($_GET['id']) && ($_GET['id'] > 0)) {
                $id = $_GET['id'];
                $id_cat = get_id_cat($id);
                $all_cat = get_all_cat();
                $all_pro = get_all_pro();
            } else if (isset($_GET['id']) && ($_GET['id'] == null)) {
                $all_cat = get_all_cat();
                $all_pro = get_all_pro();
            }
            include "view/modules/product-detail.php";
            include "view/footer.php";
            break;

        case 'cart':
            include "view/header.php";
            include "view/modules/cart.php";
            include "view/footer.php";
            break;

        case 'product':
            if (isset($_GET['id']) && ($_GET['id'] > 0)) {
                $id = $_GET['id'];
            }
            include "view/header.php";
            include "view/modules/product.php";
            include "view/footer.php";
            break;

        case 'addToCart':
            include "view/addToCart.php";
            break;

        case 'deleteCart':
            include "view/deleteCart.php";
            break;

        case 'addcart':
            if ((isset($_POST['addtocart'])) && ($_POST['addtocart'])) {
                $id = $_POST['id'];
                $tensp = $_POST['tensp'];
                $img = $_POST['img'];
                $gia = $_POST['gia'];
                $sl = 1;
                $fg = 0;
                $i = 0;
                foreach ($_SESSION['giohang'] as $sp) {
                    if ($sp[1] === $tensp) {
                        $slnew = $sl + $sp[4];
                        $_SESSION['giohang'][4] += $slnew;
                        $fg = 1;
                        break;
                    }
                    $i++;
                }

                if ($fg == 0) {
                    $item = array($id, $tensp, $img, $gia, $sl);
                    $_SESSION['giohang'][] = $item;
                }
            }
            header('location: index.php?action=shopingCart');
            break;

            case 'cart':
                include "view/header.php";
                include "view/modules/cart.php";
                include "view/footer.php";
            break;

        case 'deletecart':
            if (isset($_SESSION['giohang']))
                unset($_SESSION['giohang']);
            header('location: index.php?action=product');
            break;

        case 'shoppingCart':
            include "view/modules/shoppingCart.php";
            break;

        default:
            include "view/header.php";
            include "view/modules/slide.php";
            include "view/modules/home.php";
            include "view/footer.php";
            break;
    }

} else {
    include "view/header.php";
    include "view/modules/slide.php";
    include "view/modules/home.php";
    include "view/footer.php";
}
?>