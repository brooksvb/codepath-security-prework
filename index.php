<!doctype HTML>
<html>
<head>
  <title>Tip Calculator</title>

  <style>.required{color:red}</style>

  <?php

    $bill_total = $tip_rate = "";

    $result = $error = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Parse data
      $bill_total = process_input($_POST["bill_total"]);
      $tip_rate = process_input($_POST["tip_rate"]);

      // Evaluate tip
      $tip = calculate_tip((float) $bill_total, (float) $tip_rate);

      $result = true;
    }

    /*
      @function: sanitize input and convert to normal encoding
    */
    function process_input($data) {
      $data = trim($data);              // Remove whitespace, /n, etc.
      $data = stripslashes($data);      // Remove any backslashes for literal characters
      $data = htmlspecialchars($data);  // Convert literal characters to HTML encoded
      return $data;
    }

    /*
      @param rate: percent rate of tip
      @return: tip amount (not including bill)
    */
    function calculate_tip($total, $rate) {
      return $total * $rate / 100;
    }
  ?>
</head>
<body>

<!--
  $_SERVER["PHP_SELF"] is a global variable to refers to the current page.
  htmlspecialchars() will convert the given string from literal characters to HTML
  encoded character string.
  i.e. '<' -> '&lt;'
  This is to prevent XSS if someone appends script onto the url, and <script> tags
  will be interpreted literally.
-->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
  Bill total:<input type="text" name="bill_total" value="<?php echo $bill_total ?>"><br>
  <input type="radio" name="tip_rate" value="10" <?php if (isset($tip_rate) && $tip_rate == "10") echo "checked"; ?>>15%<br>
  <input type="radio" name="tip_rate" value="15" <?php if (isset($tip_rate) && $tip_rate == "15") echo "checked"; ?>>10%<br>
  <input type="radio" name="tip_rate" value="20" <?php if (isset($tip_rate) && $tip_rate == "20") echo "checked"; ?>>20%<br>
  <button type="submit">Calculate</button>
</form>

<?php
  // numberformat(num, dec) will format num to dec decimal places
  if ($result) {
    echo "<div id='result'>";
      echo "<h3>Bill Amount: </h3>$";
      echo numberformat((double)$bill_total, 2);
      echo "<br><h3>Tip Amount: </h3>$";
      echo numberformat($tip, 2);
      echo "<br><h3>Total to Pay: </h3>";
      echo $total;
    echo "</div>";
  } else if ($error) {
    echo "<div id='error'>";
      echo "<h2>"; echo $error; echo "</h2>";
    echo "</div>";
  }
?>


</body>
</html>
