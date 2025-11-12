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
	require_once ("funciones/fxProformas.php");
    $m_cnx_MySQL = fxAbrirConexion();
	$Registro = fxVerificaUsuario();
	
	if ($Registro == 0)
	{
?>

<div class="container text-center">
    <div id="DivContenido">
        <img src="imagenes/errordeacceso.png" />
    </div>
</div>
<?php }
	else
	{
		$Administrador = fxVerificaAdministrador();
		$PermisoUsuario = fxPermisoUsuario("procProformas");
		
		if ($Administrador == 0 and $PermisoUsuario == 0)
		{?>
<div class="container text-center">
    <div id="DivContenido">
        <img src="imagenes/errordeacceso.png" />
    </div>
</div>
<?php }
		else
		{
			if (isset($_POST["txtCodProforma"]))
			{
				$mnOperacion = $_POST["Operacion"];
				$msCodigo = $_POST["txtCodProforma"];
				$msProspecto = $_POST["txtProspecto"];
				$mdFecha = $_POST["dtpFecha"];
                $mbInatec = $_POST["optInatec"];
                $msLugar = $_POST["txtLugar"];
                $mnTipoCambio = $_POST["txnTipoCambio"];
                $mnMoneda = $_POST["optMoneda"];
                $mnDescuento = $_POST["txnDescuento"];
                $msObservaciones = $_POST["txtObservaciones"];
                if (isset($_POST["gridDetalle"]))
                    $gridDetalle = $_POST["gridDetalle"];
                if (isset($_POST["gridOtros"]))
                    $gridOtros = $_POST["gridOtros"];
                if (isset($_POST["gridObservaciones"]))
                    $gridObservaciones = $_POST["gridObservaciones"];
				{
					if ($mnOperacion == 0)
					{
						$msCodigo = fxGuardarProformas ($msProspecto, $mdFecha, $mbInatec, $mnTipoCambio, $mnMoneda, $mnDescuento, $msLugar, $msObservaciones);
						fxAgregarBitacora ($_SESSION["gsUsuario"], "KDSA090A", $msCodigo, "", "Agregar");
					}
					else
					{
						fxModificarProformas ($msCodigo, $msProspecto, $mdFecha, $mbInatec, $mnTipoCambio, $mnMoneda, $mnDescuento, $msLugar, $msObservaciones);
						fxBorrarDetProformas ($msCodigo);
                        fxBorrarOtroDetProformas ($msCodigo);
                        fxBorrarObsDetProformas($msCodigo);
						fxAgregarBitacora ($_SESSION["gsUsuario"], "KDSA090A", $msCodigo, "", "Modificar");
					}
				}
                
                if (isset($gridDetalle))
                {
                    foreach($gridDetalle as $Registro)
                    {
                        $Curso = $Registro['curso'];
                        $Cantidad = $Registro['cantidad'];
                        fxGuardarDetProformas ($msCodigo, $Curso, $Cantidad);
                    }
                }

                if (isset($gridOtros))
                {
                    $itemId = 1;
                    foreach($gridOtros as $Registro)
                    {
                        $CursoKdsa = $Registro['cursoKdsa'];
                        $CursoInatec = $Registro['cursoInatec'];
                        $DiasClase = $Registro['diasClase'];
                        $Horario = $Registro['horario'];
                        $mdFechaIni = $Registro['fechaIni'];
                        $mdFechaFin = $Registro['fechaFin'];
                        $HorasClase = $Registro['horasclase'];
                        $CodInatec = $Registro['codInatec'];
                        $Acuerdo = $Registro['acuerdo'];
                        $Precio = $Registro['precio'];
                        $Cupos = $Registro['cupos'];
                        $Total = $Registro['total'];
                        fxGuardarOtroDetProformas ($msCodigo, $itemId, $CursoKdsa, $CursoInatec, $DiasClase, $Horario, $mdFechaIni, $mdFechaFin, $HorasClase, $CodInatec, $Acuerdo, $Precio, $Cupos, $Total);
                        $itemId++;
                    }
                }

                if (isset($gridObservaciones))
                {
                    $itemId = 1;
                    foreach($gridObservaciones as $Registro)
                    {
                        $Observacion = $Registro['observacion'];
                        fxGuardarObsDetProformas ($msCodigo, $itemId, $Observacion);
                        $itemId++;
                    }
                }
								
				?>
                <meta http-equiv="Refresh" content="0;url=gridProformas.php" /><?php
			}
			else
			{
				$mnOperacion = $_POST["mOperacion"];
				
				if ($mnOperacion == 0)
				{
					$msCodigo = "";
					$msProspecto = "";
				}
				
				if ($mnOperacion == 1)
				{
					$msCodigo = $_POST["mCodigo"];
					$msProspecto = "";
				}
				
				if ($mnOperacion == 2)
				{
					$msCodigo = "";
					$msProspecto = $_POST["mProspecto"];
				}

				$RecordSet = fxDevuelveProformas (0, $msCodigo);
				$Fila = $RecordSet->fetch();
				
				if ($mnOperacion != 2)
                {
                    if ($msCodigo == "")
                        $msProspecto = "";
                    else
					    $msProspecto = $Fila["PROSPECTO_REL"];
                }

                if ($msProspecto == "")
                {
                    $NomProspecto = "";
                    $mdFecha = "";
                    $mbInatec = "";
                    $mnTipoCambio = 0;
                    $mnMoneda = 0;
                    $mnDescuento = 0;
                    $msLugar = "Instalaciones de KDSA";
                    $msObservaciones = "";
                }
                else
                {
                    $msConsulta = "select NOMBRE_060 from KDSA060A where PROSPECTO_REL = ?";
                    $mDatos = $m_cnx_MySQL->prepare($msConsulta);
				    $mDatos->execute([$msProspecto]);
                    $fAux = $mDatos->fetch();
				    $NomProspecto = $fAux["NOMBRE_060"];
                    if ($msCodigo == "")
                    {
                        $mdFecha = "1900-01-01";
                        $mbInatec = "";
                        $mnTipoCambio = 0;
                        $mnMoneda = 0;
                        $mnDescuento = 0;
                        $msLugar = "Instalaciones de KDSA";
                        $msObservaciones = "";
                    }
                    else
                    {
                        $mdFecha = $Fila["FECHA_090"];
                        $mbInatec = $Fila["INATEC_090"];
                        $mnTipoCambio = $Fila["TIPOCAMBIO_090"];
                        $mnMoneda = $Fila["MONEDA_090"];
                        $mnDescuento = $Fila["DESCUENTO_090"];
                        $msLugar = $Fila["LUGAR_090"];
                        $msObservaciones = $Fila["OBSERVACIONES_090"];
                    }
                }
	?>
<div class="container text-left">
    <div id="DivContenido">
        <div class = "row">
            <div class="col-xs-12 col-md-11">
                <div class="degradado"><strong>Proformas</strong></div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12 col-xs-offset-none col-md-12">
                <form id="procProformas" name="procProformas">
                    <div class="form-group row">
                        <label for="txtCodProforma" class="col-sm-12 col-md-2 col-form-label">Código de la Proforma</label>
                        <div class="col-sm-12 col-md-3">
                            <?php echo('<input type="text" class="form-control" id="txtCodProforma" name="txtCodProforma" value="' . $msCodigo . '" readonly />'); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="txtProspecto" class="col-sm-12 col-md-2 col-form-label">Prospecto</label>
                        <div class="col-sm-12 col-md-3">
                            <?php echo('<input type="text" class="form-control" id="txtProspecto" name="txtProspecto" value="' . $msProspecto . '" onblur="escribeProspecto()" />');?>
                        </div>
                        <br />
                        <div class="col-sm-auto col-md-7 col-sm-offset-none col-md-offset-2">
                            <?php echo('<input type="text" class="form-control" id="txtNomProspecto" name="txtNomProspecto" value="' . $NomProspecto . '" readonly />');?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="dtpFecha" class="col-sm-12 col-md-2 col-form-label">Fecha</label>
                        <div class="col-sm-12 col-md-2">
                            <?php
							if ($msCodigo == "")
								echo('<input type="date" class="form-control" id="dtpFecha" name="dtpFecha" value="' . date("Y-m-d") . '" />');
							else
								echo('<input type="date" class="form-control" id="dtpFecha" name="dtpFecha" value="' . $mdFecha . '" />');
						?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="txnTipoCambio" class="col-sm-12 col-md-2 col-form-label">Tipo de cambio</label>
                        <div class="col-sm-12 col-md-2">
                            <?php
							if ($msCodigo == "")
								echo('<input type="number" step="0.0001" style="text-align:right" class="form-control" id="txnTipoCambio" name="txnTipoCambio" value="0" />');
							else
								echo('<input type="number" step="0.0001" style="text-align:right" class="form-control" id="txnTipoCambio" name="txnTipoCambio" value="' . $mnTipoCambio . '" />');
						?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="optMoneda" class="col-sm-12 col-md-2 form-label">
                            Moneda<p style="color:rgb(150,150,150)"><i><small>Para cursos que no están en el Catálogo</small></i></p>
                        </label>
                        <div class="col-sm-12 col-md-4">
                            <div class="radio">
                                <?php
                                    if ($mnMoneda == 0 or $msCodigo == "")
                                        echo ('<input type="radio" id="optMoneda1" name="optMoneda" value="0" checked /> Córdobas ');
                                    else
                                        echo ('<input type="radio" id="optMoneda1" name="optMoneda" value="0" /> Córdobas ');
                                        
                                    if ($mnMoneda == 1)
                                        echo ('<input type="radio" id="optMoneda2" name="optMoneda" value="1" checked /> Dólares');
                                    else
                                        echo ('<input type="radio" id="optMoneda2" name="optMoneda" value="1" /> Dólares'); 
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="txnDescuento" class="col-sm-12 col-md-2 col-form-label">Descuento</label>
                        <div class="col-sm-12 col-md-2">
                            <?php
                                echo('<input type="number" step="0.01" style="text-align:right" class="form-control" id="txnDescuento" name="txnDescuento" value="' . $mnDescuento . '" />');
                            ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="optInatec" class="col-sm-12 col-md-2 form-label">Para INATEC</label>
                        <div class="col-sm-12 col-md-4">
                            <div class="radio">
                                <?php
                                if ($mbInatec == 1)
                                    echo('<input type="radio" id="optInatec1" name="optInatec" value="0" /> No <input type="radio" id="optInatec2" name="optInatec" value="1" checked /> Si');
                                else
                                    echo('<input type="radio" id="optInatec1" name="optInatec" value="0" checked /> No <input type="radio" id="optInatec2" name="optInatec" value="1" /> Si');
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="txtLugar" class="col-sm-12 col-md-2 col-form-label">Lugar de la capacitación</label>
                        <div class="col-sm-auto col-md-7">
                            <?php echo('<input type="text" class="form-control" id="txtLugar" name="txtLugar" value="' . $msLugar . '" />'); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="dgDET" class="col-sm-12 col-md-2 form-label">Cursos de interés</label>
                        <div class="col-sm-auto col-md-10">
                            <select class="form-control" id="cboCurso" name="cboCurso">
                                <?php
									$msConsulta = "select CURSO_REL, concat(NOMBRE_020, ' (', CONVOCATORIA_020, '/G', GRUPO_020, ')') as NOMBRE_020 from KDSA020A where ACTIVO_020 = 1 order by NOMBRE_020 desc";
                                    $mDatos = $m_cnx_MySQL->prepare($msConsulta);
				                    $mDatos->execute();
                                    while ($Fila = $mDatos->fetch())
                                    {
                                        $Valor = rtrim($Fila["CURSO_REL"]);
                                        $Texto = $Fila["NOMBRE_020"];
                                       	echo("<option value='" . $Valor . "'>" . $Texto . "</option>");
                                    }
                                ?>
                            </select>
                            <div id="dvDET">
                                <table id="dgDET" class="easyui-datagrid table" data-options="iconCls:'icon-edit', toolbar:'#tbDET', singleSelect:true, method:'get', onClickCell: onClickCell">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'curso',width:'20%',align:'left'">Curso</th>
                                            <th data-options="field:'nombre',width:'70%',align:'left'">Nombre</th>
                                            <th data-options="field:'cantidad',width:'10%',align:'right',editor:{type:'numberbox'}">Cupos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $mDatos = fxDevuelveDetProformas($msCodigo);
                                            while ($Fila = $mDatos->fetch())
                                            {
                                                echo('<tr>');
                                                echo('<td>' . rtrim($Fila['CURSO_REL']) . '</td>');
                                                echo('<td>' . rtrim($Fila['NOMBRE_020']) . '</td>');
                                                echo('<td>' . rtrim($Fila['CANTIDAD_091']) . '</td>');
                                                echo('</tr>');
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="tbDET" style="height:auto">
                        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="append()">Agregar</a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeit()">Borrar</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="acceptit()">Aceptar</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="reject()">Deshacer</a>
                    </div>

                    <div class="form-group row">
                        <label for="dgPF" class="col-sm-12 col-md-2 form-label">Otros cursos de interés
                            <p style="color:rgb(150,150,150)"><i><small>Cursos que no están en el Catálogo</small></i></p></label>
                        <div class="col-sm-auto col-md-10">
                            <div id="dvPF">
                                <table id="dgPF" class="easyui-datagrid table"
                                    data-options="iconCls:'icon-edit', toolbar:'#tbPF', footer:'#ftPF', singleSelect:true, method:'get', onClickCell: onClickCellPF">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'cursoKdsa',align:'left',editor:'text'">Curso KDSA</th>
                                            <th data-options="field:'cursoInatec',align:'left',editor:'text'">Curso INATEC</th>
                                            <th data-options="field:'diasClase',align:'left',editor:'text'">Días de clase</th>
                                            <th data-options="field:'horario',align:'left',editor:'text'">Horario</th>
                                            <th data-options="field:'fechaIni',align:'left',editor:{type:'datebox'}">Fecha Inicial</th>
                                            <th data-options="field:'fechaFin',align:'left',
                                            editor:
                                                {type:'datebox', 
                                                options:
                                                {
                                                    formatter:function(date) {
                                                        var y = date.getFullYear();
                                                        var m = date.getMonth() + 1;
                                                        var d = date.getDate();
                                                        return y + '/' + (m < 10 ? ('0' + m) : m) + '/' + (d < 10 ? ('0' + d) : d);
                                                    },
                                                    parser:function(s) {
                                                        if (!s) return new Date();
                                                        var ss = (s.split('-'));
                                                        var y = parseInt(ss[0], 10);
                                                        var m = parseInt(ss[1], 10);
                                                        var d = parseInt(ss[2], 10);
                                                        if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
                                                            return new Date(y, m - 1, d);
                                                        } else {
                                                            return new Date();
                                                        }
                                                    }
                                                }
                                            }">Fecha Final</th>
											<th data-options="field:'horasclase',align:'right',editor:{type:'numberbox'}">Horas-clase</th>
                                            <th data-options="field:'codInatec',align:'left',editor:'text'">Código INATEC</th>
                                            <th data-options="field:'acuerdo',align:'left',editor:'text'">Acuerdo INATEC</th>
                                            <th data-options="field:'precio',align:'right',editor:{type:'numberbox',options:{precision:2}}">Precio</th>
                                            <th data-options="field:'cupos',align:'right',editor:{type:'numberbox'}">Cupos</th>
                                            <th data-options="field:'total',align:'right',editor:{type:'numberbox',options:{precision:2}}">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $mDatos = fxDevuelveOtroDetProformas($msCodigo);
                                            while ($Fila = $mDatos->fetch())
                                            {
                                                echo('<tr>');
                                                echo('<td>' . rtrim($Fila['CURSOKDSA_092']) . '</td>');
                                                echo('<td>' . rtrim($Fila['CURSOINATEC_092']) . '</td>');
                                                echo('<td>' . rtrim($Fila['DIASCLASE_092']) . '</td>');
                                                echo('<td>' . rtrim($Fila['HORARIO_092']) . '</td>');
                                                echo('<td>' . rtrim($Fila['FECHAINI_092']) . '</td>');
                                                echo('<td>' . rtrim($Fila['FECHAFIN_092']) . '</td>');
                                                echo('<td>' . rtrim($Fila['HORASCLASE_092']) . '</td>');
                                                echo('<td>' . rtrim($Fila['CODIGOINATEC_092']) . '</td>');
                                                echo('<td>' . rtrim($Fila['ACUERDO_092']) . '</td>');
                                                echo('<td>' . rtrim($Fila['PRECIO_092']) . '</td>');
                                                echo('<td>' . rtrim($Fila['CUPOS_092']) . '</td>');
                                                echo('<td>' . rtrim($Fila['TOTAL_092']) . '</td>');
                                                echo('</tr>');
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div id="tbPF" style="height:auto; padding-top:1%; padding-bottom:2%">
                        <table width="100%">
                            <tr>
                                <td style="width:25%">Nombre de curso (KDSA)</td>
                                <td><input type="text" id="txtCursoKdsa" style="width:80%"></td>
                            </tr>
                            <tr>
                                <td>Nombre de curso (INATEC)</td>
                                <td><input type="text" id="txtCursoInatec" style="width:80%"></td>
                            </tr>
                            <tr>
                                <td>Días de clase</td>
                                <td><input type="text" id="txtDias" style="width:60%"></td>
                            </tr>
                            <tr>
                                <td>Horario</td>
                                <td><input type="text" id="txtHorario" style="width:60%"></td>
                            </tr>
                            <tr>
                                <td>Fecha Inicial</td>
                                <td><input type="date" id="dtpFechaIni" style="width:25%"></td>
                            </tr>
                            <tr>
                                <td>Fecha final</td>
                                <td><input type="date" id="dtpFechaFin" style="width:25%"></td>
                            </tr>
							<tr>
                                <td>Horas-clase</td>
                                <td><input type="number" id="txnHorasClase" style="width:25%; text-align:right"></td>
                            </tr>
                            <tr>
                                <td>Código INATEC</td>
                                <td><input type="text" id="txtCodInatec" style="width:25%"></td>
                            </tr>
                            <tr>
                                <td>Acuerdo INATEC</td>
                                <td><input type="text" id="txtAcuerdo" style="width:25%"></td>
                            </tr>
                            <tr>
                                <td>Precio</td>
                                <td><input type="number" id="txnPrecio" step="0.01" style="width:25%; text-align:right" onchange="calcularTotal()"></td>
                            </tr>
                            <tr>
                                <td>Cupos</td>
                                <td><input type="number" id="txnCupos" style="width:25%; text-align:right" onchange="calcularTotal()"></td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td><input type="number" id="txnTotal" step="0.01" style="width:25%; text-align:right"></td>
                            </tr>
                        </table>
                    </div>

                    <div id="ftPF" style="height:auto">
                        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="appendPF()">Agregar</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeitPF()">Borrar</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="acceptitPF()">Aceptar</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="rejectPF()">Deshacer</a>
                    </div>

                    <div class="form-group row">
                        <label for="dgOBS" class="col-sm-12 col-md-2 form-label">Observaciones particulares</label>
                        <div class="col-sm-auto col-md-10">
                            <input type="text" class="form-control" id="txtDetObs" name="txtDetObs" maxlength="300" placeholder="Escriba una observación" value="" />
                            <div id="dvOBS">
                                <table id="dgOBS" class="easyui-datagrid table" data-options="iconCls:'icon-edit', toolbar:'#tbOBS', singleSelect:true, method:'get', onClickCell: onClickCellOBS">
                                    <thead>
                                        <tr>
                                            <th data-options="field:'observacion',width:'90%',align:'left'">Observacion</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                        $mDatos = fxDevuelveObsDetProformas($msCodigo);
                                        while($mFila = $mDatos->fetch())
                                        {
                                            echo('<tr><td>' . $mFila["OBSERVACION_093"] . '</td></tr>');
                                        }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div id="tbOBS" style="height:auto">
                        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-add',plain:true" onclick="appendOBS()">Agregar</a>
						<a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-remove',plain:true" onclick="removeitOBS()">Borrar</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-save',plain:true" onclick="acceptitOBS()">Aceptar</a>
                        <a href="javascript:void(0)" class="easyui-linkbutton" data-options="iconCls:'icon-undo',plain:true" onclick="rejectOBS()">Deshacer</a>
                    </div>

                    <div class="form-group row">
                        <label for="txtObservaciones" class="col-sm-12 col-md-2 form-label">Observaciones generales</label>
                        <div class="col-sm-12 col-md-7">
                            <?php echo('<textarea class="form-control" id="txtObservaciones" name="txtObservaciones" rows="3">' . $msObservaciones . '</textarea>'); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-auto col-xs-offset-none col-md-8 col-md-offset-2">
                            <input type="submit" id="Guardar" name="Guardar" value="Guardar" class="btn btn-warning" />
                            <input type="button" id="Cancelar" name="Cancelar" value="Cancelar" class="btn btn-warning" onclick="location.href='gridProformas.php';" />
                            <?php
                            if ($msCodigo == "")
                            {
                                echo('<input type="button" id="ImprimirC" name="ImprimirC" value="Imprimir en córdobas" class="btn btn-warning" disabled />');
                                echo('<input type="button" id="ImprimirD" name="ImprimirD" value="Imprimir en dólares" class="btn btn-warning" disabled />');
                            }
                            else
                            {
                                echo('<input type="button" id="ImprimirC" name="ImprimirC" value="Imprimir en córdobas" class="btn btn-warning" onclick="generaReporte(0)" />');
                                echo('<input type="button" id="ImprimirD" name="ImprimirD" value="Imprimir en dólares" class="btn btn-warning" onclick="generaReporte(1)" />');
                            }
							?>
                        </div>
                    </div>
                </form>
            </div>
            <?php	}
		}
	}
?>
        </div>
    </div>
</div>
</body>

</html>
<script type='text/javascript'>
$.extend($.fn.datagrid.defaults.editors, {
    datebox: {
        init: function(container, options){
            var input = $('<input type="date">').appendTo(container);
            return input;
        },
        destroy: function(target){
            $(target).remove();
        },
        getValue: function(target){
            return $(target).val();
        },
        setValue: function(target, value){
            $(target).val(value);
        },
        resize: function(target, width){
            $(target)._outerWidth(width);
        }
    }
});

window.onload = function() {
    var mdHoy = new Date()
    var mnDia = mdHoy.getDate();
    var mnMes = mdHoy.getMonth() + 1;
    var mnAnno = mdHoy.getFullYear();
    var msFecha = mnAnno + "-" + (mnMes < 10 ? "0" + mnMes : mnMes) + "-" + mnDia;
    $('#dtpFechaIni').val(msFecha);
    $('#dtpFechaFin').val(msFecha);
    $('#txnPrecio').val(0);
    $('#txnCupos').val(0);
    $('#txnTotal').val(0);
}

function calcularTotal()
{
    var mnPrecio = document.getElementById('txnPrecio').value;
    var mnCupos = document.getElementById('txnCupos').value;
    var mnTotal = mnPrecio * mnCupos;
    document.getElementById('txnPrecio').value = parseFloat(mnPrecio).toFixed(2);
    document.getElementById('txnTotal').value = parseFloat(mnTotal).toFixed(2);
}

function generaReporte(moneda) {
    var msProforma = document.getElementById('txtCodProforma').value;
    $.redirect("repProformas.php", {KDSA: msProforma, MONEDA: moneda}, "POST", "_blank");
}

function escribeProspecto() {
    var mnCero = "0";
    var mnNumero = document.getElementById('txtProspecto').value;
    var mnLongitud;
    var mnIndice;

    if (mnNumero.length < 10) {
        mnIndice = mnNumero.indexOf("PT");
        if (mnIndice > -1)
            mnNumero = mnNumero.substring(2);

        mnLongitud = 8 - mnNumero.length;
        document.getElementById('txtProspecto').value = 'PT' + mnCero.repeat(mnLongitud) + mnNumero;
    }
    obtieneProspecto();
}

function obtieneProspecto() {
    parametros = '{"txtProspecto":"' + document.getElementById("txtProspecto").value + '"}';
    datosJson = JSON.parse(parametros);

    return $.ajax({
        url: 'funciones/fxDatosExternos.php',
        type: 'post',
        async: false,
        data: datosJson,
        success: function(respuesta) {
            document.getElementById("txtNomProspecto").value = respuesta
        }
    })
}

function verificarFormulario() {
    var datos = $('#dgDET').datagrid('getData');
    var regDET = $('#dgDET').datagrid('getRows').length;
    var regPF = $('#dgPF').datagrid('getRows').length;

    if (document.getElementById('txtProspecto').value == "") {
        $.messager.alert('KDSA', 'Falta el Prospecto.', 'warning');
        return false;
    }

    if (document.getElementById('txnTipoCambio').value == "") {
        $.messager.alert('KDSA', 'Falta el Tipo de Cambio.', 'warning');
        return false;
    }

    if (regDET == 0 && regPF == 0) {
        $.messager.alert('KDSA', 'Faltan los Cursos de interés.', 'warning');
        return false;
    }

    return true;
}

/*Grid de Cursos*/
var editIndex = undefined;
var lastIndex;

$('#dgDET').datagrid({
    onClickRow: function(rowIndex) {
        if (lastIndex != rowIndex) {
            $(this).datagrid('endEdit', lastIndex);
            $(this).datagrid('beginEdit', rowIndex);
        }
        lastIndex = rowIndex;
    }
});

function endEditing() {
    if (editIndex == undefined) {
        return true
    }
    if ($('#dgDET').datagrid('validateRow', editIndex)) {
        $('#dgDET').datagrid('endEdit', editIndex);
        editIndex = undefined;
        return true;
    } else {
        return false;
    }
}

function onClickCell(index, field) {
    if (editIndex != index) {
        if (endEditing()) {
            $('#dgDET').datagrid('selectRow', index)
                .datagrid('beginEdit', index);
            editIndex = index;
        } else {
            setTimeout(function() {
                $('#dgDET').datagrid('selectRow', editIndex);
            }, 0);
        }
    }
}

function append() {
    if (endEditing()) {
        var i;
        var codigo;
        var existeCurso = false;
        var datos = $('#dgDET').datagrid('getData');
        var registros = $('#dgDET').datagrid('getRows').length;

        if (registros > 0) {
            for (i = 0; i < registros; i++) {
                if (datos.rows[i].curso == $('#cboCurso option:selected').val())
                    existeCurso = true;
            }
        }

        if (existeCurso == true) {
            $.messager.alert('KDSA', $('#cboCurso option:selected').text() + ' ya fue incluido.', 'warning');
            $('#cboCurso').focus()
        } else {
            $('#dgDET').datagrid('appendRow', {
                curso: $('#cboCurso option:selected').val(),
                nombre: $('#cboCurso option:selected').text(),
                cantidad: 1
            });
            editIndex = $('#dgDET').datagrid('getRows').length;
            $('#dgDET').datagrid('selectRow', editIndex).datagrid('beginEdit', editIndex);
        }
    }
}

function removeit() {
    if (editIndex == undefined) {
        return
    }
    $('#dgDET').datagrid('cancelEdit', editIndex)
        .datagrid('deleteRow', editIndex);
    editIndex = undefined;
}

function acceptit() {
    if (endEditing()) {
        $('#dgDET').datagrid('acceptChanges');
    }
}

function reject() {
    $('#dgDET').datagrid('rejectChanges');
    editIndex = undefined;
}

/*Grid de Cursos que no están en el catálogo*/
var editIndexPF = undefined;
var lastIndexPF;

$('#dgPF').datagrid({
    onClickRow: function(rowIndexPF) {
        if (lastIndexPF != rowIndexPF) {
            $(this).datagrid('endEdit', lastIndexPF);
            $(this).datagrid('beginEdit', rowIndexPF);
        }
        lastIndexPF = rowIndexPF;
    }
});

function endEditingPF() {
    if (editIndexPF == undefined) {
        return true
    }
    if ($('#dgPF').datagrid('validateRow', editIndexPF)) {
        $('#dgPF').datagrid('endEdit', editIndexPF);
        editIndexPF = undefined;
        return true;
    } else {
        return false;
    }
}

function onClickCellPF(index, field) {
    if (editIndexPF != index) {
        if (endEditingPF()) {
            $('#dgPF').datagrid('selectRow', index)
                .datagrid('beginEdit', index);
            editIndexPF = index;
        } else {
            setTimeout(function() {
                $('#dgPF').datagrid('selectRow', editIndexPF);
            }, 0);
        }
    }
}

function appendPF() {
    if (endEditingPF()) {
        $('#dgPF').datagrid('appendRow', {
            cursoKdsa: $('#txtCursoKdsa').val(),
            cursoInatec: $('#txtCursoInatec').val(),
            diasClase: $('#txtDias').val(),
            horario: $('#txtHorario').val(),
            horario: $('#txtHorario').val(),
            fechaIni: $('#dtpFechaIni').val(),
            fechaFin: $('#dtpFechaFin').val(),
			horasclase: $('#txnHorasClase').val(),
            codInatec: $('#txtCodInatec').val(),
            acuerdo: $('#txtAcuerdo').val(),
            precio: $('#txnPrecio').val(),
            cupos: $('#txnCupos').val(),
            total: $('#txnTotal').val()
        });
        editIndexPF = $('#dgPF').datagrid('getRows').length;
        $('#dgPF').datagrid('selectRow', editIndexPF).datagrid('beginEdit', editIndexPF);
    }
}

function removeitPF() {
    if (editIndexPF == undefined) {
        return
    }
    $('#dgPF').datagrid('cancelEdit', editIndexPF)
        .datagrid('deleteRow', editIndexPF);
    editIndexPF = undefined;
}

function acceptitPF() {
    if (endEditingPF()) {
        $('#dgPF').datagrid('acceptChanges');
    }
}

function rejectPF() {
    $('#dgPF').datagrid('rejectChanges');
    editIndexPF = undefined;
}

/*Grid de Observaciones*/
var editIndexOBS = undefined;
var lastIndexOBS;

$('#dgOBS').datagrid({
    onClickRow: function(rowIndexOBS) {
        if (lastIndexOBS != rowIndexOBS) {
            $(this).datagrid('endEdit', lastIndexOBS);
            $(this).datagrid('beginEdit', rowIndexOBS);
        }
        lastIndexOBS = rowIndexOBS;
    }
});

function endEditingOBS() {
    if (editIndexOBS == undefined) {
        return true
    }
    if ($('#dgOBS').datagrid('validateRow', editIndexOBS)) {
        $('#dgOBS').datagrid('endEdit', editIndexOBS);
        editIndexOBS = undefined;
        return true;
    } else {
        return false;
    }
}

function onClickCellOBS(index, field) {
    if (editIndexOBS != index) {
        if (endEditingOBS()) {
            $('#dgOBS').datagrid('selectRow', index)
                .datagrid('beginEdit', index);
            editIndexOBS = index;
        } else {
            setTimeout(function() {
                $('#dgOBS').datagrid('selectRow', editIndexOBS);
            }, 0);
        }
    }
}

function appendOBS() {
    if (endEditingOBS()) {
        $('#dgOBS').datagrid('appendRow', {
            observacion: $('#txtDetObs').val()
        });
        editIndexOBS = $('#dgOBS').datagrid('getRows').length;
        $('#dgOBS').datagrid('selectRow', editIndexOBS).datagrid('beginEdit', editIndexOBS);
    }
}

function removeitOBS() {
    if (editIndexOBS == undefined) {
        return
    }
    $('#dgOBS').datagrid('cancelEdit', editIndexOBS)
        .datagrid('deleteRow', editIndexOBS);
    editIndexOBS = undefined;
}

function acceptitOBS() {
    if (endEditingOBS()) {
        $('#dgOBS').datagrid('acceptChanges');
    }
}

function rejectOBS() {
    $('#dgOBS').datagrid('rejectChanges');
    editIndexOBS = undefined;
}

$('form').submit(function(e) {
    e.preventDefault();

    if (verificarFormulario() == true) {
        var texto;
        var datos;
        var registros;
        var i;
        var fechaIni;
        var fechaFin;
        var gridDetalle = $('#dgDET').datagrid('getData');
        var gridOtros = $('#dgPF').datagrid('getData');
        var gridObservaciones = $('#dgOBS').datagrid('getData');

        texto = '{"txtCodProforma":"' + document.getElementById("txtCodProforma").value + '", ';
        if (document.getElementById("txtCodProforma").value == "")
            texto += '"Operacion":"0", ';
        else
            texto += '"Operacion":"1", ';
        texto += '"txtProspecto":"' + document.getElementById("txtProspecto").value + '", ';
        texto += '"dtpFecha":"' + document.getElementById("dtpFecha").value + '", ';
        texto += '"txtLugar":"' + document.getElementById("txtLugar").value + '", ';
        texto += '"txnTipoCambio":"' + document.getElementById("txnTipoCambio").value + '", ';
        texto += '"txnDescuento":"' + document.getElementById("txnDescuento").value + '", ';
        texto += '"txtObservaciones":"' + document.getElementById("txtObservaciones").value + '", ';
        
        if (document.getElementById("optMoneda1").checked)
            texto += '"optMoneda":"0", ';
        else
            texto += '"optMoneda":"1", ';
        
        if (document.getElementById("optInatec1").checked)
            texto += '"optInatec":"0", ';
        else
            texto += '"optInatec":"1", ';

        registros = $('#dgDET').datagrid('getRows').length - 1;
        texto += '"gridDetalle": [';
        if (registros >= 0) {
            for (i = 0; i <= registros; i++) {
                texto += '{"curso":"' + gridDetalle.rows[i].curso + '", "nombre":"' + gridDetalle.rows[i].nombre;
                texto += '", "cantidad":"' + gridDetalle.rows[i].cantidad;
                if (i != registros)
                    texto += '"},';
                else
                    texto += '"}';
            }
        }
        texto += '],';

        registros = $('#dgPF').datagrid('getRows').length - 1;
        texto += '"gridOtros": [';
        if (registros >= 0) {
            for (i = 0; i <= registros; i++) {
                fechaIni = gridOtros.rows[i].fechaIni;
                fechaFin = gridOtros.rows[i].fechaFin;

                texto += '{"cursoKdsa":"' + gridOtros.rows[i].cursoKdsa + '", "cursoInatec":"' + gridOtros.rows[i].cursoInatec;
                texto += '","diasClase":"' + gridOtros.rows[i].diasClase + '","horario":"' + gridOtros.rows[i].horario;
                texto += '","fechaIni":"' + fechaIni + '","fechaFin":"' + fechaFin + '", "horasclase":"' + gridOtros.rows[i].horasclase;
                texto += '","codInatec":"' + gridOtros.rows[i].codInatec + '","acuerdo":"' + gridOtros.rows[i].acuerdo;
                texto += '","precio":"' + gridOtros.rows[i].precio + '","cupos":"' + gridOtros.rows[i].cupos + '", "total":"' + gridOtros.rows[i].total;

                if (i != registros)
                    texto += '"},';
                else
                    texto += '"}';
            }
        }
        texto += '],';

        registros = $('#dgOBS').datagrid('getRows').length - 1;
        texto += '"gridObservaciones": [';
        if (registros >= 0) {
            for (i = 0; i <= registros; i++) {
                texto += '{"observacion":"' + gridObservaciones.rows[i].observacion;
                if (i != registros)
                    texto += '"},';
                else
                    texto += '"}';
            }
        }
        texto += ']}';

        datos = JSON.parse(texto);

        $.ajax({
                url: 'procProformas.php',
                type: 'post',
                data: datos,
                beforeSend: function() {
                    console.log(datos)
                }
            })
            .done(function() {
                location.href = "gridProformas.php";
            })
            .fail(function() {
                console.log('Error')
            });
    }
});
</script>