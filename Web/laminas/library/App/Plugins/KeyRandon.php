<?php

class App_Plugins_KeyRandon {

    public static function keyrandon() {
        $sConso = 'bcdfghjklmnpqrstvwxyzbcdfghjklmnpqrstvwxyz';
        $sVogal = 'aeiou';
        $sNum = '123456789';
        $passwd = '';

        $y = strlen($sConso) - 1;
        $z = strlen($sVogal) - 1;
        $r = strlen($sNum) - 1;

        for ($x = 0; $x <= 1; $x++) {
            $rand = rand(0, $y);
            $rand1 = rand(0, $z);
            $rand2 = rand(0, $r);
            $str = substr($sConso, $rand, 1);
            $str1 = substr($sVogal, $rand1, 1);
            $str2 = substr($sNum, $rand2, 1);

            $passwd .= $str . $str1 . $str2;
        }
        return $passwd;
    }
}
