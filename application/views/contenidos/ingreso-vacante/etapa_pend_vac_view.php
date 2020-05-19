<link rel="stylesheet" href="<?php echo base_url(); ?>assets/lib/bootstrap-3-complilado/bootstrap/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/template/tpl2-sb/css/flujo.css" />
<link rel="stylesheet" href="<?php echo site_url(); ?>assets/template/tpl2-sb/css/custom.css" type="text/css" />
<style>

.alert {
	font-size: 16px;
}

.error {
	margin-top: 15px;
}

button.subidaExitosa,
a.subidaExitosa {
    background-color: #4CAE4C;
    border-color: #36A036;
    color: #ffffff;
    margin-bottom: 7px;
    margin-left: 3px;    
    font-size: 11px;
    background-image: linear-gradient(to bottom, #69CF69, #4CAE4C);
    background-repeat: repeat-x;    
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
}

select#estadoPostu option {
	width: 61px;
}
</style>
<!-- inicio mensaje alerta -->
<?php 
	if ($this->session->flashdata('ejecutada') != null ) : 
		$subido = $this->session->flashdata('ejecutada');
		if ($subido == "no") {
			$tipoAlerta = 'alert-danger';;
		}
		if ($subido == "si") {
			$tipoAlerta = 'alert-success';
		}		
?>
	<div class="alert <?php echo $tipoAlerta; ?>">  
					<a class="close" data-dismiss="alert">×</a>
					<div id="mensaje_respuesta">
						<?php echo $this->session->flashdata('mensaje'); ?>.
					</div>
	</div>


<?php endif;?>
<!-- fin mensaje alerta -->

<!-- ::::::::::::::NUEVOS OVALOS TITULOS INICIO::::::::::::::::::: -->
<div class="row" style="margin-top: 12px;">
	<div class="col-md-5 col-md-offset-1">
		<p class="nroSolicitud"><span>Nro de Solicitud de Vacante : <?php echo $idSolicitud; ?></span></p>
	</div>
	<div class="col-md-12"  style="text-align: center; font-size: 18px; font-weight: bold;"><?php echo $titlePage; ?></div>
	<div class="col-md-6 volver" style="cursor: pointer; text-align: right; padding-right: 30px;">
		<a href="<?php echo site_url()."traslado/".$this->uri->segment(2); ?>"><i class="fa fa-mail-reply"></i><span> Volver</span></a>
	</div>
</div>
<!-- ::::::::::::::NUEVOS OVALOS TITULOS FIN::::::::::::::::::: -->
<!-- /////etapa inicio -->
<div id="etapa" class="row bloquecentrado" style="max-width: 1200px; margin-bottom: 12px; margin-left: auto; margin-right: auto;">
	<div class="col-md-24">
		<ul class="list-inline">
