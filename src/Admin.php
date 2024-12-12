<?php
#===============================================#
# File: Admin.php
# Function: Admin panel lets you reset the database (refund) and has links forwarding you to order, customers or the seating chart pages.
#===============================================#

# Comment: Verifies if the login info matches with the admin account in the database
if (!isset($_GET['submit'])) {
    header("Location: Main.php");
    exit();
} else {
    if ($_GET['submit'] != 9) {
        header("Location: Main.php");
        exit();
    }
    
    # Comment: Erases the database if it receives the command to refund tickets.
    if (isset($_GET['erase']) && $_GET['erase'] == 1) {

        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        $user_db = getenv('DB_USER');
        $password_db = getenv('DB_PASSWORD');
    
        $db = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") or die('Not connected: ' . pg_last_error());

        # Clear the orders table
        $SQL = "DELETE FROM orders";
        $result = pg_query($db, $SQL);
        if (!$result) {
            die('Error: ' . pg_last_error());
        }

        # Update the seats table to reset the order_id
        $SQL = "UPDATE seats SET order_id = 0";
        $result = pg_query($db, $SQL);
        if (!$result) {
            die('Error: ' . pg_last_error());
        }

        pg_close($db);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <link rel="stylesheet" type="text/css" href="Stylesheet.css">
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

        #adminshop {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
        }

        table {
            width: 100%;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        input[type="submit"]:last-child {
            background-color: #007BFF;
        }

        input[type="submit"]:last-child:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div id="adminshop">
        <div id="admin_pan">
            <h2>Панель администратора</h2>
            <table width="100%" border="0" align="center">
                <tr>
                    <td align="center" height="45px">
                        <form action="Shop.php?submit=9&id=0" method="post">
                            <input type="submit" name="submit" value="Схема рассадки">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td align="center" height="45px">
                        <form action="Customers.php?submit=9&id=0" method="post">
                            <input type="submit" name="submit" value="Пользователи">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td align="center" height="45px">
                        <form action="Orders.php?submit=9&customer_id=all" method="post">
                            <input type="submit" name="submit" value="Заказы">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td align="center" height="45px">
                        <form action="Admin.php?submit=9&erase=1" method="post">
                            <input type="submit" name="submit" value="Возврат билетов">
                        </form>
                    </td>
                </tr>
                <tr>
                    <td align="center" height="45px">
                        <form action="Main.php" method="post">
                            <input type="submit" name="submit" value="Выход">
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>