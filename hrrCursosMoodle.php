<?php
	session_start();
	if (!isset($_SESSION["gnVerifica"]) or $_SESSION["gnVerifica"] != 1)
	{
		echo('<meta http-equiv="Refresh" content="0;url=index.php">');
		exit('');
    }
	
	include ("MasterWeb.php");
	require_once ("funciones/fxGeneral.php");
	require_once ("funciones/fxUsuarios.php");
	require_once ("funciones/fxCursos.php");
	$m_cnx_MySQL = fxAbrirConexion();
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
		?>
    	<div class="container">
        	<div id="DivContenido">
				<div id="lateral">
					<?php
						echo('<label id="excel" data-toggle="tooltip" data-placement="top" title="Exportar"><img src="imagenes/btnLateralExcel.png" height="80%" style="cursor:pointer" /></label>');
					?>
				</div>

				<div class="row">
					<div class="col-md-12">
						<button id="export" type="button" class="btn btn-warning" >Exportar a Moodle</button>
						
						<div id="tbCursos" class="easyui-tabs tabs-narrow" style="width:100%;height:auto">
							<div title="Activos" style="padding:10px">
								<table id="grid1" class="table table-condensed table-hover table-striped" data-selection="true" data-multi-select="false" data-row-select="true" data-keep-selection="true" style="font-size:small">
									<thead>
										<tr>
											<th data-column-id="CURSO_REL" data-identifier="true" data-order="desc"  data-align="left" data-header-align="left" data-width="10%">Curso</th>
											<th data-column-id="NOMBRE_020" data-align="left" data-header-align="left">Nombre del Curso</th>
											<th data-column-id="FECHAINI_020" data-align="center" data-header-align="center" data-width="12%">Fecha inicial</th>
											<th data-column-id="FECHAFIN_020" data-align="center" data-header-align="center" data-width="12%">Fecha final</th>
											<th data-column-id="HORARIO" data-align="left" data-header-align="left" data-width="18%">Horario</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$msConsulta = "select CURSO_REL, NOMBRE_020, CONVOCATORIA_020, GRUPO_020, FECHAINI_020, FECHAFIN_020, HORAINI_020, HORAFIN_020 from KDSA020A where ACTIVO_020 = 1 and FECHAINI_020 < CURDATE()";
										$mDatos = $m_cnx_MySQL->prepare($msConsulta);
										$mDatos->execute();

										while ($Fila = $mDatos->fetch())
										{
											echo ("<tr>");
											echo ("<td>" . $Fila["CURSO_REL"] . "</td>");
											echo ("<td>" . $Fila["NOMBRE_020"] . " (" . trim($Fila["CONVOCATORIA_020"]) . " / G" . trim($Fila["GRUPO_020"]) . ")</td>");
											$fecha = date_create_from_format('Y-m-d', $Fila["FECHAINI_020"]);
											echo ("<td>" . date_format($fecha, 'd-m-Y') . "</td>");
											$fecha = date_create_from_format('Y-m-d', $Fila["FECHAFIN_020"]);
											echo ("<td>" . date_format($fecha, 'd-m-Y') . "</td>");
											$horaIni = date_create($Fila["HORAINI_020"]);
											$horaFin = date_create($Fila["HORAFIN_020"]);
											echo ("<td>De " . date_format($horaIni, 'h:i a') . " a " . date_format($horaFin, 'h:i a') . "</td>");
											echo ("</tr>");
										}
									?>
									</tbody>
								</table>
							</div>

							<div title="En matrícula" style="padding:10px">
								<table id="grid2" class="table table-condensed table-hover table-striped" data-selection="true" data-multi-select="false" data-row-select="true" data-keep-selection="true" style="font-size:small">
									<thead>
										<tr>
											<th data-column-id="CURSO_REL" data-identifier="true" data-order="desc"  data-align="left" data-header-align="left" data-width="10%">Curso</th>
											<th data-column-id="NOMBRE_020" data-align="left" data-header-align="left">Nombre del Curso</th>
											<th data-column-id="FECHAINI_020" data-align="center" data-header-align="center" data-width="12%">Fecha inicial</th>
											<th data-column-id="FECHAFIN_020" data-align="center" data-header-align="center" data-width="12%">Fecha final</th>
											<th data-column-id="HORARIO" data-align="left" data-header-align="left" data-width="18%">Horario</th>
										</tr>
									</thead>
									<tbody>
									<?php
										$msConsulta = "select CURSO_REL, NOMBRE_020, CONVOCATORIA_020, GRUPO_020, FECHAINI_020, FECHAFIN_020, HORAINI_020, HORAFIN_020 from KDSA020A where ACTIVO_020 = 1 and FECHAINI_020 >= CURDATE()";
										$mDatos = $m_cnx_MySQL->prepare($msConsulta);
										$mDatos->execute();
										
										while ($Fila = $mDatos->fetch())
										{
											echo ("<tr>");
											echo ("<td>" . $Fila["CURSO_REL"] . "</td>");
											echo ("<td>" . $Fila["NOMBRE_020"] . " (" . trim($Fila["CONVOCATORIA_020"]) . " / G" . trim($Fila["GRUPO_020"]) . ")</td>");
											$fecha = date_create_from_format('Y-m-d', $Fila["FECHAINI_020"]);
											echo ("<td>" . date_format($fecha, 'd-m-Y') . "</td>");
											$fecha = date_create_from_format('Y-m-d', $Fila["FECHAFIN_020"]);
											echo ("<td>" . date_format($fecha, 'd-m-Y') . "</td>");
											$horaIni = date_create($Fila["HORAINI_020"]);
											$horaFin = date_create($Fila["HORAFIN_020"]);
											echo ("<td>De " . date_format($horaIni, 'h:i a') . " a " . date_format($horaFin, 'h:i a') . "</td>");
											echo ("</tr>");
										}
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
    	</div>
