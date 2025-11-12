<?php
	$msMatricula = $_GET["KDSA"];
?>
<!DOCTYPE html>
<html lang="ES-NI" class="no-js">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Control Administrativo y Académico de KDSA."/>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<link rel="icon" href="imagenes/favicon.png" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="css/easyui.css" />
<link rel="stylesheet" type="text/css" href="css/icon.css" />
<link rel="stylesheet" type="text/css" href="css/StyleKDSA.css"/>

<script src="js/jquery.min.js"></script>
<script src="js/jquery-3.4.1.js"></script>
<script src="js/jquery.easyui.min.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<title>Aplicación web KDSA</title>

<style>
	a {
		color: rgb(0, 0, 255);
		text-decoration: underline;
		background-color: transparent;
	}
</style>
</head>

<body>
	<div id="cabecera">
        <div class="container-fluid">
            <img src="imagenes/headerMatricula.png" width="100%" />
        </div>
    </div>
    <div class="container text-left">
		<div id="DivContenido">
			<div class = "row">
				<div class="col-md-12">
					<form id="frmConfirmacion" name="frmConfirmacion">
						<div class = "row">
							<div class="col-auto col-md-12">
								<img src="imagenes/matriculaCondiciones.png" class="img-responsive" style="display:block; margin-left:auto; margin-right:auto">
							</div>
						</div>

						<div class = "row">
							<div class="col-auto col-md-10 col-md-offset-1">
								<br>
								<ol>
									<li>Para ser certificado, el estudiante debe asistir al 80% de los encuentros de clases. El 20% de faltas permitidas se contarán tenga o no justificación el estudiante.</li>
									<li>Al no cancelar las cuotas (aranceles) de curso en las fechas indicadas en calendario de pago se le generará recargo diario del 0.33% sobre la base de un 10% mensual del valor principal de la cuota. El máximo de recargo a cobrar será el 10% del principal, aunque el retraso exceda los 30 días. </li>
									<li>Las evaluaciones deben realizarse en las fechas establecidas, día final de modulo formativo.</li>
									<li>En ningún caso habrá devolución de dinero por pagos realizados.</li>
									<li>En caso de no completarse el número mínimo de participantes para dar inicio al curso o seminario, KDSA tendrá 45 días hábiles para reprogramar la fecha de inicio. De no lograrse el número de participantes, se procederá a la devolución de aranceles pagados por los inscritos a la fecha.</li>
									<li>Los aranceles cancelados son válidos únicamente para el curso y horario inscrito en la presente matrícula. No aplica para traslados, reingreso, reubicación o situaciones similares. Por traslado o reubicaciones deberá cancelar la suma de quince dólares (U$ 15.00)</li>
									<li>El estudiante deberá aprobar satisfactoriamente con notas superiores a 70 puntos todos los módulos del programa de estudio. En caso contrario no se extenderá certificado, cartas de egresado, cartas de pasantía u otros similares.</li>
									<li>Para la entrega del certificado del curso, los alumnos deberán estar solventes con el 100% de los aranceles establecidos, así como aprobados con 70 puntos cada uno de los módulos desarrollados, además de completo su expediente estudiantil (Cédula y soporte de nivel académico).</li>
									<li>Los certificados de participación del centro de estudio se entregarán el ultimo día de clases, una vez completado los requisitos del numeral 8. La entrega es personal al estudiante. No se entregarán certificados a padres, madres, hermanos(as), parientes, esposos(as) o amistades. Toda certificación posterior deberá cancelar el arancel por tramite extemporáneo. </li>
									<li>Los documentos adjuntos a la matrícula (Cédula y Diploma), deben de ser remitidos en formato de imagen, debidamente digitalizados al correo electrónico registro@capacitacionkdsa.com</li>
									<!-- LHVG 20230707 Eliminación de la condición por solicitud de Lic. Humberto Cárdenas
									<li>El uso de mascarilla es obligatorio. No asista a clases presenciales si presenta síntomas como gripe, tos, fiebre, afecciones respiratorias o similares a COVID-19. En tal caso puede solicitar con un día de anticipación, el usuario y contraseña temporales para que reciba su clase desde casa.</li>
									-->
									<li>Prohibido ingresar al aula de clases con alimentos, niños o acompañantes.</li>
									<li>Se prohíbe fumar, ingerir licor u otras sustancias análogas, dentro de las instalaciones de KDSA.</li>
									<li>La fecha de finalización establecida para el curso es tentativa y podrá ser reprogramada ante cualquier eventualidad con el fin de cumplir el número de sesiones definidas para el curso.</li>
									<li>Las condiciones generales establecidas en la presente matricula, constituyen el mínimo de reglas en la relación entre la institución educativa y el estudiante; sin embargo, las situaciones no previstas en los numerales que anteceden, es regulado por el reglamento institucional, al cual queda sujeto todo estudiante.</li>
								</ol>
								<br>
								<input class="form-check-input" type="checkbox" value="0" name="chkAceptar" id="chkAceptar" checked><label class="form-check-label" for="chkAceptar">&nbsp;Acepto las condiciones generales de la matrícula.</label>
								<br><input type="button" id="cmdConfirmar" name="cmdConfirmar" value="Confirmar la matrícula" class="btn btn-warning"/>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>
<script src="js/jquery.redirect.js"></script>
<script>
	$("#chkAceptar").on("change", function() {
		if (document.getElementById("chkAceptar").checked)
			document.getElementById("cmdConfirmar").disabled = false;
		else
			document.getElementById("cmdConfirmar").disabled = true;
	});

	$("#cmdConfirmar").on("click", function() {
		var codMatricula = '<?php echo($msMatricula); ?>';
		$.redirect("procConfirmaMatricula.php", {KDSA: codMatricula}, "POST");
	});
</script>