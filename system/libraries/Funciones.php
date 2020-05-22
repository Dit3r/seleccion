<?php defined('BASEPATH') OR exit('No direct script access allowed');

class CI_Funciones{
    //'-----------Validar Rut en PHP
    public function ValidaRut($r,$dv)
    {
    		$s=1;for($m=0;$r!=0;$r/=10)$s=($s+$r%10*(9-$m++%6))%11;
    
        if ($dv==chr($s?$s+47:75))
    		{
    			return true;	
    	    }else{
    
    			return false;	
    		}
    }
    //-----------in valida rut
    
    ///suma dias a una fecha
public function suma_fechas($fecha,$ndias)
            

{
            

      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha))
            

              list($dia,$mes,$año)=split("/", $fecha);
            

      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha))
            

              list($dia,$mes,$año)=split("-",$fecha);
        $nueva = mktime(0,0,0, $mes,$dia,$año) + $ndias * 24 * 60 * 60;
        $nuevafecha=date("d/m/Y",$nueva);
            

      return ($nuevafecha);  
           
}
//---fin funcion

//------------limpia caracteres------------------------
public function fncClearBadChar($string){
  preg_match_all('/(?:([a-zA-Z\ ÁÉÍÓÚáéíóúÑñ]+)|.)/i', $string, $matches);
  return implode('', $matches[1]);
}

//-----------------------------------------------------------

//funcion para validar mail

public function comprobar_email($email){ 
    //
	$mail_correcto = 0; 
    //compruebo unas cosas primeras 
    if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){ 
       if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) { 
          //miro si tiene caracter . 
          if (substr_count($email,".")>= 1){ 
             //obtengo la terminacion del dominio 
             $term_dom = substr(strrchr ($email, '.'),1); 
             //compruebo que la terminación del dominio sea correcta 
             if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){ 
                //compruebo que lo de antes del dominio sea correcto 
                $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1); 
                $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1); 
                if ($caracter_ult != "@" && $caracter_ult != "."){ 
                   $mail_correcto = 1; 
                } 
             } 
          } 
       } 
    } 
    if ($mail_correcto) 
       return 1; // Mail correcto
    else 
       return 0; // Mail incorrecto
} 
//fin de validacion de mail


public function ValidarFecha($fecha) 
{ 
    $fecha = explode("/",$fecha); 
    $mes = $fecha[1]; 
    $dia = $fecha[0]; 
    $anio = $fecha[2]; 

    if (strlen($mes) == 2 && strlen($dia) == 2 && strlen($anio) == 4) 
    { 
        if(@checkdate($mes,$dia,$anio)) 
            return (1); 
        else 
            return (-1); 
    } 
    else 
        return (-2); 
} 

public function compara_fechas($fecha1,$fecha2)
{
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))
              list($dia1,$mes1,$año1)=split("/",$fecha1);
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))
              list($dia1,$mes1,$año1)=split("-",$fecha1);
      if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))
              list($dia2,$mes2,$año2)=split("/",$fecha2);
      if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))
              list($dia2,$mes2,$año2)=split("-",$fecha2);
        $dif = mktime(0,0,0,$mes1,$dia1,$año1) - mktime(0,0,0, $mes2,$dia2,$año2);
        return ($dif);                         
}
//CAMBIA LA PRIMERA LETRA A MAYUSCULA
   //ucwords();

//PONE LOS ACENTOS O LETRAS
   //htmlentities();
  
  
