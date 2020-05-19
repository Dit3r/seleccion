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
a.btn.btn-info.funcion {
	margin-left: 3px;
	margin-bottom: 7px;
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
			<form id="frm_solicitud_vacante" method="POST" onkeypress="return event.keyCode != 13;">
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
											<div id="sol_rut_hidden" style="display: none">
											</div>
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
				<div class="col-md-24">
					<?php 
							if (isset($botones_accion)) {
								if (!isset($responsable)) {
									$responsable = '';
								}
								if ($responsable == '999' && $botones_accion == '') {
								} else {
									$tiene_intercciones = TRUE;
								}
							}	

							if (isset($botones_funcion)) {
								if (!isset($responsable)) {
									$responsable = '';
								}
								
								if ($responsable == '999' && ($botones_funcion == '' && $botones_accion == '')) {
									echo "<a name=\"mailRespons\" id=\"mailRespons\" class=\"btn btn-info funcion\" style=\"float:right;\">ENVIAR CORREO A RESPONSABLE</a>\n";
								} else {
									$tiene_intercciones = TRUE;
								}
							} 

							if (isset($tiene_intercciones) && ( $botones_accion != '' || $botones_funcion != '') ) {
								$nombre_interaccion = $this->nativesession->get( 'nombre_interaccion' );
								
								if ( isset($nombre_interaccion) && $nombre_interaccion != '' ) {
									switch ($nombre_interaccion){
										case 'IV_LLV':
											$vista_interaccion = 'form_interac_iv_llv';
											break;
										case 'IV_RTA':
											$vista_interaccion = 'form_interac_iv_rta';
											break;
										case 'IV_GGPP':
											$vista_interaccion = 'form_interac_iv_rta';
											break;	
										case 'IV_CZON':
											$vista_interaccion = 'form_interac_iv_rta';
											break;																						
									}		
									
									$this->load->view('contenidos/ingreso-vacante/interaccion/'.$vista_interaccion);
									//$this->nativesession->delete( 'nombre_interaccion' );																
								}//fin if

							}
						
					?>
				</div>
			</div>
			<div class="row fila">
				<div class="col-md-11" style="border-right: 1px solid #d1d1d1;"><!-- botones accion inicio -->
					<?php
						if (isset($botones_accion)) {
							if (!isset($responsable)) {
								$responsable = '';
							}
								//echo "--->".$botones."<------";
								if ($responsable == '999' && $botones_accion == '') {
									#jagl lo comente porque ya se e
									#echo "<a name=\"mailRespons\" id=\"mailRespons\" class=\"btn btn-info funcion\">ENVIAR CORREO A RESPONSABLE</a>\n";
								} else {
									echo $botones_accion;
								}
								//echo $_SESSION['rut'];
								//echo $responsable;
						} 
					?>
				</div><!-- botones accion fin -->			
				<div class="col-md-13" style="border-left: 1px solid rgb(192, 192, 192); padding-left: 4px;"><!-- botones funcion inicio -->
					<?php
						if (isset($botones_funcion)) {
							if (!isset($responsable)) {
								$responsable = '';
							}
							
							//echo "--->".$botones."<------";
							if ($responsable == '999' && ($botones_funcion == '' && $botones_accion == '')) {
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
				</div><!-- botones funcion fin -->
			</div>
			<div class="row"><!-- titulo -->
				<div class="col-md-24 fila tituform">
					<h4><span class="glyphicon glyphicon-info-sign"></span><span class="titleSeccionForm">Solicitud Ingreso Vacante</span></h4>
				</div>
			</div>		
			<div class="row" ><!-- fila 1 -->
				<div class="col-md-24 fila">
					<div class="col-md-6">
						<div class="form-group">
							<label for="sol_tipo_solicitid">Tipo Solicitud</label>
							<input type="text" class="form-control" name="sol_tipo_solicitid" id="sol_tipo_solicitid" disabled="disabled" />
						</div>														
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="sol_fecha_creacion">Fecha Creación</label>
							<input type="text" class="form-control text-center" name="sol_fecha_creacion" id="sol_fecha_creacion" disabled="disabled" />
						</div>														
					</div>	
					<div class="col-md-6">
						<div class="form-group">
							<label for="sol_empresa">Cod. Empresa</label>
							<input type="text" class="form-control" name="sol_empresa" id="sol_empresa" disabled="disabled" />
						</div>														
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="sol_desc_empresa">Descripción Empresa</label>
							<input type="text" class="form-control" name="sol_desc_empresa" id="sol_desc_empresa" disabled="disabled" />
						</div>														
					</div>
				</div>
			</div><!-- fin row -->
			<div class="row"><!-- fila 2 -->
				<div class="col-md-24 fila">
					<div class="col-md-3">
						<div class="form-group">
							<label for="sol_cargo">Cod. Cargo</label>
							<input type="text" class="form-control" name="sol_cargo" id="sol_cargo" disabled="disabled" />
						</div>														
					</div>
					<div class="col-md-9">
						<div class="form-group">
							<label for="sol_desc_cargo">Descripción Cargo</label>
							<input type="text" class="form-control" name="sol_desc_cargo" id="sol_desc_cargo" disabled="disabled" />
						</div>														
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="sol_cui">CUI</label>
							<input type="text" class="form-control" name="sol_cui" id="sol_cui" disabled="disabled" />
						</div>														
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="sol_desc_cui">Desc. CUI</label>
							<input type="text" class="form-control" name="sol_desc_cui" id="sol_desc_cui" disabled="disabled" />
						</div>														
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="sol_posicion">Posición</label>
							<input type="text" class="form-control" name="sol_posicion" id="sol_posicion" disabled="disabled" />
						</div>														
					</div>					
				</div>
			</div><!-- fin row -->
			<div class="row fila"><!-- fila 3 -->

							<div class="col-md-8">
								<div class="form-group">
									<label for="sol_zonal">Nombre Zonal</label>
									<input type="text" class="form-control" name="sol_zonal" id="sol_zonal" disabled="disabled" />
								</div>														
							</div>					
							<div class="col-md-8">
								<div class="form-group">
									<label for="sol_proceso">Nombre Proceso</label>
									<input type="text" class="form-control" name="sol_proceso" id="sol_proceso" disabled="disabled" />
								</div>					
							</div>
							<div class="col-md-8">
								<div class="form-group">
									<label for="sol_seleccion">Nombre Selección</label>
									<input type="text" class="form-control" name="sol_seleccion" id="sol_seleccion" disabled="disabled" />
								</div>					
							</div>
			</div><!-- fin row -->
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
				url :"<?php echo site_url(); ?>etapa_vac/fillEtapaLog",
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
				url :"<?php echo site_url(); ?>etapa_vac/fillEtapaFrmVac",
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
					//$('#frm_solicitud_vacante').populate(data);
					//$( '#sol_local' ).val(data.sol_local);
					$( '#sol_tipo_solicitid' ).val(data.sol_tipo_solicitid);
					$( '#sol_fecha_creacion' ).val(data.sol_fecha_creacion);
					$( '#sol_empresa' ).val(data.sol_empresa);
					$( '#sol_desc_empresa' ).val(data.sol_desc_empresa);
					$( '#sol_cargo' ).val(data.sol_cargo);
					$( '#sol_cui' ).val(data.sol_cui);
					$( '#sol_desc_cargo' ).val(data.sol_desc_cargo);
					$( '#sol_desc_cui' ).val(data.sol_desc_cui);
					$( '#sol_posicion' ).val(data.sol_posicion);
					$( '#sol_zonal' ).val(data.sol_zonal);
					$( '#sol_proceso' ).val(data.sol_proceso);
					$( '#sol_seleccion' ).val(data.sol_seleccion);

					$( '#rta_estructura' ).val(data.rta_estructura);
					$( '#rta_ingresada' ).val(data.rta_ingresada);
					$( '#rta_final' ).val(data.rta_final);

					//$('#inputId').attr('readonly', true);
					if ( data.rta_ingresada != 0 && data.rta_final != 0 ) {
						$( '#rta_ingresada' ).attr('readonly', true);
					}
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

			$("#rta_ingresada").click(function () {
				$(this).select();
			});

			//clona lo que se esta escribiendo en otro campo
			$(function() {
			    $('input[id$=rta_ingresada]').on('keyup blur',function() {
			        var txtClone = $(this).val();
			        $('input[id$=rta_final]').val(txtClone);
			    });
			});

			$("#rta_ingresada").keydown(function(event) {
				// Allow only backspace and delete
				if ( event.keyCode == 46 || event.keyCode == 8 ) {
					// let it happen, don't do anything
				}
				else {
					// Ensure that it is a number and stop the keypress
					if (event.keyCode < 48 || event.keyCode > 57 ) {
						event.preventDefault();	
					}	
				}
			});			
	});
</script>