<!-- para el count sirve cualquiera de los 4, porque todos tienen la misma cantidad de elementos. En este caso puse $etapa["title"]  -->
		<?php 
			$conta = 0;
			foreach ($ovalos as $indice => $ovalo): 
		?>
			<li>
				<div class="blocketapa <?php echo (($ovalo->nroEtapa == $this->uri->segment(4) && ($ovalo->correlativo == $this->uri->segment(5)) )) ? 'seleccionado':'';?>">
				<?php if ($conta != 0) : ?>
					<div class="izq left">
					<span class="glyphicon glyphicon-arrow-right"></span>
					</div>
				<?php endif; ?>
					<div class="der left">
						<div class="title"><?php echo $ovalo->title; ?></div>
						<a class="toOvalo" data-etapa="<?php echo $ovalo->nroEtapa; ?>"  href="<?php echo site_url(); ?>etapa_vac/pendiente/<?php echo $idSolicitud; ?>/<?php echo $ovalo->nroEtapa; ?>/<?php echo $ovalo->correlativo; ?>/<?php echo $this->uri->segment(6); ?>">
							<div class="oval <?php echo ($ovalo->estado == "A") ? 'destacado':''; ?>" style="<?php echo ($ovalo->estado == "N") ? 'background-color:#FFDB00':''; ?> ">
								<div class="contenidoOvalo">
									<?php echo $ovalo->txtOvalo; ?>
								</div>
							</div>
						</a>				
					</div>
					<div class="clearfix"></div>
				</div>
			</li>		
		<?php
		
		
		
		//::INICIO etapaactual::pregunta si el si esta posicionado en la etapa actual
		if (($ovalo->nroEtapa == $this->uri->segment(4)) && ($ovalo->estado == "A")) {
			
			//si existe alguna interaccion en la etapa actual
			if (isset($ovalo->interaccion)) {

				/*
				este nombre_interaccion, se lo tengo que enviar via $.ajax y ADEMAS sirve para cargar la vista
				dentro del archivo formulario_ingr_vac_view.php
				*/
				$this->nativesession->set( 'nombre_interaccion', $ovalo->interaccion );
			}

			$CI =& get_instance();
			$CI->db = $this->load->database('bdseleccion', true);
			$solicitud = $this->uri->segment(3);

			//obtener el correlativo
			$strCorrelativo = "Select (Pkg_Seleccion_Vacantes.Trae_Correlativo (".$solicitud.") - 1) CORRELATIVO From DUAL";
			$strCorrelativo = $CI->db->query($strCorrelativo)->result();
			$correlativo = $strCorrelativo[0]->CORRELATIVO;

			$strQueryBtns = "Select Pkg_Seleccion_Vacantes.Trae_Info_Etapa (".$solicitud.", ".$ovalo->nroEtapa.", ".$correlativo.") as ETAPA_LOG From DUAL";
			$strQueryBtns = $CI->db->query($strQueryBtns)->result();
			$strQueryBtns = json_decode($strQueryBtns[0]->ETAPA_LOG);
			/*
				{
				  "sol_rut": "9535821",
				  "sol_respon": "Manuel Patricio Fernandez Lagos",
				  "sol_inicio_etapa": "19-08-2014",
				  "sol_termino_etapa": "",
				  "sol_accion": "",
				  "sol_obs_etapa": "",
				  "sol_correo": "pfernandezl@sb.cl"
				}			 
			 */

			//::como string se me muestra {"sol_rut":"999","sol_respon":"Jefe Local Destino","sol_inicio_etapa":"29-07-2014","sol_termino_etapa":"","sol_accion":"","sol_obs_etapa":""}
			//::despues de transformar el JSON a PHP puedo llamar a al rut como $strQueryBtns->sol_rut

			//query creada para hacer alimentar a la funcion select pkg_seleccion.valida_rut_jefatura (11,11477812,94) from dual;
			$strQueryForJef = "Select EMPRESA, CCOSTO, Pkg_Seleccion_Vacantes.Trae_Rut_Respon_Etapa(".$this->uri->segment(3).") RESPONSABLE From SEL_WF_SOLICITUD_VACANTE where WF_KEY = ".$this->uri->segment(3)."";
			$strQueryForJef = $CI->db->query($strQueryForJef)->result();
			$datosForm['responsable'] = $strQueryForJef[0]->RESPONSABLE;
			
			if (isset($_SESSION['rut'])) {//::INICIO existelaseseionrut::
				
				//:: INICIO:: campara rut responsable (rut/888/999) con rut logueado
				if ($strQueryBtns->sol_rut == $_SESSION['rut']) { //::INICIO si rut responsable es igual al rut logueado:: no entrara si es 888/999
					#$strQueryBtns->sol_rut es el rut del responsable del ovalo seleccionado
					# puede ser el rut propio de la persona
					# o 888 si el responsable es el jefe local origen
					# o 999 si el responsable es el jefe local destino

					//inicio trae string con informacion de botones de accion, para poder construir los botones de accion
					$flujo = $this->uri->segment(6);
					$strQuery ="Select Pkg_Seleccion_Vacantes.Trae_Acciones_Etapa_Actual ('IVAC',".$flujo.",".$ovalo->nroEtapa.",$solicitud) as BOTONES From DUAL";
					$strQuery = $CI->db->query($strQuery)->result();
					$respuesta = json_decode($strQuery[0]->BOTONES);
			
					//contendra los botones creados dentro del foreach
					$htmlBtn_funcion ='';
					$htmlBtn_accion ='';
					foreach ($respuesta as $indice => $val) {
						if ($val->name != '') {
							if ($val->name == 'VIA') {
								$htmlBtn_funcion .= "<a name=\"".$val->name."\" id=\"".$val->name."\" class=\"btn btn-info funcion\" $val->deshabilitar >".$val->title."</a>\n";
								continue;
							} 
							if ($val->name == 'VBZ') {
								$htmlBtn_funcion .= "<a name=\"".$val->name."\" id=\"".$val->name."\" class=\"btn btn-info funcion\" $val->deshabilitar >".$val->title."</a>\n";
								continue;
							}							
							if ($val->name == 'VIC') {
								$htmlBtn_funcion .= "<a name=\"".$val->name."\" id=\"".$val->name."\" class=\"btn btn-info funcion\" $val->deshabilitar >".$val->title."</a>\n";
								continue;
							}							
							if ($val->name == 'VVO') {
								$htmlBtn_funcion .= "<a name=\"".$val->name."\" id=\"".$val->name."\" class=\"btn btn-info funcion\" $val->deshabilitar >".$val->title."</a>\n";
								continue;
							}							
							switch ($val->tipo) {
							    case 'ACCION' :
							        $htmlBtn_accion .= "<button name=\"".$val->name."\" type=\"submit\" id=\"".$val->name."\" class=\"btn btn-info accion\" value=\"".$val->name."\" $val->deshabilitar >".$val->title."</button>\n";
							        break;
							    case 'FUNCION' :
							        $htmlBtn_funcion .= "<button name=\"".$val->name."\" id=\"".$val->name."\" type=\"button\" class=\"btn btn-info funcion\" value=\"".$val->name."\" $val->deshabilitar >".$val->title."</button>\n";
							        break;
							}
						}
					} //fin foreach

					if ($correlativo != $this->uri->segment(5)) {
						$htmlBtn_funcion ='';
						$htmlBtn_accion ='';
					}

					$datosForm['botones_accion'] = $htmlBtn_accion;
					$datosForm['botones_funcion'] = $htmlBtn_funcion;
					
					//-->eliminar $datosForm['activar'] = 'si';
					//fin trae string con informacion de botones de accion, para poder construir los botones de accion

				} elseif ($strQueryBtns->sol_rut == "888" || $strQueryBtns->sol_rut == "999") {//si rut responsable es igual igual a 888/999
					if ($strQueryBtns->sol_rut == "888") {
						//codigo del centro de costo de origen
						$ccosto = $strQueryForJef[0]->CCOSTO;
					}
					if ($strQueryBtns->sol_rut == "999") {
						//codigo del centro de costo de destino
						$ccosto = $strQueryForJef[0]->CCOSTO;
					}	
					$strQueryValJef = "SELECT PKG_SELECCION.valida_rut_jefatura (".$strQueryForJef[0]->EMPRESA.",".$_SESSION['rut'].",".$ccosto.") VALIDA_JEFATURA from dual";
					$strQueryValJef = $CI->db->query($strQueryValJef)->result();
					//::me arrojará S o NULL::$strQueryValJef[0]->VALIDA_JEFATURA; 
				
					$htmlBtn_funcion ='';
					$htmlBtn_accion ='';
					
					if ($strQueryValJef[0]->VALIDA_JEFATURA == "S") {

						//inicio trae string con informacion de botones de accion, para poder construir los botones de accion
						$flujo = $this->uri->segment(6);
						$strQuery ="Select Pkg_Seleccion_Vacantes.Trae_Acciones_Etapa_Actual ('IVAC',".$flujo.",".$ovalo->nroEtapa.",$solicitud) as BOTONES From DUAL";
						$strQuery = $CI->db->query($strQuery)->result();
						$respuesta = json_decode($strQuery[0]->BOTONES);
						
						foreach ($respuesta as $indice => $val) {
							if ($val->name != '') {
								switch ($val->tipo) {
								    case 'ACCION' :
								        $htmlBtn_accion .= "<button name=\"".$val->name."\" id=\"".$val->name."\" type=\"submit\" class=\"btn btn-info accion\" value=\"".$val->name."\" ".$val->deshabilitar."\">".$val->title."</button>\n";
								        break;
								    case 'FUNCION' :
								        $htmlBtn_funcion .= "<button name=\"".$val->name."\" id=\"".$val->name."\" type=\"button\" class=\"btn btn-info funcion\" value=\"".$val->name."\" ".$val->deshabilitar."\">".$val->title."</button>\n";
								        break;
								}
								
							}
						} //fin foreach
						//-->eliminar $datosForm['activar'] = 'si';
					}
					//si existe la variabla activar no coloca disabled el textarea(coloca una cadena vasia)
					
					
					
					
					/*//-->eliminar 
					if (isset($datosForm['activar'])) {
						if ($datosForm['activar'] == 'si') {
							$datosForm['textarea'] = '';
						}
					} else {
						$datosForm['textarea'] = 'disabled="disabled"';
					}
					$datosForm['activar'] = 'si';
					*/
					$datosForm['botones_accion'] = $htmlBtn_accion;
					$datosForm['botones_funcion'] = $htmlBtn_funcion;
					
					
					
					
					//fin trae string con informacion de botones de accion, para poder construir los botones de accion		

				}//fin si sol_rut es 999
				//:: FIN:: campara rut responsable (rut/888/999) con rut logueado				
			}//::FIN existelaseseionrut::
		} 
				
		$conta++;
		endforeach; 
		?>
		</ul>
	</div>
