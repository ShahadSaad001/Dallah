<?php
require("../config/connection.php");

if (isset($_POST["email"]) && isset($_POST["cart_id"])) {
    $email = $_POST["email"];
    $cart_id = $_POST["cart_id"];

    $updateQuery = "UPDATE \"Cart\" SET condition = 'paid' WHERE cart_id = $1 AND customer_email = $2";
    $updateResult = pg_query_params($conn, $updateQuery, array($cart_id, $email));

    if (!$updateResult) {
        echo "فشل تحديث حالة السلة.";
        pg_close($conn);
        exit;
    }

    $checkQuery = "SELECT order_id FROM \"Order\" WHERE cart_id = $1";
    $checkResult = pg_query_params($conn, $checkQuery, array($cart_id));
    if (pg_num_rows($checkResult) > 0) {
        $existingOrderId = pg_fetch_result($checkResult, 0, "order_id");
        echo "تم الدفع مسبقاً. رقم الطلب: $existingOrderId";
        pg_close($conn);
        exit;
    }

    $insertQuery = "INSERT INTO \"Order\" (cart_id, customer_email) VALUES ($1, $2) RETURNING order_id";
    $insertResult = pg_query_params($conn, $insertQuery, array($cart_id, $email));

    if (!$insertResult) {
        echo "فشل إنشاء الطلب.";
        pg_close($conn);
        exit;
    }

    $order_id = pg_fetch_result($insertResult, 0, "order_id");

pg_close($conn);
    echo "تمت عملية الدفع بنجاح. رقم الطلب: $order_id";
} else {
  echo "معلومات ناقصة";
}