<?php }}?>
<script src="bootstrap/lib/jquery-1.11.1.min.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<script src="bootstrap/dist/jquery.bootgrid.js"></script>
<script src="bootstrap/dist/jquery.bootgrid.fa.js"></script>
<script src="js/jquery.redirect.js"></script>
<script>
	$(window).scroll(function() {
		var scroll = $(window).scrollTop();
		if (scroll >= 100) {
		$("#lateral").addClass("entra");
		} else {
		$("#lateral").removeClass("entra");
		}
	});

	$(function() {
		function init() {
			$("#grid1").bootgrid({
				formatters: {
					"link": function(column, row) {
						return "<a href=\"#\">" + column.id + ": " + row.id + "</a>";
					}
				},
				rowCount: [-1, 10, 50, 75]
			});
		}

		init();

		$("#grid1").bootgrid().on("click.rs.jquery.bootgrid", function() {
            $("#grid2").bootgrid("deselect");
        });

		$("#excel").on("click", function() {
			if ($.trim($("#grid1").bootgrid("getSelectedRows")) != "")
			{
				var codCurso = $.trim($("#grid1").bootgrid("getSelectedRows"));
				$.redirect("exlMoodle.php", {KDSA: codCurso}, "POST");
			}
		});

		$("#export").on("click", function() {
			if ($.trim($("#grid1").bootgrid("getSelectedRows")) != "")
			{
				var codCurso = $.trim($("#grid1").bootgrid("getSelectedRows"));
				$.redirect("exlMoodle.php", {KDSA: codCurso}, "POST");
			}
		});
	});

	$(function() {
		function init() {
			$("#grid2").bootgrid({
				formatters: {
					"link": function(column, row) {
						return "<a href=\"#\">" + column.id + ": " + row.id + "</a>";
					}
				},
				rowCount: [-1, 10, 50, 75]
			});
		}

		init();

		$("#grid2").bootgrid().on("click.rs.jquery.bootgrid", function() {
            $("#grid1").bootgrid("deselect");
        });

		$("#excel").on("click", function() {
			if ($.trim($("#grid2").bootgrid("getSelectedRows")) != "")
			{
				var codCurso = $.trim($("#grid2").bootgrid("getSelectedRows"));
				$.redirect("exlMoodle.php", {KDSA: codCurso}, "POST");
			}
		});
			
		$("#export").on("click", function() {
			if ($.trim($("#grid2").bootgrid("getSelectedRows")) != "")
			{
				var codCurso = $.trim($("#grid2").bootgrid("getSelectedRows"));
				$.redirect("exlMoodle.php", {KDSA: codCurso}, "POST");
			}
		});
	});
</script>
</body>
</html>