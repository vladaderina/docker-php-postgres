<?php

#===============================================#
# File: Signin.php
# Function: Processes the sign in details provided from the main login page.
#===============================================#

# Comment: Gets the sign in info that was entered from the login page
if (isset($_POST['submit'])) {
    $user = $_POST['user'];
    $email = $_POST['email'];
    $email = strtolower($email);
    $password = $_POST['password'];

    # Comment: If a username or password are missing then it sends the user back to the main page and also tells the main page to display an error saying there's a missing field.
    if ($email == "" or $password == "") {
        header("Location: Main.php?blank");
        exit();
    }

    $host = getenv('DB_HOST');
    $dbname = getenv('DB_NAME');
    $user_db = getenv('DB_USER');
    $password_db = getenv('DB_PASSWORD');

    $conn = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") or die('Connection failed: ' . pg_last_error());

    # Comment: Prepares the query to select the user based on the username
    $sql = "SELECT * FROM $user WHERE email = $1 AND password = $2";
    $result = pg_query_params($conn, $sql, array($email, $password));

    # Comment: If no results are returned, the credentials are incorrect.
    if (pg_num_rows($result) == 0) {
        header("Location: Main.php?error");
        exit();
    }

    # Comment: If credentials are correct, process the user
    $row = pg_fetch_assoc($result);

    # Comment: If the user is a customer, they get directed to the shop
    if ($user == "customers") {
        header("Location: Shop.php?submit=1&id=" . $row['id']);
        exit();
    }
    # If the user is an admin, they get directed to the admin panel
    elseif ($user == "admin") {
        header("Location: Admin.php?submit=9");
        exit();
    } else {
        header("Location: Main.php");
        exit();
    }
} else {
    header("Location: Main.php");
    exit();
}

?>