</div>
<!-- /////etapa fin -->

			<!-- inicio ventana modal -->
			<div class="modal" id="myModal">
				<div class="modal-dialog">
			      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
			          <h4 class="modal-title">Titulo</h4>
			        </div>
			        <div class="modal-body">
						<p>Lorem 2 ipsum dolor sit amet, consectetur adipisicing elit. Beatae eaque facilis suscipit repellendus harum laborum a cupiditate dolorum quis ea enim vel aliquid praesentium perspiciatis minima debitis ut exercitationem quam!</p>
			        </div>
			        <div class="modal-footer">
			          <a id="cerrarModal" href="#" data-dismiss="modal" class="btn btn-xs btn-success" style="font-size: 20px;">Cerrar</a>
			        </div>
			      </div>
			    </div>
			</div>
			<!-- fin ventana modal -->
<?php 

	if (!isset($datosForm)) {
		$datosForm['botones_accion'] = '';
		$datosForm['botones_funcion'] = '';
	}
	
	$this->load->view('contenidos/ingreso-vacante/formulario_ingr_vac_view',$datosForm);
?>

<!-- ALERT -->
<link href="<?php echo base_url(); ?>assets/template/tpl2-sb/lib/smartAlert/alert/css/alert.css" rel="stylesheet" />
<link href="<?php echo base_url(); ?>assets/template/tpl2-sb/lib/smartAlert/alert/themes/default/theme.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>assets/template/tpl2-sb/lib/smartAlert/alert/js/alert.js"></script>
<script src="<?php echo base_url(); ?>assets/template/tpl2-sb/lib/smartAlert/alert/js/alertas.personalizadas.js"></script>
<!-- ALERT -->

