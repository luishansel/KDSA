<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
if (!isset($_SESSION["gnVerifica"]) or $_SESSION["gnVerifica"] != 1)
	{
		echo('<meta http-equiv="Refresh" content="0;url=index.php"/>');
		exit('');
	}

require_once ("funciones/fxGeneral.php");
require_once ("funciones/fxUsuarios.php");
require_once ("tcpdf/tcpdf.php");

$Registro = fxVerificaUsuario();

if ($Registro == 0)
{
?>
	<div class="container text-center">
    	<div id="DivContenido">
        	<img src="imagenes/errordeacceso.png"/>
        </div>
    </div>
<?php
}
else
{
	class PDF extends TCPDF
	{
		// Page header
		function Header()
		{
			// Logos
			$this->Image('imagenes/headerLogin.jpg',12,6,0,15);
			// Title
			$mid_x = 210; // width of the "PDF screen", fixed by now.
			// helvetica bold 18
			$this->SetFont('helvetica','B',13);
			$Titulo = 'PROFORMA OFICIAL';
			$this->Text(($mid_x - $this->GetStringWidth($Titulo)) / 2, 10, $Titulo);
			// helvetica normal 18
			$this->SetFont('helvetica','',7);
			$Titulo = "Calle principal de Altamira, de Junior Music 1c. al este";
			$this->Text(($mid_x - $this->GetStringWidth($Titulo)) / 2, 20, $Titulo);
			$Titulo = mb_convert_encoding(html_entity_decode("Teléfono: 2277 1216 / eMail: ventascorp@capacitacionkdsa.com"), "UTF-8");
			$this->Text(($mid_x - $this->GetStringWidth($Titulo)) / 2, 24, $Titulo);
			$Titulo = "RUC J0310000273650";
			$this->Text(($mid_x - $this->GetStringWidth($Titulo)) / 2, 28, $Titulo);

			// Line break
			$this->Ln(20);
		}
		// Page footer
		function Footer()
		{
			// Position at 1.5 cm from bottom
			$this->SetY(-15);
			// helvetica italic 8
			$this->SetFont('helvetica','I',8);
			//$this->SetFillColor(229,50,45);
			$this->SetTextColor(0,0,0);
			// Page number
			$this->Cell(0,10,mb_convert_encoding(html_entity_decode('Página '),"UTF-8").$this->PageNo().'/'.$this->getAliasNbPages(),0,0,'L');
			$this->Cell(0,10,'Emitido: ' . date("d/m/Y h:i:s a") . '',0,0,'R',true);
		}
	}
}

function DevuelveFecha($Fecha)
{
	$FechaDividida = explode("-", $Fecha);
	
	$Anno = $FechaDividida[0];
	$Mes = $FechaDividida[1];
	$Dia = $FechaDividida[2];
	
	switch ($Mes)
	{
		case "01":
			$NombreMes = "Ene";
			break;
		case "02":
			$NombreMes = "Feb";
			break;
		case "03":
			$NombreMes = "Mar";
			break;
		case "04":
			$NombreMes = "Abr";
			break;
		case "05":
			$NombreMes = "May";
			break;
		case "06":
			$NombreMes = "Jun";
			break;
		case "07":
			$NombreMes = "Jul";
			break;
		case "08":
			$NombreMes = "Ago";
			break;
		case "09":
			$NombreMes = "Sep";
			break;
		case "10":
			$NombreMes = "Oct";
			break;
		case "11":
			$NombreMes = "Nov";
			break;
		case "12":
			$NombreMes = "Dic";
			break;
	}
	return ($Dia . "-" . $NombreMes . "-" . $Anno);
}

