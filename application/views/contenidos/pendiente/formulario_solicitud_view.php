<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bootstrap-3-complilado/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/jquery-ui-1.10.4/css/smoothness/jquery-ui-1.10.4.custom.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/tpl2-sb/css/flujo.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/tpl2-sb/css/custom.css" />

<style>

input[type=text][disabled=disabled],
textarea[disabled="disabled"]{
	cursor: default;
}
button.btn.btn-info.funcion,
a.btn.btn-info.funcion,
button.btn.btn-info.accion,
a.btn.btn-info.accion
{
	margin-left: 3px;
	margin-bottom: 7px;
}
#mailRespons {
	width: 200px;
}
</style>

<div class="row">
	<div class="col-md-24" style="-webkit-border-radius: 6px 6px 0 0;border-radius: 6px 6px 0 0; margin-top: 10px;margin-bottom: 10px;">
	</div>
</div>
<div class="contenedor_frm">
<!-- formulario inicio -->
	<div id="frm_solicitud" class="container-full contenedor form_solcitudes">
		<div class="form_derch">
			<form id="frm_solicitud_traslado_ori" method="POST" onkeypress="return event.keyCode != 13;">
				<div class="row">
					<div class="col-md-24 fila" style="padding-top: 10px; border-radius: 6px 6px 0px 0px; padding-bottom: 10px;">
						<h4 style="color: #000000;"><span class="glyphicon glyphicon-info-sign"></span><span class="titleSeccionForm">Información Etapa</span></h4>
						<hr style="margin-top: 0px; margin-bottom: 0px;" />				
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-23 col-md-offset-1">
									<div class="form-group">
										<label for="sol_observ_jefe_dest">Observacion etapa:</label>
										<textarea name="sol_observ_jefe_dest" id="sol_observ_jefe_dest" class="form-control required" rows="3" style="resize: none;padding-bottom: 0px; height: 59px;font-size: 15px;" <?php echo isset($textarea) ? $textarea :'eeeee'; ?>></textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-24">
									<div class="row" style="padding-top: 18px; font-size: 12px;">
										<div class="col-md-5 col-md-offset-1">
											<div>
												<p><strong>Accion:</strong>
												</p>
											</div>
											<div>
												<p id="accion_log"></p>
											</div>										
										</div>
										<div class="col-md-6">
											<div>
												<p><strong>Fecha Inicio:</strong>
												</p>
											</div>
											<div>
												<p id="f_inicio_log"></p>
											</div>
										</div>									
										<div class="col-md-6">
											<div>
												<p><strong>Fecha Fin:</strong>
												</p>
											</div>
											<div>
												<p id="f_fin_log"></p>
											</div>
										</div>
										<div class="col-md-6">
											<div>
												<p><strong>Responsable:</strong>
												</p>
											</div>
											<div>
												<p id="nom_respon_log"></p>
												<div id="sol_rut_hidden" style="display: none"></div>
											</div>	
											<div>
												<!-- mail del responsable de gestionar la etapa -->
												<p id="sol_correo_log" style="display: none;"></p>
											</div>	
																				
										</div>
									</div>				
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row fila">
					<div class="col-md-11" style="border-right: 1px solid #d1d1d1;">
						<?php
							if (isset($botones_accion)) {
								if (!isset($responsable)) {
									$responsable = '';
								}
									//echo "--->".$botones."<------";
									if (($responsable == '999' || $responsable == '888') && $botones_accion == '') {
										#jagl lo comente porque ya se e
										#echo "<a name=\"mailRespons\" id=\"mailRespons\" class=\"btn btn-info funcion\">ENVIAR CORREO A RESPONSABLE</a>\n";
									} else {
										echo $botones_accion;
									}
									//echo $_SESSION['rut'];
									//echo $responsable;
							} 
						?>
					</div>			
					<div class="col-md-13" style="border-left: 1px solid rgb(192, 192, 192); padding-left: 4px;">
						<?php
							if (isset($botones_funcion)) {
								if (!isset($responsable)) {
									$responsable = '';
								}
								
								//echo "--->".$botones."<------";
								if (($responsable == '999' || $responsable == '888') && ($botones_funcion == '' && $botones_accion == '')) {
									echo "<a name=\"mailRespons\" id=\"mailRespons\" class=\"btn btn-info funcion\" style=\"float:right;\">ENVIAR CORREO A RESPONSABLE</a>\n";
								} else {
									echo $botones_funcion;
								}
								
								//echo $_SESSION['rut'];
								//echo $responsable;
								
							} else {
								echo isset($responsable) ? "<a name=\"mailRespons\" id=\"mailRespons\" class=\"btn btn-info funcion\" style=\"float:right;\">ENVIAR CORREO A RESPONSABLE</a>\n": '';
							}
						?>
					</div>														
				</div>
				<div class="row">
					<div class="col-md-24 fila tituform">
						<h4><span class="glyphicon glyphicon-info-sign"></span><span class="titleSeccionForm">Traslado Origen</span></h4>
					</div>
				</div>		
				<div class="row" ><!-- fila 1 -->
					<div class="col-md-24 fila">
						<div class="col-md-12">
							<div class="form-group">
								<label for="sol_local">C. Costo:</label>
								<select name="sol_local" id="sol_local" class="selectpicker" style="width: 96%; margin-bottom: 10px;" disabled="disabled">
									<option value="" selected></option>
								</select>									
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-23 col-md-offset-1">
									<div class="form-group">
										<label for="sol_motivo">Motivo:</label>
										<select name="sol_motivo" id="sol_motivo" required class="selectpicker" style="width: 100%; margin-bottom: 10px;" disabled="disabled">
											<option value="" selected></option>
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
								<label for="sol_rut">Colaborador:</label>
								<select name="sol_rut" id="sol_rut" required class="selectpicker" style="width: 96%; margin-bottom: 10px;" disabled="disabled">
									<option value="" selected></option>
								</select>									
							</div>					
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-9 col-md-offset-1">
									<div class="form-group">
										<label for="sol_cui">CUI:</label>
										<input type="text" class="form-control" name="sol_cui" id="sol_cui" disabled="disabled" />
									</div>					
								</div>
								<div class="col-md-14">
									<div class="form-group">
										<label for="sol_cui_desc">Descripción:</label>
										<input type="text" class="form-control" name="sol_cui_desc" id="sol_cui_desc" disabled="disabled" />
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
										<label for="sol_fech_inic_contr">Inicio Contrato:</label>
										<input type="text" class="form-control" name="sol_fech_inic_contr" id="sol_fech_inic_contr" disabled="disabled" />
									</div>					
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="sol_fech_term_contr">Termino Contrato:</label>
										<input type="text" class="form-control" name="sol_fech_term_contr" id="sol_fech_term_contr" disabled="disabled" />
									</div>					
								</div>
								<div class="col-md-11">
									<div class="form-group">
										<label for="sol_situac_contr">Situacion Contractual:</label>
										<input type="text" class="form-control" name="sol_situac_contr" id="sol_situac_contr" disabled="disabled" />
									</div>					
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-5 col-md-offset-1">
									<div class="form-group">
										<label for="sol_cargo_Dbnet">Cargo Dbnet:</label>
										<input type="text" class="form-control" name="sol_cargo_Dbnet" id="sol_cargo_Dbnet" disabled="disabled" />
									</div>					
								</div>
								<div class="col-md-18">
									<div class="form-group">
										<label for="sol_cargo_Dbnet_desc">Descripción:</label>
										<input type="text" class="form-control" name="sol_cargo_Dbnet_desc" id="sol_cargo_Dbnet_desc" disabled="disabled" />
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
										<label for="sol_fech_cambio">Fecha de Cambio:</label>
										<!-- 
										<input type="text" class="form-control" name="sol_fech_cambio" id="sol_fech_cambio" required />
										 -->
										 <!-- 
										<input type="date" value="" class="form-control calendario hasDatepicker" id="sol_fech_cambio" name="sol_fech_cambio">
										 -->
										 <input type="text" id="sol_fech_cambio" class="form-control" disabled="disabled" />
									</div>					
								</div>
								<div class="col-md-7 col-md-offset-1">
									<div class="form-group">
										<label for="sol_trasl_apro12">Tras Apr 12 meses</label>
										<input type="text" class="form-control" name="sol_trasl_apro12" id="sol_trasl_apro12" disabled="disabled" />
									</div>					
								</div>
								<div class="col-md-7  col-md-offset-1">
									<div class="form-group">
										<label for="sol_trasl_pend12">Tras Pen 12 meses</label>
										<input type="text" class="form-control" name="sol_trasl_pend12" id="sol_trasl_pend12" disabled="disabled" />
									</div>					
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6 col-md-offset-1">
									<div class="form-group">
										<label for="sol_cargo_jer">Cargo Jerarquico:</label>
										<input type="text" class="form-control" name="sol_cargo_jer" id="sol_cargo_jer" disabled="disabled" />
									</div>					
								</div>
								<div class="col-md-17">
									<div class="form-group">
										<label for="sol_cargo_jer_desc">Descripción:</label>
										<input type="text" class="form-control" name="sol_cargo_jer_desc" id="sol_cargo_jer_desc" disabled="disabled" />
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
										<input type="text" class="form-control" name="sol_prom_cant" id="sol_prom_cant" disabled="disabled">
									</div>					
								</div>
								<div class="col-md-19" style="margin-left: 0;">
									<div class="form-group">
										<label for="sol_prom_desc"></label>
										<input type="text" class="form-control" name="sol_prom_desc" id="sol_prom_desc" disabled="disabled" style="margin-right: 0px; width: 320px;">
									</div>					
								</div>
							</div>
						</div>
						<div class="col-md-12">
							<div class="row">
								<div class="col-md-6 col-md-offset-1">
									<div class="form-group">
										<label for="sol_proveedor">Proveedor</label>
										<input type="text" class="form-control" name="sol_proveedor" id="sol_proveedor" disabled="disabled">
									</div>					
								</div>
								<div class="col-md-17">
									<div class="form-group">
										<label for="sol_proveedor_desc">Descripción:</label>
										<input type="text" class="form-control" name="sol_proveedor_desc" id="sol_proveedor_desc" disabled="disabled">
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
							<label for="sol_dot_teo">Dotación Teórica:</label>
							<input type="text" class="form-control text-center" name="sol_dot_teo" id="sol_dot_teo" disabled="disabled" >
						</div>					
					</div>
					<div class="col-md-3">
						<div>
							<label for="sol_dot_mae">Dotación Real</label>
							<input type="text" class="inputModal text-center" name="sol_dot_mae" id="sol_dot_mae" disabled="disabled" >
						</div>					
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="sol_dot_licen">Licencia</label>
							<input name="sol_dot_licen" id="sol_dot_licen" type="text" class="inputModal text-center" disabled="disabled" >
						</div>											
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="sol_dot_permiso">Permiso</label>
							<input name="sol_dot_permiso" id="sol_dot_permiso" type="text" class="inputModal text-center" disabled="disabled">
						</div>											
					</div>					
					<div class="col-md-3">
						<div>
							<label for="sol_tras_pend_egreso">Trasl. Pend. Egreso</label>
							<input type="text" class="inputModal text-center" name="sol_tras_pend_egreso" id="sol_tras_pend_egreso" disabled="disabled">
						</div>										
					</div>
					<div class="col-md-3">				
						<div>
							<label for="sol_tras_pend_ingreso">Trasl Pend. Ingreso</label>
							<input type="text" class="inputModal text-center" name="sol_tras_pend_ingreso" id="sol_tras_pend_ingreso" disabled="disabled">
						</div>						
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="sol_egre_trasl_act_orig">Egreso Tras Actual</label>
							<input type="text" class="form-control text-center" name="sol_egre_trasl_act_orig" id="sol_egre_trasl_act_orig" disabled="disabled">
						</div>	
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="sol_dot_fut">Dotación Futura</label>
							<input type="text" class="form-control text-center" name="sol_dot_fut" id="sol_dot_fut" disabled="disabled">
						</div>																
					</div>
				</div>
			</div><!-- fin row -->
			<div class="row fila"><!-- fila 7 -->
				<div class="col-md-12">
						<div class="row">
							<div class="col-md-23">	
								<div class="form-group">
									<label for="sol_observacion">Observación:</label>
									<textarea name="sol_observacion" id="sol_observacion" class="form-control" rows="3" style="padding-bottom: 0px; height: 71px;text-align: left;margin-bottom: 10px;" disabled="disabled"></textarea>
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
					</div><!-- fin row -->
					<!-- traslado inicio -->
					<div class="row">
						<div class="col-md-11  col-md-offset-1">
							<div class="form-group">
								<label for="sol_asist_recl_sel">Asistente Reclut. y Selección</label>
								<input type="text" class="form-control" name="sol_asist_recl_sel" id="sol_asist_recl_sel" disabled="disabled" >
							</div>					
						</div>
						<div class="col-md-11">
							<div class="form-group">
								<label for="sol_asist_proc_compe">Ejecutivoa Proc. y Compens</label>
								<input type="text" class="form-control" name="sol_asist_proc_compe" id="sol_asist_proc_compe" disabled="disabled" >
							</div>
							<!-- estos datos los ocupo para enviarlos por mail (por ahora) -->
							<div id="sol_created_hidden" style="display: none"></div>
							<div id="sol_created_date_hidden" style="display: none"></div>
						</div>
					</div>
					<!-- traslado fin -->
				</div>
			</div>
			</form>
	<form id="frm_solicitud_traslado_dest" action="<?php echo site_url(); ?>etapa/procSolicitud" method="POST">
			<div class="row">
				<div class="col-md-24 fila tituform">
					<h4><span class="glyphicon glyphicon-info-sign"></span><span class="titleSeccionForm">Traslado Destino</span></h4>
				</div>
			</div>
			<div class="row" ><!-- fila 7 -->
				<div class="col-md-24 fila">
					<div class="col-md-12">
						<div class="form-group">
							<label for="sol_local_dest">C. Costo:</label>
							<select name="sol_local_dest" id="sol_local_dest" class="selectpicker" style="width: 96%; margin-bottom: 10px;" disabled="disabled">
								<option value="" selected></option>
							</select>									
						</div>					
					</div>
					<div class="col-md-12">
						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<label for="sol_cui_dest">Unidad:</label>
									<input type="text" class="form-control" name="sol_cui_dest" id="sol_cui_dest" disabled="disabled">
								</div>
							</div>
							<div class="col-md-14">
								<div class="form-group">
									<label for="sol_cui_desc_dest">Descripción:</label>
									<input type="text" class="form-control" name="sol_cui_desc_dest" id="sol_cui_desc_dest" disabled="disabled">
								</div>					
							</div>
						</div>
					</div>
				</div>
			</div><!-- fin row -->	
			<div class="row" ><!-- fila 8 -->
				<div class="col-md-24 fila">
					<div class="col-md-8">
						<div class="form-group">
							<label for="sol_zonal_dest">Zonal:</label>
							<input type="text" class="form-control" name="sol_zonal_dest" id="sol_zonal_dest" disabled="disabled">
							<input type="hidden" class="form-control" name="sol_zonal_dest_hidden" id="sol_zonal_dest_hidden">
						</div>					
					</div>
					<div class="col-md-8">
						<div class="form-group">
							<label for="sol_asis_reclut_selec_dest">Asistente Reclutamiento y Seleccion:</label>
							<input type="text" class="form-control" name="sol_asis_reclut_selec_dest" id="sol_asis_reclut_selec_dest" disabled="disabled">
							<input type="hidden" class="form-control" name="sol_asis_reclut_selec_dest_hidden" id="sol_asis_reclut_selec_dest_hidden">
						</div>					
					</div>
					<div class="col-md-8">
						<div class="form-group">
							<label for="sol_asis_proc_comp_dest">Asistente Procesos y Compensaciones:</label>
							<input type="text" class="form-control" name="sol_asis_proc_comp_dest" id="sol_asis_proc_comp_dest" disabled="disabled">
							<input type="hidden" class="form-control" name="sol_asis_proc_comp_dest_hidden" id="sol_asis_proc_comp_dest_hidden">
						</div>					
					</div>
				</div>
			</div><!-- fin row -->		
			<div class="row"><!-- fila 10 -->
				<div class="col-md-24 fila">
					<div class="col-md-3">
						<div class="form-group">
							<label for="sol_dot_teo_dest">Dot Teórica:</label>
							<input type="text" class="form-control text-center" name="sol_dot_teo_dest" id="sol_dot_teo_dest" disabled="disabled">
						</div>									
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="sol_dot_mae_dest">Dotación Real</label>
							<input name="sol_dot_mae_dest" id="sol_dot_mae_dest" type="text" class="inputModal text-center" disabled="disabled">
						</div>											
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="sol_dot_licen_dest">Licencia</label>
							<input name="sol_dot_licen_dest" id="sol_dot_licen_dest" type="text" class="inputModal text-center" disabled="disabled">
						</div>											
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="sol_dot_permiso_dest">Permiso</label>
							<input name="sol_dot_permiso_dest" id="sol_dot_permiso_dest" type="text" class="inputModal text-center" disabled="disabled">
						</div>											
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="sol_tras_pend_egreso_dest">Tras Pend. Egre</label>
							<input name="sol_tras_pend_egreso_dest" id="sol_tras_pend_egreso_dest" type="text" class="inputModal text-center" disabled="disabled">
							
						</div>										
					</div>
					<div class="col-md-3">				
						<div class="form-group">
							<label for="sol_tras_pend_ingreso_dest">Tras Pend Ingre</label>
							<input name="sol_tras_pend_ingreso_dest" id="sol_tras_pend_ingreso_dest" type="text" class="inputModal text-center" disabled="disabled">
						</div>						
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="sol_egres_tras_act_dest">Ingreso Tras Act</label>
							<input type="text" class="form-control text-center" name="sol_egres_tras_act_dest" id="sol_egres_tras_act_dest" disabled="disabled">
						</div>									
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="sol_dot_futura_dest">Dotación Futura</label>
							<input type="text" class="form-control text-center" name="sol_dot_futura_dest" id="sol_dot_futura_dest" disabled="disabled">
						</div>											
					</div>
				</div>
			</div><!-- fin row -->							
		</div>
