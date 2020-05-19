<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bootstrap-3-complilado/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/jquery-ui-1.10.4/css/smoothness/jquery-ui-1.10.4.custom.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/tpl2-sb/css/flujo.css" />
<style>
	#contenedor_form_sidebar {
		margin: 0 auto;
		width: 1080px;
	}
	#contenedor_form_sidebar #sidebar_left {
		/*background-color: yellow;*/
		float: left;
	}
	#frm_solicitud.form_solcitudes {
		/*background-color: green;*/
		float: left;
		margin: 0;
		padding-left: 23px;
	}
	table#tblModal tbody tr td {
		font-size: 10px;
	}
	table#tblModal tr th {
		font-size: 10px;
		font-weight: bold;
	}
	
	/*Menu seleccionar empresa en solicitud*/
	ul#lst_menu_empresas {
		width: 178px;
		margin: 0;
		padding:0;
		border: 2px solid #4CAE4C;
		-webkit-border-radius: 6px 6px 0 0;
			border-radius: 6px 6px 0 0;
	}
	ul#lst_menu_empresas li {
		list-style:none;
		margin-bottom: 26px;
		text-align: center;
	}
	ul#lst_menu_empresas li h4 {
		color: #4CAE4C;
		font-weight: bold;
		font-size: 16px;
		text-align: center;
	}	
	ul#lst_menu_empresas .btn {
		background: #C1D528;
		box-shadow: 1px 1px 5px #888888;
		border: 2px solid #C1D528;
		outline: none;
		color: #FFFFFF;
		/*margin-left: 10px;*/
		width: 150px;
	}
	ul#lst_menu_empresas .btn.btnSeleccionado {
		background: #4CAE4C;
	}
	ul#lst_menu_empresas .btn:hover {
		border: 2px solid #4cae4c;
	}
	
</style>
<div class="row">
	<div class="col-md-24" style="-webkit-border-radius: 6px 6px 0 0;border-radius: 6px 6px 0 0; margin-top: 10px;margin-bottom: 10px;">
	</div>
</div>
<form id="frm_solicitud_traslado" method="POST" onkeypress="return event.keyCode != 13;">
				<!-- contenedor formulario mas sidebars inicio  -->
				<div id="contenedor_form_sidebar">
					<div class="contenedor_frm">
					<!-- formulario inicio -->
						<!-- sidebar izquierdo inicio -->
						<div id="sidebar_left" class="col-md-5">
							<ul id="lst_menu_empresas">
								<li>
									<h4>Seleccione la empresa</h4>
								</li>
								<li id="empresa_salcobrand">
									<button class="btn btn-fresh text-uppercase" type="button">Salcobrand</button>
								</li>
								<li id="empresa_preunic">
									<button class="btn btn-fresh text-uppercase" type="button">Preunic</button>
								</li>
								<li id="empresa_farmaprecio">
									<button class="btn btn-fresh text-uppercase" type="button">Farmaprecio</button>
								</li>
								<!-- jagl botones
								<li id="empresa_makeup">
									<button class="btn btn-fresh text-uppercase" type="button">Make UP</button>
								</li>
								<li id="empresa_medcell">
									<button class="btn btn-fresh text-uppercase" type="button">Medcell</button>
								</li>
								 -->
							</ul>
						</div>
						<!-- sidebar izquierdo inicio -->
						<div id="frm_solicitud" class="container-full form_solcitudes">
							<div class="form_derch">
								<div class="row">
									<div class="col-md-24 fila tituform" style="-webkit-border-radius: 6px 6px 0 0;border-radius: 6px 6px 0 0;">
										<h4><span class="glyphicon glyphicon-info-sign"></span><span class="titleSeccionForm">Traslado Origen</span></h4>
									</div>
								</div>		
								<div class="row" ><!-- fila 1 -->
									<div class="col-md-24 fila">
										<div class="col-md-12">
											<div class="form-group">
												<label for="sol_local">C. Costo</label>
												<select name="sol_local" id="sol_local" class="selectpicker required" style="width: 96%; margin-bottom: 10px;">
													<option value="" selected>Seleccionar C. Costo</option>
													<!-- 
													<?php foreach ($opCCostoOrigen as $optVal): ?>
														<option data-codEmpresa="<?php echo $optVal->EMPRESA; ?>" value="<?php echo $optVal->CODUNIDAD; ?>"><?php echo $optVal->CODUNIDAD." ".$optVal->NOMBUNIDAD; ?></option>
													<?php endforeach; ?>
													 -->
												</select>									
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-23 col-md-offset-1">
													<div class="form-group">
														<label for="sol_motivo">Motivo</label>
														<select name="sol_motivo" id="sol_motivo"  class="selectpicker required" style="width: 100%; margin-bottom: 10px;">
															<option value="" selected>Indique motivo</option>
															<?php foreach ($opSolMotivo as $optVal): ?>
																<option value="<?php echo trim($optVal->COD); ?>"><?php echo trim($optVal->DESCR); ?></option>
															<?php endforeach; ?>
														</select>
													</div>					
												</div>
											</div>
										</div>
									</div>
								</div><!-- fin row -->
								<div class="row"><!-- fila 2 -->
									<div class="col-md-24 fila">
										<div class="col-md-12">
											<div class="form-group">
												<!-- en un principio estubo pensado solo para colocar el rut -->
												<!-- ahora se muestra el rut y nombre -->
												<label for="sol_rut">Colaborador</label>
												<select name="sol_rut" id="sol_rut"  class="selectpicker required" style="width: 96%; margin-bottom: 10px;">
													<option value="">----</option>
												</select>									
											</div>					
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-8 col-md-offset-1">
													<div class="form-group">
														<label for="sol_cui
														">CUI</label>
														<input type="text" class="form-control" name="sol_cui" id="sol_cui" readonly="readonly"  />
													</div>					
												</div>
												<div class="col-md-14">
													<div class="form-group">
														<label for="sol_cui_desc">Descripción</label>
														<input type="text" class="form-control" name="sol_cui_desc" id="sol_cui_desc" readonly="readonly"  />
													</div>					
												</div>
											</div>
										</div>
									</div>
								</div><!-- fin row -->
								<div class="row"><!-- fila 3 -->
									<div class="col-md-24 fila">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group">
														<label for="sol_fech_inic_contr">Inicio Contrato</label>
														<input type="text" class="form-control text-center" name="sol_fech_inic_contr" id="sol_fech_inic_contr" readonly="readonly"  />
													</div>					
												</div>
												<div class="col-md-6">
													<div class="form-group">
														<label for="sol_fech_term_contr">Termino Contrato</label>
														<input type="text" class="form-control text-center" name="sol_fech_term_contr" id="sol_fech_term_contr" readonly="readonly"  />
													</div>					
												</div>
												<div class="col-md-11">
													<div class="form-group">
														<label for="sol_situac_contr">Situacion Contractual</label>
														<input type="text" class="form-control" name="sol_situac_contr" id="sol_situac_contr" readonly="readonly"  />
													</div>					
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-5 col-md-offset-1">
													<div class="form-group">
														<label for="sol_cargo_Dbnet">Cargo Dbnet</label>
														<input type="text" class="form-control" name="sol_cargo_Dbnet" id="sol_cargo_Dbnet"  readonly="readonly" />
													</div>					
												</div>
												<div class="col-md-18">
													<div class="form-group">
														<label for="sol_cargo_Dbnet_desc">Descripción</label>
														<input type="text" class="form-control" name="sol_cargo_Dbnet_desc" id="sol_cargo_Dbnet_desc" readonly="readonly"  />
													</div>					
												</div>
											</div>
										</div>
									</div>
								</div><!-- fin row -->
								<div class="row"><!-- fila 4 -->
									<div class="col-md-24 fila">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-7">
													<div class="form-group">
														<label for="sol_fech_cambio">Fecha de Cambio</label>
														 <input type="text" id="sol_fech_cambio" style="cursor: default;" class="form-control calendario text-center required" onkeypress="return event.keyCode != 13;" />
													</div>					
												</div>
												<div class="col-md-7 col-md-offset-1">
													<div>
														<label for="sol_trasl_apro12">Tras Apr 12 meses</label>
														<input class="inputModal text-center" type="text" name="sol_trasl_apro12" id="sol_trasl_apro12" readonly="readonly" />
														<i id="mod_sol_trasl_apro12" class="fa fa-external-link" style="font-size: 14px; cursor: pointer;" data-toggle="modal" href="#myModal"></i>
													</div>					
												</div>
												<div class="col-md-7  col-md-offset-1">
													<div>
														<label for="sol_trasl_pend12">Tras Pen 12 meses</label>
														<input type="text" class="inputModal text-center" name="sol_trasl_pend12" id="sol_trasl_pend12" readonly="readonly"  />
														<i id="mod_sol_trasl_pend12" class="fa fa-external-link" style="font-size: 14px; cursor: pointer;" data-toggle="modal" href="#myModal"></i>
													</div>					
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-6 col-md-offset-1">
													<div class="form-group">
														<label for="sol_cargo_jer">Cargo Jerárquico</label>
														<input type="text" class="form-control" name="sol_cargo_jer" id="sol_cargo_jer" readonly="readonly"  />
													</div>					
												</div>
												<div class="col-md-17">
													<div class="form-group">
														<label for="sol_cargo_jer_desc">Descripción</label>
														<input type="text" class="form-control" name="sol_cargo_jer_desc" id="sol_cargo_jer_desc" readonly="readonly"  />
													</div>					
												</div>
											</div>
										</div>
									</div>
								</div><!-- fin row -->
								<div class="row"><!-- fila 5 -->
									<div class="col-md-24 fila">
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-5">
													<div class="form-group">
														<label for="sol_prom_cant">Prom. Comisión</label>
														<input type="text" class="form-control text-right" name="sol_prom_cant" id="sol_prom_cant" readonly="readonly" >
													</div>					
												</div>
												<div class="col-md-19" style="margin-left: 0;">
													<div class="form-group">
														<label for="sol_prom_desc"></label>
														<input type="text" class="form-control" name="sol_prom_desc" id="sol_prom_desc" readonly="readonly" style="margin-right: 0px; width: 320px;">
													</div>					
												</div>
											</div>
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-6 col-md-offset-1">
													<div class="form-group">
														<label for="sol_proveedor">Proveedor</label>
														<input type="text" class="form-control" name="sol_proveedor" id="sol_proveedor" readonly="readonly">
													</div>					
												</div>
												<div class="col-md-17">
													<div class="form-group">
														<label for="sol_proveedor_desc">Descripción</label>
														<input type="text" class="form-control" name="sol_proveedor_desc" id="sol_proveedor_desc" readonly="readonly">
													</div>					
												</div>
											</div>
										</div>
									</div>
								</div><!-- fin row -->		
								<div class="row"><!-- fila 6 -->
									<div class="col-md-24 fila">
										<div class="col-md-3">
											<div class="form-group">
												<label for="sol_dot_teo">Dotación Teórica</label>
												<input type="text" class="form-control text-center" name="sol_dot_teo" id="sol_dot_teo" readonly="readonly" >
											</div>					
										</div>
										<div class="col-md-3">
											<div>
												<label for="sol_dot_mae">Dotación Real</label>
												<input type="text" class="inputModal text-center" style="width: 56px;" name="sol_dot_mae" id="sol_dot_mae" readonly="readonly" >
												<i id="mod_sol_dot_mae" class="fa fa-external-link" style="font-size: 14px; cursor: pointer;" data-toggle="modal" href="#myModal"></i>
											</div>					
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sol_dot_licen">Licencia</label>
												<input name="sol_dot_licen" id="sol_dot_licen" type="text" class="inputModal text-center" style="width: 63px;" readonly="readonly" >
												<i id="mod_sol_dot_licen" class="fa fa-external-link" style="font-size: 14px; cursor: pointer;" data-toggle="modal" href="#myModal"></i>
											</div>											
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sol_dot_permiso">Permiso</label>
												<input name="sol_dot_permiso" id="sol_dot_permiso" type="text" class="inputModal text-center" style="width: 63px;" readonly="readonly" >
												<i id="mod_sol_dot_permiso" class="fa fa-external-link" style="font-size: 14px; cursor: pointer;" data-toggle="modal" href="#myModal"></i>
											</div>											
										</div>					
										<div class="col-md-3">
											<div>
												<label for="sol_tras_pend_egreso">Tras Pen Egreso</label>
												<input type="text" class="inputModal text-center" style="width: 73px;" name="sol_tras_pend_egreso" id="sol_tras_pend_egreso" readonly="readonly" >
												<i id="mod_sol_tras_pend_egreso" class="fa fa-external-link" style="font-size: 14px; cursor: pointer;" data-toggle="modal" href="#myModal"></i>
											</div>										
										</div>
										<div class="col-md-3">				
											<div>
												<label for="sol_tras_pend_ingreso">Tras Pen Ingreso</label>
												<input type="text" class="inputModal text-center" style="width: 74px;" name="sol_tras_pend_ingreso" id="sol_tras_pend_ingreso" readonly="readonly" >
												<i id="mod_sol_tras_pend_ingreso" class="fa fa-external-link" style="font-size: 14px; cursor: pointer;" data-toggle="modal" href="#myModal"></i>
											</div>						
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sol_egre_trasl_act_orig">Egreso Tras Actual</label>
												<input type="text" class="form-control text-center" name="sol_egre_trasl_act_orig" id="sol_egre_trasl_act_orig" readonly="readonly" >
											</div>	
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sol_dot_fut">Dotación Futura</label>
												<input type="text" class="form-control text-center" name="sol_dot_fut" id="sol_dot_fut" readonly="readonly" >
											</div>																
										</div>
									</div>
								</div><!-- fin row -->
								<div class="row fila"><!-- fila 7 -->
									<div class="col-md-12">
											<div class="row">
												<div class="col-md-23">	
													<div class="form-group">
														<label for="sol_observacion">Observación</label>
														<textarea name="sol_observacion" id="sol_observacion" class="form-control" rows="3" style="padding-bottom: 0px; height: 71px;text-align: left;margin-bottom: 10px;"></textarea>
													</div>
												</div>
											</div>
									</div>
									<div class="col-md-12">
										<div class="row">
											<div class="col-md-23 col-md-offset-1">
												<div class="form-group">
													<label for="sol_zonal_origen">Zonal</label>
													<input type="text" class="form-control text-center" name="sol_zonal_origen" id="sol_zonal_origen" readonly="readonly">
												</div>	
											</div>
											<div class="col-md-11 col-md-offset-1">
											</div>
										</div>
										<!-- traslado inicio -->
										<div class="row">
											<div class="col-md-11  col-md-offset-1">
												<div class="form-group">
													<label for="sol_asist_recl_sel">Asistente Reclut. y Selección</label>
													<input type="text" class="form-control" name="sol_asist_recl_sel" id="sol_asist_recl_sel" readonly="readonly" >
												</div>					
											</div>
											<div class="col-md-11">
												<div class="form-group">
													<label for="sol_asist_proc_compe">Ejecutivoa Proc. y Compens</label>
													<input type="text" class="form-control" name="sol_asist_proc_compe" id="sol_asist_proc_compe" readonly="readonly" >
												</div>					
											</div>
										</div>
										<!-- traslado fin -->
									</div>
								</div><!-- fin row -->
								<div class="row">
									<div class="col-md-24 fila tituform">
										<h4><span class="glyphicon glyphicon-info-sign"></span>Traslado Destino</h4>
									</div>
								</div>
								<div class="row" ><!-- fila 8 -->
									<div class="col-md-24 fila">
										<div class="col-md-12">
											<div class="form-group">
												<label for="sol_local_dest">C. Costo</label>
												<select name="sol_local_dest" id="sol_local_dest" class="selectpicker required" style="width: 96%; margin-bottom: 10px;">
													<option value="" selected>----</option>
												</select>									
											</div>					
										</div>
										<div class="col-md-12">
											<div class="row">
												<div class="col-md-8">
													<div class="form-group">
														<label for="sol_cui_dest">CUI</label>
														<input type="text" class="form-control positive-integer" name="sol_cui_dest" id="sol_cui_dest" onkeypress="return event.keyCode != 13;" readonly="readonly">
													</div>
												</div>
												<div class="col-md-14">
													<div class="form-group">
														<label for="sol_cui_desc_dest">Descripción</label>
														<input type="text" class="form-control" name="sol_cui_desc_dest" id="sol_cui_desc_dest" readonly="readonly" >
													</div>					
												</div>
											</div>
										</div>
									</div>
								</div><!-- fin row -->	
								<div class="row" ><!-- fila 9 -->
									<div class="col-md-24 fila">
										<div class="col-md-8">
											<div class="form-group">
												<label for="sol_zonal_dest">Zonal</label>
												<input type="text" class="form-control" name="sol_zonal_dest" id="sol_zonal_dest" readonly="readonly" >
												<input type="hidden" class="form-control" name="sol_zonal_dest_hidden" id="sol_zonal_dest_hidden">
											</div>					
										</div>
										<div class="col-md-8">
											<div class="form-group">
												<label for="sol_asis_reclut_selec_dest">Asistente Reclutamiento y Seleccion:</label>
												<input type="text" class="form-control" name="sol_asis_reclut_selec_dest" id="sol_asis_reclut_selec_dest" readonly="readonly" >
												<input type="hidden" class="form-control" name="sol_asis_reclut_selec_dest_hidden" id="sol_asis_reclut_selec_dest_hidden">
											</div>					
										</div>
										<div class="col-md-8">
											<div class="form-group">
												<label for="sol_asis_proc_comp_dest">Ejecutiva Procesos y Compensaciones:</label>
												<input type="text" class="form-control" name="sol_asis_proc_comp_dest" id="sol_asis_proc_comp_dest" readonly="readonly" >
												<input type="hidden" class="form-control" name="sol_asis_proc_comp_dest_hidden" id="sol_asis_proc_comp_dest_hidden">
											</div>					
										</div>
									</div>
								</div><!-- fin row -->		
								<div class="row"><!-- fila 10 -->
									<div class="col-md-24 fila">
										<div class="col-md-3">
											<div class="form-group">
												<label for="sol_dot_teo_dest">Dot Teórica</label>
												<input type="text" class="form-control text-center" name="sol_dot_teo_dest" id="sol_dot_teo_dest" readonly="readonly" >
											</div>									
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sol_dot_mae_dest">Dotación Real</label>
												<input name="sol_dot_mae_dest" id="sol_dot_mae_dest" type="text" class="inputModal text-center" style="width: 63px;" readonly="readonly" >
												<i id="mod_sol_dot_mae_dest" class="fa fa-external-link" style="font-size: 14px; cursor: pointer;" data-toggle="modal" href="#myModal"></i>
											</div>											
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sol_dot_licen_dest">Licencia</label>
												<input name="sol_dot_licen_dest" id="sol_dot_licen_dest" type="text" class="inputModal text-center" style="width: 63px;" readonly="readonly" >
												<i id="mod_sol_dot_licen_dest" class="fa fa-external-link" style="font-size: 14px; cursor: pointer;" data-toggle="modal" href="#myModal"></i>
											</div>											
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sol_dot_permiso_dest">Permiso</label>
												<input name="sol_dot_permiso_dest" id="sol_dot_permiso_dest" type="text" class="inputModal text-center" style="width: 63px;" readonly="readonly" >
												<i id="mod_sol_dot_permiso_dest" class="fa fa-external-link" style="font-size: 14px; cursor: pointer;" data-toggle="modal" href="#myModal"></i>
											</div>											
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sol_tras_pend_egreso_dest">Tras Pend. Egre</label>
												<input name="sol_tras_pend_egreso_dest" id="sol_tras_pend_egreso_dest" type="text" class="inputModal text-center" style="width: 73px;" readonly="readonly" >
												<i id="mod_sol_tras_pend_egreso_dest" class="fa fa-external-link" style="font-size: 14px; cursor: pointer;" data-toggle="modal" href="#myModal"></i>
												
											</div>										
										</div>
										<div class="col-md-3">				
											<div class="form-group">
												<label for="sol_tras_pend_ingreso_dest">Tras Pend Ingre</label>
												<input name="sol_tras_pend_ingreso_dest" id="sol_tras_pend_ingreso_dest" type="text" class="inputModal text-center" style="width: 75px;" readonly="readonly" >
												<i id="mod_sol_tras_pend_ingreso_dest" class="fa fa-external-link" style="font-size: 14px; cursor: pointer;" data-toggle="modal" href="#myModal"></i>
												
											</div>						
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sol_egres_tras_act_dest">Ingreso Tras Act</label>
												<input type="text" class="form-control text-center" name="sol_egres_tras_act_dest" id="sol_egres_tras_act_dest" readonly="readonly" >
											</div>									
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="sol_dot_futura_dest">Dotación Futura</label>
												<input type="text" class="form-control text-center" name="sol_dot_futura_dest" id="sol_dot_futura_dest" readonly="readonly" >
											</div>											
										</div>
									</div>
								</div><!-- fin row -->							
								<div class="row">
									<div class="col-md-24 fila" style="text-align: center; border-radius: 0px 0px 6px 6px; padding-top: 8px; padding-bottom: 8px;">
										<input name="sol_situac_contr_cod_hidden" id="sol_situac_contr_cod_hidden" type="hidden" />
										<button id="borrar_datos" class="btn btn-danger" style="padding-top: 4px; padding-bottom: 4px; margin-right: 10px;">Limpiar pantalla</button>
										<!--  
										<button id="sendSolicTrasl" class="btn btn-success" style="padding-top: 4px; padding-bottom: 4px; margin-left: 10px;">GENERA TRASLADO</button>
										-->
										<button id="insertarSolicit" type="submit" class="btn btn-success" style="padding-top: 4px; padding-bottom: 4px; background-color: #47a447;margin-left: 10px;">Genera traslado</button>
										<!--  
										<input id="sendSolicTrasl" type="submit" value="Enviar">
										-->
									</div>
								</div>	
							</div>
							<!--nombre del zonal origen
							<input type="hidden" name="sol_zonal_origen_hidden" id="sol_zonal_origen_hidden" />
							-->
							<input type="hidden" name="sol_asist_proc_compe_hidden" id="sol_asist_proc_compe_hidden" />
							<input type="hidden" name="sol_asist_recl_sel_hidden" id="sol_asist_recl_sel_hidden" />
							<input type="hidden" name="sol_zonal_rut_origen_hidden" id="sol_zonal_rut_origen_hidden" />
							<input type="hidden" name="sol_id_flujo_hidden" id="sol_id_flujo_hidden" />
							<input type="hidden" name="sol_tiene_comi_hidden" id="sol_tiene_comi_hidden" />
					<!-- formulario fin -->
						</div>
					</div>	
					<!-- contenedor formulario mas sidebars fin  -->
				</div><!-- fin contenedor_form_sidebar -->
