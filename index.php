<!doctype HTML>
<html>
<head>
  <title>Tip Calculator</title>

  <style>
    body {
      font-size: 20px;
    }

    h2 {
      margin: 10px;
    }

    .container {
      background-color: rgb(130, 131, 131);
      background: linear-gradient(-20deg, rgb(130, 131, 131), rgb(92, 90, 92));
      text-align: center;
      width: 500px;
      height: 400px;
      border-radius: 5px;
      margin: 40px auto;
      padding: 25px;
      box-sizing: border-box;
      box-shadow: 4px 4px #9f9f9b;
    }

    .error {
      color: #ffcf17;
    }

    button {
      height: 40px;
      width: 100px;
      color: white;
      background-color: #7fb2e0;
      border: none;
      border-radius: 5px;
      font-size: 16px;
      box-shadow: 2px 2px gray;
    }

    button:hover {
    }

    button:active {
      box-shadow: inset 3px 3px gray;
    }

    #result {
      font-size: 25px;
      width: 150px;
      margin: auto;
      color: #12c4fc;
    }

  </style>

  <?php

    $bill_total = $tip_rate = "";

    $result = $error = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $error = false;
      $error_message = "";
      $total_error = $tip_error = false;

      // Parse data
      if (empty($_POST["bill_total"])) {
        $total_error = true;
        $error = true;
        $error_message .= "* Bill field must contain a number.<br>";
      } else {
        $bill_total = process_input($_POST["bill_total"]);
      }

      if (empty($_POST["tip_rate"])) {
        $tip_error = true;
        $error = true;
        $error_message .= "* Must choose a tip rate.<br>";
      } else {
        $tip_rate = process_input($_POST["tip_rate"]);
      }


      // Check values
      if ((float)$bill_total <= 0) {
        $total_error = true;
        $error = true;
        $error_message .= "* Bill cannot be negative or zero.<br>";
      } else if ((float)$tip_rate <= 0) {
        $tip_rate_error = true;
        $error = true;
        $error_message .= "* Tip rate cannot be negative or zero.";
      } else if (!$total_error && !$tip_error){

        // Evaluate tip
        $tip = calculate_tip((float) $bill_total, (float) $tip_rate);

        $total = $bill_total + $tip;

        $result = true;
      }
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
<div class="container">
  <h2>Tip Calculator</h2>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <label class="<?php if (isset($total_error) && $total_error)echo 'error';?>">Bill total: $</label>
    <input type="text" name="bill_total" value="<?php echo $bill_total ?>">
    <?php if (isset($total_error) && $total_error)echo '<span class="error">*</span>';?><br>
    <label class="<?php if (isset($tip_error) && $tip_error)echo 'error';?>">Tip Rate:</label>
    <input type="radio" name="tip_rate" value="10" <?php if (isset($tip_rate) && $tip_rate == "10") echo "checked"; ?>>10%
    <input type="radio" name="tip_rate" value="15" <?php if (isset($tip_rate) && $tip_rate == "15") echo "checked"; ?>>15%
    <input type="radio" name="tip_rate" value="20" <?php if (isset($tip_rate) && $tip_rate == "20") echo "checked"; ?>>20%
    <?php if (isset($tip_error) && $tip_error)echo '<span class="error">*</span>';?><br><br>
    <button type="submit">Calculate</button>
  </form>

  <?php
    // numberformat(num, dec) will format num to dec decimal places
    if ($result) {
      echo "<div id='result'>";
        /*
        echo "<h3>Bill Amount: </h3>$";
        echo number_format((double)$bill_total, 2);
        */
        echo "<br><p>Tip: $";
        echo number_format($tip, 2);
        echo "<br>Total: $";
        echo number_format($total, 2);
      echo "</p></div>";
    } else if ($error) {
      echo "<div class='error'>";
        echo "<h4>"; echo $error_message; echo "</h4>";
      echo "</div>";
    }
  ?>
</div>

</body>
</html>
