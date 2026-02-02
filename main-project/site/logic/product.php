<?php
ob_start();
session_start();
$ROOT_URL = __DIR__ . "/../../";

include $ROOT_URL . "/admin/models/category.php";
include $ROOT_URL . "/DAO/product.php";
include $ROOT_URL . "/DAO/category.php";

switch ($_GET['act']) {
    case 'getproduct':
        # code...
        $product = product_select_by_id($_POST['id']);

        echo json_encode(
            array(
                "product" => $product,
            )
        );
        break;

    default:
        # code...
        break;
}