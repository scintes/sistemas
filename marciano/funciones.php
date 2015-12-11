<?php
include_once 'conexion.php';
        //------------------------------------------------------------------------------
        //
        //
        //------------------------------------------------------------------------------
        function validar_fecha($fecha){
            if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{2,4}$/', $fecha)) {
                return TRUE;
            }else{
                return FALSE;
            }
        }

        //------------------------------------------------------------------------------
        //
        //
        //------------------------------------------------------------------------------
        function validar_email($email){
            if (preg_match('/^[^0-9][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[@][a-zA-Z0-9_]+([.][a-zA-Z0-9_]+)*[.][a-zA-Z]{2,4}$/',$email))
                {return TRUE; }else{ return FALSE;}
        }

        //------------------------------------------------------------------------------
        //
        //
        //------------------------------------------------------------------------------
        function validar_telefono($telefono){
            if (preg_match('/^(\(?[0-9]{3,3}\)?|[0-9]{3,3}[-. ]?)[ ][0-9]{3,3}[-. ]?[0-9]{4,4}$/', $telefono)) 
                {return TRUE;}else{return FALSE;}
        }
        ////////////////////////////////////////////////////
        //Convierte fecha de mysql a normal
        ////////////////////////////////////////////////////    
        function cambiaf_a_normal($fecha){
                //preg_replace( "/([0-9]{2,4})/([0-9]{1,2})/([0-9]{1,2})/", $fecha, $mifecha);
                //- $mifecha = explode('/','/'.$fecha);
                ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha);
                //$lafecha=$fecha->format(FORMATO_FECHA);
		$lafecha=$mifecha[3].DELIMITACION_FECHA.$mifecha[2].DELIMITACION_FECHA.$mifecha[1];
                
		return $lafecha;
        };

        function cambiah_a_normal($hora){
                $mihora = explode(":", ':'.$hora);        
                return $mihora[1].DELIMITACION_HORA.$mihora[2];
        };


	////////////////////////////////////////////////////
	//Convierte fecha de normal a mysql
	////////////////////////////////////////////////////
	function cambiaf_a_mysql($fecha){                
                //preg_replace( "/([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})/", $fecha, $mifecha);
                // echo $mifecha;
                // die();
                $mifecha = explode('/','/'.$fecha);
		//ereg( "([0-9]{1,2})/([0-9]{1,2})/([0-9]{2,4})", $fecha, $mifecha);
		$lafecha=$mifecha[3]."-".$mifecha[2]."-".$mifecha[1];
		return $lafecha;
	}; 
	
	////////////////////////////////////////////////////////////////////////////////////
	// Devuelve la fecha del servidor de base de datosen formato dd/mm/año(4)
	////////////////////////////////////////////////////////////////////////////////////
	function dar_fecha(){
		$lafecha = date("d")."/".date("m")."/".date("Y");
		return $lafecha;
	};
	
	////////////////////////////////////////////////////////////////////////////////////
	// Devuelve la hora del servidor de base de datosen formato hh:mm
	////////////////////////////////////////////////////////////////////////////////////
	function dar_hora(){
                $lahora = date(FORMATO_HORA);
		return $lahora;
	};
		
 	////////////////////////////////////////////////////////////////////////////////////
	// Devuelve la fecha ingresada en $fecha, incrementada en los dias indicados con $d 
	////////////////////////////////////////////////////////////////////////////////////
	function incrementar_dia_en_fecha($fecha, $d){
  			if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha)) 
        		list($dia,$mes,$anno)=split("/", $fecha); 
				
		    if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha)) 
		        list($dia,$mes,$anno)=split("-",$fecha); 
				
    		$nueva = mktime(0,0,0, $mes,$dia,$anno) + $d * 24 * 60 * 60; 
    		$nuevafecha=date(FORMATO_FECHA,$nueva);         
    		return ($nuevafecha);   	
	};
	
        # $host includes host and path and filename 
        # ex: "myserver.com/this/is/path/to/file.php" 
        # $query is the POST query data 
        # ex: "a=thisstring&number=46&string=thatstring 
        # $others is any extra headers you want to send 
        # ex: "Accept-Encoding: compress, gzip\r\n" 
        function post($host,$query,$others=''){ 
            $path=explode('/',$host); 
            $host=$path[0]; 
            unset($path[0]); 
            $path='/'.(implode('/',$path)); 
            $post="POST $path HTTP/1.1\r\nHost: $host\r\nContent-type: application/x-www-form-urlencoded\r\n${others}User-Agent: Mozilla 4.0\r\nContent-length: ".strlen($query)."\r\nConnection: close\r\n\r\n$query"; 
            $h=fsockopen($host,80); 
            fwrite($h,$post); 
            for($a=0,$r='';!$a;){ 
                $b=fread($h,8192); 
                $r.=$b; 
                $a=(($b=='')?1:0); 
            } 
            fclose($h); 
            return $r; 
        } 
 
 
 
        
function RGBToHex($r,$g,$b)
{
    $hex = "#";
    $hex.= str_pad(dechex($r), 2, "0", STR_PAD_LEFT);
    $hex.= str_pad(dechex($g), 2, "0", STR_PAD_LEFT);
    $hex.= str_pad(dechex($b), 2, "0", STR_PAD_LEFT);
    return $hex;
}

function HexToRGB($hex)
{
    $hex = str_replace("#", "", $hex);
    $color = array();

    if(strlen($hex)==3)
    {
        $color['r'] = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
        $color['g'] = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
        $color['b'] = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));
    }elseif(strlen($hex)==6){
        $color['r'] = hexdec(substr($hex, 0, 2));
        $color['g'] = hexdec(substr($hex, 2, 2));
        $color['b'] = hexdec(substr($hex, 4, 2));
    }
    return $color;
}
?>