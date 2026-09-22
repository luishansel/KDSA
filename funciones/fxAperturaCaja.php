<?php
	function fxGuardarAperturaCaja($mdFecha, $msSerie, $mnConsecutivo, $mnMontoApertura)
	{
		$m_cnx_MySQL = fxAbrirConexion();
		$msConsulta = "insert into KDSA045A (FECHA_045, SERIE_045, CONSECUTIVO_045, MONTO_045)";
		$msConsulta .= "values(?, ?, ?, ?)";
		$mDatos = $m_cnx_MySQL->prepare($msConsulta);
		$mDatos->execute([$mdFecha, $msSerie, $mnConsecutivo, $mnMontoApertura]);
	}
	
	function fxBorrarAperturaCaja($mdFecha)
	{
		$m_cnx_MySQL = fxAbrirConexion();
		$msConsulta = "delete from KDSA045A where FECHA_045 = ?";
		$mDatos = $m_cnx_MySQL->prepare($msConsulta);
		$mDatos->execute([$mdFecha]);
	}
?>