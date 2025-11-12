<?php
	#Inicia la sesión antes de la respuesta HTML
	session_start();
	$_SESSION["gnVerifica"] = 0;
	require_once ("funciones/fxGeneral.php");
	require_once ("funciones/fxMatricula.php");
	require_once ("funciones/fxUsuarios.php");

	$msMatricula = $_POST["KDSA"];

	$m_cnx_MySQL = fxAbrirConexion();
	
	$msConsulta = "select CONCAT_WS(' ',NOMBRES_010,APELLIDOS_010) as ESTUDIANTE, NOMBRE_020, CORREO_010 ";
	$msConsulta .= "from KDSA030A, KDSA020A, KDSA010A where KDSA030A.ESTUDIANTE_REL = KDSA010A.ESTUDIANTE_REL and ";
	$msConsulta .= "KDSA030A.CURSO_REL = KDSA020A.CURSO_REL and MATRICULA_REL = ?";
	$mDatos = $m_cnx_MySQL->prepare($msConsulta);
	$mDatos->execute([$msMatricula]);
	$mFila = $mDatos->fetch();
	$msEstudiante = $mFila["ESTUDIANTE"];
	$msCorreo = $mFila["CORREO_010"];
	$msCurso = $mFila["NOMBRE_020"];

	$msFechaHoy = date('Y-m-d H:i:s');
	$msConsulta = "insert into KDSA031A (MATRICULA_REL, FECHA_031, ESTADO_031) values (?, ?, ?)";
	$mConfirmacion = $m_cnx_MySQL->prepare($msConsulta);
	$mConfirmacion->execute([$msMatricula, $msFechaHoy, 1]);

	$msConsulta = "update KDSA030A set NOTIFICADO_030 = ?, ESTADO_030 = ? where MATRICULA_REL = ?";
	$mEstado = $m_cnx_MySQL->prepare($msConsulta);
	$mEstado->execute([1, 0, $msMatricula]); //Marca la notificación y activa la matrícula

	$msEncriptado = crypt($msMatricula, '_appwKDSA');
	$msConsulta = "insert into KDSA002A (USUARIO_REL, NOMBRE_002, CORREO_002, CLAVE_002, ACADEMICO_002, SUPERVISOR_002, ACTIVO_002) values(?, ?, ?, ?, ?, ?, ?)";
	$mResultado = $m_cnx_MySQL->prepare($msConsulta);
	$mResultado->execute([$msMatricula, $msEstudiante, $msCorreo, $msEncriptado, 0, 0, 1]);

	$msRotulo = "<h3>La matrícula de <strong>" . mb_convert_encoding($msEstudiante, "UTF-8", "latin1") . "</strong> para el curso <strong>";
	$msRotulo .= $msCurso . "</strong> ha sido confirmada.</h3><br>";
	$msRotulo .= "<h1>¡Gracias por capacitarse con KDSA!</h1>";
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
<title>Matricula en linea KDSA</title>
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
								<img src="imagenes/matriculaOk.png" class="img-responsive" style="display:block; margin-left:auto; margin-right:auto">
							</div>
						</div>

						<div class = "row">
							<div class="col-auto col-md-10 col-md-offset-1">
								<?php echo($msRotulo); ?>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</body>
</html>