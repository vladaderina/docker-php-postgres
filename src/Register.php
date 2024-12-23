<?php

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

    $errors = [];

    # Validation
    if (!ctype_alpha($fname)) {
        $errors[] = "Invalid first name.";
    }
    if (!ctype_alpha($lname)) {
        $errors[] = "Invalid last name.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    if (strlen($ccn) != 16 || !ctype_digit($ccn)) {
        $errors[] = "Invalid credit card number.";
    }
    if ($ccem === "X" || $ccey === "X") {
        $errors[] = "Invalid credit card expiration date.";
    } elseif ($ccey < $cyear || ($ccey == $cyear && $ccem < $cmonth)) {
        $errors[] = "Credit card is expired.";
    }

    if (empty($errors)) {
        $cce = "$ccem/$ccey";

        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        $user_db = getenv('DB_USER');
        $password_db = getenv('DB_PASSWORD');
        
        $dbconn = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db");
        if (!$dbconn) {
            $errors[] = "Internal server error. Please try again later.";
        } else {
            $sql = "INSERT INTO customers (last_name, first_name, email, password, credit_card_num, credit_card_expiry_date) 
                    VALUES ($1, $2, $3, $4, $5, $6)";
            $params = [$lname, $fname, $email, $password, $ccn, $cce];
            set_error_handler(function ($errno, $errstr, $errfile, $errline) {
                if ($errno === E_WARNING) {
                    // Обрабатываем warning
                    echo "Warning: $errstr in $errfile on line $errline";
                    return true; // Предотвращаем вывод стандартного предупреждения
                }
                return false; // Если это не warning, позволяем стандартному обработчику обработать ошибку
            });

            set_error_handler(function ($errno, $errstr, $errfile, $errline) {
                # Логируем предупреждения
                error_log("Warning [$errno]: $errstr in $errfile on line $errline");
                throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
            });
            
            try {
                $result = pg_query_params($dbconn, $sql, $params);
                if (!$result) {
                    $error_message = pg_last_error($dbconn);
                    if (strpos($error_message, 'duplicate key value violates unique constraint') !== false) {
                        $errors[] = "A user with this email already exists.";
                    } else {
                        $errors[] = "Database error: " . $error_message;
                    }
                }
            } catch (Exception $e) {
                # Перехватываем и обрабатываем все ошибки (включая предупреждения)
                $errors[] = "Internal server error. Please try again later.";
            } finally {
                pg_close($dbconn);
                restore_error_handler(); # Восстанавливаем обработчик ошибок
            }
            
        }                
    }

    if (!empty($errors)) {
        $error_query = http_build_query(['errors' => $errors]);
        header("Location: SignUp.php?$error_query");
        exit();
    } else {
        header("Location: Main.php?created");
        exit();
    }
} else {
    header("Location: Main.php");
    exit();
}

?>
