<?php
require("../config/connection.php");

if (isset($_POST["email"])) {
$email = $_POST["email"];
$query = "
    SELECT ci.*
    FROM \"Cart\" c
    JOIN \"Cart_Item\" ci ON c.cart_id = ci.cart_id
    WHERE c.customer_email = $1 AND c.condition = 'new'
  ";
$result = pg_query_params($conn, $query, array($email));

if (!$result) {
  echo json_encode(["error" => "فشل الاتصال بقاعدة البيانات."]);
  exit;
}

// Check if the cart exists and send the cart items to javascript file
if (pg_num_rows($result) > 0) {
  $cart = pg_fetch_all($result);
  echo json_encode($cart);
} else {
  echo json_encode([]);;
}

pg_close($conn);
} else {
  echo json_encode(["error" => "لم يتم إرسال البريد الإلكتروني."]);
}