function DevuelveFechaLarga($Fecha)
{
	$FechaDividida = explode("-", $Fecha);
	
	$Anno = $FechaDividida[0];
	$Mes = $FechaDividida[1];
	$Dia = $FechaDividida[2];
	
	switch ($Mes)
	{
		case "01":
			$NombreMes = "Enero";
			break;
		case "02":
			$NombreMes = "Febrero";
			break;
		case "03":
			$NombreMes = "Marzo";
			break;
		case "04":
			$NombreMes = "Abril";
			break;
		case "05":
			$NombreMes = "Mayo";
			break;
		case "06":
			$NombreMes = "Junio";
			break;
		case "07":
			$NombreMes = "Julio";
			break;
		case "08":
			$NombreMes = "Agosto";
			break;
		case "09":
			$NombreMes = "Septiembre";
			break;
		case "10":
			$NombreMes = "Octubre";
			break;
		case "11":
			$NombreMes = "Noviembre";
			break;
		case "12":
			$NombreMes = "Diciembre";
			break;
	}
	return ($Dia . " de " . $NombreMes . " de " . $Anno);
}

$pdf = new PDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/spa.php')) {
	require_once(dirname(__FILE__).'/lang/spa.php');
	$pdf->setLanguageArray($l);
}
$pdf->AddPage();

$codProforma = trim($_POST["KDSA"]);
$mnMonedaRep = $_POST["MONEDA"];

//Obtención de datos de la cabecera
$msConsulta = "select KDSA090A.PROFORMA_REL, FECHA_090, TIPO_060, NOMBRE_060, NOMBRECONTACTO_060, CEDULARUC_060, PATRONAL_060, ";
$msConsulta .= "CORREO_060, TELEFONOCONTACTO_060, INATEC_090, DESCUENTO_090, LUGAR_090, TIPOCAMBIO_090, OBSERVACIONES_090, MONEDA_090 ";
$msConsulta .= "from KDSA090A, KDSA060A where KDSA090A.PROSPECTO_REL = KDSA060A.PROSPECTO_REL and PROFORMA_REL = ?";
$m_cnx_MySQL = fxAbrirConexion();
$mDatos = $m_cnx_MySQL->prepare($msConsulta);
$mDatos->execute([$codProforma]);
$Linea = 40;
$Suma = 0;

$Fila = $mDatos->fetch();
$Proforma = $Fila["PROFORMA_REL"];
$Fecha = DevuelveFechaLarga($Fila["FECHA_090"]);
$Tipo = $Fila["TIPO_060"];
$Empresa = mb_convert_encoding(html_entity_decode($Fila["NOMBRE_060"]), "UTF-8", "latin1");
$Solicitante = mb_convert_encoding(html_entity_decode($Fila["NOMBRECONTACTO_060"]), "UTF-8", "latin1");
$CedulaRuc = $Fila["CEDULARUC_060"];
$Patronal = $Fila["PATRONAL_060"];
$Correo = $Fila["CORREO_060"];
$Telefono = $Fila["TELEFONOCONTACTO_060"];
$Inatec = $Fila["INATEC_090"];
$TipoCambio = floatval($Fila["TIPOCAMBIO_090"]);
$Observaciones = $Fila["OBSERVACIONES_090"];
$Lugar = $Fila["LUGAR_090"];
$Descuento = floatval($Fila["DESCUENTO_090"]);
$Moneda090 = $Fila["MONEDA_090"];

$pdf->SetTextColor(0,0,0);

$pdf->SetFont('helvetica','B',9);
$Texto = "Consecutivo:";
$pdf->SetXY(15,$Linea);
$pdf->Cell(30,5,$Texto,0,0,'L');

$pdf->SetFont('helvetica','',9);
$pdf->SetXY(55,$Linea);
$pdf->Cell(100,5,$Proforma,0,0,'L');

$Linea += 5;
$pdf->SetFont('helvetica','B',9);
$Texto = "Fecha:";
$pdf->SetXY(15,$Linea);
$pdf->Cell(30,5,$Texto,0,0,'L');

$pdf->SetFont('helvetica','',9);
$pdf->SetXY(55,$Linea);
$pdf->Cell(100,5,$Fecha,0,0,'L');
		
$Linea += 5;
$pdf->SetFont('helvetica','B',9);
$Texto = "Nombre del solicitante:";
$pdf->SetXY(15,$Linea);
$pdf->Cell(30,5,$Texto,0,0,'L');

$pdf->SetFont('helvetica','',9);
$pdf->SetXY(55,$Linea);
$pdf->Cell(100,5,$Solicitante,0,0,'L');
		
