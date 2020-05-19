	<link rel="stylesheet" href="<?php echo site_url(); ?>assets/lib/bootstrap-3-complilado/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo site_url(); ?>assets/lib/jquery-ui-1.10.4/css/smoothness/jquery-ui-1.10.4.custom.min.css">
	<link rel="stylesheet" href="<?php echo site_url(); ?>assets/template/tpl2-sb/css/tipsy.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo site_url(); ?>assets/template/tpl2-sb/css/tipsy-docs.css" type="text/css" />
	<link rel="stylesheet" href="<?php echo site_url(); ?>assets/template/tpl2-sb/css/custom.css" type="text/css" />

	<!-- 
	<link rel="stylesheet" href="<?php echo site_url(); ?>assets/template/tpl2-sb/css/custom_traslado.css" type="text/css" />
	 -->
	<div class="container-full contenedor">
	<!-- formulario top filtro inicio -->
		<div id="blk_filtro" class="row fila">
				<div class="col-md-3 fechas" style="padding-left: 20px;">
					<label for="idSol">Nro de Solicitud :</label>
					<input name="idSol" id="idSol" type="text" class="form-control"/>
				</div>			
				<div class="col-md-1 fechas borde-right" style="padding-left: 5px;">
					<button name="btnRut" id="btnRut" type="button" class="btn btn-success">
						<span class="glyphicon glyphicon-search"></span>
					</button>
				</div>			
				<div class="col-md-6 blkTxt" style="border-left: 1px solid rgb(192, 192, 192);">
					<label for="prioridad_tick_modal">Cargo :</label>
					<select name="rut" id="cargo" class="form-control">
						<option value="todos">--Todos--</option>
						<?php foreach ($filtroCargos as $v => $i) : ?>
						<option value="<?php echo $i->NROCARGO; ?>"><?php echo $i->NROCARGO. " - "  .$i->DESCRIPCION; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-6 blkTxt">
					<label for="prioridad_tick_modal">Unidad :</label>
					<select name="unidad" id="unidad" class="form-control">
						<option value="todos">--Todos--</option>
						<?php foreach ($filtroUnidad as $v => $i) : ?>
						<option value="<?php echo $i->CUI_UNIDAD ?>"><?php echo $i->CUI_UNIDAD . ' - ' .$i->DESC_CUI . ' - ' .$i->CCOSTO; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-md-6 blkTxt">
				</div>
				<div class="col-md-2">
					<button name="filtrarTriple" id="filtrarTriple" type="button" class="btn btn-success" style="margin-left:-15px;">
						<span class="glyphicon glyphicon-search"></span>
					</button>
				</div>   
		</div>
	<!-- formulario top filtro fin -->
	</div><!--/row-->
	<div class="row">
		<div class="col-md-10" style="padding-left: 30px;">
			<h3>SOLICITUDES PENDIENTES <?php //echo $_SESSION["rut"]; ?></h3>
		</div>
		<div class="col-md-1" style="padding-left: 30px;">
		</div>    
		<div class="col-md-1" style="margin-top:10px; padding-left:8px;">
			<button name="verdes" id="verdes" type="button" class="btn btn-success btnFiltro" style="padding-left:7px; padding-right:7px;height: 32px;">
				<span class="glyphicon glyphicon-search"></span>
			</button>
		</div>  
		<div class="col-md-3" style="margin-top:10px; padding-left:8px;">
			<span class="subFiltro">Registros verdes : </span><span id="cantVerdes"></span>
		</div>  
		<div class="col-md-1" style="margin-top:10px; padding-left:8px;">
			<button name="rojos" id="rojos" type="button" class="btn btn-danger btnFiltro" style="padding-left:7px; padding-right:7px;height: 32px;">
				<span class="glyphicon glyphicon-search"></span>
			</button>
		</div>          
		<div class="col-md-3" style="margin-top:10px; padding-left:8px;">
			<span class="subFiltro">Registros rojos : </span><span id="cantRojos"></span>
		</div>    
		<div class="col-md-1" style="margin-top:10px; padding-left:8px;">
			<button name="mostrarTodos" id="mostrarTodos" type="button" class="btn btn-info btnFiltro" style="padding-left:7px; padding-right:7px;height: 32px;">
			<span class="glyphicon glyphicon-search"></span>
			</button>
		</div>          
		<div class="col-md-3" style="margin-top:10px; padding-left:8px;">
			<span class="subFiltro">Todos los registros: </span><span id="cantFilas"></span>
		</div>                        
	</div><!--/row-->
	<!-- ############LITADO DE TICKETS############### -->
	<div class="row grillaSmall">
		<div class="col-md-24">
			<table class="responsive" border="1">
				<thead>
	        		<tr class="trhead">
						<th class="text-center">Id</th>
						<th class="text-center">Tipo Sol.</th>
						<th class="text-center">Creacion</th>
						<th class="text-center">Empr</th>
						<th class="text-center">Desc Empr</th>
						<th class="text-center">Cargo</th>
						<th class="text-center">Desc Cargo</th>
						<th class="text-center">CUI Unidad</th>
						<th class="text-center">Descr unidad</th>
						<th class="text-center">Posición</th>
						<th class="text-center">Zonal</th>
						<th class="text-center">Proceso</th>
						<th class="text-center">Seleccion</th>
						<th class="text-center">Jefe Unidad</th>
						<th class="text-center">Estado</th>
	        		</tr>
	      		</thead>
				<tbody>
