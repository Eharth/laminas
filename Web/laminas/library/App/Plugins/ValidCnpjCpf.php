<?php

class App_Plugins_ValidCnpjCpf {

   public static function validcnpj($number) {

      $j = 0;
      for ($i = 0; $i < (strlen($number)); $i++) {
         if (is_numeric($number[$i])) {
            $num[$j] = $number[$i];
            $j++;
         }
      }

      if (count($num) != 14) {
         
         $cpf = str_pad(ereg_replace('[^0-9]', '', $number), 11, '0', STR_PAD_LEFT);
         
         if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            $valid = false;
         } else {
            for ($t = 9; $t < 11; $t++) {
               for ($d = 0, $c = 0; $c < $t; $c++) {
                  $d += $cpf{$c} * (($t + 1) - $c);
               }

               $d = ((10 * $d) % 11) % 10;

               if ($cpf{$c} != $d) {
                  $valid = false;
               }
               else{
                  $valid = "cpf";
               }
            }

            
         }
         
      } else {

         if ($num[0] == 0 && $num[1] == 0 && $num[2] == 0 && $num[3] == 0 && $num[4] == 0 && $num[5] == 0 && $num[6] == 0 && $num[7] == 0 && $num[8] == 0 && $num[9] == 0 && $num[10] == 0 && $num[11] == 0) {
            $valid = false;
         } else {
            $j = 5;
            for ($i = 0; $i < 4; $i++) {
               $multiplica[$i] = $num[$i] * $j;
               $j--;
            }
            $soma = array_sum($multiplica);
            $j = 9;
            for ($i = 4; $i < 12; $i++) {
               $multiplica[$i] = $num[$i] * $j;
               $j--;
            }
            $soma = array_sum($multiplica);
            $resto = $soma % 11;
            if ($resto < 2) {
               $dg = 0;
            } else {
               $dg = 11 - $resto;
            }
            if ($dg != $num[12]) {
               $valid = false;
            }
         }

         if (!isset($isCnpjValid)) {
            $j = 6;
            for ($i = 0; $i < 5; $i++) {
               $multiplica[$i] = $num[$i] * $j;
               $j--;
            }
            $soma = array_sum($multiplica);
            $j = 9;
            for ($i = 5; $i < 13; $i++) {
               $multiplica[$i] = $num[$i] * $j;
               $j--;
            }
            $soma = array_sum($multiplica);
            $resto = $soma % 11;
            if ($resto < 2) {
               $dg = 0;
            } else {
               $dg = 11 - $resto;
            }
            if ($dg != $num[13]) {
               $valid = false;
            } else {
               $valid = "cnpj";
            }
         }
      }

      return $valid;
   }

}
