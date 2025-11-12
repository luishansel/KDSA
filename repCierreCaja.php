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
		public $msDiaCierre;

		// Page header
		function Header()
		{
			// Logos
			$this->Image('imagenes/headerLogin.jpg',12,6,0,15);
			// Title
			$mid_x = 210; // width of the "PDF screen", fixed by now.
			// helvetica bold 18
			$this->SetFont('helvetica','B',10);
			$Titulo = 'CIERRE DE CAJA';
			$this->Text(($mid_x - $this->GetStringWidth($Titulo)) / 2, 10, $Titulo);
			// helvetica normal 18
			$this->SetFont('helvetica','',8);
			$this->Text(($mid_x - $this->GetStringWidth($this->msDiaCierre)) / 2, 15, $this->msDiaCierre);
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

$mdFecha = $_POST["KDSA"];
$msFecha = DevuelveFechaLarga($mdFecha);

$pdf->msDiaCierre = $msFecha;
$pdf->AddPage();
$pdf->SetTextColor(0,0,0);
$pdf->SetFont('helvetica','',9);

//Obtención de datos de la cabecera
$msConsulta = "select PAGO_REL, NOMBRE_040, SERIE_040, RECIBO_040, MONEDA_040, TIPOPAGO_040, NUMEROCK_040, BANCOCK_040, MONTO_040, EMPRESARIAL_040, INATEC_040, OTROINGRESO_040 from KDSA040A where FECHA_040 = ? order by PAGO_REL";
$m_cnx_MySQL = fxAbrirConexion();
$mDatos = $m_cnx_MySQL->prepare($msConsulta);
$mDatos->execute([$mdFecha]);

$msHTML1 = "<h4>ESTUDIANTES</h4><table>";
$msHTML2 = "<h4>EMPRESAS</h4><table>";
$msHTML3 = "<h4>INATEC</h4><table>";
$msHTML4 = "<h4>OTROS INGRESOS</h4><table>";

$msHTML1 .= fxEncabezado();
$msHTML2 .= fxEncabezado();
$msHTML3 .= fxEncabezado();
$msHTML4 .= fxEncabezado();

$mnSumaC = 0;
$mnSumaD = 0;
$mnSuma1C = 0;
$mnSuma1D = 0;
$mnSuma2C = 0;
$mnSuma2D = 0;
$mnSuma3C = 0;
$mnSuma3D = 0;
$mnSuma4C = 0;
$mnSuma4D = 0;

$mbFondo1 = false;
$mbFondo2 = false;
$mbFondo3 = false;
$mbFondo4 = false;

$mbExiste1 = false;
$mbExiste2 = false;
$mbExiste3 = false;
$mbExiste4 = false;

$msHTML = "<style>";
$msHTML .= "th{";
$msHTML .= "border: 1px solid rgb(229,50,45); background-color: rgb(229,50,45); color: rgb(255,255,255); font-size: small;";
$msHTML .= "}";
$msHTML .= ".fondoGris{";
$msHTML .= "background-color: rgb(240,240,240); color: rgb(0,0,0); font-size: small;";
$msHTML .= "}";
$msHTML .= ".fondoBlanco{";
$msHTML .= "background-color: rgb(255,255,255); color: rgb(0,0,0); font-size: small;";
$msHTML .= "}";
$msHTML .= "</style>";

while ($mFila = $mDatos->fetch())
{
	$msPago = $mFila["PAGO_REL"];
	$msNombre = mb_convert_encoding(html_entity_decode($mFila["NOMBRE_040"]), "UTF-8", "latin1");
	$msSerie = $mFila["SERIE_040"];
	$msRecibo = $mFila["RECIBO_040"];
	$mnMoneda = $mFila["MONEDA_040"];
	$msTipo = fxTipoPago($mFila["TIPOPAGO_040"]);
	$msCheque = $mFila["NUMEROCK_040"];
	$msBanco = $mFila["BANCOCK_040"];
	$mnMonto = $mFila["MONTO_040"];
	$mbEmpresarial = $mFila["EMPRESARIAL_040"];
	$mbInatec = $mFila["INATEC_040"];
	$mbOtroIngreso = $mFila["OTROINGRESO_040"];

	if ($mbEmpresarial == 0 and $mbInatec == 0 and $mbOtroIngreso == 0)
	{
		$msHTML1 .= fxDetalle($msPago, $msNombre, $msSerie, $msRecibo, $mnMoneda, $msTipo, $msCheque, $msBanco, $mnMonto, $mbFondo1);
		$mbFondo1 = !$mbFondo1;
		$mbExiste1 = true;
		if ($mnMoneda == 0)
		{
			$mnSumaC += $mnMonto;
			$mnSuma1C += $mnMonto;
		}
		else
		{
			$mnSumaD += $mnMonto;
			$mnSuma1D += $mnMonto;
		}
	}

	if ($mbEmpresarial == 1)
	{
		$msHTML2 .= fxDetalle($msPago, $msNombre, $msSerie, $msRecibo, $mnMoneda, $msTipo, $msCheque, $msBanco, $mnMonto, $mbFondo2);
		$mbFondo2 = !$mbFondo2;
		$mbExiste2 = true;
		if ($mnMoneda == 0)
		{
			$mnSumaC += $mnMonto;
			$mnSuma2C += $mnMonto;
		}
		else
		{
			$mnSumaD += $mnMonto;
			$mnSuma2D += $mnMonto;
		}
	}

	if ($mbInatec == 1)
	{
		$msHTML3 .= fxDetalle($msPago, $msNombre, $msSerie, $msRecibo, $mnMoneda, $msTipo, $msCheque, $msBanco, $mnMonto, $mbFondo3);
		$mbFondo3 = !$mbFondo3;
		$mbExiste3 = true;
		if ($mnMoneda == 0)
		{
			$mnSumaC += $mnMonto;
			$mnSuma3C += $mnMonto;
		}
		else
		{
			$mnSumaD += $mnMonto;
			$mnSuma3D += $mnMonto;
		}
	}

	if ($mbOtroIngreso == 1)
	{
		$msHTML4 .= fxDetalle($msPago, $msNombre, $msSerie, $msRecibo, $mnMoneda, $msTipo, $msCheque, $msBanco, $mnMonto, $mbFondo4);
		$mbFondo4 = !$mbFondo4;
		$mbExiste4 = true;
		if ($mnMoneda == 0)
		{
			$mnSumaC += $mnMonto;
			$mnSuma4C += $mnMonto;
		}
		else
		{
			$mnSumaD += $mnMonto;
			$mnSuma4D += $mnMonto;
		}
	}
}

$msHTML1 .= "</table><br>";
$msHTML1 .= "<br><label>Pagos de estudiantes en córdobas: C$" . number_format($mnSuma1C,2,'.',',') . "</label>";
$msHTML1 .= "<br><label>Pagos de estudiantes en dólares: U$" . number_format($mnSuma1D,2,'.',',') . "</label>";

$msHTML2 .= "</table><br>";
$msHTML2 .= "<br><label>Pagos empresariales en córdobas: C$" . number_format($mnSuma2C,2,'.',',') . "</label>";
$msHTML2 .= "<br><label>Pagos empresariales en dólares: U$" . number_format($mnSuma2D,2,'.',',') . "</label>";

$msHTML3 .= "</table><br>";
$msHTML3 .= "<br><label>Pagos de INATEC en córdobas: C$" . number_format($mnSuma3C,2,'.',',') . "</label>";
$msHTML3 .= "<br><label>Pagos de INATEC en dólares: U$" . number_format($mnSuma3D,2,'.',',') . "</label>";

$msHTML4 .= "</table><br>";
$msHTML4 .= "<br><label>Otros ingresos en córdobas: C$" . number_format($mnSuma4C,2,'.',',') . "</label>";
$msHTML4 .= "<br><label>Otros ingresos en dólares: U$" . number_format($mnSuma4D,2,'.',',') . "</label>";

if ($mbExiste1 == true)
	$msHTML .= $msHTML1;
if ($mbExiste2 == true)
	$msHTML .= $msHTML2;
if ($mbExiste3 == true)
	$msHTML .= $msHTML3;
if ($mbExiste4 == true)
	$msHTML .= $msHTML4;

$msHTML .= "<h3>INGRESOS EN CORDOBAS: C$" . number_format($mnSumaC,2,'.',',') . " </h3>";
$msHTML .= "<h3>INGRESOS EN DOLARES: U$" . number_format($mnSumaD,2,'.',',') . " </h3>";

$pdf->SetY(25);
$pdf->writeHTML($msHTML);
$pdf->Output();

function fxEncabezado()
{
	$mHTML = '<thead>';
	$mHTML .= '<tr>';
	$mHTML .= '<th width="10%" style="text-align: center">Pago</th>';
	$mHTML .= '<th width="30%" style="text-align: left">Nombre</th>';
	$mHTML .= '<th width="5%" style="text-align: center">Serie</th>';
	$mHTML .= '<th width="10%" style="text-align: left">Recibo</th>';
	$mHTML .= '<th width="5%" style="text-align: center">Moneda</th>';
	$mHTML .= '<th width="10%" style="text-align: center">Tipo</th>';
	$mHTML .= '<th width="10%" style="text-align: left">CK/Depósito</th>';
	$mHTML .= '<th width="10%" style="text-align: left">Banco</th>';
	$mHTML .= '<th width="10%" style="text-align: right">Monto</th>';
	$mHTML .= '</tr>';
	$mHTML .= '</thead>';

	return $mHTML;
}

function fxDetalle($msPago, $msNombre, $msSerie, $msRecibo, $mnMoneda, $msTipo, $msCheque, $msBanco, $mnMonto, $mbFondo)
{
	if ($mbFondo == false)
		$msClase = "fondoBlanco";
	else
		$msClase = "fondoGris";

	if ($mnMoneda == 0)
		$msMoneda = "C$";
	else
		$msMoneda = "U$";

	$mHTML = '<tr>';
	$mHTML .= '<td class="' . $msClase . '" width="10%" style="text-align: center">' . $msPago . '</td>';
	$mHTML .= '<td class="' . $msClase . '" width="30%" style="text-align: left">' . $msNombre . '</td>';
	$mHTML .= '<td class="' . $msClase . '" width="5%" style="text-align: center">' . $msSerie . '</td>';
	$mHTML .= '<td class="' . $msClase . '" width="10%" style="text-align: left">' . $msRecibo . '</td>';
	$mHTML .= '<td class="' . $msClase . '" width="5%" style="text-align: center">' . $msMoneda . '</td>';
	$mHTML .= '<td class="' . $msClase . '" width="10%" style="text-align: center">' . $msTipo . '</td>';
	$mHTML .= '<td class="' . $msClase . '" width="10%" style="text-align: left">' . $msCheque . '</td>';
	$mHTML .= '<td class="' . $msClase . '" width="10%" style="text-align: left">' . $msBanco . '</td>';
	$mHTML .= '<td class="' . $msClase . '" width="10%" style="text-align: right">' . number_format($mnMonto,2,'.',',') . '</td>';
	$mHTML .= '</tr>';

	return $mHTML;
}

function fxTipoPago($mnTipo)
{
	switch(intval($mnTipo))
	{
		case 0:
			$msResultado = "Efectivo";
			break;
		case 1:
			$msResultado = "Tarjeta";
			break;
		case 2:
			$msResultado = "Cheque";
			break;
		case 3:
			$msResultado = "Dep. FICOHSA";
			break;
		case 4:
			$msResultado = "Dep. BAC";
			break;
		case 5:
			$msResultado = "eCommerce";
			break;
	}

	return $msResultado;
}
?>