<?php 
	$contador = 0; 
	$cantidadVerdes = 0;
	$cantidadFilas = count($filas);
?>				
<?php foreach ($filas as $item): ?>
					<tr class="trbody" data-id="<?php echo $item->WF_KEY; ?>" data-nrocargo="<?php echo $item->CARGO; ?>"  data-unidad="<?php echo $item->CUI_UNIDAD; ?>">
						<td class="text-center"><?php echo $item->WF_KEY; ?></td>
						<td class="text-center"><?php echo $item->TIPO_SOLICITUD; ?></td>
						<td class="text-center"><?php echo $item->CREACION; ?></td>
						<td class="text-center"><?php echo $item->EMPRESA; ?></td>
						<td class="text-left"><?php echo $item->DESC_EMPRESA; ?></td> 
						<td class="text-center"><?php echo $item->CARGO; ?></td> 
						<td class="text-left"><?php echo $item->DESC_CARGO; ?></td>
						<td class="text-center"><?php echo $item->CUI_UNIDAD; ?></td> 
						<td class="text-left"><?php echo $item->DESC_CUI; ?></td> 
						<td class="text-center"><?php echo $item->POSICION; ?></td> 
						<td class="text-left"><?php echo $item->ZONAL; ?></td> 
						<td class="text-left"><?php echo $item->PROCESO; ?></td> 
						<td class="text-left"><?php echo $item->SELECCION; ?></td> 
						<td class="text-left"><?php echo $item->JEFE_UNIDAD; ?></td> 
						<!-- 
						<td class="text-left"><?php echo $item->ESTADO; ?></td> 
						-->
						<td class="text-left">


						<?php 
