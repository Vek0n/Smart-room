<?php
$temperature=0;
$humidity=0;
$my_pos=0;
$exec_msg = "sudo /var/www/AdafruitDHT.py 11 4";
$test = shell_exec($exec_msg);
//extracts temperature
$my_pos = strpos($test, "Temp=",0);
$temperature = substr($test, $my_pos+5, 4);
echo "<h2><p>Temperatura: $temperature °C</p></h2>";
//extracts humidity
$my_pos = strpos($test, "Humidity=",$my_pos);
$humidity = substr($test, $my_pos+9, 4);
echo "<h2><p>Wilgotność: $humidity%</p></h2>";
?>
