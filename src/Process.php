<?php
#===============================================#
# File: Process.php
# Function: Retrieves the order details from the confirmation page and updates the database with the order information such as which seats were purchased, the total cost and date and time of the purchase.
#===============================================#

if (isset($_POST['process'])) {

    # Comment: Gets the date and time, order details, and the customer's ID
    date_default_timezone_set('America/New_York');
    $date = date("Y/m/d");
    $time = date("H:i:s");
    $sub_total = $_POST['sub_total'];
    $hst = $_POST['hst'];
    $total_cost = $_POST['total_cost'];
    $customer_id = $_POST['customer_id'];
    
    $host = getenv('DB_HOST');
    $dbname = getenv('DB_NAME');
    $user_db = getenv('DB_USER');
    $password_db = getenv('DB_PASSWORD');

    # Comment: Publishes the order details into the orders section of the database (Date, time, subtotal, total, tax, and the customer's ID number who made the purchase).
    $db = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") or die('Not connected: ' . pg_last_error());
    
    $SQL = "INSERT INTO orders (date, time, sub_total, hst, total_cost, customer_id) 
            VALUES ('$date', '$time', $sub_total, $hst, $total_cost, $customer_id) RETURNING id";
    $result = pg_query($db, $SQL);
    if (!$result) {
        die('Error: ' . pg_last_error());
    }
    
    $order_row = pg_fetch_assoc($result);
    $order_id = $order_row['id'];
    pg_close($db);

    # Comment: Connects to the seats section of the database and changes the property called "Order ID" for each seat purchased.
    $dbx = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") or die('Not connected: ' . pg_last_error());
    
    $SQLx = "SELECT seat FROM seats";
    $resultx = pg_query($dbx, $SQLx);
    if (!$resultx) {
        die('Error: ' . pg_last_error());
    }

    while ($rowx = pg_fetch_assoc($resultx)) {
        if (isset($_GET[$rowx['seat']])) {
            $dba = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") or die('Not connected: ' . pg_last_error());
            
            $SQLa = "UPDATE seats SET order_id = $order_id WHERE seat = '" . $rowx['seat'] . "'";
            $resulta = pg_query($dba, $SQLa);
            if (!$resulta) {
                die('Error: ' . pg_last_error());
            }
            pg_close($dba);
        }
    }

    # Comment: Prompts the user if they wish to log out or buy more tickets.
    echo "
    <!DOCTYPE html>
    <html lang='ru'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Заказ обработан</title>
        <link rel='stylesheet' type='text/css' href='Stylesheet.css'>
        <style>
            /* CSS Styling */
            * {
                font-family: 'Arial', sans-serif;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                background: linear-gradient(135deg, #f5f7fa, #c3cfe2);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                color: #333;
            }

            #confirm {
                background: #fff;
                padding: 30px;
                border-radius: 10px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                text-align: center;
            }

            h2 {
                font-size: 28px;
                color: #333;
                margin-bottom: 20px;
                font-weight: bold;
            }

            p {
                font-size: 16px;
                color: #555;
                margin-bottom: 20px;
            }

            input[type='submit'] {
                width: 100%;
                padding: 12px;
                background-color: #007BFF;
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 18px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            input[type='submit']:hover {
                background-color: #0056b3;
            }

            table {
                width: 100%;
                margin: 20px 0;
            }

            td {
                padding: 10px;
            }

            a {
                display: inline-block;
                padding: 12px 20px;
                background-color: #007BFF;
                color: white;
                border: none;
                border-radius: 5px;
                font-size: 16px;
                cursor: pointer;
                transition: background-color 0.3s ease;
                text-decoration: none;
                margin: 10px;
            }

            a:hover {
                background-color: #0056b3;
            }
        </style>
    </head>
    <body>
        <div id='confirm'>
            <div id='confirm_panel'>
                <h2>Спасибо за ваш заказ</h2>
                <p align='center'>Мы будем рады видеть вас на концерте!</p>
                <div align='center'>
                    <a href='Shop.php?submit=1&id=" . $customer_id . "'>КУПИТЬ ЕЩЁ</a>
                    <a href='Main.php'>ВЫЙТИ</a>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";
    exit();

} else {
    header("Location: Main.php");
    exit();
}

?>