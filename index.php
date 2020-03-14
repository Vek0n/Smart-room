<?php
session_start();
if ($_SESSION["verified"]) {
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
        .toprightcorner{
        position:absolute;
        top:20px;
        right:20px;
        }

        .img-responsive{
            display: block;
            margin-left: auto;
            margin-right: auto
        }
    </style>
    <?php 
        include 'Weather.php';
        $CWeather = new WeatherAPI('http://api.openweathermap.org/data/2.5/weather?id=3088171&appid=8afd70201985a1437966d5d11a16bbae&lang=pl');
    ?>

</head>
<body>

<div class="container">
    <div class="toprightcorner">
        <a class="btn btn-secondary btn-lg" href="settings.php" role="button">
            <span class="glyphicon glyphicon-cog"></span>
        </a>
    </div>

    <h1>Smart pokój</h1>
    <h2> Pogoda</h2>

    <?php
        $desc = $CWeather->getDescription();
        $temperature = $CWeather->getTemperature();
        $icon = $CWeather->getIcon();
        $hum = $CWeather->getHumidity();
        $wind = $CWeather->getWindSpeed();
        $windDesc = $CWeather->getWindDescription();
    ?>

    <h4>
        Na dworze <?php echo "<b>$desc</b>";?>, <?php echo $windDesc, " <b>$wind m/s</b>" ?>, wilgotność <?php echo "<b>$hum%</b>"?>
    </h4>
    <h1>  
        <p> 
            <?php 
                echo $icon;
                echo $temperature, " °C";
            ?> 
        </p>
    </h1>
    <br>

    <h2>Sterowanie </h2>

    <?php
        if(array_key_exists('btn1', $_POST)) { 
            system("gpio -g mode 21 out");
            $btnState = exec('gpio -g read 21');
            if ($btnState){
                system("gpio -g write 21 0");                    
            }else{
                system("gpio -g write 21 1");     
            }
        }
    ?> 

    <form method="post"> 
        <input type="submit" name="btn1" value="Światło" class="btn btn-primary btn-lg btn-block" /> 
    </form>
    
    <br><br>

    <button type="button" class="btn btn-primary btn-lg btn-block userinfo" data-toggle="modal" data-target="#empModal">
    Temperatura i wigotność
    </button>

    <!-- Modal -->
    <div class="modal fade" id="empModal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Temperatura i wilgotność</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="wait">
                        <img src="25.gif" class="img-responsive" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-lg" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


<script type='text/javascript'>
    $(document).ready(function(){
        $('.userinfo').click(function(){
        
            var userid = $(this).data('id');
            // AJAX request
            $.ajax({
                url: 'temp.php',
                type: 'post',
                data: {userid: userid},
                beforeSend: function() { $('#wait').show(); },
                complete: function() { $('#wait').hide(); },
                success: function(response){ 
                    // Add response in Modal body
                    $('.modal-body').html(response);
                    // Display Modal
                    $('#empModal').modal('show'); 
                }
            });
        });
    });
</script>

</div>

</body>
</html> 

<?php
} else {
 header("Location: /verification.php?continue=" . $_SERVER["SCRIPT_NAME"]);
}
?>