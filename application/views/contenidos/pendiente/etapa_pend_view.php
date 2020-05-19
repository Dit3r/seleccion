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
.funcion {
	width: 98px;
}
.accion {
	width: 134px;
}
.pulse1 .btn {
	z-index: 1000;
}

</style>

<!-- ::::::::::::::NUEVOS OVALOS TITULOS INICIO::::::::::::::::::: -->
<div class="row" style="margin-top: 12px;">
	<div class="col-md-5 col-md-offset-1">
		<p class="nroSolicitud"><span>Nro de Solicitud de Traslado : <?php echo $idSolicitud; ?></span></p>
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
						<a class="toOvalo" data-etapa="<?php echo $ovalo->nroEtapa; ?>"  href="<?php echo site_url(); ?>etapa/pendiente/<?php echo $idSolicitud; ?>/<?php echo $ovalo->nroEtapa; ?>/<?php echo $ovalo->correlativo; ?>/<?php echo $this->uri->segment(6); ?>">
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
			$CI =& get_instance();
			$CI->db = $this->load->database('bdseleccion', true);
			$solicitud = $this->uri->segment(3);

			//obtener el correlativo
			/*
			$strCorrelativo = "
				SELECT CORRELATIVO 
				FROM SEL_WF_LOG_TRASLADO 
				WHERE WF_KEY = ".$solicitud." 
				AND correlativo = (	
				                    SELECT max(correlativo) 
				                    FROM SEL_WF_LOG_TRASLADO 
				                    WHERE WF_KEY = ".$solicitud."
				                   ) 
				                   ORDER  BY CORRELATIVO		
			";
			*/
			$strCorrelativo = "SELECT (PKG_SELECCION.trae_correlativo (".$solicitud.") - 1) CORRELATIVO FROM DUAL";
			$strCorrelativo = $CI->db->query($strCorrelativo)->result();
			$correlativo = $strCorrelativo[0]->CORRELATIVO;

			$strQueryBtns = "SELECT PKG_SELECCION.trae_info_etapa (".$solicitud.", ".$ovalo->nroEtapa.", ".$correlativo.") as ETAPA_LOG from dual";
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
			$strQueryForJef = "SELECT EMPRESA, CC_ORIGEN, CC_DESTINO, PKG_SELECCION.trae_rut_respon_etapa(".$this->uri->segment(3).") RESPONSABLE FROM SEL_WF_SOLICITUD_TRASLADO WHERE  WF_KEY = ".$this->uri->segment(3)."";
			$strQueryForJef = $CI->db->query($strQueryForJef)->result();
			$datosForm['responsable'] = $strQueryForJef[0]->RESPONSABLE;
			
			if (isset($_SESSION['rut'])) {//::INICIO existelaseseionrut::
				
				//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				//::INICIO:: CAMPARA RUT RESPONSABLE (RUT/888/999) CON RUT LOGUEADO:://
				//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://
				if ($strQueryBtns->sol_rut == $_SESSION['rut']) { //::INICIO si rut responsable es igual al rut logueado:: no entrara si es 888/999
					#$strQueryBtns->sol_rut es el rut del responsable del ovalo seleccionado
					# puede ser el rut propio de la persona
					# o 888 si el responsable es el jefe local origen
					# o 999 si el responsable es el jefe local destino

					//inicio trae string con informacion de botones de accion, para poder construir los botones de accion
					$flujo = $this->uri->segment(6);
					$strQuery ="SELECT PKG_SELECCION.trae_acciones_etapa_actual ('TRAS',".$flujo.",".$ovalo->nroEtapa.",$solicitud) as BOTONES from dual";
					$strQuery = $CI->db->query($strQuery)->result();
					$respuesta = json_decode($strQuery[0]->BOTONES);

					//contendra los botones creados dentro del foreach
					$htmlBtn_funcion ='';
					$htmlBtn_accion ='';

					foreach ($respuesta as $indice => $val) {
						if ($val->name != '') {
							if ($val->name == 'VIA') {
								$htmlBtn_funcion .= "<a name=\"".$val->name."\" id=\"".$val->name."\" class=\"btn btn-info funcion\" $val->sololectura >".$val->title."</a>\n";
								continue;
							} 
							if ($val->name == 'VBZ') {
								$htmlBtn_funcion .= "<a name=\"".$val->name."\" id=\"".$val->name."\" class=\"btn btn-info funcion\" $val->sololectura >".$val->title."</a>\n";
								continue;
							}							
							if ($val->name == 'VIC') {
								$htmlBtn_funcion .= "<a name=\"".$val->name."\" id=\"".$val->name."\" class=\"btn btn-info funcion\" $val->sololectura >".$val->title."</a>\n";
								continue;
							}							
							if ($val->name == 'VVO') {
								$htmlBtn_funcion .= "<a name=\"".$val->name."\" id=\"".$val->name."\" class=\"btn btn-info funcion\" $val->sololectura >".$val->title."</a>\n";
								continue;
							}							
							switch ($val->tipo) {
							    case 'ACCION' :
							        $htmlBtn_accion .= "<button name=\"".$val->name."\" type=\"submit\" id=\"".$val->name."\" class=\"btn btn-info accion\" value=\"".$val->name."\" $val->sololectura >".$val->title."</button>\n";
							        break;
							    case 'FUNCION' :
							        $htmlBtn_funcion .= "<button name=\"".$val->name."\" id=\"".$val->name."\" type=\"button\" class=\"btn btn-info funcion\" value=\"".$val->name."\" $val->sololectura >".$val->title."</button>\n";
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
						$ccosto = $strQueryForJef[0]->CC_ORIGEN;
					}
					if ($strQueryBtns->sol_rut == "999") {
						//codigo del centro de costo de destino
						$ccosto = $strQueryForJef[0]->CC_DESTINO;
					}	
					$strQueryValJef = "SELECT PKG_SELECCION.valida_rut_jefatura (".$strQueryForJef[0]->EMPRESA.",".$_SESSION['rut'].",".$ccosto.") VALIDA_JEFATURA from dual";
					$strQueryValJef = $CI->db->query($strQueryValJef)->result();
					//::me arrojará S o NULL::$strQueryValJef[0]->VALIDA_JEFATURA; 
				
					$htmlBtn_funcion ='';
					$htmlBtn_accion ='';
					
					if ($strQueryValJef[0]->VALIDA_JEFATURA == "S") {

						//inicio trae string con informacion de botones de accion, para poder construir los botones de accion
						$flujo = $this->uri->segment(6);
						$strQuery ="SELECT PKG_SELECCION.trae_acciones_etapa_actual ('TRAS',".$flujo.",".$ovalo->nroEtapa.",$solicitud) as BOTONES from dual";
						$strQuery = $CI->db->query($strQuery)->result();
						$respuesta = json_decode($strQuery[0]->BOTONES);
						
						foreach ($respuesta as $indice => $val) {
							if ($val->name != '') {
								if ($val->name == 'VIA') {
									$htmlBtn_funcion .= "<a name=\"".$val->name."\" id=\"".$val->name."\" class=\"btn btn-info funcion\" $val->sololectura >".$val->title."</a>\n";
									continue;
								}			
								if ($val->name == 'VBZ') {
									$htmlBtn_funcion .= "<a name=\"".$val->name."\" id=\"".$val->name."\" class=\"btn btn-info funcion\" $val->sololectura >".$val->title."</a>\n";
									continue;
								}
								if ($val->name == 'VIC') {
									$htmlBtn_funcion .= "<a name=\"".$val->name."\" id=\"".$val->name."\" class=\"btn btn-info funcion\" $val->sololectura >".$val->title."</a>\n";
									continue;
								}
								if ($val->name == 'VVO') {
									$htmlBtn_funcion .= "<a name=\"".$val->name."\" id=\"".$val->name."\" class=\"btn btn-info funcion\" $val->sololectura >".$val->title."</a>\n";
									continue;
								}													
								switch ($val->tipo) {
								    case 'ACCION' :
								        $htmlBtn_accion .= "<button name=\"".$val->name."\" id=\"".$val->name."\" type=\"submit\" class=\"btn btn-info accion\" value=\"".$val->name."\" ".$val->sololectura."\">".$val->title."</button>\n";
								        break;
								    case 'FUNCION' :
								        $htmlBtn_funcion .= "<button name=\"".$val->name."\" id=\"".$val->name."\" type=\"button\" class=\"btn btn-info funcion\" value=\"".$val->name."\" ".$val->sololectura.">".$val->title."</button>\n";
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
			          <a id="cerrarModal" href="#" data-dismiss="modal" class="btn btn-danger" style="padding-right: 20px; display: inline-block; padding-left: 20px; height: 28px; margin-top: 5px; line-height: 14px;">Cerrar</a>
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
	$this->load->view('contenidos/pendiente/formulario_solicitud_view',$datosForm);
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




<?php 
				if ($this->session->flashdata('ejecutada') != null ) : 
					$subido = $this->session->flashdata('ejecutada');
					if ($subido == "no") :
?>
						soloAlertaPersonalizada('ADVERTENCIA !',' NO se ha subido su archivo, recuerde que solo se aceptan archivos con la extension PDF y con menos de 1 Megabyte!. ');
<?php
					endif;
					if ($subido == "si") :
?>
						soloAlertaPersonalizada('Aviso','Archivo subido correctamente!. ');
<?php						
					endif;	
?>
			
	
		<?php 	endif;?>




		<?php 
				if ($this->session->flashdata('ejecutadaMail') != null ) : 
					$mailEnviado = $this->session->flashdata('ejecutadaMail');
					if ($mailEnviado == "no") :
?>
						soloAlertaPersonalizada('ADVERTENCIA !','<span><strong>ATENCION !</strong> : </span> NO se ha logrado enviar su mail, intente nuevamente!');
<?php
					endif;
					if ($mailEnviado == "si") :
?>
						soloAlertaPersonalizada('Aviso','<span><strong>Felicitaciones!</strong>:</span> mail enviado Exitosamente!.');
<?php						
					endif;	
?>
			
	
		<?php 	endif;?>
						


			
		<!-- fin mensaje alerta -->






		

		/*:::::::::::::::::::READONLY INICIO:::::::::::::::::::::::*/
		$('.funcion').each(function(indice, elemento){
			if($(this).attr('readonly')) {
				$(this).css(
					{
						'background-color' : '#5bc0de', 
						'border-color' : '#46b8da',
						'cursor' : 'default',
						'opacity' : '0.65'
					});
				$(this).removeAttr('id');
			}			
		});


		$('.funcion').click(function(){
			if($(this).attr('readonly')) {
				soloAlertaPersonalizada('Advertencia','Solo podrá imprimir o subir documentos en los botones habilitados.');
				//alert("Solo podrá imprimir o subir documentos en los botones habilitados.");
				return false;
			}
			
		});		
		/*:::::::::::::::::::READONLY FIN:::::::::::::::::::::::*/


	   	if ($(".alert.alert-success")) {
	    	setTimeout(function(){ $(".alert.alert-success").hide("slow"); }, 5000);
	    }

	    



		var idBtn = $('#frm_solicitud_traslado_ori').find('.accion').attr('id');
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
						msg = "A ocurrido un error al ejecutar la funcion graba_log_correo";
						console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
			});//fin ajax			
		}

		function enviaMailTodosParticipantes(numeroSolicitudParaMail,resultadoTrasladoAsunto,htmlContent)
		{
			$.ajax({//inicio ajax
				type:"POST",
				url :"<?php echo site_url(); ?>etapa/enviaMailTodosParticipantes",
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
						msg = "A ocurrido un error al ejecutar la funcion enviaMailTodosParticipantes";
						console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
			});//fin ajax	
		}

//ejecuto el click para poder capturar el valor del boton al cual le hago click
$('.accion').on('click',function() { 
	//valor a rescatar IMPORTANTE
   	var accionEjecutada = $(this).val();

	$("#frm_solicitud_traslado_ori").submit(function(e) {
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
				
				//jefe de local
				var p_rut_jefe = <?php echo $_SESSION["rut"]; ?>;
				var p_tipo_local = sol_rut_hidden;

				
				$.ajax({
					type:"POST",
					url :"<?php echo site_url(); ?>etapa/insertLog",
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
						rutLogueado : 			'<?php echo $_SESSION['rut']; ?>'
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
						console.log("Este es el correo al que envio jagl20150612154502" + dato[1]);
						localStorage.setItem('mailResponsable', dato[1]);
						//--> trae el nombre y responsable(mail) que aparece en el modal
						//console.log(dato.comuna);

						var urlRedirec = "<?php echo site_url(); ?>traslado/pendiente";
						var htmlBtnCerrar = '<a id="cerrarModal" class="btn btn-success esunaaccion" href="'+urlRedirec+'" style="font-size: 20px;">Cerrar</a>';

						if (dato[0]=='FIN') {
							$( '.modal-footer' ).html(htmlBtnCerrar);
							switch(dato[1]) {
							case '77':
								var nroSolicitud = <?php echo $this->uri->segment(3); ?>;
								var colabTrasladado = $('#sol_rut').val();
								var ccostoOrigen = $('#sol_local').val();
								var ccostoDestino = $('#sol_local_dest').val();
								
								var resultadoTrasladoAsunto = 'Traslado '+ nroSolicitud +' Aprobado';
								var htmlEtapaTitle = '';
								var htmlContent = '';
								htmlContent += '<h2>Traslado '+ nroSolicitud +' Aprobado</h2>';
								htmlContent += '<h2>Se envía correo a todos los involucrados</h2>';
													
								$( '.modal-title' ).html(htmlEtapaTitle);
								$( '.modal-body' ).html(htmlContent);									
								$('#myModal').modal({show:true});	
								
								//htmlContent += '<h2>Se envía correo a todos los involucrados</h2>';
								htmlContent = '<h2>Traslado Aprobado</h2>';
								htmlContent += '<p style="color:red;">Se notificará cuando se produzca la modificación en la ficha de personal</p>';
								htmlContent += '<table class="table" border="1" align="center" cellpadding="4">';
								htmlContent += '	<thead>';
								htmlContent += '		<tr>';
								htmlContent += '			<th>ID</th>';
								htmlContent += '			<th>Colaborador</th>';
								htmlContent += '			<th>C. Costo origen</th>';
								htmlContent += '			<th>C. Costo destino</th>';
								htmlContent += '		</tr>';
								htmlContent += '	</thead>';
								htmlContent += '	<tbody>';
								htmlContent += '		<tr>';
								htmlContent += '			<td>'+ nroSolicitud +'</td>';
								htmlContent += '			<td>'+ colabTrasladado +'</td>';
								htmlContent += '			<td>'+ ccostoOrigen +'</td>';
								htmlContent += '			<td>'+ ccostoDestino +'</td>';
								htmlContent += '		</tr>';
								htmlContent += '	</tbody>';
								htmlContent += '</table>';											
								break;
							case '88':
								var nroSolicitud = <?php echo $this->uri->segment(3); ?>;
								var colabTrasladado = $('#sol_rut').val();
								var ccostoOrigen = $('#sol_local').val();
								var ccostoDestino = $('#sol_local_dest').val();
																
								var resultadoTrasladoAsunto = 'Traslado  '+ nroSolicitud +'  Anulado';
								var htmlEtapaTitle = '';
								var htmlContent = '';
								htmlContent += '<h2>Traslado Anulado</h2>';
								htmlContent += '<h2>Se envía correo a todos los involucrados</h2>';
								
								$( '.modal-title' ).html(htmlEtapaTitle);
								$( '.modal-body' ).html(htmlContent);	
								//$( '#cerrarModal' ).attr('href',url);		
								$('#myModal').modal({show:true});
								htmlContent += '<table class="table" border="1" align="center" cellpadding="4">';
								htmlContent += '	<thead>';
								htmlContent += '		<tr>';
								htmlContent += '			<th>ID</th>';
								htmlContent += '			<th>Colaborador</th>';
								htmlContent += '			<th>C. Costo origen</th>';
								htmlContent += '			<th>C. Costo destino</th>';
								htmlContent += '		</tr>';
								htmlContent += '	</thead>';
								htmlContent += '	<tbody>';
								htmlContent += '		<tr>';
								htmlContent += '			<td>'+ nroSolicitud +'</td>';
								htmlContent += '			<td>'+ colabTrasladado +'</td>';
								htmlContent += '			<td>'+ ccostoOrigen +'</td>';
								htmlContent += '			<td>'+ ccostoDestino +'</td>';
								htmlContent += '		</tr>';
								htmlContent += '	</tbody>';
								htmlContent += '</table>';											
								break;
							case '99':
								var nroSolicitud = <?php echo $this->uri->segment(3); ?>;
								var colabTrasladado = $('#sol_rut').val();
								var ccostoOrigen = $('#sol_local').val();
								var ccostoDestino = $('#sol_local_dest').val();
																
								var resultadoTrasladoAsunto = 'Traslado  '+ nroSolicitud +' Rechazado';
								var htmlEtapaTitle = '';
								var htmlContent = '';
								htmlContent += '<h2>Traslado Rechazado</h2>';
								htmlContent += '<h2>Se envía correo a todos los involucrados</h2>';
								$( '.modal-title' ).html(htmlEtapaTitle);
								$( '.modal-body' ).html(htmlContent);									
								$('#myModal').modal({show:true});	
								htmlContent += '<table class="table" border="1" align="center" cellpadding="4">';
								htmlContent += '	<thead>';
								htmlContent += '		<tr>';
								htmlContent += '			<th>ID</th>';
								htmlContent += '			<th>Colaborador</th>';
								htmlContent += '			<th>C. Costo origen</th>';
								htmlContent += '			<th>C. Costo destino</th>';
								htmlContent += '		</tr>';
								htmlContent += '	</thead>';
								htmlContent += '	<tbody>';
								htmlContent += '		<tr>';
								htmlContent += '			<td>'+ nroSolicitud +'</td>';
								htmlContent += '			<td>'+ colabTrasladado +'</td>';
								htmlContent += '			<td>'+ ccostoOrigen +'</td>';
								htmlContent += '			<td>'+ ccostoDestino +'</td>';
								htmlContent += '		</tr>';
								htmlContent += '	</tbody>';
								htmlContent += '</table>';										
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
							htmlContent += '<h2>Motivo</h2>';
							htmlContent += '<p>'+dato[2]+'</p>';
							$( '.modal-title' ).html(htmlEtapaTitle);
							$( '.modal-body' ).html(htmlContent);
							console.log('En caso de que no sea FIN abre modal de la etapa');											
							$('#myModal').modal({show:true});	
						}

					},
	                complete : function (){
	                    $.unblockUI();
	                },			
					error: function(xhr, ajaxOptions, thrownError) {
					msg = "A ocurrido un error al ejecutar la funcion insertLog";
					console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
				}).done(function() {
					$.ajax({
						type:"POST",
						url :"<?php echo site_url(); ?>etapa/goEtapa",
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
								var goNewEtapa="<?php echo site_url(); ?>etapa/pendiente/"+dato[0].WF_KEY+"/"+dato[0].ETAPA_ACTUAL+"/"+dato[0].CORRELATIVO+"/"+dato[0].ID_FLUJO;
								localStorage.setItem('url', goNewEtapa);
							}
						},
		                complete : function (){
		                    $.unblockUI();
		                },				
						error: function(xhr, ajaxOptions, thrownError) {
						msg = "A ocurrido un error al ejecutar la funcion goEtapa";
						console.log(msg+" "+xhr.status + " " + xhr.statusText);
						}
					});
				});		
				//FIN FUNCION QUE GESTIONA FLUJO
	        return false;  //This doesn't prevent the form from submitting.
	    }	
    });//fin validacion		   				 		
});	//fin evento click en.accion


		/*IR A LA SIGUIENTE ETAPA 
		cuando esta abierta la modal y luego se hace un click tanto en la click, 
		como en el boton, como en la parte sombreada ingresa a esta funcion
		*/
		$('#myModal').on('hidden.bs.modal', function () {
			var enlaceModal = $('#cerrarModal').attr('href');
			//var modalEmail = $('#mail_responsable').val();
			
			/*
			si antes de hacer el click se hizo click en un boton izquierdo
			(es decir una accion), el elemento con id #cerrarModal
			este tendra como clase esunaaccion por lo tanto ingresara aca
			*/
			if ($( "#cerrarModal" ).hasClass( "esunaaccion" )) {

				//si en el enlace tiene este signo # se enviara a la siguiente
				//etapa, de lo contrario ira a la pagina traslado traslado/pendiente
				if (enlaceModal == '#') {
					window.location = localStorage.getItem('url');
				} else {
					var togoEnlace = "<?php echo site_url(); ?>traslado/pendiente";
					window.location = togoEnlace;
				}
			} 
		});		

		$( '#IAN' ).click(function(){
			var url = "<?php echo site_url(); ?>etapa/imprAnexo/<?php echo $this->uri->segment(3); ?>";
			window.open(url,'_blank');
		});

		//imprimir bono
		$( '#IBZ' ).click(function(){
			var url = "<?php echo site_url(); ?>etapa/impBonoZona/<?php echo $this->uri->segment(3); ?>";
			window.open(url,'_blank');
		});

		//imprimir traslado voluntario boton llamado IMPRIMIR TRASLADO VOLUNTARIO
		$( '#TVO' ).click(function(){
			var url = "<?php echo site_url(); ?>etapa/impSolTraslado/<?php echo $this->uri->segment(3); ?>";
			window.open(url,'_blank');
		});

		$( '#CAV' ).click(function(){
			var url = "<?php echo site_url(); ?>etapa/imprCarta/<?php echo $this->uri->segment(3); ?>";
			window.open(url,'_blank');
		});

		$( '#SUC' ).click(function(e){
			$('.close').css('font-size','40px');
			//$('.modal-footer').html('');
			var htmlFormUpload = '';
			htmlFormUpload += '<form class="frmSubirArchivo" action="<?php echo base_url(); ?>etapa/subirCarta" method="POST" enctype="multipart/form-data" >';
			htmlFormUpload += '<p>Seleccione la <strong>"Carta de aviso"</strong> (solo fomato PDF menor a 1 mega):</p>';
			htmlFormUpload += '<br />';			
			htmlFormUpload += '<input type="file" data-buttonbefore="true" class="filestyle" name="userfile" id="userfile" tabindex="-1" style="position: absolute; clip: rect(0px 0px 0px 0px);">';					
			htmlFormUpload += '<input type="hidden" name="solicitud" value="<?php echo $this->uri->segment(3); ?>" />';
			htmlFormUpload += '<input type="hidden" name="etapa" value="<?php echo $this->uri->segment(4); ?>" />';
			htmlFormUpload += '<input type="hidden" name="correlativo" value="<?php echo $this->uri->segment(5); ?>" />';
			htmlFormUpload += '<input type="hidden" name="idFlujo" value="<?php echo $this->uri->segment(6); ?>" />';			
			htmlFormUpload += '<button id="btn_guardar" type="submit" name="submit" value="Subir" class="btn btn-success" style="padding-right: 20px; display: inline-block; padding-left: 20px; height: 28px; margin-top: 5px; line-height: 10px;">Subir archivo</button>';
			htmlFormUpload += '</form>';
			
			$('.modal-title').html('<h2>Subir carta de aviso</h2>');
			$('.modal-body').html(htmlFormUpload);
			//$('.modal-footer').html('');
			$('#myModal').modal({show:true});	

			$(":file").filestyle({buttonBefore: true});			

			$('#myModal').modal('show');

			$(".pulse1 .btn").pulsate({
				glow:false,
				color:"#00a7ed",
				repeat: 4,
				reach: 14
			});		
			//var url = "<?php echo site_url(); ?>etapa/imprCarta/<?php echo $this->uri->segment(3); ?>";
			//window.open(url,'_blank');
		});

		//subir anexo
		$( '#SUA' ).click(function(e){
			var modalTitle = '';
			$('.modal-title').html(modalTitle);
			//$('.modal-footer').html('');
			var htmlFormUpload = '';
			htmlFormUpload += '<form class="frmSubirArchivo" action="<?php echo base_url(); ?>etapa/subirAnexo" method="POST" enctype="multipart/form-data" >';
			htmlFormUpload += '<p>Seleccione el <strong>"Anexo de Contrato"</strong> (solo fomato PDF menor a 1 mega):</p>';
			htmlFormUpload += '<br />';					
			htmlFormUpload += '<input type="file" data-buttonbefore="true" class="filestyle" name="userfile" id="userfile" tabindex="-1" style="position: absolute; clip: rect(0px 0px 0px 0px);">';					
			htmlFormUpload += '<input type="hidden" name="solicitud" value="<?php echo $this->uri->segment(3); ?>" />';
			htmlFormUpload += '<input type="hidden" name="etapa" value="<?php echo $this->uri->segment(4); ?>" />';
			htmlFormUpload += '<input type="hidden" name="correlativo" value="<?php echo $this->uri->segment(5); ?>" />';
			htmlFormUpload += '<input type="hidden" name="idFlujo" value="<?php echo $this->uri->segment(6); ?>" />';				
			htmlFormUpload += '<button id="btn_guardar" type="submit" name="submit" value="Subir" class="btn btn-success" style="padding-right: 20px; display: inline-block; padding-left: 20px; height: 28px; margin-top: 5px; line-height: 10px;">Subir archivo</button>';
			htmlFormUpload += '</form>';
			
			$('.modal-title').html('Anexo de Contrato');
			$('.modal-body').html(htmlFormUpload);
			//$('.modal-footer').html('<a style="font-size: 20px;" class="btn btn-xs btn-success esunaaccion" data-dismiss="modal" href="#" id="cerrarModal">Cerrar</a>');
			$('#myModal').modal({show:true});	

	
			$(":file").filestyle({buttonBefore: true});

			$(".pulse1 .btn").pulsate({
				glow:false,
				color:"#00a7ed",
				repeat: 4,
				reach: 14
			});				
			//var url = "<?php echo site_url(); ?>etapa/imprCarta/<?php echo $this->uri->segment(3); ?>";
			//window.open(url,'_blank');
		});

		//SUBE BONO ZONA
		$( '#SBZ' ).click(function(e){
			$('.close').css('font-size','40px');
			var modalTitle = '';
			$('.modal-title').html(modalTitle);
			//$('.modal-footer').html('');
			var htmlFormUpload = '';
			htmlFormUpload += '<form class="frmSubirArchivo" action="<?php echo base_url(); ?>etapa/subirBonoZona" method="POST" enctype="multipart/form-data" >';
			htmlFormUpload += '<p>Seleccione el <strong>"Bono Zona"</strong> (solo fomato PDF menor a 1 mega):</p>';
			htmlFormUpload += '<br />';					
			htmlFormUpload += '<input type="file" data-buttonbefore="true" class="filestyle" name="userfile" id="userfile" tabindex="-1" style="position: absolute; clip: rect(0px 0px 0px 0px);">';					
			htmlFormUpload += '<input type="hidden" name="solicitud" value="<?php echo $this->uri->segment(3); ?>" />';
			htmlFormUpload += '<input type="hidden" name="etapa" value="<?php echo $this->uri->segment(4); ?>" />';
			htmlFormUpload += '<input type="hidden" name="correlativo" value="<?php echo $this->uri->segment(5); ?>" />';
			htmlFormUpload += '<input type="hidden" name="idFlujo" value="<?php echo $this->uri->segment(6); ?>" />';				
			htmlFormUpload += '<button id="btn_guardar" type="submit" name="submit" value="Subir" class="btn btn-success" style="padding-right: 20px; display: inline-block; padding-left: 20px; height: 28px; margin-top: 5px; line-height: 10px;">Subir archivo<span class="glyphicon glyphicon-upload" style="margin-left:15px;"></span></button>';
			htmlFormUpload += '</form>';
			
			$('.modal-title').html('<h2>Anexo de Contrato</h2>');
			$('.modal-body').html(htmlFormUpload);
			//$('.modal-footer').html('');
			$('#myModal').modal({show:true});	

	
			$(":file").filestyle({buttonBefore: true});

			$(".pulse1 .btn").pulsate({
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
			htmlFormUpload += '<form class="frmSubirArchivo" action="<?php echo base_url(); ?>etapa/subirTraslVolunt" method="POST" enctype="multipart/form-data" >';
			htmlFormUpload += '<p>Seleccione el <strong>"Traslado Voluntario"</strong> (solo fomato PDF menor a 1 mega):</p>';
			htmlFormUpload += '<br />';					
			htmlFormUpload += '<input type="file" data-buttonbefore="true" class="filestyle" name="userfile" id="userfile" tabindex="-1" style="position: absolute; clip: rect(0px 0px 0px 0px);">';					
			htmlFormUpload += '<input type="hidden" name="solicitud" value="<?php echo $this->uri->segment(3); ?>" />';
			htmlFormUpload += '<input type="hidden" name="etapa" value="<?php echo $this->uri->segment(4); ?>" />';
			htmlFormUpload += '<input type="hidden" name="correlativo" value="<?php echo $this->uri->segment(5); ?>" />';
			htmlFormUpload += '<input type="hidden" name="idFlujo" value="<?php echo $this->uri->segment(6); ?>" />';				
			htmlFormUpload += '<button id="btn_guardar" type="submit" name="submit" value="Subir" class="btn btn-success" style="padding-right: 20px; display: inline-block; padding-left: 20px; height: 28px; margin-top: 5px; line-height: 10px;">Subir archivo</button>';
			htmlFormUpload += '</form>';
			
			$('.modal-title').html('<h2>Anexo de Contrato</h2>');
			$('.modal-body').html(htmlFormUpload);
			//$('.modal-footer').html('');
			$('#myModal').modal({show:true});	

	
			$(":file").filestyle({buttonBefore: true});

			$(".pulse1 .btn").pulsate({
				glow:false,
				color:"#00a7ed",
				repeat: 4,
				reach: 14
			});				
			//var url = "<?php echo site_url(); ?>etapa/imprCarta/<?php echo $this->uri->segment(3); ?>";
			//window.open(url,'_blank');
		});
		//SUBE TRASL. VOLUN. FIN

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://		
//::::::::::::::::::::::::::::::BOTONES ACCION Y FUNCIONES:::::::::::::::::::::::::::::::::::::::::::://		
//::::::::::::::::::::::::::::::::::::::INICIO::::::::::::::::::::::::::::::::::::::::::::::::::::::://		
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://		
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://		
		//::::::::BOTON VISUALIZA ANEXO FIN		
		//verifica si existe el archivo A+numero de orden INICIO
			$.ajax({//inicio ajax
				type:"POST",
				url :"<?php echo site_url(); ?>etapa/existeArchivo",
				dataType:"html",
				data:{
						nombre_fichero:"A<?php echo $this->uri->segment(3); ?>.pdf"
				},
				success: function(dato){
					if (dato == "no_existe") {
						$('#VIA').attr('href', 'javascript:void(0)');	
					}
					if (dato == "si_existe") {
						//cambia color
						$('#VIA').removeClass('btn-info');
						$('#VIA').addClass('subidaExitosa');	
						
						var urlAnexo = "<?php echo site_url(); ?>upload/A<?php echo $this->uri->segment(3); ?>.pdf";
						$('#VIA').attr('href', urlAnexo);		
						$('#VIA').attr('target', '_blank');							
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
						msg = "A ocurridoff un error ";
						console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
			});//fin ajax		
		//verifica si existe el archivo A+numero de orden FIN
		
		//abre el archivo A+nombre de la solicitud INICIO
		$('#VIA').click(function(){
			$.ajax({//inicio ajax
				type:"POST",
				url :"<?php echo site_url(); ?>etapa/existeArchivo",
				dataType:"html",
				//timeout : 10000,
				data:{
						nombre_fichero:"A<?php echo $this->uri->segment(3); ?>.pdf"
				},
				success: function(dato){
					if (dato == "no_existe") {
						soloAlertaPersonalizada('Advertencia','Este Documento no se ha subido al sistema.');
					}
					//console.log(dato.comuna);
				},
				error: function(xhr, ajaxOptions, thrownError) {
						msg = "A ocurridoff un error ";
						console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
			});//fin ajax			
		});
		//abre el archivo A+nombre de la solicitud FIN
		//::::::::BOTON VISUALIZA ANEXO FIN
		
	
		//::::::::BOTON VISUALIZA VISUALIZA BONO ZONA INICIO
		//verifica si existe el archivo B+numero de orden INICIO
			$.ajax({//inicio ajax
				type:"POST",
				url :"<?php echo site_url(); ?>etapa/existeArchivo",
				dataType:"html",
				data:{
						nombre_fichero:"B<?php echo $this->uri->segment(3); ?>.pdf"
				},
				success: function(dato){
					if (dato == "no_existe") {
						$('#VBZ').attr('href', 'javascript:void(0)');	
					}
					if (dato == "si_existe") {
						//cambia color
						$('#VBZ').removeClass('btn-info');
						$('#VBZ').addClass('subidaExitosa');	
						
						var urlAnexo = "<?php echo site_url(); ?>upload/B<?php echo $this->uri->segment(3); ?>.pdf";
						$('#VBZ').attr('href', urlAnexo);		
						$('#VBZ').attr('target', '_blank');							
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
						msg = "A ocurridoff un error ";
						console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
			});//fin ajax		
		//verifica si existe el archivo B+numero de orden FIN	
		//lo abre si existe si no sale una alerta	
		$('#VBZ').click(function(){
			$.ajax({//inicio ajax
				type:"POST",
				url :"<?php echo site_url(); ?>etapa/existeArchivo",
				dataType:"html",
				//timeout : 10000,
				data:{
						nombre_fichero:"B<?php echo $this->uri->segment(3); ?>.pdf"
				},
				success: function(dato){
					if (dato == "no_existe") {
						soloAlertaPersonalizada('Advertencia','Este Documento no se ha subido al sistema.');
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
						msg = "A ocurridoff un error ";
						console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
			});//fin ajax			
		});		
		//::::::::BOTON VISUALIZA VISUALIZA BONO ZONA FIN
		

		//::::::::BOTON VISUALIZA CARTA AVISO INICIO
		//verifica si existe el archivo C+numero de orden INICIO
		$.ajax({//inicio ajax
			type:"POST",
			url :"<?php echo site_url(); ?>etapa/existeArchivo",
			dataType:"html",
			data:{
					nombre_fichero:"C<?php echo $this->uri->segment(3); ?>.pdf"
			},
			success: function(dato){
				if (dato == "no_existe") {
					$('#VIC').attr('href', 'javascript:void(0)');	
				}
				if (dato == "si_existe") {
					//cambia color
					$('#VIC').removeClass('btn-info');
					$('#VIC').addClass('subidaExitosa');	
										
					var urlAnexo = "<?php echo site_url(); ?>upload/C<?php echo $this->uri->segment(3); ?>.pdf";
					$('#VIC').attr('href', urlAnexo);		
					$('#VIC').attr('target', '_blank');							
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
					msg = "A ocurridoff un error ";
					console.log(msg+" "+xhr.status + " " + xhr.statusText);
				}
		});//fin ajax					
		//verifica si existe el archivo C+numero de orden FIN
		//lo abre si existe si no sale una alerta	
		$('#VIC').click(function(){
			$.ajax({//inicio ajax
				type:"POST",
				url :"<?php echo site_url(); ?>etapa/existeArchivo",
				dataType:"html",
				//timeout : 10000,
				data:{
						nombre_fichero:"C<?php echo $this->uri->segment(3); ?>.pdf"
				},
				success: function(dato){
					if (dato == "no_existe") {
						soloAlertaPersonalizada('Advertencia','Este Documento no se ha subido al sistema.');
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
						msg = "A ocurridoff un error ";
						console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
			});//fin ajax			
		});		
		//::::::::BOTON VISUALIZA CARTA AVISO FIN
				
				
		//::::::::BOTON VISUALIZA TRASLADO  VOLUNTARIO INICIO
		//verifica si existe el archivo V+numero de orden INICIO
		$.ajax({//inicio ajax
			type:"POST",
			url :"<?php echo site_url(); ?>etapa/existeArchivo",
			dataType:"html",
			data:{
					nombre_fichero:"V<?php echo $this->uri->segment(3); ?>.pdf"
			},
			success: function(dato){
				if (dato == "no_existe") {
					$('#VVO').attr('href', 'javascript:void(0)');	
				}
				if (dato == "si_existe") {
					//cambia color
					$('#VVO').removeClass('btn-info');
					$('#VVO').addClass('subidaExitosa');	
										
					var urlAnexo = "<?php echo site_url(); ?>upload/V<?php echo $this->uri->segment(3); ?>.pdf";
					$('#VVO').attr('href', urlAnexo);		
					$('#VVO').attr('target', '_blank');							
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
					msg = "A ocurridoff un error ";
					console.log(msg+" "+xhr.status + " " + xhr.statusText);
				}
		});//fin ajax					
		//lo abre si existe si no sale una alerta	
		$('#VVO').click(function(){
			$.ajax({//inicio ajax
				type:"POST",
				url :"<?php echo site_url(); ?>etapa/existeArchivo",
				dataType:"html",
				//timeout : 10000,
				data:{
						nombre_fichero:"V<?php echo $this->uri->segment(3); ?>.pdf"
				},
				success: function(dato){
					if (dato == "no_existe") {
						soloAlertaPersonalizada('Advertencia','Este Documento no se ha subido al sistema.');
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
						msg = "A ocurridoff un error ";
						console.log(msg+" "+xhr.status + " " + xhr.statusText);
					}
			});//fin ajax			
		});		
		//::::::::BOTON VISUALIZA TRASLADO  VOLUNTARIO FIN
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://		
//::::::::::::::::::::::::::::::BOTONES ACCION Y FUNCIONES:::::::::::::::::::::::::::::::::::::::::::://		
//::::::::::::::::::::::::::::::::::::::FIN:::::::::::::::::::::::::::::::::::::::::::::::::::::::::://		
//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://		
//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::://		

		$('#mailRespons').click(function(){
			$('.close').css('font-size','40px');
			var modalTitle = '';
			$('.modal-title').html(modalTitle);
			//$('.modal-footer').html('');
			var htmlFormUpload = '';
			htmlFormUpload += '<form action="<?php echo base_url(); ?>etapa/mailResponsable" method="POST" style="font-size:15px;">';
			htmlFormUpload += '<p>Este mail sera enviado al encargado de gestionar la etapa '+$(".seleccionado .title").html()+'.</p>';
			htmlFormUpload += '<p>';
			htmlFormUpload += '<strong>Para : </strong> '+$('#sol_correo_log').html()+'';
			htmlFormUpload += '</p>';
			htmlFormUpload += '<div class="form-group">';
			htmlFormUpload += '<label for="sol_observ_jefe_dest">ASUNTO: Observacion respecto al requerimiento Nro <span style="color:#4CAE4C"> <?php echo $this->uri->segment(3); ?></span></label>';
			htmlFormUpload += '<textarea name="observParaRespons" id="observParaRespons" class="form-control required" rows="3" style="padding: 6px; height: 59px;font-size:1.12em;"></textarea>';
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

	jQuery(window).load(function(){
		$('.accion').css('display','inline-block');
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