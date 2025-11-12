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
$m_cnx_MySQL = fxAbrirConexion();
$Registro = fxVerificaUsuario();
$Administrador = fxVerificaAdministrador();

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
		var $top_margin = 35;
		public $periodo;

		// Page header
		function Header()
		{
			// Logos
			$this->Image('imagenes/headerLogin.jpg',12,10,0,18);
			// Title
			$mid_x = 278; // width of the "PDF screen", fixed by now.

			$this->SetFont('helvetica','B',14);
			$Titulo = 'ESTUDIANTES CON DESCUENTO';
			$this->Text(($mid_x - $this->GetStringWidth($Titulo)) / 2, 10, $Titulo);
			$this->SetFont('helvetica','',11);
			$Titulo = $this->periodo;
			$this->Text(($mid_x - $this->GetStringWidth($Titulo)) / 2, 17, $Titulo);

			$msHTML = '<table>';
			$msHTML .= '<tr>';
			$msHTML .= '<th style="text-align: left;background-color:rgb(0,0,255);color: white; width: 10%;"><strong>Fecha</strong></th>';
			$msHTML .= '<th style="text-align: left;background-color:rgb(0,0,255);color: white; width: 30%;"><strong>Estudiante</strong></th>';
			$msHTML .= '<th style="text-align: left;background-color:rgb(0,0,255);color: white; width: 10%;"><strong>Porcentaje</strong></th>';
			$msHTML .= '<th style="text-align: left;background-color:rgb(0,0,255);color: white; width: 20%;"><strong>Motivo</strong></th>';
			$msHTML .= '<th style="text-align: left;background-color:rgb(0,0,255);color: white; width: 30%;"><strong>Estudio</strong></th>';
			$msHTML .= '</tr>';
			$msHTML .= '</table>';
			$this->SetY(30);
			$this->writeHTML($msHTML);

			$this->top_margin = $this->GetY() + 5; // padding for second page
		}

		// Page footer
		function Footer()
		{
			// Position at 1.5 cm from bottom
			$this->SetY(-15);
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

	$mdFechaIni = $_POST["fechaIni"];
	$mdFechaFin = $_POST["fechaFin"];

	$pdf = new PDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
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

	$pdf->setFontSize(10);
	
	$msConsulta = "select FECHA_030, DESCUENTO_030, MOTIVO_030, NOMBRES_010, APELLIDOS_010, TIPO_020, NOMBRE_020, CONVOCATORIA_020, GRUPO_020 ";
	$msConsulta .= "from KDSA030A, KDSA020A, KDSA010A ";
	$msConsulta .= "where DESCUENTO_030 > 0 and KDSA030A.CURSO_REL = KDSA020A.CURSO_REL and KDSA030A.ESTUDIANTE_REL = KDSA010A.ESTUDIANTE_REL and FECHA_030 between ? and ?";
	$msPeriodo = "Estudiantes matriculados entre " . DevuelveFecha($mdFechaIni) . " y " . DevuelveFecha($mdFechaFin);

	$mDatos = $m_cnx_MySQL->prepare($msConsulta);
	$mDatos->execute([$mdFechaIni, $mdFechaFin]);

	$pdf->periodo=$msPeriodo;
	$pdf->AddPage();

	$msHTML = '<table>';

	$mbColorea = 0;

	while ($Fila = $mDatos->fetch())
	{
		$mdFecha = date_create_from_format('Y-m-d', $Fila["FECHA_030"]);
		$msFecha = date_format($mdFecha, 'd-m-Y');
		$mnPorcentaje = $Fila["DESCUENTO_030"];
		$msMotivo = html_entity_decode($Fila["MOTIVO_030"]);
		$msEstudiante = html_entity_decode($Fila["NOMBRES_010"]) . " " . html_entity_decode($Fila["APELLIDOS_010"]);
		$mnTipoCurso = intval($Fila["TIPO_020"]);

		switch($mnTipoCurso)
		{
			case 0:
				$msTipoCurso = "Seminario";
				break;
			case 1:
				$msTipoCurso = "Curso";
				break;
			case 2:
				$msTipoCurso = "Carrera";
				break;
			case 3:
				$msTipoCurso = "Taller";
				break;
			case 4:
				$msTipoCurso = "Diplomado";
				break;
			case 5:
				$msTipoCurso = "Webinar";
				break;
			case 6:
				$msTipoCurso = "Workshop";
				break;
			case 7:
				$msTipoCurso = "Teambuilding";
		}

		$msNomCurso = $msTipoCurso . '. ' . html_entity_decode($Fila["NOMBRE_020"]) . ' (' . $Fila["CONVOCATORIA_020"] . '/G' . $Fila["GRUPO_020"] . ')';

		$msHTML .= '<tr nobr="true">';

		if ($mbColorea == 0)
		{
			$msHTML .= '<td style="text-align: left; width: 10%;">' . trim($msFecha) . '</td>';
			$msHTML .= '<td style="text-align: left; width: 30%;">' . trim($msEstudiante) . '</td>';
			$msHTML .= '<td style="text-align: left; width: 10%;">' . trim($mnPorcentaje) . '</td>';
			$msHTML .= '<td style="text-align: left; width: 20%;">' . trim($msMotivo) . '</td>';
			$msHTML .= '<td style="text-align: left; width: 30%;">' . trim($msNomCurso) . '</td>';
		}
		else
		{
			$msHTML .= '<td style="background-color: #f2f2f2; text-align: left; width: 10%;">' . trim($msFecha) . '</td>';
			$msHTML .= '<td style="background-color: #f2f2f2; text-align: left; width: 30%;">' . trim($msEstudiante) . '</td>';
			$msHTML .= '<td style="background-color: #f2f2f2; text-align: left; width: 10%;">' . trim($mnPorcentaje) . '</td>';
			$msHTML .= '<td style="background-color: #f2f2f2; text-align: left; width: 20%;">' . trim($msMotivo) . '</td>';
			$msHTML .= '<td style="background-color: #f2f2f2; text-align: left; width: 30%;">' . trim($msNomCurso) . '</td>';
		}

		$msHTML .= '</tr>';
		
		if ($mbColorea == 0)
			$mbColorea = 1;
		else
			$mbColorea = 0;
	}
	$msHTML .= '</table>';

	$pdf->SetY(35);
	$pdf->writeHTML($msHTML);
	$pdf->Output();
}
?>