<!-- formulario fin -->
	</div>
</div>	
</form>	
<div class="row">
	<div class="col-md-24" style="-webkit-border-radius: 6px 6px 0 0;border-radius: 6px 6px 0 0; margin-top: 10px;margin-bottom: 10px"></div>
</div>


<script src="<?php echo base_url(); ?>assets/template/tpl2-sb/js/jquery.blockUI.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.populate.js"></script>	
<script src="<?php echo base_url(); ?>assets/js/validar-form/jquery.numeric.js"></script>	
<script src="<?php echo base_url(); ?>assets/js/validar-form/jquery.validate.js"></script>	
<script>
	$(document).ready(function() {

		//formulario Informacion Etapa
		$( '#sol_observ_jefe_dest' ).html("");
		$( '#accion_log' ).html("");
		$( '#f_inicio_log' ).html("");
		$( '#f_fin_log' ).html("");
		$( '#nom_respon_log' ).html("");
					
			$.ajax({
				type:"POST",
				url :"<?php echo site_url(); ?>etapa/fillEtapaLog",
				dataType: "json",
				data:{
					solicitud: "<?php echo $this->uri->segment(3); ?>",
					nroEtapa:"<?php echo $this->uri->segment(4); ?>",
					correlativo:"<?php echo $this->uri->segment(5); ?>"
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
					//console.log('test 1010');
					//$( '#accion_log' ).html('sdfsdfsdfs');
					$( '#sol_observ_jefe_dest' ).html("");
					$( '#accion_log' ).html("");
					$( '#f_inicio_log' ).html("");
					$( '#f_fin_log' ).html("");
					$( '#nom_respon_log' ).html("");
					$( '#mailResponsable_log' ).html("");
										
					$( '#sol_observ_jefe_dest' ).html(data.sol_obs_etapa);
					$( '#accion_log' ).html(data.sol_accion);
					$( '#f_inicio_log' ).html(data.sol_inicio_etapa);
					$( '#f_fin_log' ).html(data.sol_termino_etapa);
					$( '#nom_respon_log' ).html(data.sol_respon);
					$( '#sol_correo_log' ).html(data.sol_correo);
					$( '#sol_rut_hidden' ).html(data.sol_rut);
					//console.log(data.sol_accion);
				},
                complete : function (){
                    $.unblockUI();
                },				
				error: function(xhr, ajaxOptions, thrownError) {
					msg = "A ocurrid un error ";
					console.log(msg+" "+xhr.status + " " + xhr.statusText);
				}
			});//fin ajax	

			//formulario Traslado origen
			$.ajax({
				type:"POST",
				url :"<?php echo site_url(); ?>etapa/fillEtapaOri",
				dataType:"json",
				data:{
					solicitud: "<?php echo $this->uri->segment(3); ?>"
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
					$('#frm_solicitud_traslado_ori').populate(data);
					//$( '#sol_local' ).val(data.sol_local);
					var html_sol_local ='<option selected>'+data.sol_local+'</option>';
					var html_sol_motivo ='<option selected>'+data.sol_motivo+'</option>';
					var html_sol_rut ='<option selected>'+data.sol_rut+'</option>';
					$( '#sol_local' ).html(html_sol_local);
					$( '#sol_motivo' ).html(html_sol_motivo);
					$( '#sol_rut' ).html(html_sol_rut);
					$( '#sol_dot_licen' ).val(data.sol_dot_licen);
					$( '#sol_dot_permiso' ).val(data.sol_dot_permiso);
					$( '#sol_zonal_origen' ).val(data.sol_zonal_origen);
					$( '#sol_created_hidden' ).val(data.sol_created_hidden);
					$( '#sol_created_date_hidden' ).val(data.sol_created_date_hidden);
					//console.log(data.sol_local);
				},
                complete : function (){
                    $.unblockUI();
                },			
				error: function(xhr, ajaxOptions, thrownError) {
					msg = "A ocurrid un error ";
					console.log(msg+" "+xhr.status + " " + xhr.statusText);
				}
			});//fin ajax
			//informacion Traslado destino
			$.ajax({
				type:"POST",
				url :"<?php echo site_url(); ?>etapa/fillEtapaDest",
				dataType:"json",
				data:{
					solicitud: "<?php echo $this->uri->segment(3); ?>"
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
					$('#frm_solicitud_traslado_dest').populate(data);
					var html_sol_local_dest ='<option selected>'+data.sol_local_dest+'</option>';
					$( '#sol_local_dest' ).html(html_sol_local_dest);
					$( '#sol_dot_licen_dest' ).val(data.sol_rebaja_lic_dest)
					$( '#sol_dot_permiso_dest' ).val(data.sol_rebaja_per_dest)
				},
                complete : function (){
                    $.unblockUI();
                },				
				error: function(xhr, ajaxOptions, thrownError) {
					msg = "A ocurrid un error ";
					console.log(msg+" "+xhr.status + " " + xhr.statusText);
				}
			});//fin ajax
	});
</script>