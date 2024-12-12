<?PHP

if(!isset($_GET['submit'])){header("Location: Main.php");exit();}else{}
if(!isset($_GET['id']) or $_GET['id']==""){header("Location: Main.php");exit();}else{}
?>

<html>
<title>Магазин</title>
<style>
* {
  font-family: 'Arial', sans-serif;
  margin: 0;
  padding: 0;
}

body {
  background-color: #f4f4f9;
  color: #333;
  font-size: 16px;
}

#inseat {
  margin: 3px;
  background: rgba(255, 255, 255, 0.85);
  display: inline-block;
  border-radius: 5px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

#animate_0_noanim {
  display: inline-block;
  opacity: 1;
}

#seats {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
  justify-items: center;
  margin-top: 20px;
}

#chart {
  max-width: 1000px;
  margin: 0 auto;
  padding: 20px;
}

#caption_c {
  font-size: 30px;
  color: #555;
  text-align: center;
  font-family: 'Arial', sans-serif;
}

#midit {
  padding: 20px;
  text-align: center;
}

h2, h3 {
  color: #333;
  margin-bottom: 10px;
}

input[type='checkbox'] {
  cursor: pointer;
  width: 30px;
  height: 30px;
  margin: 5px;
  background-color: #fff;
  border: 2px solid #ddd;
  border-radius: 5px;
}

input[type='checkbox']:checked {
  background-color: #007bff;
  border-color: #007bff;
}

#sold {
  background-image: url('Content/SOB.png');
  background-position: center;
  background-repeat: no-repeat;
  width: 78px;
  height: 78px;
  opacity: 0.5;
}

#savail {
  background-image: url('Content/Available.png');
  background-position: center;
  background-repeat: no-repeat;
  width: 78px;
  height: 78px;
}

#infow {
  padding: 30px;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  margin-top: 20px;
}

#txt_a {
  color: #555;
  font-size: 14px;
  line-height: 1.6;
}

#txt_b {
  font-weight: bold;
}

#inline-table {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 20px;
}

#pcbutton {
  background-color: #28a745;
  color: white;
  border: none;
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
}

#pcbutton:hover {
  background-color: #218838;
}

#submit {
  background-color: #007bff;
  color: white;
  padding: 10px 20px;
  font-size: 16px;
  cursor: pointer;
  border: none;
  border-radius: 5px;
}

#submit:hover {
  background-color: #0056b3;
}

#mini_error {
  color: #e74c3c;
  font-size: 12px;
  margin-top: 10px;
}

table {
  width: 100%;
  margin-top: 20px;
}

table td {
  padding: 8px;
  font-size: 14px;
}

@media (max-width: 768px) {
  #seats {
    grid-template-columns: repeat(2, 1fr);
  }

  #chart {
    padding: 10px;
  }

  #infow {
    padding: 20px;
  }
}
</style>

<?PHP

if($_GET['submit']==9){
  echo "<div id='adminshop'><h1>Панель администратора</h1>";
} else {
  echo"<div id='shop'>";
} ?>

<?PHP if($_GET['submit']==9){echo "<div id='adminpanel'>";}else{} ?>

<div id="chart">
    <br />
    <form action="Confirmation.php" method="post">
    <h2>План рассадки на концерте</h2><br>
    <p id="caption_c">Сцена</p>
    <div id="seats" align="center">
    
    <?PHP
    echo"<input type='hidden' name='id' value=".$_GET['id']." /><input type='hidden' name='usertype' value=".$_GET['submit']." />";

    $host = getenv('DB_HOST');
    $dbname = getenv('DB_NAME');
    $user_db = getenv('DB_USER');
    $password_db = getenv('DB_PASSWORD');

    $conn = pg_connect("host=$host dbname=$dbname user=$user_db password=$password_db") or die('Connection failed: ' . pg_last_error());
    $sql = "SELECT * FROM seats";
    $result = pg_query($conn, $sql) or die(pg_last_error());
    $amount = pg_num_rows($result);
    
    for ($i = 0; $i < $amount; $i++) {
        $row = pg_fetch_assoc($result);

        $schecked = isset($_GET[$row['seat']]) ? "checked" : "";
        $sdisabled = $row['order_id'] != 0 ? "disabled" : "";

        $opa = $row['order_id'] != 0 ? "opah" : "opac";
        $ssold = $row['order_id'] != 0 ? "sold" : "savail";
        
        echo "<div id='animate_" . $i . "'><div id='inseat'><div id='" . $ssold . "'><div id='midit'>
              <input id='" . $opa . "' align='middle' type='checkbox' name='" . $row['seat'] . "' value='" . $row['price'] . "' " . $schecked . " " . $sdisabled . "/></div></div></div></div>";
            
    }
    pg_close($conn);
    ?>
    </div></div></div>

    <div id="infow">
      <div id="info">
        <br />
        <h3>Закажите свои билеты здесь!</h3>
        <div id="txt_a">
        <p>Выберите места, которые вы хотите забронировать, из представленного плана рассадки. Цена билета зависит от ряда.</p> 
        <div>
            <table>
              <tr>
                <td>Первый ряд:</td>
                <td>$20.00</td>
                <td>(A)</td>
              </tr>
              <tr>
                <td>Второй ряд: </td>
                <td>$18.00</td>
                <td>(B)</td>
              </tr>
              <tr>
                <td>Третий ряд:</td>
                <td>$16.00</td>
                <td>(C)</td>
              </tr>
              <tr>
                <td>Четвёртый ряд:</td>
                <td>$14.00</td>
                <td>(D)</td>
              </tr>
            </table>
        </div>
        <br />
        <p>После выбора мест нажмите кнопку "Предварительная сумма", или "Продолжить" для завершения заказа.</p>
        
        <table>
          <tr>
            <td><input type='submit' value='Предварительная сумма' id='pcbutton' name='pricecheck'></td>
            <td><?PHP if(isset($_GET['subtotal'])){$subtotal="$".$_GET['subtotal'].".00";}else{$subtotal="$0.00";} echo $subtotal; ?></td>
          </tr>
        </table>
        </div>
      </div>
    </div>

    <div align="right" id="inline-table">
      <?PHP
        if(isset($_GET['none'])){echo"<label id='mini_error'> Вы должны выбрать место, чтобы продолжить.</label>";}
        elseif(isset($_GET['error'])){echo"<label id='mini_error'>Выбранное вами место уже забронировано.</label>";}
        else{echo"<label id='mini_error'></label>";}
      ?>
      <input id="submit" type="submit" name="continue" value="ПРОДОЛЖИТЬ">
    </div>
    </form>
</div>
</html>
