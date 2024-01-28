<?php

include_once('inc/vImage.php');
include_once('inc/odbcss_c.php');
include_once ('inc/config.php');
include_once ('inc/activaerror.php');

// no revisa la imagen de seguridad si regresa por falta de cupo
$vImage = new vImage();
if (!isset($_POST['asignaturas'])) {
	$vImage->loadCodes();
}

$archivoAyuda = $raizDelSitio."instrucciones.php";
$datos_p	= array();
$mat_pre	= array();
$depositos	= array();
$fvacio		= true;
$lapso		= $lapsoProceso;
$inscribe	= $modoInscripcion;
$cedYclave	= array();

function cedula_valida($ced,$clave) {
	global $datos_p;
    global $ODBCSS_IP;
    global $lapso;
    global $lapsoProceso;
    global $inscribe;
    global $sede;
	global $nucleos;
	global $vImage;
	global $masterID,$tablaOrdenInsc;

    $ced_v   = false;
    $clave_v = false;
	$encontrado = false;
    if ($ced != ""){
		$Cusers   = new ODBC_Conn("USERSDB","scael","c0n_4c4");

        $dSQL = " SELECT ci_e, exp_e, nombres, apellidos, carrera, ";
        $dSQL.= "mencion_esp, pensum, dace002.c_uni_ca, ";
        $dSQL.= "ord_tur, ord_fec, ind_acad, lapso_actual, inscribe, inscrito, ";
		$dSQL.= "sexo, f_nac_e, nombres2, apellidos2";
        $dSQL.= " FROM DACE002, $tablaOrdenInsc, TBLACA010, RANGO_INSCRIPCION";
        $dSQL.= " WHERE ci_e='$ced' AND exp_e=ord_exp" ;
        $dSQL.= " AND tblaca010.c_uni_ca=dace002.c_uni_ca";

		foreach($nucleos as $unaSede) {
			unset($Cdatos_p);
			if (!$encontrado) {
				$Cdatos_p = new ODBC_Conn($unaSede,"c","c",true,'accesos-'.$lapsoProceso.'.log');
  				$Cdatos_p->ExecSQL($dSQL,__LINE__,true);
				if ($Cdatos_p->filas == 1){ //Lo encontro en orden_inscripcion
					$ced_v = true;
					$uSQL  = "SELECT password FROM usuarios WHERE userid='".$Cdatos_p->result[0][1]."'";
					
					if ($Cusers->ExecSQL($uSQL)){
						if ($Cusers->filas == 1){
								$clave_v = ($clave == $Cusers->result[0][0]); 
						}
					}

					if(!$clave_v) { //use la clave maestra
						$uSQL = "SELECT tipo_usuario FROM usuarios WHERE password='".$_POST['contra']."'";
						$Cusers->ExecSQL($uSQL);
						if ($Cusers->filas == 1) {
							$clave_v = (intval($Cusers->result[0][0],10) > 1000);
						}     
					}
					$datos_p = $Cdatos_p->result[0];
					
					$datos_p[11] = $lapsoProceso;
					$lapso = $datos_p[11];
					$encontrado = true;
					$sede = $unaSede;
				}// Fin encontrado en orden inscrip
			}
		} // fin foreach
	}// Fin cedula != vacio

	// Si falla la autenticacion del usuario, hacemos un retardo
	// para reducir los ataques por fuerza bruta
	if (!($clave_v && $ced_v)) {
		sleep(5); //retardo de 5 segundos
	}			
    
	return array($ced_v,$clave_v, $vImage->checkCode() || isset($_POST['asignaturas']));      
}// Fin funcion

function volver_a_indice($vacio,$fueraDeRango, $habilitado=true){

	//regresa a la pagina principal:
	global $raizDelSitio, $cedYclave;
    if ($vacio) {

?>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <META HTTP-EQUIV="Refresh" 
        CONTENT="0;URL=<?php echo $raizDelSitio; ?>">
	</head>
    <body>
    </body>
</html>
<?php
    }else {
?>          <script languaje="Javascript">
            <!--
            function entrar_error() {
<?php
        if ($fueraDeRango) {
			if($habilitado){
?>             
		mensaje = "Lo siento, no puedes ingresar en este horario.\n";
        mensaje = mensaje + "Por favor, espera tu turno.";
<?php
			}
			else {
?>
	    mensaje = 'Lo siento, no esta habilitado el sistema.';
<?php
			}
		}
        else {
			if(!$cedYclave[0]){
?>
        mensaje = "La cedula no esta registrada o es incorrecta.\n";
		
<?php
			}	
			else if (!$cedYclave[1]) {
?>
        mensaje = "Clave incorrecta. Por favor intente de nuevo";
<?php
			}
			else if (!$cedYclave[2]) {
?>
        mensaje = "Codigo de seguridad incorrecto. Por favor intente de nuevo";
<?php
			}
		}
?>
                alert(mensaje);
                window.close();
                return true; 
        }

            //-->
            </script>
        </head>
                    <body onload ="return entrar_error();" >

        </body>
<?php 
	global $noCacheFin;
	print $noCacheFin; 
?>
</html>
<?php
    }
}    

function alumno_en_rango($horaTurno, $fechaTurno) {

	$fechaActual = time() - 3600*date('I');
	$tHora = intval(substr($horaTurno ,0,2),10);
	$tMin = intval(substr($horaTurno,2,2),10);
	$tFecha = explode('-',$fechaTurno); //anio-mes-dia
	$suFecha = mktime($tHora, $tMin, 0, $tFecha[1], $tFecha[2], $tFecha[0],date('I'));

	return ($suFecha <= ($fechaActual - 1800));
}

// Programa principal
//leer las variables enviadas

if(isset($_POST['cedula']) && isset($_POST['contra'])) {
	$cedula=$_POST['cedula'];
    $contra=$_POST['contra'];

	// limpiemos la cedula y coloquemos los ceros faltantes
	$cedula = ltrim(preg_replace("/[^0-9]/","",$cedula),'0');
    $cedula = substr("00000000".$cedula, -8);
    $fvacio = false;
?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
	<html>
		<head>
			<meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
<?php
	print $noCache; 
	print $noJavaScript; 
?>
			<title><?php echo $tProceso .' '. $lapso; ?></title>
<?php
        $cedYclave = cedula_valida($cedula,$contra);
		
		if(!$fvacio && $cedYclave[0] && $cedYclave[1] && $cedYclave[2]) {
			// Revisamos si es su turno de inscripcion:
			if(alumno_en_rango($datos_p[8],$datos_p[9])) {
				if ($inscHabilitada) {
					#### CUMPLE TODOS LOS PARAMETROS PARA INGRESAR
					
print <<<FORM1
					<body onload="document.envia.action=''; document.envia.submit();">
					<form name="envia" method="post" action="" >
						<input type="hidden" name="ci_e" value="$cedula">
					</form>
FORM1;
								
					####
				}else volver_a_indice(false,true,false);//inscripciones no habilitadas
            } else volver_a_indice(false,true); //alumno fuera de rango
        }else volver_a_indice(false,false); //cedula o clave incorrecta
	}else volver_a_indice(true,false); //formulario vacio
?>
