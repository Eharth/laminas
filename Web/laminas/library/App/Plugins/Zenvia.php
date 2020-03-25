<?php

class App_Plugins_Data {

    public static function data($data = false, $sum = 180) {

        $tipo = 'F';


        $date = new Zend_Date($data, Zend_Date::ISO_8601);
        $string->databrFull = $date->toString('F') . " - " . $date->toString('H:mm:ss');
        $string->dataISO = $date->toString('c');
        $string->databr = $date->toString($tipo);
        $string->timestamp = $date->toString("U");
        $string->hora = $date->toString("H");
        $string->sumDate = $date->add($sum, Zend_Date::DAY)->toString($tipo);
        $string->sumDateTimestamp = $date->add($sum, Zend_Date::DAY)->toString("c");
        return $string;
    }

    public static function datadb($data = NULL) {
        $dataini = $data;
        $dataex = explode("/", $dataini);
        $datafim = $dataex[2] . $dataex[1] . $dataex[0];
        return $datafim;
    }

    public static function sumDate($data = null) {
        if ($data) {
            $data = $data;
        }
    }

    public static function datadbSQL($data = NULL) {
        $dataini = $data;
        $dataex = explode("/", $dataini);
        $datafimArray = array($dataex[2], $dataex[1], $dataex[0]);
        $datafim = implode("", $datafimArray);
        return $datafim;
    }

    public static function datadb2($data = NULL) {
        $dataini = $data;
        $dataex = explode("-", $dataini);
        $datafim = $dataex[0] . $dataex[1] . $dataex[2];
        return $datafim;
    }

    public static function diasemana($data) {
        switch ($data) {
            case $data == 1;
                return "Segunda";
                break;
            case $data == 2;
                return "Terça";
                break;
            case $data == 3;
                return "Quarta";
                break;
            case $data == 4;
                return "Quinta";
                break;
            case $data == 5;
                return "Sexta";
                break;
            case $data == 6;
                return "Sábado";
                break;
            case $data == 7;
                return "Domingo";
                break;
        }
    }

    public static function calcdate($data = NULL) {

        $day_base = App_Plugins_Data::data($data, 'd')->databr;
        $mouth_base = App_Plugins_Data::data($data, 'M')->databr;
        $year_base = App_Plugins_Data::data($data, 'Y')->databr;

        $day_now = date("d");
        $mouth_now = date("m");
        $year_now = date("Y");


        $timestamp1 = mktime(0, 0, 0, $mouth_base, $day_base, $year_base);
        $timestamp2 = mktime(0, 0, 0, $mouth_now, $day_now, $year_now);

        $segundos_diferenca = $timestamp1 - $timestamp2;
        $dias_diferenca = $segundos_diferenca / (60 * 60 * 24);
        $dias_diferenca = abs($dias_diferenca);
        $dias_diferenca = floor($dias_diferenca);


        return $dias_diferenca;
    }

}