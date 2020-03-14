<?php
include 'JsonParse.php';
class WeatherAPI extends JsonParse{
    public function getDescription(){
        return $this->getData('weather', '0','description', null);
    }

    public function getTemperature(){
        return $this->getData('main', 'temp',null, null) - 273.15;
    }

    public function getHumidity(){
        return $this->getData('main', 'humidity',null, null);
    }

    public function getWindSpeed(){
        return $this->getData('wind', 'speed',null, null);
    }

    public function getIcon(){
        $icon = $this->getData('weather', '0','icon', null);
        return "<img src=\"http://openweathermap.org/img/wn/$icon@2x.png\" >" ;
    }

    public function getWindDescription(){
        $speed = $this->getWindSpeed();
        switch($speed){
            case ($speed < 1.5):
                return "bez wiatru";
                break;
            case ($speed < 5.4):
                return "łagodny wiatr";
                break;
            case ($speed < 7.9):
                return "umiarkowany wiatr";
                break;
            case ($speed < 10.7):
                return "silny wiatr";
                break;
            case ($speed < 17.1):
                return "bardzo silny wiatr";
                break;
            case ($speed < 17.1):
                return "bardzo bardzo silny wiatr, wręcz sztorm";
                break;
            case ($speed > 17.1):
                return "wiatr tak silny że nie wychodź z domu";
                break;      
        }
    }
}

?>