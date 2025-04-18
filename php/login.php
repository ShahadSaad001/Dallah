<?php
//* Code on How to Write a Database Query in PDO Style (PDO = PHP Database Object)
require("../config/connection.php");

if (isset($_POST["email"]) && isset($_POST["password"])) {
        $email = $_POST["email"];
        $password = $_POST["password"];
    $query = "SELECT * FROM \"User\" WHERE email = $1 AND password = $2";
    $result = pg_query_params($conn, $query, array($email, $password));

    if (!$result) {
        echo "<script>alert('هنالك خطأ ما');</script>";
        exit;
    }

    // Check if user exists + Based on user role redirect him to his page
    if (pg_num_rows($result) > 0) {
        $user = pg_fetch_assoc($result);
        $role = $user['role'];
        if ($role === 'admin') {
            header("Location: ../admin/landing_page.php?email=$email&role=$role");
            exit;
        } else {
            header("Location: ../customer/homepage.html?email=$email&role=$role");
            exit;
        }
    } else {
      echo "<script>alert('كلمة السر أو الايميل خطأ');</script>";
 }

pg_close($conn);
}

//* Database Schema Info
// Table: User (this is a keyword in postgres so maybe you have to use "User" in your query)
// Columns: email, name, password, role (which is customer by default)
// i inserted into the table a row with fake user data that you can use in testing
// the row data is: Email = 'test@gmail.com', Name = 'Mohammed', Password  = '12345678'. the database gave the user by default the customer role
// Note: in postgres you have to use "" between database name if it were a keyword, and use '' between string values that you want to insert