if ($Tipo = 1) //Prospecto de Empresa
{
	$Linea += 5;
	$pdf->SetFont('helvetica','B',9);
	$Texto = "Nombre de la empresa:";
	$pdf->SetXY(15,$Linea);
	$pdf->Cell(30,5,$Texto,0,0,'L');

	$pdf->SetFont('helvetica','',9);
	$Texto = $Empresa . " (RUC " . $CedulaRuc . ")";
	$pdf->SetXY(55,$Linea);
	$pdf->Cell(100,5,$Texto,0,0,'L');
	
	$Linea += 5;
	$pdf->SetFont('helvetica','B',9);
	$Texto = mb_convert_encoding("Número patronal:", "UTF-8");
	$pdf->SetXY(15,$Linea);
	$pdf->Cell(30,5,$Texto,0,0,'L');

	$pdf->SetFont('helvetica','',9);
	$pdf->SetXY(55,$Linea);
	$pdf->Cell(100,5,$Patronal,0,0,'L');
}
else //Prospecto Natural
{
	$Linea += 5;
	$pdf->SetFont('helvetica','B',9);
	$Texto = mb_convert_encoding("Cédula:", "UTF-8");
	$pdf->SetXY(15,$Linea);
	$pdf->Cell(30,5,$Texto,0,0,'L');

	$pdf->SetFont('helvetica','',9);
	$pdf->SetXY(55,$Linea);
	$pdf->Cell(100,5,$CedulaRuc,0,0,'L');
}
		
$Linea += 5;
$pdf->SetFont('helvetica','B',9);
$Texto = "Correo del solicitante:";
$pdf->SetXY(15,$Linea);
$pdf->Cell(30,5,$Texto,0,0,'L');

$pdf->SetFont('helvetica','',9);
$pdf->SetXY(55,$Linea);
$pdf->Cell(100,5,$Correo,0,0,'L');

$Linea += 5;
$pdf->SetFont('helvetica','B',9);
$Texto = mb_convert_encoding("Lugar de la capacitación:", "UTF-8");
$pdf->SetXY(15,$Linea);
$pdf->Cell(30,5,$Texto,0,0,'L');

$pdf->SetFont('helvetica','',9);
$pdf->SetXY(55,$Linea);
$Texto = mb_convert_encoding(html_entity_decode($Lugar), "UTF-8", "latin1");
$pdf->Cell(100,5,$Texto,0,0,'L');

//***INICIO DEL CUADRO DE DETALLE***
$pdf->SetFont('helvetica','',10);

//Estilos de HTML
$msHTML = "<style>";
$msHTML .= "th{";
$msHTML .= "border: 1px solid rgb(229,50,45); background-color: rgb(229,50,45); color: rgb(255,255,255);";
$msHTML .= "}";
$msHTML .= ".fondoGris{";
$msHTML .= "background-color: rgb(240,240,240); color: rgb(0,0,0);";
$msHTML .= "}";
$msHTML .= ".bordesLaterales{";
$msHTML .= "border-left: 1px solid rgb(229,50,45); border-right: 1px solid rgb(229,50,45);";
$msHTML .= "}";
$msHTML .= ".tresBordes{";
$msHTML .= "border-left: 1px solid rgb(229,50,45); border-right: 1px solid rgb(229,50,45); border-bottom: 1px solid rgb(229,50,45);";
$msHTML .= "}";
$msHTML .= ".cuatroBordes{";
$msHTML .= "border: 1px solid rgb(229,50,45);";
$msHTML .= "}";
$msHTML .= ".letraNormal{";
$msHTML .= "font-size: medium;";
$msHTML .= "}";
$msHTML .= ".letraMenor{";
$msHTML .= "font-size: smaller;";
$msHTML .= "}";
$msHTML .= "</style>";

//Inicio de la tabla
$msHTML .= '<table class= "letraNormal">';

//Cabecera de la tabla
$msHTML .= '<thead>';
$msHTML .= '<tr>';
$msHTML .= '<th width="10%" style="text-align: center">Cupos</th>';
$msHTML .= '<th width="60%" style="text-align: center">Descripción</th>';
$msHTML .= '<th width="15%" style="text-align: right">Precio unitario</th>';
$msHTML .= '<th width="15%" style="text-align: right">Precio total</th>';
$msHTML .= '</tr>';
$msHTML .= '</thead>';

