<?php
	session_start();
	if (!isset($_SESSION["gnVerifica"]) or $_SESSION["gnVerifica"] != 1)
	{
		echo('<meta http-equiv="Refresh" content="0;url=index.php"/>');
		exit('');
	}
	
	include ("MasterWeb.php");
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
		$PermisoUsuario = fxPermisoUsuario("repMatRecibo", $mbAgregar, $mbModificar, $mbBorrar, $mbAnular);
		
		if ($Administrador == 0 and $PermisoUsuario == 0)
		{?>
        <div class="container text-center">
            <div id="DivContenido">
                <img src="imagenes/errordeacceso.png"/>
            </div>
        </div>
		<?php }
		else
			{
				$FechaIni = date("Y-m-d", time());
				$FechaFin = date("Y-m-d", time());
			?>
			<div class="container">
            <div id="DivContenido">
			<div class = "row">
				<div class="col-xs-12 col-md-11">
					<div class="degradado"><strong>Matriculados por período (Financiero)</strong></div>
				</div>
			</div>

            <div class="row">
            	<div class="col-xs-12 col-xs-offset-none col-md-12 col-md-offset-2">
                    <form name="frmMatPeriodo">
						<div class = "form-group row">
                            <label for="optTipo" class="col-sm-12 col-md-2 form-label">Tipo de reporte</label>
                            <div class="col-sm-12 col-md-6">
                                <div class = "radio">
                                    <input type="radio" id="optTipo1" name="optTipo" value="0" checked /> Con recibos
                                    <input type="radio" id="optTipo2" name="optTipo" value="1" /> Por tipo de ingreso
                                </div>
                            </div>
                        </div>

						<div class = "form-group row">
							<label for="dtpFechaIni" class="col-sm-12 col-md-2 col-form-label">Fecha inicial</label>
                            <div class="col-sm-12 col-md-3">
                            	<?php echo('<input type="date" class="form-control" id="dtpFechaIni" name="dtpFechaIni" value="' . $FechaIni . '" />');?>
                            </div>
                        </div>
                        
                        <div class = "form-group row">
							<label for="dtpFechaFin" class="col-sm-12 col-md-2 col-form-label">Fecha final</label>
                            <div class="col-sm-12 col-md-3">
                            	<?php echo('<input type="date" class="form-control" id="dtpFechaFin" name="dtpFechaFin" value="' . $FechaFin . '" />');?>
                            </div>
                        </div>
                        
                        <div class = "row">
                            <div class="col-auto col-xs-offset-none col-md-2 col-md-offset-2">
                                <input type="submit" id="Imprimir" name="Imprimir" value="Imprimir" class="btn btn-warning" />
                            </div>
                        </div>
                    </form>			
			<?php	}
			}
		?>
        	</div>
        </div>
		</div>
        </div>
</body>
</html>
<script>
	$('form').submit(function(e) {
		e.preventDefault();

		if (verificarFormulario() == true){
			var fechaIni = document.getElementById('dtpFechaIni').value;
			var fechaFin = document.getElementById('dtpFechaFin').value;

			if (document.getElementById('optTipo1').checked == true)
				$.redirect("repMatriculadosRec.php", {dtpFechaIni: fechaIni, dtpFechaFin: fechaFin}, "POST", "_blank");
			else
				$.redirect("repMatriculadosIng.php", {dtpFechaIni: fechaIni, dtpFechaFin: fechaFin}, "POST", "_blank");
		}
	})

	function verificarFormulario()
	{
		if (document.getElementById('dtpFechaIni').value > document.getElementById('dtpFechaFin').value)
		{
			$.messager.alert('KDSA','La Fecha Inicial es mayor que la Final.','warning');
			return false;
		}
		
		return true;
	}
</script>