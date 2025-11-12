<?php
	session_start();
	if (!isset($_SESSION["gnVerifica"]) or $_SESSION["gnVerifica"] != 1)
	{
		echo('<meta http-equiv="Refresh" content="0;url=index.php">');
		exit('');
    }
	
	include ("MasterWeb.php");
	require_once ("funciones/fxGeneral.php");
    $m_cnx_MySQL = fxAbrirConexion();
?>
    <div class="container">
        <div id="DivContenido">
        	<div class = "row">
            	<div class="col-xs-12 col-md-12">
            		<div class="degradado">
                		<strong><?php echo($_SESSION["gsNombre"]) ?></strong>
                    </div>
                </div>
            </div>
            
        	<div class = "row">
            	<div class="col-xs-4 col-md-2">
                	<div class="divBotonInicio">
    				<a href="catCursos.php"><img src="imagenes/btnCurso.png" style="border-radius:5%" width="100%" /></a>
                    </div>
            	</div>
                <div class="col-xs-4 col-md-2">
                	<div class="divBotonInicio">
    				<a href="gridEstudiantes.php"><img src="imagenes/btnAlumno.png" style="border-radius:5%" width="100%" /></a>
                    </div>
            	</div>
                <div class="col-xs-4 col-md-2">
                	<div class="divBotonInicio">
    				<a href="procCierreCaja.php"><img src="imagenes/btnCierreCaja.png" style="border-radius:5%" width="100%" /></a>
                    </div>
            	</div>
                <div class="col-xs-4 col-md-2">
                	<div class="divBotonInicio">
    				<a href="frmHojaMatricula.php"><img src="imagenes/btnImpMatricula.png" style="border-radius:5%" width="100%" /></a>
                    </div>
            	</div>
                <div class="col-xs-4 col-md-2">
                	<div class="divBotonInicio">
    				<a href="gridPagos.php"><img src="imagenes/btnPagos.png" style="border-radius:5%" width="100%" /></a>
                    </div>
             	</div>
                <div class="col-xs-4 col-md-2">
                	<div class="divBotonInicio">
	    			<a href="frmEstadoCuentas.php"><img src="imagenes/btnEstCta.png" style="border-radius:5%" width="100%" /></a>
                    </div>
            	</div>
            </div>
            
            <div class = "row" style="margin-top:2%">
                <div class="col-xs-12 col-md-6">
                    <div class = "row">
                    <div class="col-xs-12 col-md-12">
                        <div class="degradado">
                            <strong>Cursos Activos</strong>
                        </div>
                    </div>
                    </div>
                    
                    <div class = "row">
                    <div class="col-12">
                        <table id="dgActivos" class="easyui-datagrid table" width="100%" height="300px">
                        <thead>
                            <th data-options="field:'NOMBRE_020', width:'40%', align:'left'">Curso</th>
                            <th data-options="field:'FECHAINI_020', width:'20%', align:'center'">Fecha de Inicio</th>
                            <th data-options="field:'DIASCLASE', width:'20%', align:'left'">Días</th>
                            <th data-options="field:'HORARIO', width:'20%', align:'left'">Horario</th>
                        </thead>
                        <?php
                            $msConsulta = "select NOMBRE_020, FECHAINI_020, fxDevuelveDias(KDSA020A.CURSO_REL) as DIASCLASE, concat('De ', DATE_FORMAT(HORAINI_020, '%h:%i %p'), ' a ', DATE_FORMAT(HORAFIN_020, '%h:%i %p')) as HORARIO from KDSA020A where ACTIVO_020 = 1 and FECHAINI_020 <= date(NOW())";
                            $mDatos = $m_cnx_MySQL->prepare($msConsulta);
				            $mDatos->execute();
							while ($Fila = $mDatos->fetch())
							{
								echo ("<tr>");
								echo ("<td>" . $Fila["NOMBRE_020"] . "</td>");
								$fecha = date_create_from_format('Y-m-d', $Fila["FECHAINI_020"]);
								echo ("<td style='text-align:center'>" . date_format($fecha, 'd-m-Y') . "</td>");
                                echo ("<td>" . $Fila["DIASCLASE"] . "</td>");
                                echo ("<td>" . $Fila["HORARIO"] . "</td>");
								echo ("</tr>");
							}
                        ?>
                        </table>
                    </div>
                    </div>
                </div>
                
                <div class="col-xs-12 col-md-6">
                    <div class = "row">
                    <div class="col-xs-12 col-md-12">
                        <div class="degradado">
                            <strong>Próximos Cursos</strong>
                        </div>
                    </div>
                    </div>
                    
                    <div class = "row">
                    <div class="col-12">
                        <table id="dgProximos" class="easyui-datagrid table" width="100%" height="300px">
                        <thead>
                            <th data-options="field:'NOMBRE_020', width:'40%', align:'left'">Curso</th>
                            <th data-options="field:'FECHAINI_020', width:'20%', align:'center'">Fecha de Inicio</th>
                            <th data-options="field:'DIASCLASE', width:'20%', align:'left'">Días</th>
                            <th data-options="field:'HORARIO', width:'20%', align:'left'">Horario</th>
                        </thead>
                        <?php
							$msConsulta = "select NOMBRE_020, FECHAINI_020, fxDevuelveDias(KDSA020A.CURSO_REL) as DIASCLASE, concat('De ', DATE_FORMAT(HORAINI_020, '%h:%i %p'), ' a ', DATE_FORMAT(HORAFIN_020, '%h:%i %p')) as HORARIO from KDSA020A where ACTIVO_020 = 1 and FECHAINI_020 > date(NOW())";
                            $mDatos = $m_cnx_MySQL->prepare($msConsulta);
				            $mDatos->execute();
							while ($Fila = $mDatos->fetch())
							{
								echo ("<tr>");
								echo ("<td>" . $Fila["NOMBRE_020"] . "</td>");
								$fecha = date_create_from_format('Y-m-d', $Fila["FECHAINI_020"]);
								echo ("<td style='text-align:center'>" . date_format($fecha, 'd-m-Y') . "</td>");
								echo ("<td>" . $Fila["DIASCLASE"] . "</td>");
								echo ("<td>" . $Fila["HORARIO"] . "</td>");
								echo ("</tr>");
							}
                        ?>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
    	</div>
    </div>
