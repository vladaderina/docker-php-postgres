<?php
#===============================================#
# File: Confirmation.php
# Function: Retrieves the user's selection of seats they wish to purchase from the shop and then displays the details of the order a customer is about to make, such as the tickets they're buying, the cost of each ticket, the total before and after tax and the user's name and last 4 digits of their credit card number. If the user decides to modify the order, the code sends back what seats were selected to save their options if they wish to change something. This script will also send the user back to the shop if they try to buy a ticket that has already been purchased if they find out a way to bypass the system in the shop.
#===============================================#

if (isset($_POST['pricecheck']) || isset($_POST['continue'])) {
    # Creating a new blank array.
    $seats = array();

    # Connecting to the PostgreSQL database and retrieves all the ticket information.
    $host = getenv('DB_HOST');
    $dbname = getenv('DB_NAME');
    $user_db = getenv('DB_USER');
    $password_db = getenv('DB_PASSWORD');

    $dbconn = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") or die('Could not connect: ' . pg_last_error());

    # Execute the query
    $SQL = "SELECT * FROM seats";
    $result = pg_query($dbconn, $SQL);

    if (!$result) {
        die('Query failed: ' . pg_last_error($dbconn));
    }

    # Checks what seats the user wishes to buy and adds these seats into an array.
    while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        if (isset($_POST[$row['seat']]) && $row['order_id'] == 0) {
            array_push($seats, array($row['seat'], $_POST[$row['seat']]));
        }
    }

    # Redirects the user back to the shop if they attempt to buy a ticket that has already been purchased.
    pg_result_seek($result, 0); // Reset the result pointer to the beginning
    while ($row = pg_fetch_array($result, null, PGSQL_ASSOC)) {
        if (isset($_POST[$row['seat']]) && $row['order_id'] != 0) {
            header("Location: Shop.php?submit=" . $_POST['usertype'] . "&id=" . $_POST['id'] . "&error");
            exit();
        }
    }

    pg_close($dbconn);

    $subtotal = 0;
    $purchased = "";

    # This adds up the total cost of the tickets and then calculates the final cost including tax.
    for ($i = 0; $i < count($seats); $i++) {
        $subtotal = $subtotal + $seats[$i][1];
        $purchased = $purchased . $seats[$i][0] . "&";
    }

    if (isset($_POST['pricecheck'])) {
        header("Location: Shop.php?submit=" . $_POST['usertype'] . "&id=" . $_POST['id'] . "&" . $purchased . "subtotal=" . $subtotal . "");
        exit();
    } elseif (isset($_POST['continue'])) {
        # Sends the user back to the shop if they selected no tickets.
        if (count($seats) == 0) {
            header("Location: Shop.php?submit=" . $_POST['usertype'] . "&id=" . $_POST['id'] . "&none");
            exit();
        }
    

        # Connects to the database and pulls up the account's information such as name and the last digits of the credit card number the user is using.
        $dbconn = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db")  or die('Could not connect: ' . pg_last_error());
        $SQL = "SELECT * FROM customers WHERE id = " . $_POST['id'];
        $result = pg_query($dbconn, $SQL) or die(pg_last_error());
        $num_results = pg_num_rows($result);
        pg_close($dbconn);
        $row = pg_fetch_array($result, null, PGSQL_ASSOC);

        echo "
        <!DOCTYPE html>
        <html lang='ru'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Подтверждение заказа</title>
            <link rel='stylesheet' type='text/css' href='Stylesheet.css'>
            <style>
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
					color: black;
                    background: #fff;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
                    text-align: center;
                }

                h1 {
                    font-size: 28px;
                    color: #333;
                    margin-bottom: 20px;
                    font-weight: bold;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin: 20px 0;
                }

                th, td {
                    padding: 12px;
                    text-align: left;
                    border-bottom: 1px solid #ddd;
                }

                th {
                    background-color: #f5f5f5;
                }

                .button {
					font-family: 'Arial', sans-serif;
                    padding: 12px 20px;
                    background-color: #007BFF;
                    color: white;
                    border: none;
                    border-radius: 5px;
                    font-size: 16px;
                    cursor: pointer;
                    transition: background-color 0.3s ease;
                    text-decoration: none;
                    display: inline-block;
                }

                .button:hover {
                    background-color: #0056b3;
                }

                .cancel-button {
					font-family: 'Arial', sans-serif;
                    background-color: #d9534f;
                }

                .cancel-button:hover {
                    background-color: #c9302c;
                }
            </style>
        </head>
        <body>
            <div id='confirm'>
                <div id='confirm_panel'>
                    <h1>Подтверждение заказа</h1>
                    <table width='100%' border='0' align='center'>
                        <tr>
                            <td height='35px' width='47%' align='left'>Имя клиента:</td>
                            <td width='53%' align='right'>" . $row['first_name'] . ", " . $row['last_name'] . "</td>
                        </tr>
                        <tr>
                            <td height='35px' valign='top' align='left'>Места:</td>
                            <td align='right' valign='top'>
                                <table border='0'>
                                    <tr>
                                        <th>Место</th>
                                        <th>Цена</th>
                                    </tr>";

        for ($i = 0; $i < count($seats); $i++) {
            echo "<tr>
                    <td align='left'>" . $seats[$i][0] . "</td>
                    <td align='left'>$" . $seats[$i][1] . ".00</td>
                  </tr>";
        }
        echo "
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td height='35px' align='left'>Итого:</td>
                            <td align='right'>$";

        # Rounds the subtotal to 2 decimal places.
        $subtotal = number_format((float)$subtotal, 2, '.', '');
        echo $subtotal;

        echo "</td>
                        </tr>
                        <tr>
                            <td height='35px' align='left'>Налог (НДС):</td>
                            <td align='right'>$";

        # Calculates tax and rounds to 2 decimal places.
        $hst = $subtotal * 0.13;
        $hst = number_format((float)$hst, 2, '.', '');
        echo $hst;

        echo "</td>
                        </tr>
                        <tr>
                            <td height='35px' align='left'>Всего:</td>
                            <td align='right'>$";

        # Calculates total price and rounds.
        $total = $subtotal + $hst;
        $total = number_format((float)$total, 2, '.', '');
        echo $total;

        echo "</td>
                        </tr>
                        <tr>
                            <td height='35px' align='left'>Номер кредитной карты:</td>
                            <td align='right'>";

        # Gets the user's credit card number and then only displays the last 4 digits.
        $credit_card_number = $row['credit_card_num'];
        $lastdigits = substr($credit_card_number, 12, 4);
        echo "XXXX-XXXX-XXXX-" . $lastdigits;

        echo "</td>
                        </tr>
                    </table>
                    <br />
                    <table width='100%' border='0' align='center'>
                        <tr>
                            <td align='center'>
                                <form method='POST' action='Process.php?" . $purchased . "'>
                                    <input type='hidden' name='customer_id' value=" . $_POST['id'] . ">
                                    <input type='hidden' name='sub_total' value=" . $subtotal . ">
                                    <input type='hidden' name='hst' value=" . $hst . ">
                                    <input type='hidden' name='total_cost' value=" . $total . ">
                                    <input class='button' type='submit' name='process' value='ОБРАБОТАТЬ ЗАКАЗ'>
                                </form>
                            </td>
                            <td align='center'>
                                <!-- If the user wishes to modify their order, their current selection will be sent to the shop. -->
                                <form method='POST' action='Shop.php?submit=" . $_POST['usertype'] . "&id=" . $_POST['id'] . "&" . $purchased . "subtotal=" . $subtotal . "'>
                                    <input class='button cancel-button' type='submit' name='Cancel' value='ОТМЕНА'>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </body>
        </html>";
    }
} else {
    # Redirects the user to the homepage if they're not logged in and try to access the page.
    header("Location: Main.php");
    exit();
}

if (!isset($_POST['id']) || $_POST['id'] == "") {
    header("Location: Main.php");
    exit();
}
?>