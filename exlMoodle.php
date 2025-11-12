<?php
	session_start();
	if (!isset($_SESSION["gnVerifica"]) or $_SESSION["gnVerifica"] != 1)
	{
		echo('<meta http-equiv="Refresh" content="0;url=index.php">');
		exit('');
    }
	
	require_once ("funciones/fxGeneral.php");
	require_once ("funciones/fxUsuarios.php");
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
		$Administrador = fxVerificaAdministrador();
		$PermisoUsuario = fxPermisoUsuario("hrrCursosMoodle", $mbAgregar, $mbModificar, $mbBorrar, $mbAnular);
		
		if ($Administrador == 0 and $PermisoUsuario == 0)
		{ ?>
        <div class="container text-center">
        	<div id="DivContenido">
				<img src="imagenes/errordeacceso.png"/>
            </div>
        </div>
		<?php }
		else
		{
			$codCurso = trim($_POST["KDSA"]);
            $m_cnx_MySQL = fxAbrirConexion();
            $msConsulta = "select NOMBRE_020, CONVOCATORIA_020, GRUPO_020 from KDSA020A where CURSO_REL = ?";
            $mDatos = $m_cnx_MySQL->prepare($msConsulta);
			$mDatos->execute([$codCurso]);
            $Fila = $mDatos->fetch();
            $msNombre = fxTildes($Fila["NOMBRE_020"]);
            $msConvocatoria = $Fila["CONVOCATORIA_020"];
            $msGrupo = $Fila["GRUPO_020"];
            $msCaracter = substr(trim($msNombre), -1);
            if ($msCaracter == ".")
                $msNombreArchivo = stristr($msNombre, '.', true) . ' ' . $msConvocatoria . 'G' . $msGrupo . '.csv';
            else
                $msNombreArchivo = $msNombre . ' ' . $msConvocatoria . 'G' . $msGrupo . '.csv';

            //Obtención de datos
            $msConsulta = "select MATRICULA_REL, NOMBRES_010, APELLIDOS_010, CORREO_010, DOMICILIO_010 ";
            $msConsulta .= "from KDSA030A, KDSA010A where KDSA030A.ESTUDIANTE_REL = KDSA010A.ESTUDIANTE_REL ";
            $msConsulta .= "and ESTADO_030 <> 4 and KDSA030A.CURSO_REL = ?";
			$mDatos = $m_cnx_MySQL->prepare($msConsulta);
			$mDatos->execute([$codCurso]);

			$archivo = fopen($msNombreArchivo, "w");
            fwrite($archivo, 'username;password;firstname;lastname;email;city;country;course1' . PHP_EOL);

            while ($Fila = $mDatos->fetch())
            {
                fwrite($archivo, strtolower(trim($Fila['MATRICULA_REL'])) . ';'); //Usuario
                fwrite($archivo, cadenaAleatoria() . ';'); //Clave
                $mNombre = fxTildes(trim($Fila['NOMBRES_010']));
                fwrite($archivo, utf8_decode($mNombre) . ';'); //Nombres
                $mApellido = fxTildes(trim($Fila['APELLIDOS_010']));
                fwrite($archivo, utf8_decode($mApellido) . ';'); //Apellidos
                fwrite($archivo, trim($Fila['CORREO_010']) . ';'); //Correo
                fwrite($archivo, trim($Fila['DOMICILIO_010']) . ';'); //Ciudad
                fwrite($archivo, 'NI;'); //País
                fwrite($archivo, stristr(utf8_decode($msNombreArchivo), '.', true) . PHP_EOL); //Curso
            }

            fclose($archivo);

            header('Cache-Control: public');
            header('Content-Description: File Transfer');
            header('Content-type:text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=' . trim($msNombreArchivo));
            header('Content-Transfer-Encoding: binary');
            readfile($msNombreArchivo);
        }
	}

    function cadenaAleatoria(){
        $mnASCII = rand(65,90);
        $msMayuscula = chr($mnASCII);
        $mnASCII = rand(97,122);
        $msMinuscula = chr($mnASCII);
        $mnNumero = rand(10000,99999);
        $mnValor = rand(1,4);

        switch($mnValor){
            case 1:
                $msCaracter = "@";
                break;
            case 2:
                $msCaracter = "#";
                break;
            case 3:
                $msCaracter = "&";
                break;
            case 4:
                $msCaracter = "%";
                break;
        }

        $msCadena = $msMayuscula . $mnNumero . $msMinuscula . $msCaracter;
        return $msCadena;
    }

    function fxTildes($msCadena){
        $msCadena = str_replace("&aacute;", "á", $msCadena);
        $msCadena = str_replace("&eacute;", "é", $msCadena);
        $msCadena = str_replace("&iacute;", "í", $msCadena);
        $msCadena = str_replace("&oacute;", "ó", $msCadena);
        $msCadena = str_replace("&uacute;", "ú", $msCadena);
        $msCadena = str_replace("&nacute;", "ñ", $msCadena);
        $msCadena = str_replace("&Nacute;", "Ñ", $msCadena);

        $msCadena = str_replace("&atilde;", "á", $msCadena);
        $msCadena = str_replace("&etilde;", "é", $msCadena);
        $msCadena = str_replace("&itilde;", "í", $msCadena);
        $msCadena = str_replace("&otilde;", "ó", $msCadena);
        $msCadena = str_replace("&utilde;", "ú", $msCadena);
        $msCadena = str_replace("&ntilde;", "ñ", $msCadena);
        $msCadena = str_replace("&Ntilde;", "Ñ", $msCadena);
        return $msCadena;
    }
?>