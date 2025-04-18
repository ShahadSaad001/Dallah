<?php
require_once '../config/connection.php';

if (!isset($_POST['email'])) {
    echo "يجب تسجيل الدخول أولاً.";
    exit;
}

$user_email = $_POST['email'];

if (!isset($_POST['product_id'])) {
    echo "بيانات غير صالحة.";
    exit;
}

$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'] ?? '';
$price = $_POST['price'] ?? 0;
$quantity = $_POST['quantity'] ?? 1;
$product_image = $_POST['product_image'] ?? null;

$query = "SELECT cart_id FROM \"Cart\" WHERE customer_email = $1 AND condition = 'new'";
$result = pg_query_params($conn, $query, array($user_email));
$cart = pg_fetch_assoc($result);

if ($cart) {
    $cart_id = $cart['cart_id'];
} else {
    $query = "INSERT INTO \"Cart\" (customer_email) VALUES ($1) RETURNING cart_id";
    $result = pg_query_params($conn, $query, array($user_email));
    if (!$result) {
        echo "حدث خطأ أثناء إنشاء السلة";
        exit;
    }
    $cart_id = pg_fetch_result($result, 0, "cart_id");
}

$query = "SELECT id, quantity FROM \"Cart_Item\" WHERE cart_id = $1 AND product_name = $2";
$result = pg_query_params($conn, $query, array($cart_id, $product_name));
$item = pg_fetch_assoc($result);

if ($item) {
    $new_quantity = $item["quantity"] + 1;
    $query = "UPDATE \"Cart_Item\" SET quantity = $1 WHERE id = $2";
    $result = pg_query_params($conn, $query, array($new_quantity, $item["id"]));
    echo "تم تحديث الكمية.";
} else {
    $query = "INSERT INTO \"Cart_Item\" (cart_id, product_name, price, quantity, product_image) VALUES ($1, $2, $3, $4, $5)";
    $result = pg_query_params($conn, $query, array($cart_id, $product_name, $price, $quantity, $product_image));
    echo "تمت إضافة المنتج للسلة.";
}
