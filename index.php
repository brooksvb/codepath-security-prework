<!doctype HTML>
<html>
<head>
  <title>Tip Calculator</title>

  <style>.required{color:red}</style>

  <?php

    $bill_total = $tip_rate = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      // Parse data
      $bill_total = process_input($_POST["bill_total"]);
      $tip_rate = process_input($_POST["tip_rate"]);

      // Evaluate tip
      $tip = calculate_tip((float) $bill_total, (float) $tip_rate);

      // Update page

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

    function display_error($message) {

    }
  ?>
</head>
<body>

<!--
  $_SERVER["PHP_SELF"] is a global variable to refers to the current page.
  htmlspecialchars() will convert the given string from liter characters to HTML
  encoded character string.
  i.e. '<' -> '&lt;'
  This is to prevent XSS if someone appends script onto the url, and <script> tags
  will be interpreted literally.
-->
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
  Bill total:<input type="text" name="bill_total" value="<?php echo $bill_total ?>"><br>
  <input type="radio" name="tip_rate" value="10">15%<br>
  <input type="radio" name="tip_rate" value="15">10%<br>
  <input type="radio" name="tip_rate" value="20">20%<br>
  <button type="submit">
</form>

<div id="result" style="display:none">
  <h3>Bill Amount: <span id="bill"></span></h3>
  <h3>Tip Amount: <span id="tip"></span></h3>
  <h3>Total to Pay: <span id="total"></span></h3>
</div>

<div id="error" style="display:none">
</div>

</body>
</html>
