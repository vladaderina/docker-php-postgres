<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" type="text/css" href="Stylesheet.css">
    <style>
        /* CSS Стили */
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
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            width: 500px;
            text-align: center;
        }

        h1 {
            font-size: 28px;
            color: black;
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

        #submit {
            width: 100%; /* Ширина 100% */
            padding: 12px; /* Внутренние отступы */
            background-color: #1e90ff; /* Синий цвет фона */
            color: white; /* Белый цвет текста */
            border: none; /* Убираем границу */
            border-radius: 5px; /* Закругленные углы */
            font-size: 18px; /* Размер текста */
            cursor: pointer; /* Курсор в виде руки */
            transition: background-color 0.3s ease; /* Плавный переход цвета */
            margin-top: 20px; /* Отступ сверху */
            text-align: center; /* Выравнивание текста по центру */
            display: block; /* Отображение как блочный элемент */
            text-decoration: none; /* Убираем подчеркивание */
            height: 50px;
        }

        #submit:hover {
            background-color: #0056b3; /* Темно-синий цвет при наведении */
        }

        .return {
            width: 100%;
            padding: 12px;
            background-color: #1e90ff; /* Синий цвет кнопки */
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
            background-color: #0056b3; /* Темно-синий цвет при наведении */
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="credit_card_num"],
        input[type="first_name"],
        input[type="last_name"],
        select {
            background-color: #007BFF; /* Синий цвет кнопки */
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
        }

        select:focus {
            background-color: #0056b3; /* Темно-синий цвет при фокусе */
            outline: none; /* Убираем стандартный outline */
        }

        #textbox {
            background-color: rgb(232, 240, 254); /* Синий цвет */
            color: black;
        }

        #input {
            background-color: rgb(232, 240, 254); /* Синий цвет */
            color: black;
        }

        input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: #007BFF; /* Синий цвет кнопки */
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #0056b3; /* Темно-синий цвет при наведении */
        }

        #emsg {
            color: #d9534f; /* Красный цвет для ошибок */
            font-size: 14px;
            margin-bottom: 15px;
            text-align: left;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007BFF; /* Синий цвет ссылки */
            text-decoration: none;
            font-size: 16px;
        }

        a:hover {
            color: #0056b3; /* Темно-синий цвет при наведении */
        }

        /* Добавляем flexbox для выравнивания кнопок */
        .button-container {
            display: flex;
            justify-content: center;
            gap: 20px; /* Расстояние между кнопками */
            margin-top: 20px;
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

                # Комментарий: Отображает ошибки, возникшие при регистрации, если какие-либо поля были заполнены неправильно.
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
                        # Комментарий: Автоматически создает выпадающие меню для месяцев и лет, вместо ручного ввода множества лет.
                        echo "<label><b>Месяц:</b></label> <select name='cce_m' id='input'>";
                        if (isset($_GET['ve'])) {
                            if ($gccem != "X") {
                                echo "<option value='" . $gccem . "'>" . $gccem . "</option>";
                            }
                        }
                        echo "<option value='X'>ВЫБЕРИТЕ</option>";
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
                        echo "<option value='X'>ВЫБЕРИТЕ</option>";
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
            </table>

            <!-- Контейнер для кнопок -->
            <div class="button-container">
                <input id="submit" type="submit" name="submit" value="ЗАРЕГИСТРИРОВАТЬСЯ">
                <button type="button" class="return" onclick="window.location.href='Main.php'">НАЗАД</button>
            </div>
        </form>
    </div>
</body>

</html>