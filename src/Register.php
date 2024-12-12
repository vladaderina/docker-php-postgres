<?php

#===============================================#
# File: Register.php
# Function: Processes user signup data and validates input format.
#===============================================#

# Check if the form was submitted
if (isset($_POST['submit'])) {
    $fname = trim($_POST['first_name']);
    $lname = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $ccn = $_POST['credit_card_num'];
    $ccem = $_POST['cce_m'];
    $ccey = $_POST['cce_y'];
    $cmonth = date("n");
    $cyear = date("Y");

    # Initialize error flags
    $errors = [];

    # Validate first name (only letters)
    if (!ctype_alpha($fname)) {
        $errors[] = "Invalid first name.";
    }

    # Validate last name (only letters)
    if (!ctype_alpha($lname)) {
        $errors[] = "Invalid last name.";
    }

    # Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    # Validate password (at least 6 characters)
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    # Validate credit card number (exactly 16 digits)
    if (strlen($ccn) != 16 || !ctype_digit($ccn)) {
        $errors[] = "Invalid credit card number.";
    }

    # Validate credit card expiration date
    if ($ccem === "X" || $ccey === "X") {
        $errors[] = "Invalid credit card expiration date.";
    } elseif ($ccey < $cyear || ($ccey == $cyear && $ccem < $cmonth)) {
        $errors[] = "Credit card is expired.";
    }

    # If there are no errors, process the signup
    if (empty($errors)) {
        $cce = "$ccem/$ccey";

        # Connect to PostgreSQL database
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        $user_db = getenv('DB_USER');
        $password_db = getenv('DB_PASSWORD');
        
        $dbconn = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") 
        if (!$dbconn) {
            die('Database connection failed: ' . pg_last_error());
        }

        # SQL query to insert user data into the `customers` table
        $sql = "INSERT INTO customers (last_name, first_name, email, password, credit_card_num, credit_card_expiry_date) 
                VALUES ($1, $2, $3, $4, $5, $6)";

        # Prepare parameters for the query
        $params = [$lname, $fname, $email, $password, $ccn, $cce];

        # Execute the SQL query
        $result = pg_query_params($dbconn, $sql, $params);
        if (!$result) {
            die('Query execution error: ' . pg_last_error());
        }

        # Close the database connection
        pg_close($dbconn);

        # Redirect to the main page
        header("Location: Main.php?created");
        exit();
    } else {
        # Redirect back to the signup page with error messages
        $error_query = http_build_query(['errors' => $errors]);
        header("Location: SignUp.php?$error_query");
        exit();
    }
} else {
    # Redirect to the main login page if accessed directly
    header("Location: Main.php");
    exit();
}

?>
