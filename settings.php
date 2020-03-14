<?php
session_start();
if ($_SESSION["verified"]) {
?>

<?php
$myfile = fopen("stuff.txt", "r") or die("Unable to open file!");
$hashedkey = fread($myfile,filesize("stuff.txt"));
fclose($myfile);
$correctPass = 2;
if (isset($_POST["key"])) {
  $key = trim($_POST["key"]);
  $newKey = trim($_POST["newKey"]);
  $verifiedpassword = password_verify(base64_encode(hash("sha256", $key, true)),$hashedkey);
    if ($verifiedpassword) {
        $correctPass = 1;
        $myfile = fopen("stuff.txt", "w") or die("Unable to open file!");
        $newPIN = password_hash(base64_encode(hash("sha256", $newKey, true)),PASSWORD_DEFAULT);
        fwrite($myfile, $newPIN);
        fclose($myfile);

        $sess_path = session_save_path();
        foreach (glob("$sess_path/sess_*") as $filename) {
            unlink($filename);
        }

    }else {
        $correctPass = 0;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Smart pokój</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        .topleftcorner{
        position:absolute;
        top:20px;
        left:0;
        }
    </style>

</head>
<body>

<div class="container">

    <div class="topleftcorner">
        <a class="btn btn-secondary btn-lg" href="index.php" role="button">
            <span class="glyphicon glyphicon-chevron-left"></span>Powrót
        </a>
    </div>

    <br>
    <br>
    <br>
    <h2>Zmień hasło</h2>

    <form action="settings.php<?php if (isset($_GET["continue"])) echo "?continue=" . htmlentities($_GET["continue"]); ?>" method="post" autocomplete="off">
        <div class="form-group">
            <label for="email">Stary PIN</label>
            <input type="password" name="key" class="form-control" id="key" class="text-center">
        </div>

        <div class="form-group">
            <label for="email">Nowy PIN</label>
            <input type="password" name="newKey" class="form-control" id="newKey" class="text-center">
        </div>

        <div class = "text-center">
            <input type="submit" class="btn btn-primary btn-lg" value="Wyślij">
        </div>
    </form>
    <br>    
    <?php
        if($correctPass == 0){
            echo  "<div class=\"alert alert-danger\" role=\"alert\"><span class=\"glyphicon glyphicon-remove\"></span>    Zły PIN.</div>";
        }else if($correctPass == 1){
            echo  "<div class=\"alert alert-success\" role=\"alert\"><span class=\"glyphicon glyphicon-ok\"></span>    PIN został zmieniony</div>";
        }
    ?>

    <?php
        if(array_key_exists('restart', $_POST)) { 
            shell_exec("sudo reboot");
        }else if(array_key_exists('shutdown', $_POST)){
            shell_exec("sudo poweroff");
        }
    ?> 

    <br><br><br>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#restartModal">Restart</button>
    <br><br>
    <button type="button" class="btn btn-danger btn-lg btn-block" data-toggle="modal" data-target="#shutdownModal">Wyłącz</button>

    <!-- Modal -->
    <div class="modal fade" id="restartModal" tabindex="-1" role="dialog" aria-labelledby="restartModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="restartModalLabel">Potwierdzenie</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <h4>Na pewno zrestartować urządzenie?</h4>
        </div>
        <div class="modal-footer">
            <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button> -->
            <form method="post">
                <input type="submit" name="restart" value="Restart" class="btn btn-danger btn-lg"/>
            </form>
        </div>
        </div>
    </div>
    </div>

    
    <div class="modal fade" id="shutdownModal" tabindex="-1" role="dialog" aria-labelledby="shutdownModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="shutdownModalLabel">Potwierdzenie</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <h4>Na pewno wyłączyć urządzenie?</h4>
        </div>
        <div class="modal-footer">
            <form method="post">
                <input type="submit" name="shutdown" value="Wyłącz" class="btn btn-danger btn-lg"/>
            </form>
        </div>
        </div>
    </div>
    </div>


</div>

</body>
</html> 


<?php
} else {
  header("Location: /verification.php?continue=" . $_SERVER["SCRIPT_NAME"]);
}
?>