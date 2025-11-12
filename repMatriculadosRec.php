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
<?php }
else
{
	class PDF extends TCPDF
	{
		public $Periodo;
		
		// Page header
		function Header()
		{
			// Logos
			$this->Image('imagenes/headerLogin.jpg',12,6,0,15);
			$mid_x = 210;
			// Title
			$this->SetFont('helvetica','B',13);
			$Titulo = 'ESTUDIANTES MATRICULADOS (CON RECIBO)';
			$this->Text(($mid_x - $this->GetStringWidth($Titulo)) / 2, 10, $Titulo);
			$this->SetFont('helvetica','B',10);
			$this->Text(($mid_x - $this->GetStringWidth($this->Periodo)) / 2, 16, $this->Periodo);

			$LineaTitulo = 30;
			$this->SetFont('helvetica','B',9);
			$this->SetFillColor(0,100,255);
			$this->SetTextColor(255,255,255);
			$this->SetXY(15,$LineaTitulo);
			$this->Cell(20,5,'FECHA',0,0,'C',true);
			$this->SetXY(35,$LineaTitulo);
			$this->Cell(70,5,'CURSO',0,0,'L',true);
			$this->SetXY(105,$LineaTitulo);
			$this->Cell(70,5,'ESTUDIANTE',0,0,'L',true);
			$this->SetXY(175,$LineaTitulo);
			$this->Cell(25,5,'RECIBO',0,0,'L',true);
		}

		// Page footer
		function Footer()
		{
			// Position at 1.5 cm from bottom
			$this->SetY(-15);
			// helvetica italic 8
			$this->SetFont('helvetica','I',8);
			// Page number
			$this->Cell(0,10,'Página '.$this->PageNo().'/'.$this->getAliasNbPages(),0,0,'L');
			$this->Cell(0,10,'Emitido: ' . date("d/m/Y h:i:s a") . '',0,0,'R');
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

	$mdFechaIni = trim($_POST["dtpFechaIni"]);
	$mdFechaFin = trim($_POST["dtpFechaFin"]);

	$pdf = new PDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	if ($mdFechaIni == $mdFechaFin)
		$Rotulo = "Fecha del " . DevuelveFecha($mdFechaIni);
	else
		$Rotulo = "Del " . DevuelveFecha($mdFechaIni) . " al " . DevuelveFecha($mdFechaFin);
	
	$pdf->Periodo=$Rotulo;

	//Obtención de datos
	$msConsulta = "select distinct KDSA030A.MATRICULA_REL, FECHA_030, concat(NOMBRES_010, ' ', APELLIDOS_010) as NOMBRECOMPLETO, NOMBRE_020, ";
	$msConsulta .= "CONVOCATORIA_020, GRUPO_020, RECIBO_040 ";
	$msConsulta .= "from KDSA030A, KDSA010A, KDSA020A, KDSA051A, KDSA040A, KDSA041A, KDSA050A ";
	$msConsulta .= "where KDSA030A.ESTUDIANTE_REL = KDSA010A.ESTUDIANTE_REL and KDSA030A.CURSO_REL = KDSA020A.CURSO_REL and ";
	$msConsulta .= "KDSA030A.MATRICULA_REL = KDSA051A.MATRICULA_REL and KDSA030A.MATRICULA_REL = KDSA041A.MATRICULA_REL and ";
	$msConsulta .= "KDSA051A.COBRO_REL = KDSA050A.COBRO_REL and KDSA041A.PAGO_REL = KDSA040A.PAGO_REL and ";
	$msConsulta .= "KDSA051A.COBRO_REL = KDSA041A.COBRO_REL and TIPO_050 = 2 and KDSA030A.ESTADO_030 <> 4 and FECHA_030 between ? and ? ";
	$msConsulta .= "order by FECHA_030, NOMBRE_020";

	$m_cnx_MySQL = fxAbrirConexion();
	$mDatos = $m_cnx_MySQL->prepare($msConsulta);
	$mDatos->execute([$mdFechaIni, $mdFechaFin]);

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set
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

	$Linea = 35;
	$pdf->SetFont('helvetica','',8);
	$pdf->SetTextColor(0,0,0);

	while ($Fila = $mDatos->fetch())
	{
		$Matricula = $Fila["MATRICULA_REL"];
		$FechaMatricula = $Fila["FECHA_030"];
		$Recibo = $Fila["RECIBO_040"];
		$NombreCompleto = mb_convert_encoding(html_entity_decode($Fila["NOMBRECOMPLETO"]), "UTF-8");
		$Curso = mb_convert_encoding(html_entity_decode($Fila["NOMBRE_020"]), "UTF-8") . " (" . $Fila["CONVOCATORIA_020"] . "/G" . $Fila["GRUPO_020"] . ")";

		if ($Linea % 2 == 0)
			$pdf->SetFillColor(200,200,200);
		else
			$pdf->SetFillColor(255,255,255);

		$pdf->SetXY(15,$Linea);
		$pdf->Cell(20,5,DevuelveFecha($FechaMatricula),0,0,'C',true);
		$pdf->SetXY(35,$Linea);
		$pdf->Cell(70,5,$Curso,0,0,'L',true);
		$pdf->SetXY(105,$Linea);
		$pdf->Cell(70,5,$NombreCompleto,0,0,'L',true);
		$pdf->SetXY(175,$Linea);
		$pdf->Cell(25,5,$Recibo,0,0,'L',true);
		
		if ($Linea >= 245)
		{
			$Linea=35;
			$pdf->AddPage();
		}
		else
			$Linea+=5;
	}
	$pdf->Output();
}
?>