//Inicio del cuerpo de la tabla
$msHTML .= '<tbody>';

//Detalle de cursos en el catálogo
$msConsulta = "select MONEDA_020 as MONEDA_CURSO, NOMBRE_020 as NOMBRE_KDSA, FECHAINI_020 as FECHA_INI, FECHAFIN_020 as FECHA_FIN, ";
$msConsulta .= "VALOR_020 as VALOR_CURSO, CONCAT('De ', DATE_FORMAT(HORAINI_020, '%h:%i %p'), ' a ', DATE_FORMAT(HORAFIN_020, '%h:%i %p')) as HORARIO, ";
$msConsulta .= "fxDevuelveDias(KDSA020A.CURSO_REL) as DIAS_CLASE, NOMBRE_070 as NOMBRE_INATEC, CODIGO_070 as COD_INATEC, ";
$msConsulta .= "ACUERDO_070 as ACUERDO_INATEC, CANTIDAD_091 as CUPOS, HORASCLASE_070 as HORAS_CLASE ";
$msConsulta .= "from KDSA091A, KDSA070A, KDSA020A ";
$msConsulta .= "where KDSA091A.CURSO_REL = KDSA020A.CURSO_REL and KDSA020A.CURSOINATEC_REL = KDSA070A.CURSOINATEC_REL ";
$msConsulta .= "and PROFORMA_REL = ?";
$mDatos = $m_cnx_MySQL->prepare($msConsulta);
$mDatos->execute([$codProforma]);

while ($Fila = $mDatos->fetch())
{
	$Horario = $Fila["HORARIO"];
	$Dias = $Fila["DIAS_CLASE"];
	$DetalleHorario = trim($Dias . " " . $Horario);
	$FechaIni = DevuelveFecha($Fila["FECHA_INI"]);
	$FechaFin = DevuelveFecha($Fila["FECHA_FIN"]);
	$DetalleFecha = "Del " . trim($FechaIni) . " al " . trim($FechaFin);
	$HorasClase = $Fila["HORAS_CLASE"];
	$CodigoInatec = $Fila["COD_INATEC"];
	$AcuerdoInatec = $Fila["ACUERDO_INATEC"];
	$Cantidad = $Fila["CUPOS"];
	$MonedaCurso = intval($Fila["MONEDA_CURSO"]);

	if ($mnMonedaRep == 0) //Reporte expresado en córdobas
	{
		if ($MonedaCurso == 0)
			$Valor = floatval($Fila["VALOR_CURSO"]);
		else
			$Valor = floatval($Fila["VALOR_CURSO"]) * $TipoCambio;
	}
	else //Reporte expresado en dólares
	{
		if ($MonedaCurso == 1)
			$Valor = floatval($Fila["VALOR_CURSO"]);
		else
			$Valor = floatval($Fila["VALOR_CURSO"]) / $TipoCambio;
	}

	$Total = $Cantidad * $Valor;
	$Suma += $Total;

	if ($Inatec == 0)
		$Curso = $Fila["NOMBRE_KDSA"];
	else
		$Curso = $Fila["NOMBRE_INATEC"] . mb_convert_encoding(" (Código ", "UTF-8") . trim($CodigoInatec) . " / Acuerdo ". trim($AcuerdoInatec) . ")";

	/*Detalle del Curso*/
	$msHTML .= '<tr>';
	$msHTML .= '<td width="10%" class="bordesLaterales letraNormal" style="text-align: center">' . $Cantidad .'</td>';
	$msHTML .= '<td width="60%" class="bordesLaterales letraNormal" style="text-align: left">' . $Curso . '</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraNormal" style="text-align: right">' . number_format($Valor,2,'.',',') . '</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraNormal" style="text-align: right">' . number_format($Total,2,'.',',') . '</td>';
	$msHTML .= '</tr>';

	/*Detalle de la Descripción*/
	$msHTML .= '<tr>';
	$msHTML .= '<td width="10%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '<td width="60%" class="bordesLaterales letraMenor" style="text-align: left"><b>Horario: </b>' . $DetalleHorario . '</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: right">&nbsp;</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: right">&nbsp;</td>';
	$msHTML .= '</tr>';
	$msHTML .= '<tr>';
	$msHTML .= '<td width="10%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '<td width="60%" class="bordesLaterales letraMenor" style="text-align: left"><b>' . mb_convert_encoding(html_entity_decode('Período: '), "UTF-8") . '</b>' . $DetalleFecha . '</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: center">&nbsp;</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: center">&nbsp;</td>';
	$msHTML .= '</tr>';
	$msHTML .= '<tr>';
	$msHTML .= '<td width="10%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '<td width="60%" class="bordesLaterales letraMenor" style="text-align: left"><b>Horas-clase: </b>' . $HorasClase . '</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: center">&nbsp;</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: center">&nbsp;</td>';
	$msHTML .= '</tr>';
	$msHTML .= '<tr>';
	$msHTML .= '<td width="10%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '<td width="60%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '</tr>';
}

