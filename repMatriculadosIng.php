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
		var $top_margin = 30;

		// Page header
		function Header()
		{
			// Logos
			$this->Image('imagenes/headerLogin.jpg',12,6,0,15);
			$mid_x = 210;
			// Title
			$this->SetFont('helvetica','B',11);
			$Titulo = 'ESTUDIANTES MATRICULADOS POR TIPO DE INGRESO';
			$this->Text(($mid_x - $this->GetStringWidth($Titulo)) / 2, 10, $Titulo);
			$this->SetFont('helvetica','B',9);
			$this->Text(($mid_x - $this->GetStringWidth($this->Periodo)) / 2, 16, $this->Periodo);

			$msHTML = '<table><thead><tr>';
			$msHTML .= '<th style="text-align: center;background-color:rgb(0,0,255);color: white; width: 10%">Matrícula</th>';
			$msHTML .= '<th style="text-align: center;background-color:rgb(0,0,255);color: white; width: 10%">Fecha</th>';
			$msHTML .= '<th style="text-align: left;background-color:rgb(0,0,255);color: white; width: 35%">Curso</th>';
			$msHTML .= '<th style="text-align: left;background-color:rgb(0,0,255);color: white; width: 35%">Estudiante</th>';
			$msHTML .= '<th style="text-align: center;background-color:rgb(0,0,255);color: white; width: 10%">Estado</th>';
			$msHTML .= '</tr></thead></table>';
			$this->setFontSize(8);
			$this->SetY(25);
			$this->writeHTML($msHTML);
			$this->top_margin = $this->GetY() + 5; // padding for second page
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
	$msConsulta = "select MATRICULA_REL, FUENTEINGRESO_030, INATEC_030, FECHA_030, ESTADO_030, NOMBRE_020, CONVOCATORIA_020, GRUPO_020, NOMBRES_010, APELLIDOS_010 ";
	$msConsulta .= "from KDSA030A, KDSA010A, KDSA020A ";
	$msConsulta .= "where KDSA030A.ESTUDIANTE_REL = KDSA010A.ESTUDIANTE_REL and KDSA030A.CURSO_REL = KDSA020A.CURSO_REL and ";
	$msConsulta .= "FECHA_030 between ? and ? order by FUENTEINGRESO_030, KDSA030A.CURSO_REL, NOMBRES_010, APELLIDOS_010";

	$m_cnx_MySQL = fxAbrirConexion();
	$mDatos = $m_cnx_MySQL->prepare($msConsulta);
	$mDatos->execute([$mdFechaIni, $mdFechaFin]);

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set
	$pdf->SetMargins(PDF_MARGIN_LEFT, $pdf->top_margin, PDF_MARGIN_RIGHT);
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

	$pdf->SetY(30);
	$pdf->SetFont('helvetica','',8);
	$pdf->SetTextColor(0,0,0);
	$mnFuenteIngresoAnt = -1;
	$mbColorea = 0;

	while ($Fila = $mDatos->fetch())
	{
		$Matricula = $Fila["MATRICULA_REL"];
		$FechaMatricula = $Fila["FECHA_030"];
		$msNombre = $Fila["NOMBRES_010"] . ' ' . $Fila["APELLIDOS_010"];
		$NombreCompleto = mb_convert_encoding(html_entity_decode($msNombre), "UTF-8");
		$Curso = mb_convert_encoding(html_entity_decode($Fila["NOMBRE_020"]), "UTF-8") . " (" . $Fila["CONVOCATORIA_020"] . "/G" . $Fila["GRUPO_020"] . ")";
		$mnFuenteIngreso = intval($Fila["FUENTEINGRESO_030"]);
		$mnEstado = intval($Fila["ESTADO_030"]);

		$msHTML = "";
		if ($mnFuenteIngreso != $mnFuenteIngresoAnt)
		{
			switch($mnFuenteIngreso)
			{
				case 0:
					$msHTML .= '<h1 style="color: blue"><strong>Propios</strong></h1>';
					break;
				case 1:
					$msHTML .= '<h1 style="color: blue"><strong>Empresa</strong></h1>';
					break;
				case 2:
					$msHTML .= '<h1 style="color: blue"><strong>Papás</strong></h1>';
					break;
				case 3:
					$msHTML .= '<h1 style="color: blue"><strong>Familiar</strong></h1>';
			}

			$mnFuenteIngresoAnt = $mnFuenteIngreso;
		}

		switch($mnEstado)
		{
			case 0:
				$Estado = "Activo";
				break;
			case 1:
				$Estado = "Inactivo";
				break;
			case 2:
				$Estado = "Deserción";
				break;
			case 3:
				$Estado = "Certificado";
				break;
			case 4:
				$Estado = "Anulado";
		}

		$msHTML .= '<table><tr>';
		if ($mbColorea == 0)
		{
			$msHTML .= '<td style="text-align: center; width: 10%">' . $Matricula . '</td>';
			$msHTML .= '<td style="text-align: center; width: 10%">' . DevuelveFecha($FechaMatricula) . '</td>';
			$msHTML .= '<td style="text-align: left; width: 35%">' . $Curso . '</td>';
			$msHTML .= '<td style="text-align: left; width: 35%">' . $NombreCompleto . '</td>';
			$msHTML .= '<td style="text-align: center; width: 10%">' . $Estado . '</td>';
		}
		else
		{
			$msHTML .= '<td style="background-color: #f2f2f2; text-align: center; width: 10%">' . $Matricula . '</td>';
			$msHTML .= '<td style="background-color: #f2f2f2; text-align: center; width: 10%">' . DevuelveFecha($FechaMatricula) . '</td>';
			$msHTML .= '<td style="background-color: #f2f2f2; text-align: left; width: 35%">' . $Curso . '</td>';
			$msHTML .= '<td style="background-color: #f2f2f2; text-align: left; width: 35%">' . $NombreCompleto . '</td>';
			$msHTML .= '<td style="background-color: #f2f2f2; text-align: center; width: 10%">' . $Estado . '</td>';
		}

		$msHTML .= '</tr></table>';
		$pdf->writeHTML($msHTML);
		
		if ($mbColorea == 0)
			$mbColorea = 1;
		else
			$mbColorea = 0;
	}
	$pdf->Output();
}
?>