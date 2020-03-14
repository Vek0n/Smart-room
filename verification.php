<?php
session_start();

$myfile = fopen("stuff.txt", "r") or die("Unable to open file!");
$hashedkey = fread($myfile,filesize("stuff.txt"));
fclose($myfile);

if (isset($_SESSION["verified"]) && $_SESSION["verified"]) {
  header("Location: /index.php");
}

if (isset($_POST["key"])) {
  $key = trim($_POST["key"]);
  $verifiedpassword = password_verify(base64_encode(hash("sha256", $key, true)),$hashedkey);

  if ($verifiedpassword) {
    $_SESSION["verified"] = true;
    $whitelist = ["/index.php","/settings.php"];
    $nextpage = $_GET["continue"];

    if (isset($nextpage) && in_array($nextpage, $whitelist)) {
      header("Location: $nextpage");
    } else {
      header("Location: /index.php");
    }
  } else {
    $error = "That key is invalid!";
  }
}

?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Weryfikacja</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  </head>
  <body>
  <div class="container">
  <h1>Wpisz PIN</h1>
  <form action="verification.php<?php if (isset($_GET["continue"])) echo "?continue=" . htmlentities($_GET["continue"]); ?>" method="post" autocomplete="off">
      <div class="form-group">
        <input type="password" name="key" class="form-control" id="key" placeholder="PIN" class="text-center">
      </div>
      <div class = "text-center">
        <input type="submit" class="btn btn-primary btn-lg" value="Wyślij">
      </div>
    </form>
    <?php if (isset($error)) echo "    <p>$error</p>\n"; ?>
  </div>
  </body>
</html>