//Detalle de cursos fuera del catálogo
$msConsulta = "select CURSOKDSA_092 as NOMBRE_KDSA, FECHAINI_092 as FECHA_INI, FECHAFIN_092 as FECHA_FIN, TOTAL_092, ";
$msConsulta .= "PRECIO_092 as VALOR_CURSO, HORARIO_092 as HORARIO, DIASCLASE_092 as DIAS_CLASE, CURSOINATEC_092 as NOMBRE_INATEC, ";
$msConsulta .= "CODIGOINATEC_092 as COD_INATEC, ACUERDO_092 as ACUERDO_INATEC, CUPOS_092 as CUPOS, HORASCLASE_092 as HORAS_CLASE ";
$msConsulta .= "from KDSA092A where PROFORMA_REL = ?";
$mDatos = $m_cnx_MySQL->prepare($msConsulta);
$mDatos->execute([$codProforma]);

while ($Fila = $mDatos->fetch())
{
	$Horario = $Fila["HORARIO"];
	$Dias = $Fila["DIAS_CLASE"];
	$DetalleHorario = trim($Dias . " " . $Horario);
	$FechaIni = DevuelveFecha($Fila["FECHA_INI"]);
	$FechaFin = DevuelveFecha($Fila["FECHA_FIN"]);
	$DetalleFecha = "Del " . trim($FechaIni) . " al " . trim($FechaFin);
	$HorasClase = $Fila["HORAS_CLASE"];
	$CodigoInatec = $Fila["COD_INATEC"];
	$AcuerdoInatec = $Fila["ACUERDO_INATEC"];
	$Cantidad = $Fila["CUPOS"];
	$MonedaCurso = $Moneda090;

	if ($mnMonedaRep == 0) //Reporte expresado en córdobas
	{
		if ($MonedaCurso == 0)
		{
			$Valor = floatval($Fila["VALOR_CURSO"]);
			$Total = floatval($Fila["TOTAL_092"]);
		}
		else
		{
			$Valor = floatval($Fila["VALOR_CURSO"]) * $TipoCambio;
			$Total = floatval($Fila["TOTAL_092"]) * $TipoCambio;
		}
	}
	else //Reporte expresado en dólares
	{
		if ($MonedaCurso == 1)
		{
			$Valor = floatval($Fila["VALOR_CURSO"]);
			$Total = floatval($Fila["TOTAL_092"]);
		}
		else
		{
			$Valor = floatval($Fila["VALOR_CURSO"]) / $TipoCambio;
			$Total = floatval($Fila["TOTAL_092"]) / $TipoCambio;
		}
	}

	$Suma += $Total;

	if ($Inatec == 0)
		$Curso = $Fila["NOMBRE_KDSA"];
	else
		$Curso = $Fila["NOMBRE_INATEC"] . mb_convert_encoding(" (Código ", "UTF-8") . trim($CodigoInatec) . " / Acuerdo ". trim($AcuerdoInatec) . ")";

	/*Detalle del Curso*/
	$msHTML .= '<tr>';
	$msHTML .= '<td width="10%" class="bordesLaterales letraNormal" style="text-align: center">' . $Cantidad .'</td>';
	$msHTML .= '<td width="60%" class="bordesLaterales letraNormal" style="text-align: left">' . $Curso . '</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraNormal" style="text-align: right">' . number_format($Valor,2,'.',',') . '</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraNormal" style="text-align: right">' . number_format($Total,2,'.',',') . '</td>';
	$msHTML .= '</tr>';

	/*Detalle de la Descripción*/
	$msHTML .= '<tr>';
	$msHTML .= '<td width="10%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '<td width="60%" class="bordesLaterales letraMenor" style="text-align: left"><b>Horario: </b>' . $DetalleHorario . '</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: center">&nbsp;</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: center">&nbsp;</td>';
	$msHTML .= '</tr>';
	$msHTML .= '<tr>';
	$msHTML .= '<td width="10%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '<td width="60%" class="bordesLaterales letraMenor" style="text-align: left"><b>' . mb_convert_encoding(html_entity_decode('Período: '), "UTF-8") . '</b>' . $DetalleFecha . '</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: center">&nbsp;</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: center">&nbsp;</td>';
	$msHTML .= '</tr>';
	$msHTML .= '<tr>';
	$msHTML .= '<td width="10%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '<td width="60%" class="bordesLaterales letraMenor" style="text-align: left"><b>Horas-clase: </b>' . $HorasClase . '</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: center">&nbsp;</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: center">&nbsp;</td>';
	$msHTML .= '</tr>';
	$msHTML .= '<tr>';
	$msHTML .= '<td width="10%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '<td width="60%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '</tr>';
}

