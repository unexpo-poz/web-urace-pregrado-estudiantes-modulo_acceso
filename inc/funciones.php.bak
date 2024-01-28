<?php 

function Tipo_Solicitud($Id_Tipo_C){
	if ($Id_Tipo_C == "1"){
		return "Presentación";
	}
	elseif ($Id_Tipo_C == "2"){
		return "Postulación";
	}
}
function Tipo_Pasantia($Id_Tipo_P){
	if ($Id_Tipo_P == 1){
		return "Practica Profesional";
	}
	elseif ($Id_Tipo_P == 2){
		return "Trabajo de Grado";
	}
}
function Duracion_Pasantia($Id_Tipo_P){
	if ($Id_Tipo_P == 1){
		return "de 16 semanas";
	}
	elseif ($Id_Tipo_P == 2){
		return "minima de 16 y máxima de 24 semanas";
	}
}

function Nombre_Empresa($Id_Empresa, $Var_1, $Var_2, $Var_DSN, $Var_User_db, $Var_Pass_db){
	$C_Emp = new ODBC_Conn($Var_DSN, $Var_User_db, $Var_Pass_db, $Var_1, $Var_2);
	$SQL_Emp = "SELECT ID_EMPRESA, NOMBRE_EMPRESA FROM SOL_EMPRESAS WHERE ID_EMPRESA = '".$Id_Empresa."'";
	$C_Emp->ExecSQL($SQL_Emp,__LINE__,true);
	$Datos_Emp = $C_Emp->result;
	foreach ($Datos_Emp as $D_Emp){}

	if ($D_Emp[1] != ""){
		echo $D_Emp[1];
	}
}

function Nro_Romanos($Numero){
	if ($Numero == 1){return "I";}
	if ($Numero == 2){return "II";}
	if ($Numero == 3){return "III";}
	if ($Numero == 4){return "IV";}
	if ($Numero == 5){return "V";}
	if ($Numero == 6){return "VI";}
	if ($Numero == 7){return "VI";}
	if ($Numero == 8){return "VII";}
	if ($Numero == 9){return "IX";}
	if ($Numero == 10){return "X";}
}
function Mes_Txt($Numero){
	if ($Numero == 1){return "Enero";}
	if ($Numero == 2){return "Febrero";}
	if ($Numero == 3){return "Marzo";}
	if ($Numero == 4){return "Abril";}
	if ($Numero == 5){return "Mayo";}
	if ($Numero == 6){return "Junio";}
	if ($Numero == 7){return "Julio";}
	if ($Numero == 8){return "Agosto";}
	if ($Numero == 9){return "Septiembre";}
	if ($Numero == 10){return "Octubre";}
	if ($Numero == 11){return "Noviembre";}
	if ($Numero == 12){return "Diciembre";}
}

function Fecha(){
	$h = "4.5";
	$hm = $h*60;
	$ms = $hm*60;
	$hora = gmdate("g:i a",time()-($ms));
//	return date("F j, Y, g:i a");
	return date("d") . " de " . Mes_Txt(date("n")) . " de " . date("Y");
}
function Fecha_dmy(){
	$h = "4.5";
	$hm = $h*60;
	$ms = $hm*60;
	$hora = gmdate("g:i a",time()-($ms));
	return date("d-m-Y");
}

function Y_M_D__to__D_M_Y($Fecha_In){
	$Temp = explode("-", $Fecha_In);
	return $Temp[2] ."-". $Temp[1] ."-". $Temp[0];
}
function D_M_Y__to__Y_M_D($Fecha_In){
	$Temp = explode("-", $Fecha_In);
	return $Temp[2] ."-". $Temp[1] ."-". $Temp[0];
}
function D_M_Y__to__M_D_Y($Fecha_In){
	$Temp = explode("-", $Fecha_In);
	return $Temp[1] ."-". $Temp[0] ."-". $Temp[2];
}
function M_D_Y__to__D_M_Y($Fecha_In){
	$Temp = explode("-", $Fecha_In);
	return $Temp[1] ."-". $Temp[0] ."-". $Temp[2];
}

function Mes_Txt__Anio($Fecha_In){
	$Temp = explode("-", $Fecha_In);
	return $Temp[2]." de ".Mes_Txt($Temp[1])." ". $Temp[0];
}

function Redirect_URL($url){
?>
	echo" <script>window.location="<?php echo $url ?>"</script> ";
<?
}

function Sel_Selected($Variable, $Constante){
if($Variable == $Constante){
	echo "selected";
	}
}
?>