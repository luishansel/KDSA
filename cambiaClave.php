<?php
    set_time_limit(300);
    require_once ("funciones/fxGeneral.php");
	$m_cnx_MySQL = fxAbrirConexion();

    $msConsulta = "select USUARIO_REL from KDSA002A order by USUARIO_REL";
	$mDatos = $m_cnx_MySQL->prepare($msConsulta);
    $mDatos->execute();

    while ($Fila = $mDatos->fetch())
    {
        $msUsuario = $Fila["USUARIO_REL"];
        $msClave = crypt($msUsuario, '_appwKDSA');
        $msConsulta = "update KDSA002A set CLAVE_002 = ? where USUARIO_REL = ?";
        $m_Auxiliar = $m_cnx_MySQL->prepare($msConsulta);
        $m_Auxiliar->execute([$msClave, $msUsuario]);
    }
    echo('<script>Completado</script>');
?>