</form>	
<div class="row">
	<div class="col-md-24" style="-webkit-border-radius: 6px 6px 0 0;border-radius: 6px 6px 0 0; margin-top: 10px;margin-bottom: 10px;"></div>
</div>
			<!-- inicio ventana modal -->
			<div class="modal" id="myModal">
				<div class="modal-dialog">
			      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			          <h4 class="modal-title">Sin Información</h4>
			        </div>
			        <div class="modal-body">
						<p>Ninguna información para mostrar</p>
			        </div>
			        <div class="modal-footer">
			          <a href="#" data-dismiss="modal" class="btn btn-xs btn-success" style="font-size: 17px;">Cerrar</a>
			          <!-- 
			          <a href="#" class="btn btn-primary">Save changes</a>
			           -->
			        </div>
			      </div>
			    </div>
			</div>
			<!-- fin ventana modal -->

			<!-- inicio de modal para las peticiones ajax -->
			<div id="divMessage" style="display: none;">
            	Por favor espere un momento ....
        	</div>			
			<!-- fin de modal para las peticiones ajax -->
<div id="destinoFocusAlert"></div>

<!-- ALERT -->
<link href="<?php echo base_url(); ?>assets/template/tpl2-sb/lib/smartAlert/alert/css/alert.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/template/tpl2-sb/lib/smartAlert/alert/themes/default/theme.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/template/tpl2-sb/lib/smartAlert/alert/js/alert.js"></script>
<script src="<?php echo base_url(); ?>assets/template/tpl2-sb/lib/smartAlert/alert/js/alertas.personalizadas.js"></script>
<!-- ALERT -->

<script src="<?php echo base_url(); ?>assets/template/tpl2-sb/js/jquery.blockUI.js"></script>
<script src="<?php echo base_url(); ?>assets/template/tpl2-sb/js/jquery-ui.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.populate.js"></script>	
<script src="<?php echo base_url(); ?>assets/js/validar-form/jquery.numeric.js"></script>	
<script src="<?php echo base_url(); ?>assets/js/validar-form/jquery.validate.js"></script>	