//Detalle de Observaciones
$msConsulta = "select OBSERVACION_093 from KDSA093A where PROFORMA_REL = ? order by DETOBSERVACION_REL";
$mDatos = $m_cnx_MySQL->prepare($msConsulta);
$mDatos->execute([$codProforma]);
while ($Fila = $mDatos->fetch())
{
	$Observacion = $Fila["OBSERVACION_093"];

	$msHTML .= '<tr>';
	$msHTML .= '<td width="10%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
	$msHTML .= '<td width="60%" class="bordesLaterales letraMenor" style="text-align: left">' . $Observacion . '</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: center">&nbsp;</td>';
	$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: center">&nbsp;</td>';
	$msHTML .= '</tr>';
	
}
$msHTML .= '<tr>';
$msHTML .= '<td width="10%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
$msHTML .= '<td width="60%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
$msHTML .= '<td width="15%" class="bordesLaterales letraMenor" style="text-align: left">&nbsp;</td>';
$msHTML .= '</tr>';

if ($Descuento > 0)
{
	$mnDescuento = $Suma * ($Descuento / 100);
	
	$msHTML .= '<tr>';
	$msHTML .= '<td width="10%" class="bordesLaterales letraNormal" style="text-align: center">&nbsp;</td>';
	$msHTML .= '<td width="60%" class="bordesLaterales letraNormal" style="text-align: center">&nbsp;</td>';
	if ($mnMonedaRep == 0)
		$msHTML .= '<td width="15%" class="cuatroBordes letraNormal" style="text-align: left">Sub-Total (C$)</td>';
	else
		$msHTML .= '<td width="15%" class="cuatroBordes letraNormal" style="text-align: left">Sub-Total (U$)</td>';
	$msHTML .= '<td width="15%" class="cuatroBordes letraNormal" style="text-align: right">' . number_format($Suma,2,'.',',') . '</td>';
	$msHTML .= '</tr>';
	$msHTML .= '<tr>';
	$msHTML .= '<td width="10%" class="bordesLaterales letraNormal" style="text-align: center">&nbsp;</td>';
	$msHTML .= '<td width="60%" class="bordesLaterales letraNormal" style="text-align: center">&nbsp;</td>';
	$msHTML .= '<td width="15%" class="cuatroBordes letraNormal" style="text-align: left">Descuento</td>';
	$msHTML .= '<td width="15%" class="cuatroBordes letraNormal" style="text-align: right">' . number_format($mnDescuento,2,'.',',') . '</td>';
	$msHTML .= '</tr>';

	$TotalRep = $Suma - $mnDescuento;
}
else
{
	$TotalRep = $Suma;
}