<script src="<?php echo base_url(); ?>assets/template/tpl2-sb/js/jquery.blockUI.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.populate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/jquery.pulsate.js"></script>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-filestyle.min.js" ></script>
<script>
	$(document).ready(function() {

	   	if ($(".alert.alert-success")) {
	    	setTimeout(function(){ $(".alert.alert-success").hide("slow"); }, 5000);
	    }

	    



		var idBtn = $('#frm_solicitud_vacante').find('.accion').attr('id');
		//alert(idBtn);
		if (!idBtn) {
			//alert(2);
			//alert('hay una funcion');
			//'disabled', 'disabled'
			$('#sol_observ_jefe_dest').attr('disabled', 'disabled');
			
		}

		function insertarCorreo(numeroSolicitud,etapaActual,etapaSiguiente,mailDesde,mailResponsable,textoModal)
		{
			$.ajax({//inicio ajax
				type:"POST",
				url :"<?php echo site_url(); ?>etapa_vac/graba_log_correo",
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

		function enviaMailTodosParticipantes(numeroSolicitudParaMail,resultadoTrasladoAsunto,htmlContent)
		{
			$.ajax({//inicio ajax
				type:"POST",
				url :"<?php echo site_url(); ?>etapa_vac/enviaMailTodosParticipantes",
				dataType:"html",
				//timeout : 10000,
				data:{
						p_id_solicitud:numeroSolicitudParaMail,
						asunto:resultadoTrasladoAsunto,
						contenido:htmlContent
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

//ejecuto el click para poder capturar el valor del boton al cual le hago click
$('.accion').on('click',function() { 

	//cuando aparezca el campo Renta Final evitara que se envie el formulario cuando 
	//este tenga un valor igual o menor que cero
	if (typeof $('#rta_final').val() != 'undefined') {
		if($('#rta_final').val() <= 0) {
			//console.log('La renta ingresada debe ser mayor a 0');
			soloAlertaPersonalizada('Advertencia','La "Renta Final" debe ser mayor a 0');
			return false;
		}
	}
	
	//valor a rescatar IMPORTANTE
   	var accionEjecutada = $(this).val();
   	localStorage.setItem("accionEjecutada", accionEjecutada);

	$("#frm_solicitud_vacante").submit(function(e) {
   		e.preventDefault();
	}).validate({
		rules: {
			sol_observ_jefe_dest: {
				required: true, 
				maxlength: 500
			}
		},

		messages: {
			sol_observ_jefe_dest: {
				required: "Debe colocar una observacion",
				maxlength: "Debe colocar menos de 500 caracteres"
			}

		},
        //perform an AJAX post to ajax.php
	    submitHandler: function(form) { 

	    		var str_sol_observ_jefe_dest = $('#sol_observ_jefe_dest').val();
	    		str_sol_observ_jefe_dest = str_sol_observ_jefe_dest.replace(/\n/g, " ");
	    			    	
	    		//INICIO FUNCION QUE GESTIONA FLUJO
	    		//codigo ajax que ocupaba antes para enviar la accion a la base de datos
	    		//pero ahora se ejecuta dentro de submitHandler


	        	var p_id_solicitud = "<?php echo $this->uri->segment(3); ?>";
				var p_correlativo = "0";
				var p_etapa = "<?php echo $this->uri->segment(4); ?>";
				var p_visualiza = "null";//revizar si queda en la base de datos con valor null(no string)
				
				var meses = new Array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
				var f=new Date();
				var p_inicio  = f.getDate() +'/'+ meses[f.getMonth()] + "/" + f.getFullYear();	
				
				var p_termino = "null";
				var p_responsable1 = "null";
				var p_responsable2 = "null";
				var p_accion = accionEjecutada;//<----
				var p_observacion = str_sol_observ_jefe_dest;
				var p_id_flujo = "<?php echo $this->uri->segment(6); ?>";
				var p_dot_teor_destino = $('#sol_dot_teo_dest').val();
				var p_dot_mae_destino = $('#sol_dot_teo_dest').val();
				var p_pend_egre_destino = $('#sol_dot_mae_dest').val();
				var p_pend_ingre_destino = $('#sol_tras_pend_ingreso_dest').val();
				var sol_rut_hidden = $('#sol_rut_hidden').html();

				var ls_accionEjecutada = localStorage.getItem("accionEjecutada");
				//var ls_accionEjecutada = 'APR';


					//estas son variables de interaccion que las recojo en formulario_ingreso_vac_view.php
					var nombre_interaccion =  '<?php echo $this->nativesession->get( 'nombre_interaccion' ); ?>';

					switch(nombre_interaccion) {
						case "IV_LLV":
							var IV_LLV = $("input[name=IV_LLV]:checked").val();
							break;
						case "IV_RTA":
							var rta_ingresada = 	$("#rta_ingresada").val();
							var rta_final = 		$("#rta_final").val();
							break;
						case "IV_GGPP":
							if(ls_accionEjecutada == 'APR') {
								var rta_ingresada = 	$("#rta_ingresada").val();
								var rta_final = 		$("#rta_final").val();
							} else {
								var rta_ingresada = 	0;
								var rta_final = 		0;					
							}
							break;	
						case "IV_CZON":
							if(ls_accionEjecutada == 'ACE' || ls_accionEjecutada == 'ANU') {
								var rta_ingresada = 	$("#rta_ingresada").val();
								var rta_final = 		$("#rta_final").val();
							} else {
								var rta_ingresada = 	0;
								var rta_final = 		0;					
							}
							break;													
						default:
					}
					
				
				
				//jefe de local
				var p_rut_jefe = <?php echo $_SESSION["rut"]; ?>;
				var p_tipo_local = sol_rut_hidden;

				
				$.ajax({
					type:"POST",
					url :"<?php echo site_url(); ?>etapa_vac/insertLog",
					dataType:"json",
					data:{
						
						p_id_solicitud : 		p_id_solicitud,
						p_correlativo : 		p_correlativo,
						p_etapa : 				p_etapa,
						p_visualiza : 			p_visualiza,
						p_inicio : 				p_inicio,
						p_termino : 			p_termino,
						p_responsable1 : 		p_responsable1,
						p_responsable2 : 		p_responsable2,
						p_accion : 				p_accion,
						p_observacion : 		p_observacion,
						p_id_flujo : 			p_id_flujo,
						p_dot_teor_destino : 	p_dot_teor_destino,
						p_dot_mae_destino : 	p_dot_mae_destino,
						p_pend_egre_destino : 	p_pend_egre_destino,
						p_pend_ingre_destino : 	p_pend_ingre_destino,
						p_rut_jefe : 			p_rut_jefe,
						p_tipo_local : 			p_tipo_local,
						<?php 
							if ($this->nativesession->get( 'nombre_interaccion') != '') {
								switch ($this->nativesession->get( 'nombre_interaccion')){
									case 'IV_LLV':
						?>
						IV_LLV: 				IV_LLV,
						<?php				
										//echo "jagl9734973498";
										break;
									case 'IV_RTA':
						?>
						rta_ingresada : 			rta_ingresada,
						rta_final : 				rta_final,
						<?php
									break;
									case 'IV_GGPP':
						?>
						rta_ingresada : 			rta_ingresada,
						rta_final : 				rta_final,
						<?php
									break;	
									case 'IV_CZON':
						?>
						rta_ingresada : 			rta_ingresada,
						rta_final : 				rta_final,
						<?php
									break;																		
									default:
										//echo "jagl3849848590348509";
								}
							}
						?>						
						nombre_interaccion : 	'<?php echo $this->nativesession->get( 'nombre_interaccion'); ?>',
						
						
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
						//console.log("El insertlog trae esto");
						//-->trae el mail console.log(dato[1]);
						localStorage.setItem('mailResponsable', dato[1]);
						//--> trae el nombre y responsable(mail) que aparece en el modal
						//console.log(dato.comuna);

						var urlRedirec = "<?php echo site_url(); ?>traslado/pendiente";
						var htmlBtnCerrar = '<a id="cerrarModal" class="btn btn-success esunaaccion" href="'+urlRedirec+'" style="font-size: 20px;">Cerrar</a>';

						if (dato[0]=='FIN') {
							$( '.modal-footer' ).html(htmlBtnCerrar);
							switch(dato[1]) {
							case '77':
								var resultadoTrasladoAsunto = 'Traslado Aprobado';
								var htmlEtapaTitle = '';
								var htmlContent = '';
								htmlContent += '<h2>Traslado Aprobado</h2>';
								htmlContent += '<h2>Se envía correo a todos los involucrados</h2>';
								$( '.modal-title' ).html(htmlEtapaTitle);
								$( '.modal-body' ).html(htmlContent);									
								$('#myModal').modal({show:true});	
								break;
							case '88':
								var resultadoTrasladoAsunto = 'Traslado Anulado';
								var htmlEtapaTitle = '';
								var htmlContent = '';
								htmlContent += '<h2>Traslado Anulado</h2>';
								htmlContent += '<h2>Se envía correo a todos los involucrados</h2>';
								$( '.modal-title' ).html(htmlEtapaTitle);
								$( '.modal-body' ).html(htmlContent);	
								//$( '#cerrarModal' ).attr('href',url);								
								$('#myModal').modal({show:true});	
								break;
							case '99':
								var resultadoTrasladoAsunto = 'Traslado Rechazado';
								var htmlEtapaTitle = '';
								var htmlContent = '';
								htmlContent += '<h2>Traslado Rechazado</h2>';
								htmlContent += '<h2>Se envía correo a todos los involucrados</h2>';
								$( '.modal-title' ).html(htmlEtapaTitle);
								$( '.modal-body' ).html(htmlContent);									
								$('#myModal').modal({show:true});	
								break;
							}		

							//enviar mail a todos los involugrados
							var numeroSolicitudParaMail = "<?php echo $this->uri->segment(3); ?>";
							enviaMailTodosParticipantes(numeroSolicitudParaMail,resultadoTrasladoAsunto,htmlContent);

						} else {
							var htmlEtapaTitle = 'Información etapa: Se envía mail a responsable';
							var htmlContent = '';
							htmlContent += '<h2>Nombre del responsable</h2>';
							htmlContent += '<p>'+dato[0]+'</p>';
							htmlContent += '<h2>Correo del responsable</h2>';
							htmlContent += '<p>'+dato[1]+'</p>';
							$( '.modal-title' ).html(htmlEtapaTitle);
							$( '.modal-body' ).html(htmlContent);									
							$('#myModal').modal({show:true});	
						}

					},
	                complete : function (){
	                    $.unblockUI();
	                },			
					error: function(xhr, ajaxOptions, thrownError) {
					msg = "A ocurridoff un error ";
					console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
				}).done(function() {
					$.ajax({
						type:"POST",
						url :"<?php echo site_url(); ?>etapa_vac/goEtapa",
						dataType:"json",
						data:{
							solicitud : "<?php echo $this->uri->segment(3); ?>"
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
							//console.log("El goEtapa trae esto");
							//console.log(dato);
							//CORRELATIVO,ETAPA_ACTUAL,WF_KEY	
							$( '#cerrarModal' ).addClass('esunaaccion');	
							if(dato && dato !="") {

								var numeroSolicitud = dato[0].WF_KEY;
								var etapaActual = <?php echo $this->uri->segment(4); ?>;
								var etapaSiguiente = dato[0].ETAPA_ACTUAL;
								var mailDesde = "workflow@sb.cl";
								var mailResponsable = localStorage.getItem('mailResponsable');
								localStorage.removeItem('mailResponsable');
								var textoModal = "";
								textoModal += $('.modal-title:eq(0)').text();
								textoModal += " "+$('.modal-body h2:eq(0)').text()+" : ";
								textoModal += " "+$('.modal-body p:eq(0)').text()+" | ";
								textoModal += " "+$('.modal-body h2:eq(1)').text()+" : ";
								textoModal += " "+$('.modal-body p:eq(1)').text()+".";

								/*
								console.log("Numero de solicitud es : "+numeroSolicitud);
								console.log("Numero de etapa actual es : "+etapaActual);
								console.log("Numero de etapa siguiente es : "+etapaSiguiente);
								console.log("Mail from es : "+mailDesde);
								console.log("Mail del siguiente responsable es : "+mailResponsable);
								console.log("El texto de la modal es : "+textoModal);
								*/
								
								insertarCorreo(numeroSolicitud,etapaActual,etapaSiguiente,mailDesde,mailResponsable,textoModal);

								//::goto url para ir a la siguiente etapa::
								//despues de la redireccion dato[0].ETAPA_ACTUAL quedara como etapa actual
								var goNewEtapa="<?php echo site_url(); ?>etapa_vac/pendiente/"+dato[0].WF_KEY+"/"+dato[0].ETAPA_ACTUAL+"/"+dato[0].CORRELATIVO+"/"+dato[0].ID_FLUJO;
								localStorage.setItem('url', goNewEtapa);
							}
						},
		                complete : function (){
		                    $.unblockUI();
		                },				
						error: function(xhr, ajaxOptions, thrownError) {
						msg = "A ocurridoff un error ";
						console.log(msg+" "+xhr.status + " " + xhr.statusText);
						}
					});
				});		
				//FIN FUNCION QUE GESTIONA FLUJO
	        return false;  //This doesn't prevent the form from submitting.
	    }	
    });//fin validacion		   				 		
	    	});		


		$('#myModal').on('hidden.bs.modal', function () {
			var enlaceModal = $('#cerrarModal').attr('href');
			//var modalEmail = $('#mail_responsable').val();
			if ($( "#cerrarModal" ).hasClass( "esunaaccion" )) {
				if (enlaceModal == '#') {
					window.location = localStorage.getItem('url');
				} else {
					var togoEnlace = "<?php echo site_url(); ?>traslado/pendiente";
					window.location = togoEnlace;
				}
			} 
		});		

		$( '#IAN' ).click(function(){
			var url = "<?php echo site_url(); ?>etapa_vac/imprAnexo/<?php echo $this->uri->segment(3); ?>";
			window.open(url,'_blank');
		});

		//imprimir bono
		$( '#IBZ' ).click(function(){
			var url = "<?php echo site_url(); ?>etapa_vac/impBonoZona/<?php echo $this->uri->segment(3); ?>";
			window.open(url,'_blank');
		});

		//imprimir traslado voluntario boton llamado IMPRIMIR TRASLADO VOLUNTARIO
		$( '#TVO' ).click(function(){
			var url = "<?php echo site_url(); ?>etapa_vac/impSolTraslado/<?php echo $this->uri->segment(3); ?>";
			window.open(url,'_blank');
		});

		$( '#CAV' ).click(function(){
			var url = "<?php echo site_url(); ?>etapa_vac/imprCarta/<?php echo $this->uri->segment(3); ?>";
			window.open(url,'_blank');
		});

		$( '#SUC' ).click(function(e){
			$('.close').css('font-size','40px');
			//$('.modal-footer').html('');
			var htmlFormUpload = '';
			htmlFormUpload += '<form class="frmSubirArchivo" action="<?php echo base_url(); ?>etapa_vac/subirCarta" method="POST" enctype="multipart/form-data" >';
			htmlFormUpload += '<p>Seleccione la <strong>"Carta de aviso"</strong> (solo fomato PDF menor a 1 mega):</p>';
			htmlFormUpload += '<br />';			
			htmlFormUpload += '<input type="file" data-buttonbefore="true" class="filestyle" name="userfile" id="userfile" tabindex="-1" style="position: absolute; clip: rect(0px 0px 0px 0px);">';					
			htmlFormUpload += '<input type="hidden" name="solicitud" value="<?php echo $this->uri->segment(3); ?>" />';
			htmlFormUpload += '<input type="hidden" name="etapa" value="<?php echo $this->uri->segment(4); ?>" />';
			htmlFormUpload += '<input type="hidden" name="correlativo" value="<?php echo $this->uri->segment(5); ?>" />';
			htmlFormUpload += '<input type="hidden" name="idFlujo" value="<?php echo $this->uri->segment(6); ?>" />';			
			htmlFormUpload += '<button id="btn_guardar" type="submit" name="submit" value="Subir" class="btn btn-success" style="padding-left: 20px; padding-right: 20px; display: inline-block; float: right;">Subir Archivo<span class="glyphicon glyphicon-upload" style="margin-left:15px;"></span></button>';
			htmlFormUpload += '</form>';
			
			$('.modal-title').html('<h2>Subir carta de aviso</h2>');
			$('.modal-body').html(htmlFormUpload);
			$('.modal-footer').html('');
			$('#myModal').modal({show:true});	

			$(":file").filestyle({buttonBefore: true});			

			$('#myModal').modal('show');

			$(".pulse1").pulsate({
				glow:false,
				color:"#00a7ed",
				repeat: 4,
				reach: 14
			});		
			//var url = "<?php echo site_url(); ?>etapa_vac/imprCarta/<?php echo $this->uri->segment(3); ?>";
			//window.open(url,'_blank');
		});

		$( '#SUA' ).click(function(e){
			$('.close').css('font-size','40px');
			var modalTitle = '';
			$('.modal-title').html(modalTitle);
			//$('.modal-footer').html('');
			var htmlFormUpload = '';
			htmlFormUpload += '<form class="frmSubirArchivo" action="<?php echo base_url(); ?>etapa_vac/subirAnexo" method="POST" enctype="multipart/form-data" >';
			htmlFormUpload += '<p>Seleccione el <strong>"Anexo de Contrato"</strong> (solo fomato PDF menor a 1 mega):</p>';
			htmlFormUpload += '<br />';					
			htmlFormUpload += '<input type="file" data-buttonbefore="true" class="filestyle" name="userfile" id="userfile" tabindex="-1" style="position: absolute; clip: rect(0px 0px 0px 0px);">';					
			htmlFormUpload += '<input type="hidden" name="solicitud" value="<?php echo $this->uri->segment(3); ?>" />';
			htmlFormUpload += '<input type="hidden" name="etapa" value="<?php echo $this->uri->segment(4); ?>" />';
			htmlFormUpload += '<input type="hidden" name="correlativo" value="<?php echo $this->uri->segment(5); ?>" />';
			htmlFormUpload += '<input type="hidden" name="idFlujo" value="<?php echo $this->uri->segment(6); ?>" />';				
			htmlFormUpload += '<button id="btn_guardar" type="submit" name="submit" value="Subir" class="btn btn-success" style="padding-left: 20px; padding-right: 20px; display: inline-block; float: right; margin-top: 4px;">Subir Archivo<span class="glyphicon glyphicon-upload" style="margin-left:15px;"></span></button>';
			htmlFormUpload += '</form>';
			
			$('.modal-title').html('<h2>Anexo de Contrato</h2>');
			$('.modal-body').html(htmlFormUpload);
			$('.modal-footer').html('');
			$('#myModal').modal({show:true});	

	
			$(":file").filestyle({buttonBefore: true});

			$(".pulse1").pulsate({
				glow:false,
				color:"#00a7ed",
				repeat: 4,
				reach: 14
			});				
			//var url = "<?php echo site_url(); ?>etapa_vac/imprCarta/<?php echo $this->uri->segment(3); ?>";
			//window.open(url,'_blank');
		});

		//SUBE BONO ZONA
		$( '#SBZ' ).click(function(e){
			$('.close').css('font-size','40px');
			var modalTitle = '';
			$('.modal-title').html(modalTitle);
			//$('.modal-footer').html('');
			var htmlFormUpload = '';
			htmlFormUpload += '<form class="frmSubirArchivo" action="<?php echo base_url(); ?>etapa_vac/subirBonoZona" method="POST" enctype="multipart/form-data" >';
			htmlFormUpload += '<p>Seleccione el <strong>"Anexo de Contrato"</strong> (solo fomato PDF menor a 1 mega):</p>';
			htmlFormUpload += '<br />';					
			htmlFormUpload += '<input type="file" data-buttonbefore="true" class="filestyle" name="userfile" id="userfile" tabindex="-1" style="position: absolute; clip: rect(0px 0px 0px 0px);">';					
			htmlFormUpload += '<input type="hidden" name="solicitud" value="<?php echo $this->uri->segment(3); ?>" />';
			htmlFormUpload += '<input type="hidden" name="etapa" value="<?php echo $this->uri->segment(4); ?>" />';
			htmlFormUpload += '<input type="hidden" name="correlativo" value="<?php echo $this->uri->segment(5); ?>" />';
			htmlFormUpload += '<input type="hidden" name="idFlujo" value="<?php echo $this->uri->segment(6); ?>" />';				
			htmlFormUpload += '<button id="btn_guardar" type="submit" name="submit" value="Subir" class="btn btn-success" style="padding-left: 20px; padding-right: 20px; display: inline-block; float: right; margin-top: 4px;">Subir Archivo<span class="glyphicon glyphicon-upload" style="margin-left:15px;"></span></button>';
			htmlFormUpload += '</form>';
			
			$('.modal-title').html('<h2>Anexo de Contrato</h2>');
			$('.modal-body').html(htmlFormUpload);
			$('.modal-footer').html('');
			$('#myModal').modal({show:true});	

	
			$(":file").filestyle({buttonBefore: true});

			$(".pulse1").pulsate({
				glow:false,
				color:"#00a7ed",
				repeat: 4,
				reach: 14
			});				

		});
		//FIN SUBE BONO ZONA

		//SUBE TRASL. VOLUN.
		$( '#SVO' ).click(function(e){
			$('.close').css('font-size','40px');
			var modalTitle = '';
			$('.modal-title').html(modalTitle);
			var htmlFormUpload = '';
			htmlFormUpload += '<form class="frmSubirArchivo" action="<?php echo base_url(); ?>etapa_vac/subirTraslVolunt" method="POST" enctype="multipart/form-data" >';
			htmlFormUpload += '<p>Seleccione el <strong>"Anexo de Contrato"</strong> (solo fomato PDF menor a 1 mega):</p>';
			htmlFormUpload += '<br />';					
			htmlFormUpload += '<input type="file" data-buttonbefore="true" class="filestyle" name="userfile" id="userfile" tabindex="-1" style="position: absolute; clip: rect(0px 0px 0px 0px);">';					
			htmlFormUpload += '<input type="hidden" name="solicitud" value="<?php echo $this->uri->segment(3); ?>" />';
			htmlFormUpload += '<input type="hidden" name="etapa" value="<?php echo $this->uri->segment(4); ?>" />';
			htmlFormUpload += '<input type="hidden" name="correlativo" value="<?php echo $this->uri->segment(5); ?>" />';
			htmlFormUpload += '<input type="hidden" name="idFlujo" value="<?php echo $this->uri->segment(6); ?>" />';				
			htmlFormUpload += '<button id="btn_guardar" type="submit" name="submit" value="Subir" class="btn btn-success" style="padding-left: 20px; padding-right: 20px; display: inline-block; float: right; margin-top: 4px;">Subir Archivo<span class="glyphicon glyphicon-upload" style="margin-left:15px;"></span></button>';
			htmlFormUpload += '</form>';
			
			$('.modal-title').html('<h2>Anexo de Contrato</h2>');
			$('.modal-body').html(htmlFormUpload);
			$('.modal-footer').html('');
			$('#myModal').modal({show:true});	

	
			$(":file").filestyle({buttonBefore: true});

			$(".pulse1").pulsate({
				glow:false,
				color:"#00a7ed",
				repeat: 4,
				reach: 14
			});				
			//var url = "<?php echo site_url(); ?>etapa_vac/imprCarta/<?php echo $this->uri->segment(3); ?>";
			//window.open(url,'_blank');
		});
		//SUBE TRASL. VOLUN. FIN


		$('#mailRespons').click(function(){
			$('.close').css('font-size','40px');
			var modalTitle = '';
			$('.modal-title').html(modalTitle);
			//$('.modal-footer').html('');
			var htmlFormUpload = '';
			htmlFormUpload += '<form action="<?php echo base_url(); ?>etapa_vac/mailResponsable" method="POST" style="font-size:15px;">';
			htmlFormUpload += '<p>Este mail sera enviado al encargado de gestionar la etapa '+$(".seleccionado .title").html()+'.</p>';
			htmlFormUpload += '<p>';
			htmlFormUpload += '<strong>Para : </strong> '+$('#sol_correo_log').html()+'';
			htmlFormUpload += '</p>';
			htmlFormUpload += '<div class="form-group">';
			htmlFormUpload += '<label for="sol_observ_jefe_dest">ASUNTO: Observacion respecto al requerimiento Nro <span style="color:#4CAE4C"> <?php echo $this->uri->segment(3); ?></span></label>';
			htmlFormUpload += '<textarea name="observParaRespons" id="observParaRespons" class="form-control required" rows="3" style="padding: 6px; height: 59px;"></textarea>';
			htmlFormUpload += '</div>';
			htmlFormUpload += '<input type="hidden" name="to" value="'+$('#sol_correo_log').html()+'" />';
			htmlFormUpload += '<input type="hidden" name="asunto" value="Observacion respecto al requerimiento Nro <?php echo $this->uri->segment(3); ?>" />';
			htmlFormUpload += '<input type="hidden" name="solicitud" value="<?php echo $this->uri->segment(3); ?>" />';
			htmlFormUpload += '<input type="hidden" name="etapa" value="<?php echo $this->uri->segment(4); ?>" />';
			htmlFormUpload += '<input type="hidden" name="correlativo" value="<?php echo $this->uri->segment(5); ?>" />';
			htmlFormUpload += '<input type="hidden" name="idFlujo" value="<?php echo $this->uri->segment(6); ?>" />';				
			htmlFormUpload += '<input type="hidden" name="mail_responsable" id="mail_responsable" value="modalemailaresponsable" />';				
			htmlFormUpload += '<input type="hidden" name="rutLogeado" id="rutLogeado" value="<?php echo $_SESSION['rut']; ?>" />';				
			htmlFormUpload += '<input id="btnSendMailRespons" type="submit" value="Enviar Mail" class="btn btn-success" style="padding-left:20px;padding-right:20px;font-size:18px;" />';
			htmlFormUpload += '</form>';
			
			$('.modal-title').html('<h2>ENVIAR CORREO A RESPONSABLE</h2>');
			$('.modal-body').html(htmlFormUpload);
			$('#myModal').modal('show');


			/*
			var numeroSolicitud = <?php echo $this->uri->segment(3); ?>;
			var etapaActual = <?php echo $this->uri->segment(4); ?>;
			var etapaSiguiente = etapaActual;
			var mailDesde = "workflow@sb.cl";
			var mailResponsable = $('#sol_correo_log').html();
			var textoModal = "dfsdfsdf";
			insertarCorreo(numeroSolicitud,etapaActual,etapaSiguiente,mailDesde,mailResponsable,textoModal);
			*/




		});
		//alert(34234);
		/*
		$(window).resize(function() {
			var alto = $(window).height() - 100;
			alto = alto + 'px';
			$('.contenedor_frm').css('height',alto);			 
		});
		*/
	});
</script>
<!-- tickets a los botones -->
<?php 
	if ($this->session->flashdata('tipoDocumento') == 'carta' || ( isset($_SESSION['subioCarta']) && in_array($this->uri->segment(3),$_SESSION['arrCarta']) ) ) : 
		$_SESSION['subioCarta'] = 'si';
		$_SESSION['arrCarta'][] = $this->uri->segment(3);
		array_unique($_SESSION['arrCarta']);
?>
	<script>
		$('#SUC').removeClass('btn-info');
		$('#SUC').addClass('subidaExitosa');
	</script>
<?php endif;?>
<?php 
	if ($this->session->flashdata('tipoDocumento') == 'anexo' || ( isset($_SESSION['subioAnexo']) && in_array($this->uri->segment(3),$_SESSION['arrAnexo']) ) ) : 
		$_SESSION['subioAnexo'] = 'si';
		$_SESSION['arrAnexo'][] = $this->uri->segment(3);
		array_unique($_SESSION['arrAnexo']);	
?>
		<script>
			$('#SUA').removeClass('btn-info');
			$('#SUA').addClass('subidaExitosa');			
		</script>
<?php endif;?>
<?php 
	if ($this->session->flashdata('tipoDocumento') == 'bono' || ( isset($_SESSION['subioBono']) && in_array($this->uri->segment(3),$_SESSION['arrBono']) ) ) : 
		$_SESSION['subioBono'] = 'si';
		$_SESSION['arrBono'][] = $this->uri->segment(3);
		array_unique($_SESSION['arrBono']);	
?>
		<script>
			$('#SBZ').removeClass('btn-info');
			$('#SBZ').addClass('subidaExitosa');
		</script>
<?php endif;?>
<?php 
	if ($this->session->flashdata('tipoDocumento') == 'trasl_volunt' || ( isset($_SESSION['subioTrasVol']) && in_array($this->uri->segment(3),$_SESSION['arrTrasVol']) ) ) : 
		$_SESSION['subioTrasVol'] = 'si';
		$_SESSION['arrTrasVol'][] = $this->uri->segment(3);
		array_unique($_SESSION['arrTrasVol']);	
?>
		<script>
			$('#SVO').removeClass('btn-info');
			$('#SVO').addClass('subidaExitosa');			
		</script>
<?php endif;?>