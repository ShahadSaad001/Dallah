<?php
require("../config/connection.php");

if (isset($_POST["email"])) {
    $user_email = $_POST["email"];

    $query = "SELECT o.order_id, ci.product_name, ci.quantity, ci.product_image, ci.price
              FROM \"Order\" o
              JOIN \"Cart\" c ON o.cart_id = c.cart_id
              JOIN \"Cart_Item\" ci ON c.cart_id = ci.cart_id
              WHERE o.customer_email = $1";

    $result = pg_query_params($conn, $query, array($user_email));

    if (!$result) {
        echo json_encode(["error" => "Failed to fetch order data"]);
        exit;
    }

    // Fetch all the results as an associative array
    $orders = pg_fetch_all($result);

    // Return the results as JSON
    echo json_encode($orders);

    // Close the database connection
    pg_close($conn);
} else {
    echo json_encode(["error" => "User email not provided"]);
}