</body>
</html>
<script>
    window.onload = function() {
        var dgA = $('#dgActivos');
        var dgP = $('#dgProximos');
        dgA.datagrid({striped: true});
        dgP.datagrid({striped: true});

        var tdA1 = dgA.datagrid('getPanel').find('div.datagrid-header td[field="NOMBRE_020"]');
        var tdA2 = dgA.datagrid('getPanel').find('div.datagrid-header td[field="FECHAINI_020"]');
        var tdA3 = dgA.datagrid('getPanel').find('div.datagrid-header td[field="DIASCLASE"]');
        var tdA4 = dgA.datagrid('getPanel').find('div.datagrid-header td[field="HORARIO"]');
        var tdP1 = dgP.datagrid('getPanel').find('div.datagrid-header td[field="NOMBRE_020"]');
        var tdP2 = dgP.datagrid('getPanel').find('div.datagrid-header td[field="FECHAINI_020"]');
        var tdP3 = dgP.datagrid('getPanel').find('div.datagrid-header td[field="DIASCLASE"]');
        var tdP4 = dgP.datagrid('getPanel').find('div.datagrid-header td[field="HORARIO"]');
        tdA1.css({'background-color':'#ff0000', 'color':'#ffffff'});
        tdA2.css({'background-color':'#ff0000', 'color':'#ffffff'});
        tdA3.css({'background-color':'#ff0000', 'color':'#ffffff'});
        tdA4.css({'background-color':'#ff0000', 'color':'#ffffff'});
        tdP1.css({'background-color':'#ff0000', 'color':'#ffffff'});
        tdP2.css({'background-color':'#ff0000', 'color':'#ffffff'});
        tdP3.css({'background-color':'#ff0000', 'color':'#ffffff'});
        tdP4.css({'background-color':'#ff0000', 'color':'#ffffff'});
	}
</script>