//Totales
$msHTML .= '<tr>';
$msHTML .= '<td width="10%" class="tresBordes letraNormal" style="text-align: center">&nbsp;</td>';
$msHTML .= '<td width="60%" class="tresBordes letraNormal" style="text-align: center">&nbsp;</td>';
if ($mnMonedaRep == 0)
	$msHTML .= '<td width="15%" class="cuatroBordes letraNormal" style="text-align: left">Total (C$)</td>';
else
	$msHTML .= '<td width="15%" class="cuatroBordes letraNormal" style="text-align: left">Total (U$)</td>';
$msHTML .= '<td width="15%" class="cuatroBordes letraNormal" style="text-align: right">' . number_format($TotalRep,2,'.',',') . '</td>';
$msHTML .= '</tr>';

//Final del cuerpo de la tabla
$msHTML .= '</tbody>';

//Final de la tabla
$msHTML .= '</table>';

if ($Observaciones <> "")
{
	$msHTML .= '<br><br><b>OBSERVACIONES</b>';
	$msHTML .= '<div class="fondoGris">' . $Observaciones . '</div>';
}

$msHTML .= '<br><div style="font-size: smaller;">';
$msHTML .= '<label>' . mb_convert_encoding(html_entity_decode("-Esta proforma es válida por quince (15) días a partir de su elaboración."),"UTF-8") . '</label><br>';
if ($Inatec == 1)
{
	$msHTML .= '<label>' . mb_convert_encoding(html_entity_decode("-Centro de capacitación autorizado por INATEC (Autorización 1099698)."),"UTF-8") . '</label><br>';
	$msHTML .= '<label>' . mb_convert_encoding(html_entity_decode("-El saldo diferencial entre el monto de esta proforma y el monto aprobado por INATEC debe ser asumido por la empresa participante."),"UTF-8") . '</label><br>';
	$msHTML .= '<label>' . mb_convert_encoding(html_entity_decode("-El saldo total o el complemento empresarial (En caso que el trámite sea aprobado por INATEC) debe ser cancelado por la empresa el primer día de clases."),"UTF-8") . '</label><br>';
}
else
{
	$msHTML .= '<label>' . mb_convert_encoding(html_entity_decode("-El saldo total debe ser cancelado por la empresa el primer día de clases."),"UTF-8") . '</label><br>';
}
$msHTML .= '<label>' . mb_convert_encoding(html_entity_decode("-En caso de cancelar a través de cheque, elaborarlo a nombre de "),"UTF-8") . '<b>Knowledge for Development S.A.</b></label><br>';
//$msHTML .= '<label>' . mb_convert_encoding(html_entity_decode("-De no cancelarse en la fecha indicada, se cobrará mantenimiento del valor y mora del 10% mensual."),"UTF-8") . '</label><br>';
$msHTML .= '<label>' . mb_convert_encoding(html_entity_decode("-De no cancelarse en la fecha indicada, se cobrará un cargo por mora del 10% mensual."),"UTF-8") . '</label><br>';
$msHTML .= '<label>' . mb_convert_encoding(html_entity_decode("-Se emplea la tasa de cambio oficial del Banco Central de Nicaragua."),"UTF-8") . '</label><br>';
$msHTML .= '<label>' . mb_convert_encoding(html_entity_decode("-El precio en proforma, corresponde a la cantidad de cupos acordados. En caso que no asista uno o más participantes, la empresa esta obligada a cancelar el total de esta cotización."),"UTF-8") . '</label><br>';
$msHTML .= '<label>' . mb_convert_encoding(html_entity_decode("-KDSA no extiende certificado a quienes no asistan y aprueben satisfactoriamente al seminario, curso o taller."),"UTF-8") . '</label><br>';
$msHTML .= '</div>';

$msHTML .= '<div style="text-align: center">';
$msHTML .= '<img src="imagenes/firmaSello.jpg" alt="" width="100"><br>';
$msHTML .= '<label style="font-weight: bolder;">Lic. Seydi Castillo H.</label><br>';
$msHTML .= '<label style="font-size: smaller;">' . mb_convert_encoding(html_entity_decode("Dirección de Ventas"),"UTF-8") . '</label>';
$msHTML .= '</div>';

$Linea += 10;
$pdf->SetY($Linea);
$pdf->writeHTML($msHTML);
$pdf->Output();
?>