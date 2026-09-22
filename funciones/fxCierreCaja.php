<?php
	function fxGuardarCierreCaja($mdFecha, $msSerie, $mnConsecutivo, $mnBillete500, $mnBillete100, $mnBillete50,
	$mnBillete20, $mnBillete10,	$mnMoneda5, $mnMoneda1, $mnMoneda050, $mnMoneda025, $mnMoneda010, $mnEfectivoContado,
	$mnEfectivoSobrante, $mnEfectivo, $mnTarjeta, $mnCheque, $mnDeposito)
	{
		$m_cnx_MySQL = fxAbrirConexion();
		$msConsulta = "insert into KDSA044A (FECHA_044, SERIE_044, CONSECUTIVO_044, BILLETE500_044, BILLETE100_044, ";
		$msConsulta .= "BILLETE50_044, BILLETE20_044, BILLETE10_044, MONEDA5_044, MONEDA1_044, MONEDA050_044, MONEDA025_044, ";
		$msConsulta .= "MONEDA010_044, EFECTIVOCONTADO_044, EFECTIVOSOBRANTE_044, EFECTIVO_044, TARJETA_044, CHEQUES_044, DEPOSITO_044)";
		$msConsulta .= "values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$mDatos = $m_cnx_MySQL->prepare($msConsulta);
		$mDatos->execute([$mdFecha, $mnBillete500, $msSerie, $mnConsecutivo, $mnBillete100, $mnBillete50, $mnBillete20,
		$mnBillete10, $mnMoneda5, $mnMoneda1, $mnMoneda050, $mnMoneda025, $mnMoneda010, $mnEfectivoContado,
		$mnEfectivoSobrante, $mnEfectivo, $mnTarjeta, $mnCheque, $mnDeposito]);
	}
	
	function fxBorrarCierreCaja($mdFecha)
	{
		$m_cnx_MySQL = fxAbrirConexion();
		$msConsulta = "delete from KDSA044A where FECHA_044 = ?";
		$mDatos = $m_cnx_MySQL->prepare($msConsulta);
		$mDatos->execute([$mdFecha]);
	}
?>