//numero a letras  
public function num2letras($num, $fem = true, $dec = true) { 
//if (strlen($num) > 14) die("El n?mero introducido es demasiado grande"); 
   $matuni[2]  = "dos"; 
   $matuni[3]  = "tres"; 
   $matuni[4]  = "cuatro"; 
   $matuni[5]  = "cinco"; 
   $matuni[6]  = "seis"; 
   $matuni[7]  = "siete"; 
   $matuni[8]  = "ocho"; 
   $matuni[9]  = "nueve"; 
   $matuni[10] = "diez"; 
   $matuni[11] = "once"; 
   $matuni[12] = "doce"; 
   $matuni[13] = "trece"; 
   $matuni[14] = "catorce"; 
   $matuni[15] = "quince"; 
   $matuni[16] = "dieciseis"; 
   $matuni[17] = "diecisiete"; 
   $matuni[18] = "dieciocho"; 
   $matuni[19] = "diecinueve"; 
   $matuni[20] = "veinte"; 
   $matunisub[2] = "dos"; 
   $matunisub[3] = "tres"; 
   $matunisub[4] = "cuatro"; 
   $matunisub[5] = "quin"; 
   $matunisub[6] = "seis"; 
   $matunisub[7] = "sete"; 
   $matunisub[8] = "ocho"; 
   $matunisub[9] = "nove"; 

   $matdec[2] = "veint"; 
   $matdec[3] = "treinta"; 
   $matdec[4] = "cuarenta"; 
   $matdec[5] = "cincuenta"; 
   $matdec[6] = "sesenta"; 
   $matdec[7] = "setenta"; 
   $matdec[8] = "ochenta"; 
   $matdec[9] = "noventa"; 
   $matsub[3]  = 'mill'; 
   $matsub[5]  = 'bill'; 
   $matsub[7]  = 'mill'; 
   $matsub[9]  = 'trill'; 
   $matsub[11] = 'mill'; 
   $matsub[13] = 'bill'; 
   $matsub[15] = 'mill'; 
   $matmil[4]  = 'millones'; 
   $matmil[6]  = 'billones'; 
   $matmil[7]  = 'de billones'; 
   $matmil[8]  = 'millones de billones'; 
   $matmil[10] = 'trillones'; 
   $matmil[11] = 'de trillones'; 
   $matmil[12] = 'millones de trillones'; 
   $matmil[13] = 'de trillones'; 
   $matmil[14] = 'billones de trillones'; 
   $matmil[15] = 'de billones de trillones'; 
   $matmil[16] = 'millones de billones de trillones'; 

   $num = trim((string)@$num); 
   if ($num[0] == '-') { 
      $neg = 'menos '; 
      $num = substr($num, 1); 
   }else 
      $neg = ''; 
   while ($num[0] == '0') $num = substr($num, 1); 
   if ($num[0] < '1' or $num[0] > 9) $num = '0' . $num; 
   $zeros = true; 
   $punt = false; 
   $ent = ''; 
   $fra = ''; 
   for ($c = 0; $c < strlen($num); $c++) { 
      $n = $num[$c]; 
      if (! (strpos(".,'''", $n) === false)) { 
         if ($punt) break; 
         else{ 
            $punt = true; 
            continue; 
         } 

      }elseif (! (strpos('0123456789', $n) === false)) { 
         if ($punt) { 
            if ($n != '0') $zeros = false; 
            $fra .= $n; 
         }else 

            $ent .= $n; 
      }else 

         break; 

   } 
   $ent = '     ' . $ent; 
   if ($dec and $fra and ! $zeros) { 
      $fin = ' coma'; 
      for ($n = 0; $n < strlen($fra); $n++) { 
         if (($s = $fra[$n]) == '0') 
            $fin .= ' cero'; 
         elseif ($s == '1') 
            $fin .= $fem ? ' una' : ' un'; 
         else 
            $fin .= ' ' . $matuni[$s]; 
      } 
   }else 
      $fin = ''; 
   if ((int)$ent === 0) return 'Cero ' . $fin; 
   $tex = ''; 
   $sub = 0; 
   $mils = 0; 
   $neutro = false; 
   while ( ($num = substr($ent, -3)) != '   ') { 
      $ent = substr($ent, 0, -3); 
      if (++$sub < 3 and $fem) { 
         $matuni[1] = 'uno'; 
         $subcent = 'as'; 
      }else{ 
         $matuni[1] = $neutro ? 'un' : 'uno'; 
         $subcent = 'os'; 
      } 
      $t = ''; 
      $n2 = substr($num, 1); 
      if ($n2 == '00') { 
      }elseif ($n2 < 21) 
         $t = ' ' . $matuni[(int)$n2]; 
      elseif ($n2 < 30) { 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = 'i' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      }else{ 
         $n3 = $num[2]; 
         if ($n3 != 0) $t = ' y ' . $matuni[$n3]; 
         $n2 = $num[1]; 
         $t = ' ' . $matdec[$n2] . $t; 
      } 
      $n = $num[0]; 
      if ($n == 1) { 
         $t = ' ciento' . $t; 
      }elseif ($n == 5){ 
         $t = ' ' . $matunisub[$n] . 'ient' . $subcent . $t; 
      }elseif ($n != 0){ 
         $t = ' ' . $matunisub[$n] . 'cient' . $subcent . $t; 
      } 
      if ($sub == 1) { 
      }elseif (! isset($matsub[$sub])) { 
         if ($num == 1) { 
            $t = ' mil'; 
         }elseif ($num > 1){ 
            $t .= ' mil'; 
         } 
      }elseif ($num == 1) { 
         $t .= ' ' . $matsub[$sub] . '?n'; 
      }elseif ($num > 1){ 
         $t .= ' ' . $matsub[$sub] . 'ones'; 
      }   
      if ($num == '000') $mils ++; 
      elseif ($mils != 0) { 
         if (isset($matmil[$sub])) $t .= ' ' . $matmil[$sub]; 
         $mils = 0; 
      } 
      $neutro = true; 
      $tex = $t . $tex; 
   } 
   $tex = $neg . substr($tex, 1) . $fin; 
   return ucfirst($tex); 
} 
  
  
public function datediff($interval, $datefrom, $dateto, $using_timestamps = false) {
			/*
			$interval can be:
			yyyy - Number of full years
			q - Number of full quarters
			m - Number of full months
			y - Difference between day numbers
				(eg 1st Jan 2004 is "1", the first day. 2nd Feb 2003 is "33". The datediff is "-32".)
			d - Number of full days
			w - Number of full weekdays
			ww - Number of full weeks
			h - Number of full hours
			n - Number of full minutes
			s - Number of full seconds (default)
			*/
			
			if (!$using_timestamps) {
				$datefrom = strtotime($datefrom, 0);
				$dateto = strtotime($dateto, 0);
			}
			$difference = $dateto - $datefrom; // Difference in seconds
			 
			switch($interval) {
			 
			case 'yyyy': // Number of full years
		
				$years_difference = floor($difference / 31536000);
				if (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom), date("j", $datefrom), date("Y", $datefrom)+$years_difference) > $dateto) {
					$years_difference--;
				}
				if (mktime(date("H", $dateto), date("i", $dateto), date("s", $dateto), date("n", $dateto), date("j", $dateto), date("Y", $dateto)-($years_difference+1)) > $datefrom) {
					$years_difference++;
				}
				$datediff = $years_difference;
				break;
		
			case "q": // Number of full quarters
		
				$quarters_difference = floor($difference / 8035200);
				while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($quarters_difference*3), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
					$months_difference++;
				}
				$quarters_difference--;
				$datediff = $quarters_difference;
				break;
		
			case "m": // Number of full months
		
				$months_difference = floor($difference / 2678400);
				while (mktime(date("H", $datefrom), date("i", $datefrom), date("s", $datefrom), date("n", $datefrom)+($months_difference), date("j", $dateto), date("Y", $datefrom)) < $dateto) {
					$months_difference++;
				}
				$months_difference--;
				$datediff = $months_difference;
				break;
		
			case 'y': // Difference between day numbers
		
				$datediff = date("z", $dateto) - date("z", $datefrom);
				break;
		
			case "d": // Number of full days
		
				$datediff = floor($difference / 86400);
				break;
		
			case "w": // Number of full weekdays
		
				$days_difference = floor($difference / 86400);
				$weeks_difference = floor($days_difference / 7); // Complete weeks
				$first_day = date("w", $datefrom);
				$days_remainder = floor($days_difference % 7);
				$odd_days = $first_day + $days_remainder; // Do we have a Saturday or Sunday in the remainder?
				if ($odd_days > 7) { // Sunday
					$days_remainder--;
				}
				if ($odd_days > 6) { // Saturday
					$days_remainder--;
				}
				$datediff = ($weeks_difference * 5) + $days_remainder;
				break;
		
			case "ww": // Number of full weeks
		
				$datediff = floor($difference / 604800);
				break;
		
			case "h": // Number of full hours
		
				$datediff = floor($difference / 3600);
				break;
		
			case "n": // Number of full minutes
		
				$datediff = floor($difference / 60);
				break;
		
			default: // Number of full seconds (default)
		
				$datediff = $difference;
				break;
			}    
		
			return $datediff;
		
}

public function CalculaEdad($fecha_nac){
	//Esta funcion toma una fecha de nacimiento 
	//desde una base de datos mysql
	//en formato aaaa/mm/dd y calcula la edad en números enteros
	$dia=date("j");
	$mes=date("n");
	$anno=date("Y");
	
	//descomponer fecha de nacimiento
	$dia_nac=substr($fecha_nac, 8, 2);
	$mes_nac=substr($fecha_nac, 5, 2);
	$anno_nac=substr($fecha_nac, 0, 4);
	
	if($mes_nac>$mes){
		$calc_edad= $anno-$anno_nac-1;
	}else{
		if($mes==$mes_nac AND $dia_nac>$dia){
			$calc_edad= $anno-$anno_nac-1; 
		}else{
			$calc_edad= $anno-$anno_nac;
		}
	}
	return $calc_edad;
}    
}