<script>
	$( '#borrar_datos' ).click(function(e){
		e.preventDefault();
	
		limpiarFormulario('Advertencia','Los datos del formulario seran borrados');

		/*
		$('#frm_solicitud_traslado')[0].reset();
		$('#sol_rut,#sol_local_dest').html('<option value="">----</option>');
		$('#sol_motivo').removeAttr( "disabled" );
		*/
	});
	
	$("#frm_solicitud_traslado").validate({
		rules: {
			sol_local: {required: true},
			sol_motivo:{required: true},
			sol_rut:{required: true},
			sol_local_dest:{required: true},
			sol_fech_cambio:{required: true},
			sol_observacion:{maxlength: 100}
		},
		messages: {
			sol_local: "Indique Loc origen.",
			sol_motivo: "Indique el motivo.",
			sol_rut: "Indique colaborador.",
			sol_local_dest:"Indique Loc Destino .",
			sol_fech_cambio:"Indique fecha",
			sol_observacion:"Ingrese menos de 100 caracteres"
		},
        //perform an AJAX post to ajax.php
        submitHandler: function() {
	    	var str_sol_observacion = $( '#sol_observacion' ).val();
	    	str_sol_observacion = str_sol_observacion.replace(/\n/g, " ");   
            
        	//alert($( '#sol_id_flujo_hidden' ).val());
			var p_created = <?php echo $_SESSION['rut']; ?>;//11477812 (1)
			var p_colaborador = $( '#sol_rut' ).val();//(2)
			var p_empresa = $('#sol_local').find(':selected').attr('data-codempresa');//(3)
			var p_cargo = $( '#sol_cargo_Dbnet' ).val();//(4)
			var p_desc_cargo = $( '#sol_cargo_Dbnet_desc' ).val();//(5)
			var p_cargo_especifico = $( '#sol_cargo_jer' ).val();//(6)
			var p_comision_garantizada = 0;//(7)
			var p_origen = $( '#sol_local' ).val();//(8)
			//var p_unidad_origen = va ir en null; //(9)
			var p_destino = $( '#sol_local_dest' ).val();//(10)
			//var p_unidad_destino = va ir en null $( '#sol_cui_dest' ).val();//(11)
			var p_etapa = 1;//(12)
			var p_fecha_cambio = $( '#sol_fech_cambio' ).val();//(13)
			var p_zonal_origen = $( '#sol_zonal_rut_origen_hidden' ).val();//(14)
			var p_zonal_destino = $( '#sol_zonal_dest_hidden' ).val();//(15)
			var p_procesos_origen = $('#sol_asist_proc_compe_hidden').val();//(16)
			var p_procesos_destino = $( '#sol_asis_proc_comp_dest_hidden' ).val();//(17)
			var p_selec_origen = $(  '#sol_asist_recl_sel_hidden' ).val();//(18)
			var p_selec_destino = $( '#sol_asis_reclut_selec_dest_hidden' ).val();//(19)
			var p_motivo = $( '#sol_motivo' ).val();//(20)
			var p_cui_origen = $( '#sol_cui' ).val();//(21)
			var p_proveedor = $( '#sol_proveedor' ).val();//(22)
			var p_observacion = str_sol_observacion;//(23)
			var p_dot_teor_origen = $( '#sol_dot_teo' ).val();//(24)
			var p_dot_mae_origen = $( '#sol_dot_mae' ).val();//(25)
			var p_pend_egre_origen = $( '#sol_tras_pend_egreso' ).val();//(26)
			var p_pend_ingre_origen = $( '#sol_tras_pend_ingreso' ).val();//(26)
			var p_dot_teor_destino = $( '#sol_dot_teo_dest' ).val();//(28)
			var p_dot_mae_destino = $( '#sol_dot_mae_dest' ).val();//(29)
			var p_pend_egre_destino = $( '#sol_tras_pend_egreso_dest' ).val();//(30)
			var p_pend_ingre_destino = $( '#sol_tras_pend_ingreso_dest' ).val();//(31)
			var p_id_flujo = $( '#sol_id_flujo_hidden' ).val();//(32)
			var p_inicio_contrato = $( '#sol_fech_inic_contr' ).val();//(33)
			var p_termino_contrato = $( '#sol_fech_term_contr' ).val();//(34)
			var p_sit_contractual = $('#sol_situac_contr_cod_hidden').val();//(35)
			var p_trasl_aprob_12_meses = $( '#sol_trasl_apro12' ).val();//(36)
			var p_trasl_pend_12_meses = $( '#sol_trasl_pend12' ).val();//(37)
			var p_desc_proveedor = $( '#sol_proveedor_desc' ).val();//(38)

			var p_rebaja_lic_origen = $('#sol_dot_licen').val();//(39)
			var p_rebaja_per_origen = $('#sol_dot_permiso').val();//(40)
			var p_rebaja_lic_dest = $('#sol_dot_licen_dest').val();//(41)
			var p_rebaja_per_dest = $('#sol_dot_permiso_dest').val();//(42)
			var p_cui_destino = $('#sol_cui_dest').val();//(43)

			var p_tiene_comi = $('#sol_tiene_comi_hidden').val();//(44)
			var p_monto_comi = $('#sol_prom_cant').val();//(45)
			p_monto_comi = p_monto_comi.replace(/\D/g,'');
			var p_desc_prom_comi = $('#sol_prom_desc').val();//(46)


			$.ajax({
				type:"POST",
				url :"<?php echo site_url(); ?>traslado/insertarTraslado",
				dataType:"JSON",
				data:{
					p_created : p_created,
					p_colaborador : p_colaborador,
					p_empresa : p_empresa,
					p_cargo : p_cargo,
					p_desc_cargo : p_desc_cargo,
					p_cargo_especifico : p_cargo_especifico,
					p_comision_garantizada : p_comision_garantizada,
					p_origen : p_origen,
					//p_unidad_origen : p_unidad_origen,
					p_destino : p_destino,
					//p_unidad_destino : p_unidad_destino,
					p_etapa : p_etapa,
					p_fecha_cambio : p_fecha_cambio,
					p_zonal_origen : p_zonal_origen,
					p_zonal_destino : p_zonal_destino,
					p_procesos_origen : p_procesos_origen,
					p_procesos_destino : p_procesos_destino,
					p_selec_origen : p_selec_origen,
					p_selec_destino : p_selec_destino,
					p_motivo : p_motivo,
					p_cui_origen : p_cui_origen,
					p_proveedor : p_proveedor,
					p_observacion : p_observacion,
					p_dot_teor_origen : p_dot_teor_origen,
					p_dot_mae_origen : p_dot_mae_origen,
					p_pend_egre_origen : p_pend_egre_origen,
					p_pend_ingre_origen : p_pend_ingre_origen,
					p_dot_teor_destino : p_dot_teor_destino,
					p_dot_mae_destino : p_dot_mae_destino,
					p_pend_egre_destino : p_pend_egre_destino,
					p_pend_ingre_destino : p_pend_ingre_destino,
					p_id_flujo : p_id_flujo,
					p_inicio_contrato : p_inicio_contrato,
					p_termino_contrato : p_termino_contrato,
					p_sit_contractual : p_sit_contractual,
					p_trasl_aprob_12_meses : p_trasl_aprob_12_meses,
					p_trasl_pend_12_meses : p_trasl_pend_12_meses,
					p_desc_proveedor : p_desc_proveedor,
					p_rebaja_lic_origen : p_rebaja_lic_origen,
					p_rebaja_per_origen : p_rebaja_per_origen,
					p_rebaja_lic_dest : p_rebaja_lic_dest,
					p_rebaja_per_dest : p_rebaja_per_dest,
					p_cui_destino : p_cui_destino,		
						
					p_tiene_comi : p_tiene_comi,
					p_monto_comi : p_monto_comi,
					p_desc_prom_comi :	p_desc_prom_comi
					},
	            beforeSend : function (){
	                $.blockUI({
	                    fadeIn : 0,
	                    fadeOut : 0,
	                    showOverlay : true,
	                    message: '<h1><img src="<?php echo site_url(); ?>assets/template/tpl-sb/img/ajax-loader.gif" /><br />Espere un momento...</h1>'
	                });
	            },					
				success: function(dato){
					//console.log(dato[1]);
					//console.log(dato);
					//["24430", "Pamela Andrea Vera Pino", "pvera@sb.cl"]

					//solo cuando genero la solicitud inserto en la tabla SEL_WF_LOG_CORREOS. La primera es aca y la otra es la linea
					//con el codigo 475fsmmi99382
					function insertarCorreo(numeroSolicitud,etapaActual,etapaSiguiente,mailDesde,mailResponsable,textoModal)
					{
						$.ajax({//inicio ajax
							type:"POST",
							url :"<?php echo site_url(); ?>etapa/graba_log_correo",
							dataType:"html",
							//timeout : 10000,
							data:{
									p_id_solicitud:numeroSolicitud,
									p_etapa_origen:etapaActual,
									p_etapa_destino:etapaSiguiente,
									p_correo_desde:mailDesde,
									p_correo_hasta:mailResponsable,
									p_texto:textoModal
								},
							success: function(dato){
								//console.log(dato.comuna);
								},
							error: function(xhr, ajaxOptions, thrownError) {
									msg = "A ocurridoff un error ";
									console.log(msg+" "+xhr.status + " " + xhr.statusText);
								}
						});//fin ajax			
					}

					var htmlInser = '';
					htmlInser += '<h2>Su orden de traslado se ha generado correctamente</h2>';
					htmlInser += '<h3>Numero de orden</h3>';
					htmlInser += '<p>'+dato[0]+'</p>';
					htmlInser += '<h3>Nombre del responsable de la siguiente etapa </h3>';
					htmlInser += '<p>'+dato[1]+'</p>';
					htmlInser += '<h3>Correo del responsable de la siguiente etapa</h3>';
					htmlInser += '<p>'+dato[2]+'</p>';
					htmlInser += '<h3>Descripcion de la siguiente etapa</h3>';
					htmlInser += '<p>'+dato[4]+'</p>';
					var tituloModal = 'Solicitud de traslado';
					$( '.modal-title' ).html(tituloModal);
					$( '.modal-body' ).html(htmlInser);
					//resetear formulario inicio
					$('#frm_solicitud_traslado')[0].reset();
					$('#sol_rut,#sol_local,#sol_local_dest').html('<option value="">----</option>');
					$('#sol_motivo').removeAttr( "disabled" );		
					$('#empresa_salcobrand > button').removeClass( "btnSeleccionado" );		
					//resetear formulario fin
					
					$('#myModal').modal('show') ;

					var numeroSolicitud = dato[0];	
					var etapaActual = 1;
					var etapaSiguiente = dato[3];
					var mailDesde = "workflow@sb.cl";
					var mailResponsable = dato[2];
					var textoModal = "";
					textoModal += " "+$('.modal-body h2:eq(0)').text()+ " ";	
					textoModal += " | ";					
					textoModal += $('.modal-body h3:eq(0)').text();				
					textoModal += " : ";		
					textoModal += $('.modal-body p:eq(0)').text();	
					textoModal += " | ";
					textoModal += $('.modal-body h3:eq(1)').text();	
					textoModal += " : ";
					textoModal += $('.modal-body p:eq(1)').text();		
					textoModal += " | ";
					textoModal += $('.modal-body h3:eq(2)').text();	
					textoModal += " : ";
					textoModal += $('.modal-body p:eq(2)').text();		
					
					var mailLogueado = "<?php echo $correoLogueado; ?>";


					function insertarPrimero() {
						//475fsmmi99382
					 	insertarCorreo(numeroSolicitud,0,0,mailLogueado,mailLogueado,"Ingreso de solicitud de traslado");
					    window.setTimeout(insertarSegundo, 10)
					}
					 
					function insertarSegundo() {
					    insertarCorreo(numeroSolicitud,etapaActual,etapaSiguiente,mailDesde,mailResponsable,textoModal);
					}
					 
					insertarPrimero();					
					
				},							
                complete : function (){
                    $.unblockUI();
                },            		
				error: function(xhr, ajaxOptions, thrownError) {
						msg = "A ocurridoff un error ";
						console.log(msg+" "+xhr.status + " " + xhr.statusText);
				}
			});
        }		
	});
	$(".positive-integer").numeric({ decimal: false, negative: false }, function() { alert("Solo se acepta enteros positivos"); this.value = ""; this.focus(); });
	$(document).ready(function() {






		/*validar correos inicio 809384094*/
		/*
		validar si habra problema con correo con: 
		-Jefe Zonal Origen
		-Asistente Procesos
		-Asistente Seleccion
		-Local de Origen
		-Control de Gestion
		*/		
		function enviaMailInvolugrados(correo, body)
		{
			$.ajax({//inicio ajax
				type:"POST",
				url :"<?php echo site_url(); ?>traslado/enviaMailInvolugrados",
				dataType:"html",
				//timeout : 10000,
				data:{
						correo: 	correo,
						body: 		body,
						rut: 		'<?php echo $_SESSION['rut']; ?>'
					},
				success: function(dato){
					console.log(dato);
					},
				error: function(xhr, ajaxOptions, thrownError) {
						msg = "A ocurridoff un error ";
						console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
			});//fin ajax	
		}
					



		

		

		$("#sol_fech_cambio").keypress(function(event) {event.preventDefault();});
		/*
			trae_diferencia_traslado ( p_rut, p_empresa, p_cargo , p_fecha_cambio)
		*/
		function validarCarenciaFecha () {
			//console.log("jagl mi funcion callback qqqq");
			//TAMBIEN
			//console.log($( '#sol_rut' ).val());
			var rut = $( '#sol_rut' ).val();
			var empresa = $('#sol_local').find(':selected').attr('data-codempresa');
			var cargoDbnet = $('#sol_cargo_Dbnet').val();
			var solicitud_fecha_cambio = $('#sol_fech_cambio').val();
			
			$.ajax({
				url: "<?php echo site_url(); ?>traslado/ctrlValidarCarenciaFecha",
				type: 'POST',
				dataType:"html",
				async: true,
				data:{
					rut: 						rut,
					empresa: 					empresa,
					cargoDbnet: 				cargoDbnet,
					solicitud_fecha_cambio: 	solicitud_fecha_cambio
					
				},
				success: function(dato){
					if (dato != "OK") {
						soloAlertaPersonalizada('Advertencia',dato);
						$( '#sol_fech_cambio' ).val("");
					}
				},		
				error: function(xhr, ajaxOptions, thrownError) {
						msg = "A ocurridoff un error ";
						console.log(msg+" "+xhr.status + " " + xhr.statusText);
				}
			}); 			
		}

		$.datepicker.regional['es'] = {
				 closeText: 'Cerrar',
				 prevText: '<Ant',
				 nextText: 'Sig>',
				 currentText: 'Hoy',
				 monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
				 monthNamesShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
				 dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
				 dayNamesShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
				 dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
				 weekHeader: 'Sm',
				 dateFormat: 'dd/mm/yy',
				 firstDay: 1,
				 isRTL: false,
				 showMonthAfterYear: false,
				 yearSuffix: ''
		};
		$.datepicker.setDefaults($.datepicker.regional['es']);		
		$('#sol_fech_cambio').datepicker({
			dateFormat: "dd-mm-yy",
			onSelect: validarCarenciaFecha
		});

		
		$('#mod_sol_trasl_apro12').hover(function(){
			if (!($('#sol_trasl_apro12').val()) == '') {
				$.ajax({
					type:"POST",
					url :"<?php echo site_url(); ?>traslado/trasAprobUlt12mes",
					dataType:"json",
					data:{
							rutColab:$( '#sol_rut' ).val()
					},
					success: function(dato){
						//$('.modal-body').html(dato);
						var mihtml = '';
						mihtml += '<table id="tblModal">'; 
						mihtml += '	<thead>'; 
						mihtml += '		<tr style="padding-left: 1px; color: rgb(255, 255, 255); font-weight: bold; background-color: rgb(203, 204, 199);">'; 
						mihtml += '			<th>Nro Orden</th>'; 
						mihtml += '			<th>F. Creacion</th>'; 
						mihtml += '			<th>C. Costo Origen</th>'; 
						mihtml += '			<th>Descripcion</th>'; 
						mihtml += '			<th>C. Costo Destino</th>'; 
						mihtml += '			<th>Descripcion</th>'; 
						mihtml += '		</tr>'; 
						mihtml += '	</thead>'; 
						mihtml += '	<tbody>'; 
						$.each(dato, function(i, item) {
							mihtml += '		<tr>';
							mihtml += '			<td class="text-center">'+item.WF_KEY+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CREATION_DATE+'</td>';
							mihtml += '			<td class="text-center">'+item.CC_ORIGEN+'</td>';
							mihtml += '			<td class="text-center">'+item.DESC_UNIDAD_ORIGEN+'</td>';
							mihtml += '			<td class="text-center">'+item.CC_DESTINO+'</td>';
							mihtml += '			<td class="text-center">'+item.DESC_UNIDAD_DESTINO+'</td>';
							mihtml += '		</tr>';
						});
						mihtml += '	<tbody>'; 
						mihtml += '</table>'; 
						var tituloModal = 'Traslados aprobados ultimos 12 meses';
						$( '.modal-title' ).html(tituloModal);
						$('.modal-body').html(mihtml);
					},			
					error: function(xhr, ajaxOptions, thrownError) {
							msg = "A ocurridoff un error ";
							console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
				});//fin ajax			
			}
		});
		//Trasl Pend últ 12 meses:
		$('#mod_sol_trasl_pend12').hover(function(){
			if (!($('#sol_trasl_apro12').val()) == '') {
				$.ajax({
					type:"POST",
					url :"<?php echo site_url(); ?>traslado/trasPendbUlt12mes",
					dataType:"json",
					data:{
						rutColab:$( '#sol_rut' ).val()
					},
					success: function(dato){
						var mihtml = '';
						mihtml += '<table id="tblModal">'; 
						mihtml += '	<thead>'; 
						mihtml += '		<tr style="padding-left: 1px; color: rgb(255, 255, 255); font-weight: bold; background-color: rgb(203, 204, 199);">'; 
						mihtml += '			<th>Nro. Orden</th>'; 
						mihtml += '			<th>F. Creacion</th>'; 
						mihtml += '			<th>C Costo Origen</th>'; 
						mihtml += '			<th>Descripcion</th>'; 
						mihtml += '			<th>C Costo Destino</th>'; 
						mihtml += '			<th>Descripcion</th>'; 
						mihtml += '			<th>Etapa</th>'; 
						mihtml += '			<th>Estado</th>'; 
						mihtml += '			<th>Detalle estado</th>'; 
						mihtml += '		</tr>'; 
						mihtml += '	</thead>'; 
						mihtml += '	<tbody>'; 
						$.each(dato, function(i, item) {
							mihtml += '		<tr>';
							mihtml += '			<td class="text-center">'+item.WF_KEY+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CREATION_DATE+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CC_ORIGEN+'</td>'; 
							mihtml += '			<td class="text-center">'+item.DESC_UNIDAD_ORIGEN+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CC_DESTINO+'</td>'; 
							mihtml += '			<td class="text-center">'+item.DESC_UNIDAD_DESTINO+'</td>'; 
							mihtml += '			<td class="text-center">'+item.ETAPA_ACTUAL+'</td>'; 
							mihtml += '			<td class="text-center">'+item.ESTADO+'</td>'; 
							mihtml += '			<td class="text-center">'+item.ACCION_ESTADO+'</td>'; 
							mihtml += '		</tr>';
						});
						mihtml += '	<tbody>'; 
						mihtml += '</table>'; 
						var tituloModal = 'Traslados pendientes ultimos 12 meses';
						$( '.modal-title' ).html(tituloModal);
						$('.modal-body').html(mihtml);
					},		
					error: function(xhr, ajaxOptions, thrownError) {
							msg = "A ocurridoff un error ";
							console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
				});//fin ajax			
			
			}
		});
		
		//======Dotación Maestra origen=========//
		$('#mod_sol_dot_mae').hover(function(){
			
			if (!($('#sol_dot_mae').val()) == '') {
				var p_empresa = $('#sol_local').find(':selected').attr('data-codempresa');
				var ccosto = $( '#sol_local' ).val();
				var p_cargo = $( '#sol_cargo_Dbnet' ).val();
				$.ajax({
					type:"POST",
					url :"<?php echo site_url(); ?>traslado/dotacionMaestra",
					dataType:"json",
					data:{
						p_empresa : 	p_empresa,
						ccosto : 		ccosto,
						p_cargo : 		p_cargo
						
					},
					success: function(dato){
						var mihtml = '';
						mihtml += '<table id="tblModal" class="table table-striped">'; 
						mihtml += '	<thead>'; 
						mihtml += '		<tr style="padding-left: 1px; color: rgb(255, 255, 255); font-weight: bold; background-color: #00a7ed;">'; 
						mihtml += '			<th class="text-left">NOMBRE</th>'; 
						mihtml += '			<th class="text-left">CARGO</th>'; 
						mihtml += '			<th class="text-center">INICIO CONTRATO.</th>'; 
						mihtml += '			<th class="text-center">TERMINO CONTRATO.</th>'; 
						mihtml += '			<th class="text-center">SITUACION CONTRACTUAL.</th>'; 
						mihtml += '		</tr>'; 
						mihtml += '	</thead>'; 
						mihtml += '	<tbody>'; 
						$.each(dato, function(i, item) {
							mihtml += '		<tr>';
							mihtml += '			<td class="text-left">'+item.NOMBRE+'</td>'; 
							mihtml += '			<td class="text-left">'+item.CARGO+'</td>';
							mihtml += '			<td class="text-center">'+item.EMP_F_INICICONTR+'</td>';
							mihtml += '			<td class="text-center">'+item.EMP_F_TERMICONTR+'</td>';
							mihtml += '			<td class="text-center">'+item.SITUACION+'</td>';
							mihtml += '		</tr>';
						});
						mihtml += '	<tbody>'; 
						mihtml += '</table>'; 
						$('.modal-dialog').css({"width":"940px","margin":"30px auto"});
						var tituloModal = 'Dotacion Real';
						$( '.modal-title' ).html(tituloModal);
						$('.modal-body').html(mihtml);
					},			
					error: function(xhr, ajaxOptions, thrownError) {
							msg = "A ocurrido un error ";
							console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
				});//fin ajax			
			
			}
		});
		//======Dotación Maestra destino=========//
		$('#mod_sol_dot_mae_dest').hover(function(){

			if (!($('#sol_dot_mae_dest').val()) == '') {
				var p_empresa = $('#sol_local_dest').find(':selected').attr('data-codempresa');
				var ccosto = $( '#sol_local_dest' ).val();
				var p_cargo = $( '#sol_cargo_Dbnet' ).val();
				$.ajax({
					type:"POST",
					url :"<?php echo site_url(); ?>traslado/dotacionMaestra",
					dataType:"json",
					data:{
						p_empresa : 	p_empresa,
						ccosto : 		ccosto,
						p_cargo : 		p_cargo
						
					},
					success: function(dato){
						var mihtml = '';
						mihtml += '<table id="tblModal" class="table table-striped">'; 
						mihtml += '	<thead>'; 
						mihtml += '		<tr style="padding-left: 1px; color: rgb(255, 255, 255); font-weight: bold; background-color: #00a7ed;">'; 
						mihtml += '			<th class="text-left">NOMBRE</th>'; 
						mihtml += '			<th class="text-left">CARGO</th>'; 					
						mihtml += '			<th class="text-center">INICIO CONTRATO.</th>'; 
						mihtml += '			<th class="text-center">TERMINO CONTRATO.</th>'; 
						mihtml += '			<th class="text-center">SITUACION CONTRACTUAL.</th>'; 
						mihtml += '		</tr>'; 
						mihtml += '	</thead>'; 
						mihtml += '	<tbody>'; 
						$.each(dato, function(i, item) {
							mihtml += '		<tr>';
							mihtml += '			<td class="text-left">'+item.NOMBRE+'</td>'; 
							mihtml += '			<td class="text-left">'+item.CARGO+'</td>';
							mihtml += '			<td class="text-center">'+item.EMP_F_INICICONTR+'</td>';
							mihtml += '			<td class="text-center">'+item.EMP_F_TERMICONTR+'</td>';
							mihtml += '			<td class="text-center">'+item.SITUACION+'</td>';
							
							mihtml += '		</tr>';
						});
						mihtml += '	<tbody>'; 
						mihtml += '</table>'; 
						$('.modal-dialog').css({"width":"1100px","margin":"30px auto"});
						var tituloModal = 'Dotacion Real';
						$( '.modal-title' ).html(tituloModal);
						$('.modal-body').html(mihtml);
					},	
					error: function(xhr, ajaxOptions, thrownError) {
							msg = "A ocurrido un error ";
							console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
				});//fin ajax			
			}
				
		});
		
		//Traslados Pend. Egreso
		$('#mod_sol_tras_pend_egreso').hover(function(){
			if (!($('#sol_tras_pend_egreso').val()) == '') {
				var p_empresa = $('#sol_local').find(':selected').attr('data-codempresa');
				var ccosto = $( '#sol_local' ).val();
				var p_cargo = $( '#sol_cargo_Dbnet' ).val();
							
				$.ajax({
					type:"POST",
					url :"<?php echo site_url(); ?>traslado/trasPendEgre",
					dataType:"json",
					data:{
						p_empresa : 	p_empresa,
						ccosto : 		ccosto,
						p_cargo : 		p_cargo
					},
					success: function(dato){
						var mihtml = '';
						mihtml += '<table id="tblModal" class="table table-striped">'; 
						mihtml += '	<thead>'; 
						mihtml += '		<tr style="padding-left: 1px; color: rgb(255, 255, 255); font-weight: bold; background-color: #00a7ed;">'; 
						mihtml += '			<th>NRO SOLICITUD</th>'; 
						mihtml += '			<th>RUT</th>'; 
						mihtml += '			<th>NOMBRE</th>'; 
						mihtml += '			<th>FECHA CREACION</th>'; 
						mihtml += '			<th>CC. ORIGEN</th>'; 
						mihtml += '			<th>DESCRIPCION</th>'; 
						mihtml += '			<th>CC. DESTINO</th>'; 
						mihtml += '			<th>DESCRIPCION</th>'; 
						mihtml += '			<th>ETAPA</th>'; 
						mihtml += '			<th>ESTADO</th>'; 
						mihtml += '			<th>DESCRICION ESTADO</th>'; 
						mihtml += '		</tr>'; 
						mihtml += '	</thead>'; 
						mihtml += '	<tbody>'; 
						$.each(dato, function(i, item) {
							mihtml += '		<tr>';
							mihtml += '			<td class="text-center">'+item.WF_KEY+'</td>'; 
							mihtml += '			<td class="text-center">'+item.RUT+'</td>'; 
							mihtml += '			<td class="text-left">'+item.NOMBRE+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CREATION_DATE+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CC_ORIGEN+'</td>'; 
							mihtml += '			<td class="text-left">'+item.DESC_UNIDAD_ORIGEN+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CC_DESTINO+'</td>'; 
							mihtml += '			<td class="text-left">'+item.DESC_UNIDAD_DESTINO+'</td>'; 
							mihtml += '			<td class="text-center">'+item.ETAPA_ACTUAL+'</td>'; 
							mihtml += '			<td class="text-left">'+item.ESTADO+'</td>'; 
							mihtml += '			<td class="text-left">'+item.ACCION_ESTADO+'</td>'; 
							mihtml += '		</tr>';
						});
						mihtml += '	<tbody>'; 
						mihtml += '</table>'; 
						var tituloModal = 'Traslados Pendiente de Egreso';
						$( '.modal-dialog' ).css({"width":"1100px","margin":"30px auto"});
						$( '.modal-title' ).html(tituloModal);
						$( '.modal-body' ).html(mihtml);
					},			
					error: function(xhr, ajaxOptions, thrownError) {
							msg = "A ocurrido un error ";
							console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
				});//fin ajax			
			}			
		});
		
		//Traslados Pend. Egreso destino
		$('#mod_sol_tras_pend_egreso_dest').hover(function(){

			if (!($('#sol_tras_pend_egreso_dest').val()) == '') {
				var p_empresa = $('#sol_local_dest').find(':selected').attr('data-codempresa');
				var ccosto = $( '#sol_local_dest' ).val();
				var p_cargo = $( '#sol_cargo_Dbnet' ).val();
			
				$.ajax({
					type:"POST",
					url :"<?php echo site_url(); ?>traslado/trasPendEgre",
					dataType:"json",
					data:{
						p_empresa : 	p_empresa,
						ccosto : 		ccosto,
						p_cargo : 		p_cargo
					},
					success: function(dato){
						var mihtml = '';
						mihtml += '<table id="tblModal" class="table table-striped">'; 
						mihtml += '	<thead>'; 
						mihtml += '		<tr style="padding-left: 1px; color: rgb(255, 255, 255); font-weight: bold; background-color: #00a7ed;">'; 
						mihtml += '			<th>NRO SOLICITUD</th>'; 
						mihtml += '			<th>RUT</th>'; 
						mihtml += '			<th>FECHA CREACION</th>'; 
						mihtml += '			<th>NOMBRE</th>'; 
						mihtml += '			<th>CC. ORIGEN</th>'; 
						mihtml += '			<th>DESCRIPCION</th>'; 
						mihtml += '			<th>CC. DESTINO</th>'; 
						mihtml += '			<th>DESCRIPCION</th>'; 
						mihtml += '			<th>ETAPA</th>'; 
						mihtml += '			<th>ESTADO</th>'; 
						mihtml += '			<th>DESCRICION ESTADO</th>'; 
						mihtml += '		</tr>'; 
						mihtml += '	</thead>'; 
						mihtml += '	<tbody>'; 
						$.each(dato, function(i, item) {
							mihtml += '		<tr>';
							mihtml += '			<td class="text-center">'+item.WF_KEY+'</td>'; 
							mihtml += '			<td class="text-center">'+item.RUT+'</td>'; 
							mihtml += '			<td class="text-left">'+item.NOMBRE+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CREATION_DATE+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CC_ORIGEN+'</td>'; 
							mihtml += '			<td class="text-left">'+item.DESC_UNIDAD_ORIGEN+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CC_DESTINO+'</td>'; 
							mihtml += '			<td class="text-left">'+item.DESC_UNIDAD_DESTINO+'</td>'; 
							mihtml += '			<td class="text-center">'+item.ETAPA_ACTUAL+'</td>'; 
							mihtml += '			<td class="text-left">'+item.ESTADO+'</td>'; 
							mihtml += '			<td class="text-left">'+item.ACCION_ESTADO+'</td>'; 
							mihtml += '		</tr>';
						});
						mihtml += '	<tbody>'; 
						mihtml += '</table>'; 
						var tituloModal = 'Traslados Pendiente de Egreso';
						$( '.modal-dialog' ).css({"width":"1000px","margin":"30px auto"});
						$( '.modal-title' ).html(tituloModal);
						$( '.modal-body' ).html(mihtml);
					},			
					error: function(xhr, ajaxOptions, thrownError) {
							msg = "A ocurrido un error ";
							console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
				});//fin ajax			
			}
		});
		
		//Traslados Pend.  Ingreso
		$('#mod_sol_tras_pend_ingreso').hover(function(){

			if (!($('#sol_tras_pend_ingreso').val()) == '') {	
				var p_empresa = 	$('#sol_local').find(':selected').attr('data-codempresa');
				var ccosto = 		$( '#sol_local' ).val();
				var p_cargo = 		$( '#sol_cargo_Dbnet' ).val();
							
				$.ajax({
					type:"POST",
					url :"<?php echo site_url(); ?>traslado/trasPendIngre",
					dataType:"json",
					data:{
						p_empresa : 	p_empresa,
						ccosto : 		ccosto,
						p_cargo : 		p_cargo
					},
					success: function(dato){
						var mihtml = '';
						mihtml += '<table id="tblModal" class="table table-striped">'; 
						mihtml += '	<thead>'; 
						mihtml += '		<tr style="padding-left: 1px; color: rgb(255, 255, 255); font-weight: bold; background-color: #00a7ed;">'; 
						mihtml += '			<th>NRO SOLICITUD</th>'; 
						mihtml += '			<th>FECHA CREACION</th>'; 
						mihtml += '			<th>RUT</th>'; 
						mihtml += '			<th>NOMBRE</th>'; 
						mihtml += '			<th>CC. ORIGEN</th>'; 
						mihtml += '			<th>DESCRIPCION</th>'; 
						mihtml += '			<th>CC. DESTINO</th>'; 
						mihtml += '			<th>DESCRIPCION</th>'; 
						mihtml += '			<th>ETAPA</th>'; 
						mihtml += '			<th>ESTADO</th>'; 
						mihtml += '			<th>DESCRICION ESTADO</th>'; 
						mihtml += '		</tr>'; 
						mihtml += '	</thead>'; 
						mihtml += '	<tbody>'; 
						$.each(dato, function(i, item) {
							mihtml += '		<tr>';
							mihtml += '			<td class="text-center">'+item.WF_KEY+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CREATION_DATE+'</td>'; 
							mihtml += '			<td class="text-center">'+item.RUT+'</td>'; 
							mihtml += '			<td class="text-left">'+item.NOMBRE+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CC_ORIGEN+'</td>'; 
							mihtml += '			<td class="text-left">'+item.DESC_UNIDAD_ORIGEN+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CC_DESTINO+'</td>'; 
							mihtml += '			<td class="text-left">'+item.DESC_UNIDAD_DESTINO+'</td>'; 
							mihtml += '			<td class="text-center">'+item.ETAPA_ACTUAL+'</td>'; 
							mihtml += '			<td class="text-left">'+item.ESTADO+'</td>'; 
							mihtml += '			<td class="text-left">'+item.ACCION_ESTADO+'</td>'; 
							mihtml += '		</tr>';
						});
						mihtml += '	<tbody>'; 
						mihtml += '</table>'; 
						var tituloModal = 'Traslados Pendiente de Ingreso';
						$( '.modal-dialog' ).css({"width":"1100px","margin":"30px auto"});
						$( '.modal-title' ).html(tituloModal);
						$( '.modal-body' ).html(mihtml);
					},			
					error: function(xhr, ajaxOptions, thrownError) {
							msg = "A ocurrido un error ";
							console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
				});//fin ajax			
			}		
		});
		
		//Traslados Pend.  Ingreso destino
		$('#mod_sol_tras_pend_ingreso_dest').hover(function(){

			if (!($('#sol_tras_pend_ingreso_dest').val()) == '') {
				var p_empresa = 	$('#sol_local_dest').find(':selected').attr('data-codempresa');
				var ccosto = 		$( '#sol_local_dest' ).val();
				var p_cargo = 		$( '#sol_cargo_Dbnet' ).val();
							
				$.ajax({
					type:"POST",
					url :"<?php echo site_url(); ?>traslado/trasPendIngre",
					dataType:"json",
					data:{
						p_empresa : 	p_empresa,
						ccosto : 		ccosto,
						p_cargo : 		p_cargo
					},
					success: function(dato){
						var mihtml = '';
						mihtml += '<table id="tblModal" class="table table-striped">'; 
						mihtml += '	<thead>'; 
						mihtml += '		<tr style="padding-left: 1px; color: rgb(255, 255, 255); font-weight: bold; background-color: #00a7ed;"">'; 
						mihtml += '			<th>NRO SOLICITUD</th>'; 
						mihtml += '			<th>FECHA CREACION</th>'; 
						mihtml += '			<th>RUT</th>'; 
						mihtml += '			<th>NOMBRE</th>'; 
						mihtml += '			<th>CC. ORIGEN</th>'; 
						mihtml += '			<th>DESCRIPCION</th>'; 
						mihtml += '			<th>CC. DESTINO</th>'; 
						mihtml += '			<th>DESCRIPCION</th>'; 
						mihtml += '			<th>ETAPA</th>'; 
						mihtml += '			<th>ESTADO</th>'; 
						mihtml += '			<th>DESCRICION ESTADO</th>'; 
						mihtml += '		</tr>'; 
						mihtml += '	</thead>'; 
						mihtml += '	<tbody>'; 
						$.each(dato, function(i, item) {
							mihtml += '		<tr>';
							mihtml += '			<td class="text-center">'+item.WF_KEY+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CREATION_DATE+'</td>'; 
							mihtml += '			<td class="text-left">'+item.RUT+'</td>'; 
							mihtml += '			<td class="text-center">'+item.NOMBRE+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CC_ORIGEN+'</td>'; 
							mihtml += '			<td class="text-left">'+item.DESC_UNIDAD_ORIGEN+'</td>'; 
							mihtml += '			<td class="text-center">'+item.CC_DESTINO+'</td>'; 
							mihtml += '			<td class="text-left">'+item.DESC_UNIDAD_DESTINO+'</td>'; 
							mihtml += '			<td class="text-center">'+item.ETAPA_ACTUAL+'</td>'; 
							mihtml += '			<td class="text-left">'+item.ESTADO+'</td>'; 
							mihtml += '			<td class="text-left">'+item.ACCION_ESTADO+'</td>'; 
							mihtml += '		</tr>';
						});
						mihtml += '	<tbody>'; 
						mihtml += '</table>'; 
						var tituloModal = 'Traslados Pendiente de Ingreso';
						$( '.modal-dialog' ).css({"width":"940px","margin":"30px auto"});
						$( '.modal-title' ).html(tituloModal);
						$( '.modal-body' ).html(mihtml);
					},			
					error: function(xhr, ajaxOptions, thrownError) {
							msg = "A ocurridoff un error ";
							console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
				});//fin ajax	
			
			}
		});

		//======Mostrar en modal las personas con 30 dias de licencia acumulados los ultimos 45 dias en origen=========//
		$('#mod_sol_dot_licen').hover(function(){
			
			if (!($('#sol_dot_licen').val()) == '') {
				var p_empresa = $('#sol_local').find(':selected').attr('data-codempresa');
				var ccosto = $( '#sol_local' ).val();
				var p_cargo = $( '#sol_cargo_Dbnet' ).val();
				$.ajax({
					type:"POST",
					url :"<?php echo site_url(); ?>traslado/trasLic",
					dataType:"json",
					data:{
						p_empresa : 	p_empresa,
						ccosto : 		ccosto,
						p_cargo : 		p_cargo
						
					},
					success: function(dato){
						var mihtml = '';
						mihtml += '<table id="tblModal" class="table table-striped">'; 
						mihtml += '	<thead>'; 
						mihtml += '		<tr style="padding-left: 1px; color: rgb(255, 255, 255); font-weight: bold; background-color: #00a7ed;">'; 
						mihtml += '			<th class="text-left">NOMBRE</th>'; 
						mihtml += '			<th class="text-left">CARGO</th>'; 
						mihtml += '			<th class="text-left">INICIO LICENCIA</th>'; 
						mihtml += '			<th class="text-center">TERMINO LICENCIA</th>'; 
						mihtml += '			<th class="text-center">DIAS DE LICENCIA</th>'; 
						mihtml += '		</tr>'; 
						mihtml += '	</thead>'; 
						mihtml += '	<tbody>'; 
						$.each(dato, function(i, item) {
							mihtml += '		<tr>';
							mihtml += '			<td class="text-left">'+item.NOMBRE+'</td>'; 
							mihtml += '			<td class="text-left">'+item.DESC_CARGO+'</td>'; 
							mihtml += '			<td class="text-left">'+item.INICIO_LIC+'</td>';
							mihtml += '			<td class="text-center">'+item.TERM_LIC+'</td>';
							mihtml += '			<td class="text-center">'+item.NRO_DIAS_LIC+'</td>';
							mihtml += '		</tr>';
						});
						mihtml += '	<tbody>'; 
						mihtml += '</table>'; 
						$('.modal-dialog').css({"width":"940px","margin":"30px auto"});
						var tituloModal = 'Licencias';
						$( '.modal-title' ).html(tituloModal);
						$('.modal-body').html(mihtml);
					},			
					error: function(xhr, ajaxOptions, thrownError) {
							msg = "A ocurridoff un error ";
							console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
				});//fin ajax			
			
			}
		});

		//======Mostrar en modal las personas con 30 dias de licencia acumulados los ultimos 45 dias en destino=========//
		$('#mod_sol_dot_licen_dest').hover(function(){
			
			if (!($('#sol_dot_licen').val()) == '') {
				var p_empresa = $('#sol_local').find(':selected').attr('data-codempresa');
				var ccosto = $( '#sol_local_dest' ).val();
				var p_cargo = $( '#sol_cargo_Dbnet' ).val();
				$.ajax({
					type:"POST",
					url :"<?php echo site_url(); ?>traslado/trasLic",
					dataType:"json",
					data:{
						p_empresa : 	p_empresa,
						ccosto : 		ccosto,
						p_cargo : 		p_cargo
						
					},
					success: function(dato){
						var mihtml = '';
						mihtml += '<table id="tblModal" class="table table-striped">'; 
						mihtml += '	<thead>'; 
						mihtml += '		<tr style="padding-left: 1px; color: rgb(255, 255, 255); font-weight: bold; background-color: #00a7ed;">'; 
						mihtml += '			<th class="text-left">NOMBRE</th>'; 
						mihtml += '			<th class="text-left">CARGO</th>'; 
						mihtml += '			<th class="text-left">INICIO LICENCIA</th>'; 
						mihtml += '			<th class="text-center">TERMINO LICENCIA</th>'; 
						mihtml += '			<th class="text-center">DIAS DE LICENCIA</th>'; 
						mihtml += '		</tr>'; 
						mihtml += '	</thead>'; 
						mihtml += '	<tbody>'; 
						$.each(dato, function(i, item) {
							mihtml += '		<tr>';
							mihtml += '			<td class="text-left">'+item.NOMBRE+'</td>'; 
							mihtml += '			<td class="text-left">'+item.DESC_CARGO+'</td>'; 
							mihtml += '			<td class="text-left">'+item.INICIO_LIC+'</td>';
							mihtml += '			<td class="text-center">'+item.TERM_LIC+'</td>';
							mihtml += '			<td class="text-center">'+item.NRO_DIAS_LIC+'</td>';
							mihtml += '		</tr>';
						});
						mihtml += '	<tbody>'; 
						mihtml += '</table>'; 
						$('.modal-dialog').css({"width":"940px","margin":"30px auto"});
						var tituloModal = 'Licencias';
						$( '.modal-title' ).html(tituloModal);
						$('.modal-body').html(mihtml);
					},			
					error: function(xhr, ajaxOptions, thrownError) {
							msg = "A ocurridoff un error ";
							console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
				});//fin ajax			
			
			}
		});

		//======Mostrar en modal las personas con 30 dias de permiso acumulados los ultimos 45 dias en destino=========//
		$('#mod_sol_dot_permiso').hover(function(){
			
			if (!($('#sol_dot_permiso').val()) == '') {
				var p_empresa = $('#sol_local').find(':selected').attr('data-codempresa');
				var ccosto = $( '#sol_local' ).val();
				var p_cargo = $( '#sol_cargo_Dbnet' ).val();
				$.ajax({
					type:"POST",
					url :"<?php echo site_url(); ?>traslado/trasPer",
					dataType:"json",
					data:{
						p_empresa : 	p_empresa,
						ccosto : 		ccosto,
						p_cargo : 		p_cargo
						
					},
					success: function(dato){
						var mihtml = '';
						mihtml += '<table id="tblModal" class="table table-striped">'; 
						mihtml += '	<thead>'; 
						mihtml += '		<tr style="padding-left: 1px; color: rgb(255, 255, 255); font-weight: bold; background-color: #00a7ed;">'; 
						mihtml += '			<th class="text-left">NOMBRE</th>'; 
						mihtml += '			<th class="text-left">CARGO</th>'; 
						mihtml += '			<th class="text-left">INICIO PERMISO</th>'; 
						mihtml += '			<th class="text-center">TERMINO PERMISO</th>'; 
						mihtml += '			<th class="text-center">DIAS DE PERMISO</th>'; 
						mihtml += '		</tr>'; 
						mihtml += '	</thead>'; 
						mihtml += '	<tbody>'; 
						$.each(dato, function(i, item) {
							mihtml += '		<tr>';
							mihtml += '			<td class="text-left">'+item.NOMBRE+'</td>'; 
							mihtml += '			<td class="text-left">'+item.DESC_CARGO+'</td>'; 
							mihtml += '			<td class="text-left">'+item.FECHA_INICIO_PERM+'</td>';
							mihtml += '			<td class="text-center">'+item.FECHA_TERMINO_PERM+'</td>';
							mihtml += '			<td class="text-center">'+item.NRO_DIAS_PERM+'</td>';
							mihtml += '		</tr>';
						});
						mihtml += '	<tbody>'; 
						mihtml += '</table>'; 
						$('.modal-dialog').css({"width":"940px","margin":"30px auto"});
						var tituloModal = 'PERMISOS';
						$( '.modal-title' ).html(tituloModal);
						$('.modal-body').html(mihtml);
					},			
					error: function(xhr, ajaxOptions, thrownError) {
							msg = "A ocurridoff un error ";
							console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
				});//fin ajax			
			
			}
		});

		//======Mostrar en modal las personas con 30 dias de permiso acumulados los ultimos 45 dias en destino=========//
		$('#mod_sol_dot_permiso_dest').hover(function(){
			
			if (!($('#sol_dot_permiso').val()) == '') {
				var p_empresa = $('#sol_local').find(':selected').attr('data-codempresa');
				var ccosto = $( '#sol_local_dest' ).val();
				var p_cargo = $( '#sol_cargo_Dbnet' ).val();
				$.ajax({
					type:"POST",
					url :"<?php echo site_url(); ?>traslado/trasPer",
					dataType:"json",
					data:{
						p_empresa : 	p_empresa,
						ccosto : 		ccosto,
						p_cargo : 		p_cargo
						
					},
					success: function(dato){
						var mihtml = '';
						mihtml += '<table id="tblModal" class="table table-striped">'; 
						mihtml += '	<thead>'; 
						mihtml += '		<tr style="padding-left: 1px; color: rgb(255, 255, 255); font-weight: bold; background-color: #00a7ed;">'; 
						mihtml += '			<th class="text-left">NOMBRE</th>'; 
						mihtml += '			<th class="text-left">CARGO</th>'; 
						mihtml += '			<th class="text-left">INICIO PERMISO</th>'; 
						mihtml += '			<th class="text-center">TERMINO PERMISO</th>'; 
						mihtml += '			<th class="text-center">DIAS DE PERMISO</th>'; 
						mihtml += '		</tr>'; 
						mihtml += '	</thead>'; 
						mihtml += '	<tbody>'; 
						$.each(dato, function(i, item) {
							mihtml += '		<tr>';
							mihtml += '			<td class="text-left">'+item.NOMBRE+'</td>'; 
							mihtml += '			<td class="text-left">'+item.DESC_CARGO+'</td>'; 
							mihtml += '			<td class="text-left">'+item.FECHA_INICIO_PERM+'</td>';
							mihtml += '			<td class="text-center">'+item.FECHA_TERMINO_PERM+'</td>';
							mihtml += '			<td class="text-center">'+item.NRO_DIAS_PERM+'</td>';
							mihtml += '		</tr>';
						});
						mihtml += '	<tbody>'; 
						mihtml += '</table>'; 
						$('.modal-dialog').css({"width":"940px","margin":"30px auto"});
						var tituloModal = 'PERMISOS';
						$( '.modal-title' ).html(tituloModal);
						$('.modal-body').html(mihtml);
					},			
					error: function(xhr, ajaxOptions, thrownError) {
							msg = "A ocurridoff un error ";
							console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
				});//fin ajax			
			
			}
		});

		/*==========poblar rut origen inicio==============*/
		$("#sol_local").change(function() {

			$("#sol_local_dest").val("");
			$("#sol_cui").val("");
			$("#sol_cui_desc").val("");
			$("#sol_fech_inic_contr").val("");
			$("#sol_fech_term_contr").val("");
			$("#sol_situac_contr_cod_hidden").val("");
			$("#sol_situac_contr").val("");
			$("#sol_cargo_Dbnet").val("");
			$("#sol_cargo_Dbnet_desc").val("");
			$("#sol_fech_cambio").val("");
			$("#sol_trasl_apro12").val("");
			$("#sol_trasl_pend12").val("");
			$("#sol_cargo_jer").val("");
			$("#sol_cargo_jer_desc").val("");
			$("#sol_asist_recl_sel").val("");
			$("#sol_asist_recl_sel_hidden").val("");
			$("#sol_asist_proc_compe").val("");
			$("#sol_asist_proc_compe_hidden").val("");
			$("#sol_proveedor").val("");
			$("#sol_proveedor_desc").val("");
			$("#sol_dot_teo").val("");
			$("#sol_dot_mae").val("");
			$('#sol_dot_licen_dest').val("");
			$('#sol_dot_permiso_dest').val("");
			$("#sol_tras_pend_egreso").val("");
			$("#sol_tras_pend_ingreso").val("");
			$("#sol_egre_trasl_act_orig").val("");
			$("#sol_dot_fut").val("");
			$("#sol_zonal_origen").val("");
			$("#sol_cui_dest").val("");
			$("#sol_cui_desc_dest").val("");
			$("#sol_zonal_dest").val("");
			$("#sol_asis_reclut_selec_dest").val("");
			$("#sol_asis_proc_comp_dest").val("");
			$("#sol_dot_teo_dest").val("");
			$("#sol_dot_mae_dest").val("");
			$("#sol_tras_pend_egreso_dest").val("");
			$("#sol_tras_pend_ingreso_dest").val("");
			$("#sol_egres_tras_act_dest").val("");
			$("#sol_dot_futura_dest").val("");

			$("#sol_prom_cant").val("");
			$('#sol_local_dest').html('<option value="">----</option>');			
			
			/*vaciar inicio*/	
			if($('#sol_local').val() == '') {
				$('#frm_solicitud_traslado')[0].reset();
				$('#sol_rut,#sol_local_dest').html('<option value="">----</option>');
				return false;
			}
			//alert($('#sol_local').val());
			localStorage.removeItem("idflujoLS"); 
			
			/*vaciar fin*/			
			//ccosto = UNI_K_CODUNIDAD(campo bd)
			var codempresa = $(this).find(':selected').attr('data-codempresa');
			//console.log(codempresa); da 34/11
			//console.log($('#sol_local').val());



			/*obtener el id del flujo inicio*/
			$.ajax({
				type:"POST",
				url :"<?php echo site_url(); ?>traslado/flujo",
				dataType:"json",
				data:{
					empresa: 	codempresa,
					rutLogueado: 	"<?php echo $_SESSION['rut']; ?>"//8710593 ::r1 flujo 100 10024389
				},
	            beforeSend : function (){
	                $.blockUI({
	                    fadeIn : 0,
	                    fadeOut : 0,
	                    showOverlay : true,
	                    message: '<h1><img src="<?php echo site_url(); ?>assets/template/tpl-sb/img/ajax-loader.gif" /><br />Espere un momento...</h1>'
	                });
	            },				
				success: function(data){
					if (data.id_de_flujo == 'nulo') {
						alert("El rut no esta asociado con ningun flujo. Por lo que no puede continuar ingresando datos en esta solicitud");
						location.href="<?php echo site_url(); ?>";
					} else {
						//alert('tiene asociado un flujo');
						//obtener flujo
						$( '#sol_id_flujo_hidden' ).val(data);
						localStorage.setItem('idflujoLS', data);
						//console.log('creo la variable en localStorage');
					}
				},
                complete : function (){
                    $.unblockUI();
                },  				
				error: function(xhr, ajaxOptions, thrownError) {
					msg = "A ocurrid un error. Puede haber un inconveniente con la comunicacion hacia nuestro servidor de BASE DE DATOS para obtener el listado de Colaboradores. ";
					//console.log(msg+" "+xhr.status + " " + xhr.statusText);
					//alert("(0001) Puede haber un inconveniente con la comunicacion hacia nuestro servidor de BASE DE DATOS para obtener el Flujo.");
				}
			}).done(function() {
/*inicio dermo*/
			/*llenar rut inicio*/
			//trae o obtiene un listado de los colaboradores del local seleccionado LISTA,LISTADO,COLABORADORES
			//var codempresa = $(this).find(':selected').attr('data-codempresa');
			//alert("dsfsdfsdf7987987");
			$.ajax({
				type:"POST",
				url :"<?php echo site_url(); ?>traslado/rutSolTras",
				dataType:"json",
				data:{
					empresa: 	codempresa,
					ccosto: 	$('#sol_local').val(),
					idflujo:  	localStorage.getItem('idflujoLS'),
                    rut: "<?php echo $_SESSION['rut']; ?>"
				},
				success: function(data){
					ccostoElegito = $('#sol_local').val();
					//console.log(resultado);
					if (typeof data.existendatos != "undefined") {
						var lstRutsHtml = ''; 
						lstRutsHtml += '<option value=\"\" selected>----</option>';
						$('#sol_rut').html(lstRutsHtml); 
						//alert("No hay colaboradores asociados a este Centro de costo.");
						soloAlertaPersonalizada('Advertencia','No hay colaboradores asociados a este Centro de costo.');
						
					} else {
						var lstRutsHtml = ''; 
						lstRutsHtml += '<option value="" selected>Elija colaborador</option>'; 
						$.each(data.rut, function(i, item) {
							lstRutsHtml += '<option value="'+data.rut[i]+'">'+data.rut[i]+' '+data.nombre[i]+'</option>';
						});
						$('#sol_rut').html(lstRutsHtml);
					}
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					msg = "A ocurrid un error. Puede habar un inconveniente con la comunicacion hacia nuestro servidor de BASE DE DATOS para obtener el listado de Colaboradores. ";
					//console.log(msg+" "+xhr.status + " " + xhr.statusText);
					//alert("(0002) Puede habar un inconveniente con la comunicacion hacia nuestro servidor de BASE DE DATOS para obtener el listado de Colaboradores.");
					console.log('error 0002');
				}
			});	
			/*llenar rut fin*/
			
			
			
			
			
			
			
			
			/*validar correos inicio 809384094*/	
			$.ajax({
				type:"POST",
				url :"<?php echo site_url(); ?>traslado/validarMailFlujo",
				dataType:"json",
				data:{
					nroCCosto:$('#sol_local').val(),
					codempresa:codempresa,
					flujo : $('#sol_id_flujo_hidden').val()
				},
				success: function(dato){
					var mensajeCompleto = '';
					
					for (var i=0; i < dato.length; i++) {
						if ( dato[i].v_mail != ' ' ) {
							mensajeCompleto += '<p style="font-size:1em;">' + dato[i].v_mail + '<br />';
							mensajeCompleto += 'Se enviara correo a ' + dato[i].nombre + ' ' + dato[i].email + ' para solucionar su problema</p>';
							mensajeCompleto += '<hr />';

							//->jagl eliminar el de abajo para prasar a produccion
							//dato[i].email = '<?php echo MAILUSUARIOTEST; ?>';
							
							enviaMailInvolugrados(dato[i].email, dato[i].v_mail);							
						}
					}

					if ( mensajeCompleto != '' ) {
						var tituloMsg = '<p><b>Motivos</b></p>';
						mensajeCompleto = tituloMsg + mensajeCompleto
						alertaReloadPage('Advertencia: No se puede generar esta solicitud', mensajeCompleto);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
				msg = "A ocurrido un error ";
					console.log(msg+" "+xhr.status + " " + xhr.statusText);
				}
			});
			/*validar correos fin 809384094*/	
			
			
			
			
			
			
			
			

			/*llenar locales destino inicio #sol_local_dest*/
			$.ajax({
				type:"POST",
				url :"<?php echo site_url(); ?>traslado/cCostoDest",
				dataType:"json",
				data:{
					empresa: 	codempresa,
					ccosto: 	$('#sol_local').val()
				},
				success: function(respuesta){
					var lstCCostoDest = '<option value="" selected>Elija Centro de costo</option>'; 
					$.each(respuesta, function(i, item) {
						lstCCostoDest += '<option data-codempresa="'+item.EMPRESA+'" value="'+item.CODUNIDAD+'">'+item.CODUNIDAD+' '+item.NOMBUNIDAD+'</option>'; 
					});		
					$('#sol_local_dest').html(lstCCostoDest);			
				},		
				error: function(xhr, ajaxOptions, thrownError) {
					msg = "A ocurrid un error. Puede haber un inconveniente con la comunicacion hacia nuestro servidor de BASE DE DATOS para obtener el/los C costos de destino. ";
					console.log(msg+" "+xhr.status + " " + xhr.statusText);
					soloAlertaPersonalizada('Advertencia',"Puede haber un inconveniente con la comunicacion hacia nuestro servidor de BASE DE DATOS para obtener el/los C costos de destino. COD:001");
				}
			});	
		});		


/*fin dermo*/
			});			
			/*obtener el id del flujo fin*/
			
		$("#sol_rut").mousedown(function() {
			var sol_motivo = $('#sol_motivo').val();

			if (sol_motivo == "") {
				$( "#sol_motivo" ).focus();
				var contenidoAlert = "Antes debe seleccionar motivo";
				var tituloAlert = "Advertencia";
				soloAlertaPersonalizada(tituloAlert,contenidoAlert);	
				return false;
			}
		});					

		/*
		$('#sol_rut').click(function(e) {

			var sol_motivo = $('#sol_motivo').val();

			if (sol_motivo == "") {
				$( "#sol_motivo" ).focus();
				var contenidoAlert = "Antes debe seleccionar motivo";
				var tituloAlert = "Advertencia";
				soloAlertaPersonalizada(tituloAlert,contenidoAlert);	
				return false;
			}
						
						
		});
		*/

		/*==========poblar rut origen fin==============*/
		/*==========================poblar formulario automaticamente inicio=============================*/
		$('#sol_rut').change(function(e) {
			
			$("#sol_local_dest").val("");
			$("#sol_cui").val("");
			$("#sol_cui_desc").val("");
			$("#sol_fech_inic_contr").val("");
			$("#sol_fech_term_contr").val("");
			$("#sol_situac_contr_cod_hidden").val("");
			$("#sol_situac_contr").val("");
			$("#sol_cargo_Dbnet").val("");
			$("#sol_cargo_Dbnet_desc").val("");
			$("#sol_trasl_apro12").val("");
			$("#sol_trasl_pend12").val("");
			$("#sol_cargo_jer").val("");
			$("#sol_cargo_jer_desc").val("");
			$("#sol_asist_recl_sel").val("");
			$("#sol_asist_recl_sel_hidden").val("");
			$("#sol_asist_proc_compe").val("");
			$("#sol_asist_proc_compe_hidden").val("");
			$("#sol_proveedor").val("");
			$("#sol_proveedor_desc").val("");
			$("#sol_dot_teo").val("");
			$("#sol_dot_mae").val("");
			$('#sol_dot_licen_dest').val("");
			$('#sol_dot_permiso_dest').val("");
			$("#sol_tras_pend_egreso").val("");
			$("#sol_tras_pend_ingreso").val("");
			$("#sol_egre_trasl_act_orig").val("");
			$("#sol_dot_fut").val("");
			$("#sol_cui_dest").val("");
			$("#sol_cui_desc_dest").val("");
			$("#sol_zonal_dest").val("");
			$("#sol_asis_reclut_selec_dest").val("");
			$("#sol_asis_proc_comp_dest").val("");
			$("#sol_dot_teo_dest").val("");
			$("#sol_dot_mae_dest").val("");
			$("#sol_tras_pend_egreso_dest").val("");
			$("#sol_tras_pend_ingreso_dest").val("");
			$("#sol_egres_tras_act_dest").val("");
			$("#sol_dot_futura_dest").val("");
			localStorage.removeItem("idflujoLS"); 

			//autocompleta los datos una vez elegido el rut
			var codempresa = $('#sol_local').find(':selected').attr('data-codempresa');
			$.ajax({
				type:"POST",
				url :"<?php echo site_url(); ?>etapa/fillFields",
				dataType:"json",
				data:{
					tipo: 		'ORI',
					empresa: 	codempresa,
					rut: 		$('#sol_rut').val(),
					ccosto: 	$('#sol_local').val(),
					sol_motivo: $('#sol_motivo').val(),
					flujo : 	$('#sol_id_flujo_hidden').val()
				},
	            beforeSend : function (){
	                $.blockUI({
	                    fadeIn : 0,
	                    fadeOut : 0,
	                    showOverlay : true,
	                    message: '<h1><img src="<?php echo site_url(); ?>assets/template/tpl-sb/img/ajax-loader.gif" /><br />Espere un momento...</h1>'
	                });
	            },					
				success: function(data){
					//console.log(data);
					//console.log(data.existe);
					//var solMotivo = $('#sol_motivo').val();
					//$('#sol_motivo').val(solMotivo);	
					
					if (data.sol_cui == "") {
						var contenidoAlert = "Este colaborador no tiene CUI asociado, usted no puede generar esta solicitud, por favor comunicarse con Gestión de Personas.";
						var tituloAlert = "Advertencia";
						alertaPersonalizada(tituloAlert,contenidoAlert);						
						localStorage.removeItem("idflujoLS"); 
						return false;
					}
					
					if (data.existe == "si") {
						//alert("Este colaborador ya tiene una solicitud de traslado pendiente, por lo que es IMPOSIBLE generar otra");
						var tituloAlert = "Advertencia";
						var contenidoAlert = "Este colaborador ya tiene una solicitud de traslado pendiente, por lo que es IMPOSIBLE generar otra.";
						alertaPersonalizada(tituloAlert,contenidoAlert);
					} else {
						var manRut = $('#sol_rut').val();
						var manCcosto = $('#sol_local').val();
						$('#sol_rut').val(manRut);
						$('#sol_local').val(manCcosto);
						
						$('#sol_asist_proc_compe').val(data.sol_asist_proc_compe);
						$('#sol_asist_proc_compe_hidden').val(data.sol_asist_proc_compe_hidden);
						$('#sol_asist_recl_sel').val(data.sol_asist_recl_sel);
						$('#sol_asist_recl_sel_hidden').val(data.sol_asist_recl_sel_hidden);
						$('#sol_cargo_Dbnet').val(data.sol_cargo_Dbnet);
						$('#sol_cargo_Dbnet_desc').val(data.sol_cargo_Dbnet_desc);
						$('#sol_cargo_jer').val(data.sol_cargo_jer);
						$('#sol_cargo_jer_desc').val(data.sol_cargo_jer_desc);
						$('#sol_desc_loc').val(data.sol_desc_loc);
						$('#sol_dot_fut').val(data.sol_dot_fut);
						$('#sol_dot_licen').val(data.sol_dot_licen);
						$('#sol_dot_permiso').val(data.sol_dot_permiso)
						$('#sol_zonal_origen').val(data.sol_zonal_origen);
						$('#sol_dot_mae').val(data.sol_dot_mae);
						$('#sol_dot_teo').val(data.sol_dot_teo);
						$('#sol_egre_trasl_act_orig').val(data.sol_egre_trasl_act_orig);
						$('#sol_fech_inic_contr').val(data.sol_fech_inic_contr);
						$('#sol_fech_term_contr').val(data.sol_fech_term_contr);
						$('#sol_nombre').val(data.sol_nombre);
						$('#sol_proveedor').val(data.sol_proveedor);
						$('#sol_proveedor_desc').val(data.sol_proveedor_desc);
						$('#sol_situac_contr').val(data.sol_situac_contr);
						$('#sol_situac_contr_cod_hidden').val(data.sol_situac_contr_cod_hidden);
						$('#sol_tras_pend_egreso').val(data.sol_tras_pend_egreso);
						$('#sol_tras_pend_ingreso').val(data.sol_tras_pend_ingreso);
						$('#sol_trasl_apro12').val(data.sol_trasl_apro12);
						$('#sol_trasl_pend12').val(data.sol_trasl_pend12);
						$('#sol_cui').val(data.sol_cui);
						$('#sol_cui_desc').val(data.sol_cui_desc);
						$('#sol_zonal_origen_hidden').val(data.sol_zonal_origen_hidden);
						$('#sol_zonal_rut_origen_hidden').val(data.sol_zonal_rut_origen_hidden);	

						$('#sol_prom_cant').val(data.sol_prom_cant);
						$('#sol_prom_desc').val(data.sol_prom_desc);	
						$('#sol_prom_desc').val(data.sol_prom_desc);		
						$('#sol_tiene_comi_hidden').val(data.sol_tiene_comi_hidden);
					}; //fin else

					//desactiva el motivo para evitar que luego de haber elegido al colaborador cambie la opcion. Si quiere hacer esto, tiene que ingresar la orden de nuevo.
					$("#sol_motivo").attr("disabled", true);

				},	
                complete : function (){
                    $.unblockUI();
                },						
				error: function(xhr, ajaxOptions, thrownError) {
	
				msg = "A ocurrid un error. Puede haber un inconveniente con la comunicación hacia nuestro servidor de BASE DE DATOS para obtener los datos asociados del C. costo de origen. ";
				console.log(msg+" "+xhr.status + " " + xhr.statusText);
				alertaPersonalizada('Aviso','Puede haber un inconveniente con la comunicación hacia nuestro servidor de BASE DE DATOS para obtener los datos asociados del C. costo de origen.');
				
				}
			});//fin ajax	   
			e.preventDefault();
		});

		$("#sol_local_dest").change(function(){
			var codempresa = $(this).find(':selected').attr('data-codempresa');
/*validar correos inicio 435345345234*/
/*
validar si habra problema con correo con: 
-Jefe Zonal Origen
-Asistente Procesos
-Asistente Seleccion
-Local de Origen
-Control de Gestion
*/		
$.ajax({
	type:"POST",
	url :"<?php echo site_url(); ?>traslado/validarMailFlujoDest",
	dataType:"json",
	data:{
		nroCCosto:$('#sol_local_dest').val(),
		codempresa:codempresa,
		flujo : 	$('#sol_id_flujo_hidden').val()
	},
	success: function(dato){
		var mensajeCompleto = '';
		for (var i=0; i < dato.length; i++) {
			if ( dato[i].v_mail != ' ' ) {
				mensajeCompleto += '<p style="font-size:1em;">' + dato[i].v_mail + '<br />';
				mensajeCompleto += 'Se enviara correo a ' + dato[i].nombre + ' ' + dato[i].email + ' para solucionar su problema</p>';
				mensajeCompleto += '<hr />';

				//->jagl eliminar el de abajo para pasar a produccion				
				//dato[i].email = '<?php echo MAILUSUARIOTEST; ?>';
				
				enviaMailInvolugrados(dato[i].email, dato[i].v_mail);
			}
		}

		if ( mensajeCompleto != '' ) {
			alertaReloadPage('Advertencia', mensajeCompleto);
		}
	},
	error: function(xhr, ajaxOptions, thrownError) {
	msg = "A ocurrido un error ";
		console.log(msg+" "+xhr.status + " " + xhr.statusText);
	}
});
/*validar correos fin 435345345234*/				
			$.ajax({
				type:"POST",
				url :"<?php echo site_url(); ?>etapa/fillFields",
				dataType:"json",
				data:{
					tipo: 		'DEST',
					empresa: 	codempresa,
					rut: 		$('#sol_rut').val(),
					ccosto: 	$('#sol_local_dest').val(),
					flujo : 	$('#sol_id_flujo_hidden').val()
				},
	            beforeSend : function (){
	                $.blockUI({
	                    fadeIn : 0,
	                    fadeOut : 0,
	                    showOverlay : true,
	                    message: '<h1><img src="<?php echo site_url(); ?>assets/template/tpl-sb/img/ajax-loader.gif" /><br />Espere un momento...</h1>'
	                });
	            },					
				success: function(resultado){
					$('#sol_asis_proc_comp_dest').val(resultado.sol_asis_proc_comp_dest);
					$('#sol_asis_proc_comp_dest_hidden').val(resultado.sol_asis_proc_comp_dest_hidden);
					$('#sol_asis_reclut_selec_dest').val(resultado.sol_asis_reclut_selec_dest);
					$('#sol_asis_reclut_selec_dest_hidden').val(resultado.sol_asis_reclut_selec_dest_hidden);
					$('#sol_dot_futura_dest').val(resultado.sol_dot_futura_dest);
					$('#sol_dot_mae_dest').val(resultado.sol_dot_mae_dest);
					$('#sol_dot_teo_dest').val(resultado.sol_dot_teo_dest);

					$('#sol_dot_licen_dest').val(resultado.sol_rebaja_lic_dest);
					$('#sol_dot_permiso_dest').val(resultado.sol_rebaja_per_dest);

					$('#sol_egres_tras_act_dest').val(resultado.sol_egres_tras_act_dest);
					$('#sol_tras_pend_egreso_dest').val(resultado.sol_tras_pend_egreso_dest);
					$('#sol_tras_pend_ingreso_dest').val(resultado.sol_tras_pend_ingreso_dest);
					$('#sol_cui_desc_dest').val(resultado.sol_cui_desc_dest);
					$('#sol_cui_dest').val(resultado.sol_cui_dest);
					$('#sol_zonal_dest').val(resultado.sol_zonal_dest);
					$('#sol_zonal_dest_hidden').val(resultado.sol_zonal_dest_hidden);
				},
                complete : function (){
                    $.unblockUI({ message: '<h1><img src="<?php echo site_url(); ?>assets/template/tpl-sb/img/ajax-loader.gif" /><br />14 14 14 Espere un momento...</h1>' });
                }, 						
				error: function(xhr, ajaxOptions, thrownError) {
					msg = "A ocurrid un error. Puede habar un inconveniente con la comunicacion hacia nuestro servidor de BASE DE DATOS para obtener los datos asociados al C. Costo de destino. ";
					console.log(msg+" "+xhr.status + " " + xhr.statusText);
				}
			});//fin ajax			
		});

		//al cargar la pagina no arroja ningun local
		$('select#sol_local > [data-codempresa]').hide();

		/*:::::::::::::::::::::::::::::::::*/
		/*inicio seleccionar empresa*/
		
		/*
		valida si hay un boton del listado previamente seleccionado para borra o no el formulario,
		dependiendo de lo que indique el usuario
		*/
		function advAlCambiarEmpresa(codigoEmpresa,este) {
			
			var lstBtnsEmp = $('#lst_menu_empresas');
			var hayEmpSelec = false;
			$(lstBtnsEmp).find('.btnSeleccionado').each(function() {
				hayEmpSelec = true;
			});

			if(hayEmpSelec){
				
				$.alert.open({
					type: 'confirm',
					title: 'Advertencia',
					icon: 'info',
					content: 'Al seleccionar otra empresa se eliminará la información  ingresada, continúa?',
					buttons:{
						ok:'Si',
						no_ok:'No'
					},
				    callback: function(button) {
				        if (button == 'ok') {
				            //$.alert.open('Usted presiono si');
				        	borrarAlElegirEmp();

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
                            $.ajax({//inicio ajax
                                type:"POST",
                                url :"<?php echo site_url(); ?>traslado/obtenerCC",
                                dataType:"json",
                                data:{
                                    rut: "<?php echo $_SESSION['rut']; ?>",
                                    cod_empresa: codigoEmpresa
                                },
                                beforeSend : function (){
                                    $.blockUI({
                                        fadeIn : 0,
                                        fadeOut : 0,
                                        showOverlay : true,
                                        message: '<h1><img src="<?php echo site_url(); ?>assets/template/tpl-sb/img/ajax-loader.gif" /><br />Espere un momento...</h1>'
                                    });
                                },
                                success: function(data){
                                    var lstCCostos = '';
                                    lstCCostos += '<option value="" selected>Seleccionar C. Costo</option>';
                                    $.each(data, function(key, valor) {
                                        lstCCostos += '<option data-codEmpresa="' + valor.EMPRESA + '" value="' + valor.CODUNIDAD+'">' + valor.CODUNIDAD + ' ' + valor.NOMBUNIDAD + '</option>';
                                    });
                                    $('#sol_local').html(lstCCostos);
                                },
                                complete : function (){
                                    $.unblockUI();
                                },
                                error: function(xhr, ajaxOptions, thrownError) {
                                    msg = "A ocurridoff un error ";
                                    console.log(msg+" "+xhr.status + " " + xhr.statusText);
                                }
                            });//fin ajax

							$('.btn').removeClass("btnSeleccionado");
							$(este,'.btn').children().toggleClass("btnSeleccionado");

/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
				        } else if (button == 'no_ok') {
				            //$.alert.open('Usted presiono no.');
				        	return false;
				        } else {
				        	return false;
				        }
				    }
				});
			} else {
				/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/
				$.ajax({//inicio ajax
					type:"POST",
					url :"<?php echo site_url(); ?>traslado/obtenerCC",
					dataType:"json",
					data:{
							rut: "<?php echo $_SESSION['rut']; ?>",
							cod_empresa: codigoEmpresa
						},
                    beforeSend : function (){
                        $.blockUI({
                            fadeIn : 0,
                            fadeOut : 0,
                            showOverlay : true,
                            message: '<h1><img src="<?php echo site_url(); ?>assets/template/tpl-sb/img/ajax-loader.gif" /><br />Espere un momento...</h1>'
                        });
                    },
					success: function(data){
						var lstCCostos = ''; 
						lstCCostos += '<option value="" selected>Seleccionar C. Costo</option>'; 
						$.each(data, function(key, valor) {
							lstCCostos += '<option data-codEmpresa="' + valor.EMPRESA + '" value="' + valor.CODUNIDAD+'">' + valor.CODUNIDAD + ' ' + valor.NOMBUNIDAD + '</option>';
						});
						$('#sol_local').html(lstCCostos);
					},
                    complete : function (){
                        $.unblockUI();
                    },
					error: function(xhr, ajaxOptions, thrownError) {
							msg = "A ocurridoff un error ";
							console.log(msg+" "+xhr.status + " " + xhr.statusText);
						}
				});//fin ajax
	
				$('.btn').removeClass("btnSeleccionado");
				$(este,'.btn').children().toggleClass("btnSeleccionado");
/*:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::*/				
				
			}//fin hayEmpSelec
			//vuelve a habilitar el boton
			//$('#sol_motivo').attr('disabled',false);
		}

		function borrarAlElegirEmp() {
			$('#sol_motivo').attr('disabled',false);
			$("#sol_motivo").val($("#sol_motivo option:first").val());
			$('#sol_rut').html('<option value="">----</option>');

			//inicio borrar
			$("#sol_cui").val("");
			$("#sol_cui_desc").val("");
			$("#sol_fech_inic_contr").val("");
			$("#sol_fech_term_contr").val("");
			$("#sol_situac_contr_cod_hidden").val("");
			$("#sol_situac_contr").val("");
			$("#sol_cargo_Dbnet").val("");
			$("#sol_cargo_Dbnet_desc").val("");
			$("#sol_fech_cambio").val("");
			$("#sol_trasl_apro12").val("");
			$("#sol_trasl_pend12").val("");
			$("#sol_cargo_jer").val("");
			$("#sol_cargo_jer_desc").val("");
			$("#sol_asist_recl_sel").val("");
			$("#sol_asist_recl_sel_hidden").val("");
			$("#sol_asist_proc_compe").val("");
			$("#sol_asist_proc_compe_hidden").val("");
			$("#sol_proveedor").val("");
			$("#sol_proveedor_desc").val("");
			$("#sol_dot_teo").val("");
			$("#sol_dot_mae").val("");
			$('#sol_dot_licen_dest').val("");
			$('#sol_dot_permiso_dest').val("");
			$("#sol_tras_pend_egreso").val("");
			$("#sol_tras_pend_ingreso").val("");
			$("#sol_egre_trasl_act_orig").val("");
			$("#sol_dot_fut").val("");
			$("#sol_zonal_origen").val("");
			$("#sol_cui_dest").val("");
			$("#sol_cui_desc_dest").val("");
			$("#sol_zonal_dest").val("");
			$("#sol_asis_reclut_selec_dest").val("");
			$("#sol_asis_proc_comp_dest").val("");
			$("#sol_dot_teo_dest").val("");
			$("#sol_dot_mae_dest").val("");
			$("#sol_tras_pend_egreso_dest").val("");
			$("#sol_tras_pend_ingreso_dest").val("");
			$("#sol_egres_tras_act_dest").val("");
			$("#sol_dot_futura_dest").val("");

			$("#sol_prom_cant").val("");
			$('#sol_local_dest').html('<option value="">----</option>');
			
			
			//fin borrar
			
		}
		$('#empresa_salcobrand').click(function(event){
			
			var hayEmpSelec = true;

			//busca se el boton esta seleccionado, si lo esta no sale el confirm
			$(this).find('.btnSeleccionado').each(function() {
				hayEmpSelec = false;
			});
			if (hayEmpSelec) {
				//al elegir otra empresa borrara el formulario
				advAlCambiarEmpresa(11,this);
				event.preventDefault();				
			} else {
				return false;
			}

		});
		
		$('#empresa_preunic').click(function(event){
			
			var hayEmpSelec = true;

			//busca se el boton esta seleccionado, si lo esta no sale el confirm
			$(this).find('.btnSeleccionado').each(function() {
				hayEmpSelec = false;
			});
			if (hayEmpSelec) {
				//al elegir otra empresa borrara el formulario
				advAlCambiarEmpresa(34,this);
				event.preventDefault();				
			} else {
				return false;
			}
			
		});	
			
		$('#empresa_makeup').click(function(event){
			
			var hayEmpSelec = true;

			//busca se el boton esta seleccionado, si lo esta no sale el confirm
			$(this).find('.btnSeleccionado').each(function() {
				hayEmpSelec = false;
			});
			if (hayEmpSelec) {
				//al elegir otra empresa borrara el formulario
				advAlCambiarEmpresa(36,this);
				event.preventDefault();				
			} else {
				return false;
			}
			
		});		
		
		$('#empresa_farmaprecio').click(function(event){
			
			var hayEmpSelec = true;

			//busca se el boton esta seleccionado, si lo esta no sale el confirm
			$(this).find('.btnSeleccionado').each(function() {
				hayEmpSelec = false;
			});
			if (hayEmpSelec) {
				//al elegir otra empresa borrara el formulario
				advAlCambiarEmpresa(41,this);
				event.preventDefault();				
			} else {
				return false;
			}
			
		});		
		$('#empresa_medcell').click(function(event){
			
			var hayEmpSelec = true;

			//busca se el boton esta seleccionado, si lo esta no sale el confirm
			$(this).find('.btnSeleccionado').each(function() {
				hayEmpSelec = false;
			});
			if (hayEmpSelec) {
				//al elegir otra empresa borrara el formulario
				advAlCambiarEmpresa(14,this);
				event.preventDefault();				
			} else {
				return false;
			}
			
		});								
		/*fin seleccionar empresa*/

	});
</script>