/*							indica quien es el actual responsable
							si es 888 el responsable es el o uno de los jefes del local de origen
							si es 999 el responsable es el o uno de los jefes del local de destino
							que lo saco con la query
							select emp_k_rutemplead,car_k_codigcargo 
							from mae_empleado 
							where sys_c_codestado = 1 and 
							car_k_codigcargo = 2 and 
							uni_k_codunidad = 8	
							*/						
							//echo $item->RESPONSABLE; 
							
						?>


						<?php 
							if (isset($_SESSION["rut"])) {
								if ($item->RESPONSABLE == "999") {
									//:::::inicio 999
									$CI =& get_instance();
									$CI->db = $this->load->database('bdseleccion', true);
									$strQueryValJef = "select pkg_seleccion.valida_rut_jefatura (".$item->EMPRESA.",".$_SESSION['rut'].",".$item->CCOSTO.") VALIDA_JEFATURA from dual";
									$strQueryValJef = $CI->db->query($strQueryValJef)->result();
									if ($strQueryValJef[0]->VALIDA_JEFATURA == "S") {
										$cantidadVerdes++;
						?>
									<a style="color:#4CAE4C" title="<?php echo $descEstLarga[$contador][0]->EST_DESC_LARGA; $contador++; ?>" class="hastip verde" href="<?php echo base_url()?>etapa_vac/pendiente/<?php echo $item->WF_KEY; ?>/<?php echo $item->ETAPA_ACTUAL; ?>/<?php echo $item->CORRELATIVO; ?>/<?php echo $item->ID_FLUJO; ?>" original-title="Hello World">
										<span class="glyphicon glyphicon-info-sign"></span>
										<?php echo $item->ESTADO; ?>
									</a>	
						<?php

									} else {
						?>
									<a style="color:#E65D11" title="<?php echo $descEstLarga[$contador][0]->EST_DESC_LARGA; $contador++; ?>" class="hastip rojo" href="<?php echo base_url()?>etapa_vac/pendiente/<?php echo $item->WF_KEY; ?>/<?php echo $item->ETAPA_ACTUAL; ?>/<?php echo $item->CORRELATIVO; ?>/<?php echo $item->ID_FLUJO; ?>" original-title="Hello World">
										<span class="glyphicon glyphicon-info-sign"></span>
										<?php echo $item->ESTADO; ?>
									</a>						
						<?php
									}	
									//:::::fin 999

								} elseif ($_SESSION["rut"] == $item->RESPONSABLE) {
									$cantidadVerdes++;
									//:::::inicio comparar rut logueado con responsable
						?>
									<a style="color:#4CAE4C" title="<?php echo $descEstLarga[$contador][0]->EST_DESC_LARGA; $contador++; ?>" class="hastip verde" href="<?php echo base_url()?>etapa_vac/pendiente/<?php echo $item->WF_KEY; ?>/<?php echo $item->ETAPA_ACTUAL; ?>/<?php echo $item->CORRELATIVO; ?>/<?php echo $item->ID_FLUJO; ?>" original-title="Hello World">
										<span class="glyphicon glyphicon-info-sign"></span>
										<?php echo $item->ESTADO; ?>
									</a>							
						<?php
								} else {
						?>
									<a style="color:#E65D11" title="<?php echo $descEstLarga[$contador][0]->EST_DESC_LARGA; $contador++; ?>" class="hastip rojo" href="<?php echo base_url()?>etapa_vac/pendiente/<?php echo $item->WF_KEY; ?>/<?php echo $item->ETAPA_ACTUAL; ?>/<?php echo $item->CORRELATIVO; ?>/<?php echo $item->ID_FLUJO; ?>" original-title="Hello World">
										<span class="glyphicon glyphicon-info-sign"></span>
										<?php echo $item->ESTADO; ?>
									</a>							
						<?php
									//:::::fin comparar rut logueado con responsable
								}

							}
						?>	
						
						</td> 
					</tr>
