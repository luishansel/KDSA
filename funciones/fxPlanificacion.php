<?php
	function fxGuardarPlanificacion($msModulo, $mdFecha)
	{
		$m_cnx_MySQL = fxAbrirConexion();
		$msConsulta = "Select ifnull(mid(max(PLANIFICACION_REL), 3), 0) as Ultimo from KDSA120A";
		$mDatos = $m_cnx_MySQL->prepare($msConsulta);
		$mDatos->execute();
		$Fila = $mDatos->fetch();
		$Numero = intval($Fila["Ultimo"]);
		$Numero += 1;
		$Longitud = strlen($Numero);
		$msCodigo = "PL" . str_repeat("0", 8 - $Longitud) . trim($Numero);
		$msConsulta = "insert into KDSA120A (PLANIFICACION_REL, MODULO_REL, FECHA_120) values(?, ?, ?)";
		$mDatos = $m_cnx_MySQL->prepare($msConsulta);
		$mDatos->execute([$msCodigo, $msModulo, $mdFecha]);
		return ($msCodigo);
	}

	function fxDevuelvePlanificacion($msCodigo)
	{
		$m_cnx_MySQL = fxAbrirConexion();

		$msConsulta = "select PLANIFICACION_REL, MODULO_REL, FECHA_120 from KDSA120A where KDSA120A.MODULO_REL = ?";
		$mDatos = $m_cnx_MySQL->prepare($msConsulta);
		$mDatos->execute([$msCodigo]);
		return $mDatos;
	}
	
	function fxBorrarPlanificacion($msCodigo)
	{
		$m_cnx_MySQL = fxAbrirConexion();
		$msConsulta = "delete from KDSA120A where PLANIFICACION_REL = ?";
		$mDatos = $m_cnx_MySQL->prepare($msConsulta);
		$mDatos->execute([$msCodigo]);
	}
	
	function fxDevuelveDetPlanificacion($msCodigo)
	{
		$m_cnx_MySQL = fxAbrirConexion();

		$msConsulta = "select KDSA121A.PLANIFICACION_REL, DETPLANIFICACION_REL, FECHA_121, UNIDAD_121, CONTENIDO_121, OBJETIVOS_121, ACTIVIDADES_121, RECURSOS_121, EVALUACION_121, ESTADO_121 ";
		$msConsulta .= "from KDSA120A, KDSA121A where KDSA120A.PLANIFICACION_REL = KDSA121A.PLANIFICACION_REL and KDSA120A.PLANIFICACION_REL = ? order by FECHA_121, DETPLANIFICACION_REL";
		$mDatos = $m_cnx_MySQL->prepare($msConsulta);
		$mDatos->execute([$msCodigo]);
		return $mDatos;
	}

	function fxBorrarDetPlanificacion($msCodigo)
	{
		$m_cnx_MySQL = fxAbrirConexion();
		$msConsulta = "delete from KDSA121A where PLANIFICACION_REL = ?";
		$mDatos = $m_cnx_MySQL->prepare($msConsulta);
		$mDatos->execute([$msCodigo]);
	}

	function fxGuardarDetPlanificacion($msCodigo, $mnDetalle, $mdFecha, $msUnidad, $msContenido, $msObjetivos, $msActividades, $msRecursos, $msEvaluacion, $mnEstado)
	{
		$m_cnx_MySQL = fxAbrirConexion();
		$msConsulta = "insert into KDSA121A (PLANIFICACION_REL, DETPLANIFICACION_REL, FECHA_121, UNIDAD_121, CONTENIDO_121, OBJETIVOS_121, ACTIVIDADES_121, RECURSOS_121, EVALUACION_121, ESTADO_121) ";
		$msConsulta .= "values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$mDatos = $m_cnx_MySQL->prepare($msConsulta);
		$mDatos->execute([$msCodigo, $mnDetalle, $mdFecha, $msUnidad, $msContenido, $msObjetivos, $msActividades, $msRecursos, $msEvaluacion, $mnEstado]);
	}
?>