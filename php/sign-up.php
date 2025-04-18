<?php
//* Code on How to Write a Database Query in PDO Style (PDO = PHP Database Object)
require("../config/connection.php");

if (isset($_POST["email"]) && isset($_POST["password"])&& isset($_POST["name"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $name = $_POST["name"];
        
    $query = "INSERT INTO \"User\" (email, password, name) VALUES ($1, $2, $3)";
    $result = pg_query_params($conn, $query, array($email, $password, $name));

    if (!$result) {
        echo "<script>alert('هنالك خطأ ما');</script>";
        exit;
    } else {
        echo "<script>alert('تم التسجيل بنجاح!');</script>";
        header("Location: ../customer/homepage.html?email=$email&role=customer");
    }

    pg_close($conn);
}