<?php endforeach;
$cantidadRojos = $cantidadFilas - $cantidadVerdes;
?>
				</tbody>
			</table>
			<!-- inicio ventana modal -->
			<div class="modal" id="myModal">
				<div class="modal-dialog">
			      <div class="modal-content">
			        <div class="modal-header">
			          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			          <h4 class="modal-title"></h4>
			        </div>
			        <div class="modal-body"></div>
			        <div class="modal-footer">
			          <a href="#" data-dismiss="modal" class="btn btn-xs btn-success">Cerrar</a>
			          <!-- 
			          <a href="#" class="btn btn-primary">Save changes</a>
			           -->
			        </div>
			      </div>
			    </div>
			</div>
			<!-- fin ventana modal -->	
		</div>
	</div>
	<div class="row tall30">
		<div class="col-md-12 col-md-offset-6">
			
		</div>
	</div>
	
	

    
    <script src="<?php echo site_url(); ?>assets/lib/jquery-ui-1.10.4/js/jquery-ui-1.10.4.min.js" ></script>
    <script src="<?php echo site_url(); ?>assets/template/tpl2-sb/js/daterangepicker.js" ></script>
    <script src="<?php echo site_url(); ?>assets/template/tpl2-sb/js/jquery.tipsy.js"></script>
    <script>
		$(document).ready(function(){
			
			var cantidadVerdes = <?php echo $cantidadVerdes; ?>;
			var cantidadRojos = <?php echo $cantidadRojos; ?>;
			var cantidadFilas = <?php echo $cantidadFilas; ?>;
			$( '#cantVerdes' ).html(cantidadVerdes);
			$( '#cantRojos' ).html(cantidadRojos);
			$( '#cantFilas' ).html(cantidadFilas);

			//mostrar los registros verdes
			$( '#verdes' ).click(function(event) {
				event.preventDefault;
				$('tr').find('.rojo').parent().parent().css('display','none');
				$('tr').find('.verde').parent().parent().css('display','table-row');
			});

			//mostrar los registros rojos
			$( '#rojos' ).click(function(event) {
				event.preventDefault;
				$('tr').find('.verde').parent().parent().css('display','none');
				$('tr').find('.rojo').parent().parent().css('display','table-row');
			});

			//mostrar todos los registros
			$( '#mostrarTodos' ).click(function(event) {
				event.preventDefault;
				$('tr.trbody').filter('[data-unidad],[data-nrocargo]').css('display','table-row');
			});
			
			$('.hastip').tipsy(
				{
					gravity: 'n',
					opacity: 1,
				}
			);

			$('#btnRut').click(function(event) {
				event.preventDefault();
				var nroSolicitud = $('#idSol').val();

				if (nroSolicitud != "") {
					$('tr.trbody').filter('[data-id="'+nroSolicitud+'"]').css('display','table-row');
					$('tr.trbody').filter(':not([data-id="'+nroSolicitud+'"])').css('display','none');	
				} else {
					$('tr.trbody').filter('[data-id]').css('display','table-row');
					$('tr.trbody').filter(':not([data-id])').css('display','none');	
				}
				
			});//fin #btnRut

			$( '#filtrarTriple' ).click(function(event){
				event.preventDefault()
				var cargo = $('#cargo').val();
				var unidad = $('#unidad').val();

				//una condicion
				if (cargo != "todos" && unidad == "todos") {
					$('tr.trbody').filter('[data-nrocargo="'+cargo+'"]').css('display','table-row');
					$('tr.trbody').filter(':not([data-nrocargo="'+cargo+'"])').css('display','none');	
				};		
				if (cargo == "todos" && unidad != "todos") {
					$('tr.trbody').filter('[data-unidad="'+unidad+'"]').css('display','table-row');
					$('tr.trbody').filter(':not([data-unidad="'+unidad+'"])').css('display','none');	
				};	

				//dos condiciones
				if (cargo != "todos" && unidad != "todos") {
					$('tr.trbody').filter('[data-unidad="'+unidad+'"],[data-nrocargo="'+cargo+'"]').css('display','table-row');
					$('tr.trbody').filter(':not([data-unidad="'+unidad+'"]),:not([data-nrocargo="'+cargo+'"])').css('display','none');	
				};

				//todos
				if (cargo == "todos" && unidad == "todos") {
					$('tr.trbody').filter('[data-nrocargo],[data-unidad]').css('display','table-row');
					$('tr.trbody').filter(':not([data-nrocargo]),:not([data-unidad])').css('display','none');	
				};						
			}); //fin #filtrarTriple

			
			$( '.btnFiltro' ).click(function(event){
				event.preventDefault()
				$('#idSol').val('');
				$('#cargo').prop('selectedIndex',0);
				$('#unidad').prop('selectedIndex',0);
			});

		});
    </script>