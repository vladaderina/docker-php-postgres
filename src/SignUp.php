<?php
#===============================================#
# File: SignUp.php
# Function: This page lets a new customer sign up for an account. If they are missing any details or they provide details that aren't in the correct format (Such as an incorrect email, an expired credit card number or a letter in the credit card field, it will notify the user and keep the existing data they entered so they don't need to restart the form each time an error occurs.)
#===============================================#

# Comment: Gets the previously entered data if they're being redirected back to the sign up page due to an error which the registration process page has picked up.
if (isset($_GET['ve'])) {
    $ea = $_GET['ea'];
    $eb = $_GET['eb'];
    $ec = $_GET['ec'];
    $ed = $_GET['ed'];
    $ee = $_GET['ee'];
    $efa = $_GET['efa'];
    $efb = $_GET['efb'];

    $gfn = $_GET['first_name'];
    $gln = $_GET['last_name'];
    $gea = $_GET['email'];
    $gap = $_GET['password'];
    $gcn = $_GET['credit_card_num'];
    $gccem = $_GET['ccem'];
    $gccey = $_GET['ccey'];

    # Comment: States which errors occurred from the info received from the process page, if a certain field had no error then it won't show that error.
    if ($ea == "no") {
        $hea = "Use only alphabetic characters in the first name.";
        $fnc = "FF0000";
    } else {
        $hea = "X";
        $fnc = "FFFFFF";
    }
    if ($eb == "no") {
        $heb = "Use only alphabetic characters in the last name.";
        $lnc = "FF0000";
    } else {
        $heb = "X";
        $lnc = "FFFFFF";
    }
    if ($ec == "no") {
        $hec = "The provided e-mail address is not valid.";
        $eac = "FF0000";
    } else {
        $hec = "X";
        $eac = "FFFFFF";
    }
    if ($ed == "no") {
        $hed = "The password must be at least 6 characters long.";
        $apc = "FF0000";
    } else {
        $hed = "X";
        $apc = "FFFFFF";
    }
    if ($ee == "no") {
        $hee = "The credit card number must be a 16 digit number.";
        $cnc = "FF0000";
    } else {
        $hee = "X";
        $cnc = "FFFFFF";
    }
    if ($efa == "no") {
        $hefa = "You must provide a credit card expiry date.";
        $cec = "FF0000";
    } else {
        $hefa = "X";
        $cec = "FFFFFF";
    }
    if ($efa == "yes") {
        if ($efb == "no") {
            $hefb = "The provided credit card has expired.";
            $cec = "FF0000";
        } else {
            $hefb = "X";
            $cec = "FFFFFF";
        }
    } else {
        $hefb = "X";
    }
} else {
    $hea = "X";
    $heb = "X";
    $hec = "X";
    $hed = "X";
    $hee = "X";
    $hefa = "X";
    $hefb = "X";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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

        #su_pan {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
        }

        h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 20px;
            font-weight: bold;
        }

        label {
            font-size: 16px;
            color: #555;
            display: block;
            text-align: left;
            margin-bottom: 5px;
        }
        .return {
            width: 100%;
            padding: 12px;
            background-color: rgba(226, 99, 148, 0.8);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            text-align: center;
            display: block;
            text-decoration: none;
        }

        .return:hover {
            background-color: rgba(200, 80, 128, 0.8);
            color: white; /* Убедитесь, что цвет текста остаётся белым */
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        input[type="submit"] {
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

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        #emsg {
            color: #d9534f;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: left;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007BFF;
            text-decoration: none;
            font-size: 16px;
        }

        a:hover {
            color: #0056b3;
        }
    </style>
</head>

<body>
    <div id="su_pan">
        <form action="Register.php" method="post">
            <h1>РЕГИСТРАЦИЯ</h1>

            <?php
            if (isset($_GET['ve'])) {
                echo "<div id='emsg'>";

                # Comment: Displays the errors that occurred when signing up if there were any incorrect fields
                if ($hea != "X") {
                    echo "<li>" . $hea . "</li></br>";
                }
                if ($heb != "X") {
                    echo "<li>" . $heb . "</li></br>";
                }
                if ($hec != "X") {
                    echo "<li>" . $hec . "</li></br>";
                }
                if ($hed != "X") {
                    echo "<li>" . $hed . "</li></br>";
                }
                if ($hee != "X") {
                    echo "<li>" . $hee . "</li></br>";
                }
                if ($hefa != "X") {
                    echo "<li>" . $hefa . "</li></br>";
                }
                if ($hefb != "X") {
                    echo "<li>" . $hefb . "</li></br>";
                }
                echo "</div><br><br>";
            }
            ?>

            <table width="100%" border="0">
                <tr>
                    <td height="40" valign="middle" align="left"><label for="first_name"><b>Имя:</b></label></td>
                    <td align="right"><input id="textbox" type="text" name="first_name" <?php if (isset($_GET['ve'])) { echo "value='" . $gfn . "'"; } ?> /></td>
                </tr>
                <tr>
                    <td height="40" valign="middle" align="left"><label for="last_name"><b>Фамилия:</b></label></td>
                    <td align="right"><input id="textbox" type="text" name="last_name" <?php if (isset($_GET['ve'])) { echo "value='" . $gln . "'"; } ?> /></td>
                </tr>
                <tr>
                    <td height="40" valign="middle" align="left"><label for="email"><b>Почта:</b></label></td>
                    <td align="right"><input id="textbox" type="email" name="email" <?php if (isset($_GET['ve'])) { echo "value='" . $gea . "'"; } ?> /></td>
                </tr>
                <tr>
                    <td height="40" valign="middle" align="left"><label for="password"><b>Пароль:</b></label></td>
                    <td align="right"><input id="textbox" type="password" name="password" <?php if (isset($_GET['ve'])) { echo "value='" . $gap . "'"; } ?> /></td>
                </tr>
                <tr>
                    <td height="40" valign="middle" align="left"><label for="credit_card_num"><b>Номер банковской карты:</b></label></td>
                    <td align="right"><input id="textbox" type="text" name="credit_card_num" maxlength="16" <?php if (isset($_GET['ve'])) { echo "value='" . $gcn . "'"; } ?> /></td>
                </tr>
                <tr>
                    <td height="40" valign="middle" align="left"><label for="cce_m"><b>Срок действия карты:</b></label></td>
                    <td align="right">
                        <?php
                        # Comment: Automatically creates the drop-down menu for the months and years instead of manually entering tons of years in a row.
                        echo "<label><b>Месяц:</b></label> <select name='cce_m' id='input'>";
                        if (isset($_GET['ve'])) {
                            if ($gccem != "X") {
                                echo "<option value='" . $gccem . "'>" . $gccem . "</option>";
                            }
                        }
                        echo "<option value='X'>SELECT</option>";
                        for ($q = 1; $q <= 12; $q++) {
                            echo "<option value='" . $q . "'>" . $q . "</option>";
                        }
                        echo "</select>&nbsp;";
                        echo "<label><b>Год:</b></label> <select name='cce_y' id='input'>";
                        if (isset($_GET['ve'])) {
                            if ($gccey != "X") {
                                echo "<option value='" . $gccey . "'>" . $gccey . "</option>";
                            }
                        }
                        echo "<option value='X'>SELECT</option>";
                        for ($s = 2014; $s <= 2030; $s++) {
                            echo "<option value='" . $s . "'>" . $s . "</option>";
                        }
                        echo "</select>";
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr align="right">
                    <td></td>
                    <td><input id="submit" type="submit" name="submit" value="SIGN UP"></td>
                </tr>
            </table>
            <br><br>
            <a href="Main.php" class="return">НАЗАД</a>
        </form>
    </div>
</body>

</html>