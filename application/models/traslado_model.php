<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Traslado_model extends CI_Model {

	protected $id;
	private $bd_desarrollo;
	
	/*
		Constructor del modelo, aqui establecemos
		que tabla utilizamos y cual es su llave primaria.
	*/
	function __construct() {
		parent::__construct();
		//$this->bd_desarrollo = $this->load->database('bdseleccion', true);
		
		$this->bd_desarrollo = $this->load->database( 'bdseleccion', TRUE, TRUE );
		
		// Pass reference of database to the CI-instance
		$CI =& get_instance();
		$CI->bd_desarrollo =& $this->bd_desarrollo;		
		
		$this->load->library('form_validation');
	}
	

	
	function autocompletarSolicitudTrasladoOri($tipo,$empresa,$rut,$ccosto,$motivo, $flujo)
	{
		#SOLIC_PEND devuelve 0 si se encuentra en el HISTORICO o 1 si esta en PENDIENTES
		$strQuery = "SELECT pkg_seleccion.trae_traslados_pendientes(".$rut.") SOLIC_PEND FROM DUAL";
		$result = $this->bd_desarrollo->query($strQuery)->result();
		$result = $result[0]->SOLIC_PEND;	
		
		#parametro 1:EMPRESA,parametro 2:CARGO, de la tabla SEL_WF_SOLICITUD_TRASLADO
		//$tipo es si es 'ORI' o 'DEST'
		
		//0 si esta en historico/ 1 si esta en pendiente
		if ($result == 0) {
			$strQuery ="SELECT PKG_SELECCION.trae_informacion_general('".$tipo."',".$empresa.",".$rut.",".$ccosto.",'".$motivo."','".$flujo."') as INFOSOLICITUD FROM dual";
            
			$resultado = $this->bd_desarrollo->query($strQuery)->result();
			return $resultado;			
		}

		//si retorna esto es porque este colaborador tiene una solicitud de traslado pendiente
		return '{"existe":"si"}';
			//Array (stdClass Object ([LOCAL] => 806 [CANTIDAD] => 1184 ))
		//return array('INFOSOLICITUD' => , '[{"sol_desc_loc":"VITACURA 2","sol_nombre":"Jocelyn Nataly Guajardo Umaña","sol_unidad":"1001","sol_unidad_desc":"Locales Centro","sol_fech_inic_contr":"21-10-2013","sol_fech_term_contr":"31-12-2500","sol_situac_contr_cod_hidden":"3","sol_situac_contr":"Indefinido","sol_cargo_Dbnet":"88","sol_cargo_Dbnet_desc":"CONSEJERA DE CLIENTES","sol_trasl_apro12":"0","sol_trasl_pend12":"0","sol_cargo_jer":"","sol_cargo_jer_desc":"","sol_asist_recl_sel":"Violeta Del Carmen Varela Lienqueo","sol_asist_recl_sel_hidden":"17282950","sol_asist_proc_compe":"Maciel Alexandr Castillo Fuentealba","sol_asist_proc_compe_hidden":"15478095","sol_proveedor":"---","sol_proveedor_desc":"------","sol_dot_teo":"5","sol_dot_mae":"5","sol_tras_pend_egreso":"0","sol_tras_pend_ingreso":"0","sol_egre_trasl_act_orig":"1","sol_dot_fut":"4","sol_zonal_origen":"LORENA DEL CARMEN WERCHEZ SANTIBAÑEZ","sol_zonal_rut_origen_hidden":"11845366","sol_dot_licen":"0","sol_dot_permiso":"0"}]');;
	}	
	
	public function solTrasPend($rut)
	{
	/*
		$strQuery = "
			SELECT trae_nombre_colaborador(sst.CREATED_BY) as NOMBREBY, 
			trae_nombre_colaborador(sst.rut) AS COLABORADOR, 
			saca_desc_unidad(sst.EMPRESA,sst.CC_ORIGEN) as DESCLOCORIG, 
			saca_desc_unidad(sst.EMPRESA,sst.CC_DESTINO) as DESCLOCDEST, 
			(pkg_seleccion.trae_correlativo (sst.WF_KEY) -1) as CORRELATIVO,
			sst.EMPRESA EMPRESA,
			sst.FECHA_DBNET FECHA_DBNET,
			pkg_seleccion.trae_rut_respon_etapa(sst.WF_KEY) RESPONSABLE, sst.* 
			FROM SEL_WF_SOLICITUD_TRASLADO sst 
			WHERE 
			  --estado not in ('Aprobado','Anulado','Rechazado'), 
			  (etapa_actual not in (77,88,99) or fecha_dbnet is null ) and 
			(
			  created_by = " .$rut. " or
			  user_zonal_cc_origen = " .$rut. " or 
			  user_zonal_cc_destino = " .$rut. " or 
			  user_proceso_cc_destino = " .$rut. " or 
			  USER_GESTION = " .$rut. " or 
			  user_selec_cc_destino = " .$rut. " or 
			  user_supervisor_exp = " .$rut. " or 
			  " .$rut. " in (
			                select correlativo_param 
			                from SEL_WF_PARAMETROS 
			                where tipo_param = 'ACCESO' and 
			                desc_codigo_param = 'ADM'
			                )  or
                                 " .$rut. " in ( 	select emp_k_rutemplead
                             						from mae_empleado
                          							where cia_k_Empresa = EMPRESA and 
                                					uni_k_codunidad in (CC_ORIGEN,CC_DESTINO) and 
                                					sys_c_codestado = 1 and
                                					( 
  														pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. " ,CC_ORIGEN) = 'S' or
                                    					pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. " ,CC_DESTINO) = 'S'
													)
                        						)
			)
			ORDER BY CREATION_DATE DESC
			";		
		*/			
		//jagl cambios 20150702135401
		$strQuery = "
			SELECT trae_nombre_colaborador(sst.CREATED_BY) as NOMBREBY, 
			trae_nombre_colaborador(sst.rut) AS COLABORADOR, 
			saca_desc_unidad(sst.EMPRESA,sst.CC_ORIGEN) as DESCLOCORIG, 
			saca_desc_unidad(sst.EMPRESA,sst.CC_DESTINO) as DESCLOCDEST, 
			(pkg_seleccion.trae_correlativo (sst.WF_KEY) -1) as CORRELATIVO,
			sst.EMPRESA EMPRESA,
			sst.FECHA_DBNET FECHA_DBNET,
			pkg_seleccion.trae_rut_respon_etapa(sst.WF_KEY) RESPONSABLE, sst.* 
			FROM SEL_WF_SOLICITUD_TRASLADO sst 
			WHERE 
(
	                       (etapa_actual not in (77,88,99)) or
	                       (etapa_actual = 77 and fecha_dbnet is null)
	                       ) and
			(
			  created_by = " .$rut. " or
			  user_zonal_cc_origen = " .$rut. " or 
			  user_zonal_cc_destino = " .$rut. " or 
			  user_proceso_cc_destino = " .$rut. " or 
			  USER_GESTION = " .$rut. " or 
			  user_selec_cc_destino = " .$rut. " or 
			  user_supervisor_exp = " .$rut. " or 
			  " .$rut. " in (
			                select correlativo_param 
			                from SEL_WF_PARAMETROS 
			                where tipo_param = 'ACCESO' and 
			                desc_codigo_param = 'ADM'
			                )  or
                                 " .$rut. " in ( 	select emp_k_rutemplead
                             						from mae_empleado
                          							where cia_k_Empresa = EMPRESA and 
                                					uni_k_codunidad in (CC_ORIGEN,CC_DESTINO) and 
                                					sys_c_codestado = 1 and
                                					( 
  														pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. " ,CC_ORIGEN) = 'S' or
                                    					pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. " ,CC_DESTINO) = 'S'
													)
                        						)
			)
			ORDER BY CREATION_DATE DESC
			";	
		$resultado = $this->bd_desarrollo->query($strQuery)->result();
		return $resultado;		
	}
	
	public function solTrasHist($rut)
	{
		/*
		$strQuery = "
			SELECT trae_nombre_colaborador(sst.CREATED_BY) as NOMBREBY, 
			trae_nombre_colaborador(sst.rut) AS COLABORADOR, 
			saca_desc_unidad(sst.EMPRESA,sst.CC_ORIGEN) as DESCLOCORIG, 
			saca_desc_unidad(sst.EMPRESA,sst.CC_DESTINO) as DESCLOCDEST, 
			(pkg_seleccion.trae_correlativo (sst.WF_KEY) -1) as CORRELATIVO,
			sst.EMPRESA EMPRESA,
			sst.FECHA_DBNET FECHA_DBNET,
			pkg_seleccion.trae_rut_respon_etapa(sst.WF_KEY) RESPONSABLE, sst.* 
			FROM SEL_WF_SOLICITUD_TRASLADO sst 
			WHERE 
			  --estado not in ('Aprobado','Anulado','Rechazado'), 
			  etapa_actual in (77,88,99) and 
			  fecha_dbnet is not null and
			(
			  created_by = " .$rut. " or
			  user_zonal_cc_origen = " .$rut. " or 
			  user_zonal_cc_destino = " .$rut. " or 
			  user_proceso_cc_destino = " .$rut. " or 
			  USER_GESTION = " .$rut. " or 
			  user_selec_cc_destino = " .$rut. " or 
			  " .$rut. " in (
			                select correlativo_param 
			                from SEL_WF_PARAMETROS 
			                where tipo_param = 'ACCESO' and 
			                desc_codigo_param = 'ADM'
			                )  or
                                 " .$rut. " in ( 	select emp_k_rutemplead
                             						from mae_empleado
                          							where cia_k_Empresa = EMPRESA and 
                                					uni_k_codunidad in (CC_ORIGEN,CC_DESTINO) and 
                                					sys_c_codestado = 1 and
                                					( 
  														pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. " ,CC_ORIGEN) = 'S' or
                                    					pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. " ,CC_DESTINO) = 'S'
													)
                        						)
			)
			ORDER BY CREATION_DATE DESC
			";	
			*/
		//jagl cambios 20150702135401
		$strQuery = "
			SELECT trae_nombre_colaborador(sst.CREATED_BY) as NOMBREBY, 
			trae_nombre_colaborador(sst.rut) AS COLABORADOR, 
			saca_desc_unidad(sst.EMPRESA,sst.CC_ORIGEN) as DESCLOCORIG, 
			saca_desc_unidad(sst.EMPRESA,sst.CC_DESTINO) as DESCLOCDEST, 
			(pkg_seleccion.trae_correlativo (sst.WF_KEY) -1) as CORRELATIVO,
			sst.EMPRESA EMPRESA,
			sst.FECHA_DBNET FECHA_DBNET,
			pkg_seleccion.trae_rut_respon_etapa(sst.WF_KEY) RESPONSABLE, sst.* 
			FROM SEL_WF_SOLICITUD_TRASLADO sst 
			WHERE 
			  --estado not in ('Aprobado','Anulado','Rechazado'), 
(
              (etapa_actual in (88,99) ) or
              (etapa_actual = 77 and fecha_dbnet is not null)
              ) and

			(
			  created_by = " .$rut. " or
			  user_zonal_cc_origen = " .$rut. " or 
			  user_zonal_cc_destino = " .$rut. " or 
			  user_proceso_cc_destino = " .$rut. " or 
			  USER_GESTION = " .$rut. " or 
			  user_selec_cc_destino = " .$rut. " or 
			  " .$rut. " in (
			                select correlativo_param 
			                from SEL_WF_PARAMETROS 
			                where tipo_param = 'ACCESO' and 
			                desc_codigo_param = 'ADM'
			                )  or
                                 " .$rut. " in ( 	select emp_k_rutemplead
                             						from mae_empleado
                          							where cia_k_Empresa = EMPRESA and 
                                					uni_k_codunidad in (CC_ORIGEN,CC_DESTINO) and 
                                					sys_c_codestado = 1 and
                                					( 
  														pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. " ,CC_ORIGEN) = 'S' or
                                    					pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. " ,CC_DESTINO) = 'S'
													)
                        						)
			)
			ORDER BY CREATION_DATE DESC
			";								

		$resultado = $this->bd_desarrollo->query($strQuery)->result();
		return $resultado;		
	}//fin funcion solTrasHist

	public function solTrasPendFiltroRut($rut)
	{
		$strQuery = "
				SELECT 
					distinct sst.RUT,
					trae_nombre_colaborador(sst.rut) as COLABORADOR
				FROM SEL_WF_SOLICITUD_TRASLADO sst 
				WHERE 
				  --estado not in ('Aprobado','Anulado','Rechazado'), 
				  etapa_actual not in (77,88,99) and 
				(
				  created_by = " .$rut. " or
				  user_zonal_cc_origen = " .$rut. " or 
				  user_zonal_cc_destino = " .$rut. " or 
				  user_proceso_cc_destino = " .$rut. " or 
				  USER_GESTION = " .$rut. " or 
				  user_selec_cc_destino = " .$rut. " or 
				  " .$rut. " in (
				                select correlativo_param 
				                from SEL_WF_PARAMETROS 
				                where tipo_param = 'ACCESO' and 
				                desc_codigo_param = 'ADM'
				                )  or
	                                 " .$rut. " in (select emp_k_rutemplead
                                               from mae_empleado
                                               where  cia_k_Empresa = EMPRESA and 
                                                      uni_k_codunidad in (CC_ORIGEN,CC_DESTINO) and 
                                                      sys_c_codestado = 1 and
                                                        ( 
                                                          pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. ",CC_ORIGEN) = 'S' or
                                                          pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. ",CC_DESTINO) = 'S'
                                                        )
                                              )
				)
				ORDER BY COLABORADOR ASC
				";	

		$resultado = $this->bd_desarrollo->query($strQuery)->result();
		return $resultado;		
	}

	public function solTrasHistFiltroRut($rut)
	{
		$strQuery = "
				SELECT 
					distinct sst.RUT,
					trae_nombre_colaborador(sst.rut) as COLABORADOR
				FROM SEL_WF_SOLICITUD_TRASLADO sst 
				WHERE 
				  --estado not in ('Aprobado','Anulado','Rechazado'), 
				  etapa_actual in (77,88,99) and 
				(
				  created_by = " .$rut. " or
				  user_zonal_cc_origen = " .$rut. " or 
				  user_zonal_cc_destino = " .$rut. " or 
				  user_proceso_cc_destino = " .$rut. " or 
				  USER_GESTION = " .$rut. " or 
				  user_selec_cc_destino = " .$rut. " or 
				  " .$rut. " in (
				                select correlativo_param 
				                from SEL_WF_PARAMETROS 
				                where tipo_param = 'ACCESO' and 
				                desc_codigo_param = 'ADM'
				                )  or
	                                 " .$rut. " in (select emp_k_rutemplead
                                               from mae_empleado
                                               where  cia_k_Empresa = EMPRESA and 
                                                      uni_k_codunidad in (CC_ORIGEN,CC_DESTINO) and 
                                                      sys_c_codestado = 1 and
                                                        ( 
                                                          pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. ",CC_ORIGEN) = 'S' or
                                                          pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. ",CC_DESTINO) = 'S'
                                                        )
                                              )
				)
				ORDER BY COLABORADOR ASC
				";	

		$resultado = $this->bd_desarrollo->query($strQuery)->result();
		return $resultado;		
	}//fin solTrasHistFiltroRut
	
	public function solTrasPendFiltroLocOri($rut)
	{
		$strQuery ="
			SELECT
			DISTINCT sst.CC_ORIGEN,saca_desc_unidad(sst.EMPRESA,sst.CC_ORIGEN)  DESCLOCORIG
			FROM SEL_WF_SOLICITUD_TRASLADO sst	
				WHERE 
				  --estado not in ('Aprobado','Anulado','Rechazado'), 
				  etapa_actual not in (77,88,99) and 
				(
				  created_by = " .$rut. " or
				  user_zonal_cc_origen = " .$rut. " or 
				  user_zonal_cc_destino = " .$rut. " or 
				  user_proceso_cc_destino = " .$rut. " or 
				  USER_GESTION = " .$rut. " or 
				  user_selec_cc_destino = " .$rut. " or 
				  " .$rut. " in (
				                select correlativo_param 
				                from SEL_WF_PARAMETROS 
				                where tipo_param = 'ACCESO' and 
				                desc_codigo_param = 'ADM'
				                )  or
	                                 " .$rut. " in (select emp_k_rutemplead
                                               from mae_empleado
                                               where  cia_k_Empresa = EMPRESA and 
                                                      uni_k_codunidad in (CC_ORIGEN,CC_DESTINO) and 
                                                      sys_c_codestado = 1 and
                                                        ( 
                                                          pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. ",CC_ORIGEN) = 'S' or
                                                          pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. ",CC_DESTINO) = 'S'
                                                        )
                                              )
				)
				ORDER BY CC_ORIGEN ASC
		";

		$resultado = $this->bd_desarrollo->query($strQuery)->result();
		return $resultado;		
	}
	
	public function solTrasHistFiltroLocOri($rut)
	{
		$strQuery ="
			SELECT
			DISTINCT sst.CC_ORIGEN,saca_desc_unidad(sst.EMPRESA,sst.CC_ORIGEN)  DESCLOCORIG
			FROM SEL_WF_SOLICITUD_TRASLADO sst	
				WHERE 
				  --estado not in ('Aprobado','Anulado','Rechazado'), 
				  etapa_actual in (77,88,99) and 
				(
				  created_by = " .$rut. " or
				  user_zonal_cc_origen = " .$rut. " or 
				  user_zonal_cc_destino = " .$rut. " or 
				  user_proceso_cc_destino = " .$rut. " or 
				  USER_GESTION = " .$rut. " or 
				  user_selec_cc_destino = " .$rut. " or 
				  " .$rut. " not in (
				                select correlativo_param 
				                from SEL_WF_PARAMETROS 
				                where tipo_param = 'ACCESO' and 
				                desc_codigo_param = 'ADM'
				                )  or
	                                 " .$rut. " in (select emp_k_rutemplead
                                               from mae_empleado
                                               where  cia_k_Empresa = EMPRESA and 
                                                      uni_k_codunidad in (CC_ORIGEN,CC_DESTINO) and 
                                                      sys_c_codestado = 1 and
                                                        ( 
                                                          pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. ",CC_ORIGEN) = 'S' or
                                                          pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. ",CC_DESTINO) = 'S'
                                                        )
                                              )
				)
				ORDER BY CC_ORIGEN ASC
		";

		$resultado = $this->bd_desarrollo->query($strQuery)->result();
		return $resultado;		
		
	}//fin funcion solTrasHistFiltroLocOri

	public function solTrasPendFiltroLocDest($rut)
	{

		$strQuery = "
			SELECT DISTINCT sst.CC_DESTINO,saca_desc_unidad(sst.EMPRESA,sst.CC_DESTINO) as DESCLOCDEST 
			FROM SEL_WF_SOLICITUD_TRASLADO sst
				WHERE 
				  --estado not in ('Aprobado','Anulado','Rechazado'), 
				  etapa_actual not in (77,88,99) and 
				(
				  created_by = " .$rut. " or
				  user_zonal_cc_origen = " .$rut. " or 
				  user_zonal_cc_destino = " .$rut. " or 
				  user_proceso_cc_destino = " .$rut. " or 
				  USER_GESTION = " .$rut. " or 
				  user_selec_cc_destino = " .$rut. " or 
				  " .$rut. " in (
				                select correlativo_param 
				                from SEL_WF_PARAMETROS 
				                where tipo_param = 'ACCESO' and 
				                desc_codigo_param = 'ADM'
				                )  or
	                                 " .$rut. " in (select emp_k_rutemplead
                                               from mae_empleado
                                               where  cia_k_Empresa = EMPRESA and 
                                                      uni_k_codunidad in (CC_ORIGEN,CC_DESTINO) and 
                                                      sys_c_codestado = 1 and
                                                        ( 
                                                          pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. ",CC_ORIGEN) = 'S' or
                                                          pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. ",CC_DESTINO) = 'S'
                                                        )
                                              )
				)					
		";

		$resultado = $this->bd_desarrollo->query($strQuery)->result();
		return $resultado;		
	}//fin funcion solTrasPendFiltroLocDest

	public function solTrasHistFiltroLocDest($rut)
	{
		$strQuery = "
			SELECT DISTINCT sst.CC_DESTINO,saca_desc_unidad(sst.EMPRESA,sst.CC_DESTINO) as DESCLOCDEST 
			FROM SEL_WF_SOLICITUD_TRASLADO sst
				WHERE 
				  --estado not in ('Aprobado','Anulado','Rechazado'), 
				  etapa_actual in (77,88,99) and 
				(
				  created_by = " .$rut. " or
				  user_zonal_cc_origen = " .$rut. " or 
				  user_zonal_cc_destino = " .$rut. " or 
				  user_proceso_cc_destino = " .$rut. " or 
				  USER_GESTION = " .$rut. " or 
				  user_selec_cc_destino = " .$rut. " or 
				  " .$rut. " not in (
				                select correlativo_param 
				                from SEL_WF_PARAMETROS 
				                where tipo_param = 'ACCESO' and 
				                desc_codigo_param = 'ADM'
				                )  or
	                                 " .$rut. " in (select emp_k_rutemplead
                                               from mae_empleado
                                               where  cia_k_Empresa = EMPRESA and 
                                                      uni_k_codunidad in (CC_ORIGEN,CC_DESTINO) and 
                                                      sys_c_codestado = 1 and
                                                        ( 
                                                          pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. ",CC_ORIGEN) = 'S' or
                                                          pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. ",CC_DESTINO) = 'S'
                                                        )
                                              )
				)					
		";

		$resultado = $this->bd_desarrollo->query($strQuery)->result();
		return $resultado;		
	}//fin funcion solTrasHistFiltroLocDest
	
	public function getSolTrasPendID($id,$rut)
	{

		$strQuery = "
			SELECT trae_nombre_colaborador(sst.CREATED_BY) as NOMBREBY, 
			trae_nombre_colaborador(sst.rut) AS COLABORADOR, 
			saca_desc_unidad(sst.EMPRESA,sst.CC_ORIGEN) as DESCLOCORIG, 
			saca_desc_unidad(sst.EMPRESA,sst.CC_DESTINO) as DESCLOCDEST, 
			(pkg_seleccion.trae_correlativo (sst.WF_KEY) -1) as CORRELATIVO,
			sst.EMPRESA EMPRESA,
			pkg_seleccion.trae_rut_respon_etapa(sst.WF_KEY) RESPONSABLE, sst.* 
			FROM SEL_WF_SOLICITUD_TRASLADO sst 
			WHERE 
			WF_KEY = ".$id." AND
			  --estado not in ('Aprobado','Anulado','Rechazado'), 
			  etapa_actual not in (77,88,99) and 
			(
			  created_by = " .$rut. " or
			  user_zonal_cc_origen = " .$rut. " or 
			  user_zonal_cc_destino = " .$rut. " or 
			  user_proceso_cc_destino = " .$rut. " or 
			  USER_GESTION = " .$rut. " or 
			  user_selec_cc_destino = " .$rut. " or 
			  " .$rut. " in (
			                select correlativo_param 
			                from SEL_WF_PARAMETROS 
			                where tipo_param = 'ACCESO' and 
			                desc_codigo_param = 'ADM'
			                )  or
                                 " .$rut. " in ( 	select emp_k_rutemplead
                             						from mae_empleado
                          							where cia_k_Empresa = EMPRESA and 
                                					uni_k_codunidad in (CC_ORIGEN,CC_DESTINO) and 
                                					sys_c_codestado = 1 and
                                					( 
  														pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. " ,CC_ORIGEN) = 'S' or
                                    					pkg_seleccion.valida_rut_jefatura (EMPRESA," .$rut. " ,CC_DESTINO) = 'S'
													)
                        						)
			)
			ORDER BY CREATION_DATE DESC
			";			
		//return $this->bd_desarrollo->get_where('SEL_WF_SOLICITUD_TRASLADO', array('WF_KEY' => $id))->result();
		
		$resultado = $this->bd_desarrollo->query($strQuery)->result();
		return $resultado;		
	}

	public function getfiltrarTriple($rut,$loc_origen,$loc_destino, $rutlogueado)
	{
		$strQuery = $this->constSqlBusqueda($rut, $loc_origen, $loc_destino, $rutlogueado);

		$resultado = $this->bd_desarrollo->query($strQuery)->result();
		return $resultado;			
	}
	
	public function constSqlBusqueda($rut,$loc_origen,$loc_destino, $rutlogueado)
	{
		//donde almacenare el string para JSON en este caso
		$strQuery = '';
		
		$sqlArreglo = array();
		
		if ($rut != 'todos') {
			$sqlArreglo[] = ' rut = '.$rut;
		}
		if ($loc_origen != 'todos') {
			$sqlArreglo[] = ' CC_ORIGEN = '.$loc_origen;
		}
		if ($loc_destino != 'todos') {
			$sqlArreglo[] = ' CC_DESTINO = '.$loc_destino;
		}
		
		if(count($sqlArreglo) == 0) {

				$strQuery = "
					SELECT 
					trae_nombre_colaborador(sst.RUT) AS COLABORADOR,
					trae_nombre_colaborador(sst.CREATED_BY) as NOMBREBY,
					saca_desc_unidad(sst.EMPRESA,sst.CC_ORIGEN) as DESC_CC_ORIGEN, 
					saca_desc_unidad(sst.EMPRESA,sst.CC_DESTINO) as DESC_CC_DESTINO,
					pkg_seleccion.trae_rut_respon_etapa(sst.WF_KEY) RESPONSABLE, 
					PKG_SELECCION.trae_desc_estado_etapa ('TRAS',sst.ID_FLUJO,sst.ETAPA_ACTUAL) EST_DESC_LARGA,
					sst.* 
					FROM SEL_WF_SOLICITUD_TRASLADO sst	
					WHERE 
				  etapa_actual not in (77,88,99) and 
				(
				  created_by = " .$_SESSION['rut']. " or
				  user_zonal_cc_origen = " .$_SESSION['rut']. " or 
				  user_zonal_cc_destino = " .$_SESSION['rut']. " or 
				  user_proceso_cc_destino = " .$_SESSION['rut']. " or 
				  USER_GESTION = " .$_SESSION['rut']. " or 
				  user_selec_cc_destino = " .$_SESSION['rut']. " or 
				  " .$_SESSION['rut']. " in (
				                select correlativo_param 
				                from SEL_WF_PARAMETROS 
				                where tipo_param = 'ACCESO' and 
				                desc_codigo_param = 'ADM'
				                )  or
	                                 " .$_SESSION['rut']. " in (select emp_k_rutemplead
                                               from mae_empleado
                                               where  cia_k_Empresa = EMPRESA and 
                                                      uni_k_codunidad in (CC_ORIGEN,CC_DESTINO) and 
                                                      sys_c_codestado = 1 and
                                                        ( 
                                                          pkg_seleccion.valida_rut_jefatura (EMPRESA," .$_SESSION['rut']. ",CC_ORIGEN) = 'S' or
                                                          pkg_seleccion.valida_rut_jefatura (EMPRESA," .$_SESSION['rut']. ",CC_DESTINO) = 'S'
                                                        )
                                              )
				)								
				";
			return $strQuery;
		}
		//point to end of the array:::jagl:::$filas es un array que traigo con elementos desde codeIgniter
		end($sqlArreglo);
		//fetch key of the last element of the array. :::jagl:::$Obtiene el ultimo elemento del array
		$lastElementKey = key($sqlArreglo);
		
		$strQuery = '
				SELECT trae_nombre_colaborador(sst.rut) AS COLABORADOR,
				trae_nombre_colaborador(sst.CREATED_BY) as NOMBREBY,
				saca_desc_unidad(sst.EMPRESA,sst.CC_ORIGEN) as DESC_CC_ORIGEN, 
				saca_desc_unidad(sst.EMPRESA,sst.CC_DESTINO) as DESC_CC_DESTINO, 
				pkg_seleccion.trae_rut_respon_etapa(sst.WF_KEY) RESPONSABLE, 
				sst.* 
				FROM SEL_WF_SOLICITUD_TRASLADO sst';
		
		if (count($sqlArreglo) > 0) {
			# code...
			$strQuery .= ' WHERE 
							etapa_actual not in (77,88,99) AND
						';
		}
		//iterate the array
		foreach($sqlArreglo as $k => $item) :
		if(($k == $lastElementKey)) {
			//si es el ultimo elemento adicionara esta cadena y sandra del loop
			$strQuery .= $item;
			continue;
		}
		$strQuery .= $item . ' AND ';
		endforeach;
		return $strQuery;
	}//fin constSqlBusqueda
/*::::::::::::::::::: paginacion inicio:::::::::::::::::::::::*/
	public function get_filtrar_rango ($inicio = FALSE,$limite= FALSE)
	{
		$this->bd_desarrollo->order_by('WF_KEY','DESC');
		
		if ($inicio !== FALSE && $limite !== FALSE) {
			$this->bd_desarrollo->limit($limite, $inicio);
		}
		
		$consulta = $this->bd_desarrollo->get('SEL_WF_SOLICITUD_TRASLADO');
		
		$resultado = $consulta->result();
		return $resultado;		
		/*
		$sql = "select * FROM SEL_WF_SOLICITUD_TRASLADO";
		$resultado = $this->bd_desarrollo->query($sql);
		return $resultado->result();
		*/
	
	}	
/*::::::::::::::::::: paginacion fin:::::::::::::::::::::::*/
	
	//obtener contenido para C. Costo
	public function opcCCostoOrigen($rutLogueado, $cod_empresa, $cod_flujo)
	{
		/*
		$strSQL = "
			Select
			DISTINCT jer_cc CODUNIDAD,
			saca_desc_unidad (jer_compania,jer_cc) NOMBUNIDAD,
			jer_compania EMPRESA
			From jer_cui_dependencia dep,jer_unidad uni
			Where (	cui_sup like
						(
							Select fn_nivel_cui(emp.jer_unidad)|| '%'
							From jer_empleado emp, jer_unidad uni
							Where (jer_rut = ".$rutLogueado." )
							And emp.jer_unidad = uni.jer_unidad
						)
						Or
						(".$rutLogueado." = (	
												Select responsable1_param
												From sel_wf_parametros
												Where tipo_param   = 'RESPON' and
												codigo_param = 'JEXP'
											)
											And pkg_seleccion.valida_total_exp_dermo_local(".$cod_flujo.",".$cod_empresa.",jer_cc) > 0
						)
													
	                    or
	                    ( ".$rutLogueado." =  (
	                              select responsable1_param 
	                                 from sel_wf_parametros
	                              where tipo_param   = 'RESPON' and 
	                               codigo_param = 'JEXM'
	                               )  
	                       and
	                              pkg_seleccion.valida_total_exp_dermo_local(".$cod_flujo.",".$cod_empresa.",jer_cc) > 0
	                    )					
													
													
					)
			And uni.jer_tipo_unidad = 1
			And uni.jer_compania = ".$cod_empresa."
			And dep.cui_dep = uni.jer_unidad
			Order By 3,1
		";
		*/

		$strSQL = "
			Select
			DISTINCT jer_cc CODUNIDAD,
			saca_desc_unidad (jer_compania,jer_cc) NOMBUNIDAD,
			jer_compania EMPRESA
			From jer_cui_dependencia dep,jer_unidad uni
			Where (	cui_sup like
						(
							Select fn_nivel_cui(emp.jer_unidad)|| '%'
							From jer_empleado emp, jer_unidad uni
							Where (jer_rut = ".$rutLogueado." )
							And emp.jer_unidad = uni.jer_unidad
						)
						Or
						(".$rutLogueado." in (	
												Select responsable1_param
												From sel_wf_parametros
												Where tipo_param   = 'RESPON' and
												codigo_param in ( 'JEXM','JEXP','JEXS','JEX1','JEX2','JEX3','JEX4'
											)
						)
											And pkg_seleccion.valida_total_exp_dermo_local(".$cod_flujo.",".$cod_empresa.",jer_cc, ".$rutLogueado.") > 0
						)					
					)
			And uni.jer_tipo_unidad = 1
			And uni.jer_compania = ".$cod_empresa."
			And uni.jer_feceli is null
			And dep.cui_dep = uni.jer_unidad
			Order By 3,1
		";
        
		return $this->bd_desarrollo->query($strSQL)->result();
	}
	
	//obtener contenido para sol_motivo
	public function opcSolMot()
	{
		$strSQL = "
			select CODIGO_PARAM As COD, DESC_CODIGO_PARAM As DESCR 
			from SEL_WF_parametros where tipo_param = 'MOTIVO' 
			order by correlativo_param			
		";
		$resultado = $this->bd_desarrollo->query($strSQL)->result();
		return $resultado;		
	}
	
	//obtener el listado de rut perteneciente a ese centro de costo de esa empresa
	public function getrutSolTras($empresa,$ccosto,$idflujo, $rut_logueado)
	{
		//por ahora lo unico que ocupare sera el campo EMP_K_RUTEMPLEAD para llenar los rut en solicitud traslado

		/*
		$strSQL = "
			SELECT EMP_K_RUTEMPLEAD,
			        substr(EMP_A_NOMBRE,1,(instr(EMP_A_NOMBRE||' ',' ')- 1))
			        ||' '||EMP_A_APELLPATER||' '||EMP_A_APELLMATER||' ('||
			        --SACA_DESC_CARGO(cia_k_empresa,car_k_codigcargo)||')' 
			        SACA_DESC_CARGO_ESPE (".$empresa.",trae_caracteristica(cia_k_empresa,emp_k_rutemplead,8))||')'
			         AS NOM_COMPLETO
			 FROM MAE_EMPLEADO 
			 WHERE CIA_K_EMPRESA   = ".$empresa." AND 
			       SYS_C_CODESTADO = 1 AND 
			       UNI_K_CODUNIDAD = ".$ccosto." AND 
			       pkg_seleccion.valida_muestra_exp_dermo(".$idflujo.",".$empresa.",car_k_codigcargo) = 'S' 
			 ORDER BY 2
		";	
		*/		
		$strSQL = "
		   SELECT mae.EMP_K_RUTEMPLEAD,
		                    substr(mae.EMP_A_NOMBRE,1,(instr(mae.EMP_A_NOMBRE||' ',' ')- 1))
		                    ||' '||mae.EMP_A_APELLPATER||' '||mae.EMP_A_APELLMATER||' ('||
		                    --SACA_DESC_CARGO(cia_k_empresa,car_k_codigcargo)||')' 
							SACA_DESC_CARGO_ESPE (".$empresa.",trae_caracteristica(cia_k_empresa,emp_k_rutemplead,8))||')'
			         		AS NOM_COMPLETO
		                    --mae.emp_f_termicontr
		             FROM MAE_EMPLEADO mae
		             WHERE mae.CIA_K_EMPRESA   = $empresa AND 
		                   mae.SYS_C_CODESTADO = 1 AND 
		                   mae.UNI_K_CODUNIDAD = $ccosto AND 
		                   pkg_seleccion.valida_muestra_exp_dermo($idflujo,$empresa,mae.car_k_codigcargo,$rut_logueado) = 'S'               
		                    and
		                   NOT EXISTS (SELECT 'x' FROM mae_grcaract gr WHERE gr.cia_k_empresa IN ($empresa)
		                       AND gr.emp_k_rutemplead = mae.emp_k_rutemplead 
		                       AND gr.tip_k_tipocaract = 69)
		             ORDER BY 2
		";	




		$resultado = $this->bd_desarrollo->query($strSQL)->result();
		return $resultado;		
	}
	
	public function getcCostoDest($empresa,$ccosto)
	{
		//por ahora lo unico que ocupare sera el campo EMP_K_RUTEMPLEAD para llenar los rut en solicitud traslado

		$strSQL = "
			select 
				DISTINCT jer_cc CODUNIDAD,
				saca_desc_unidad (jer_compania,jer_cc) NOMBUNIDAD,
				jer_compania EMPRESA 
			from jer_cui_dependencia dep,jer_unidad uni 
			where uni.jer_tipo_unidad = 1
			and uni.jer_compania    = ".$empresa." 
			and uni.jer_cc         != ".$ccosto."
			and UNI.JER_FECELI  is null
			and dep.cui_dep         = uni.jer_unidad
			order by 3,1		
		";

		$resultado = $this->bd_desarrollo->query($strSQL)->result();
		return $resultado;
	}	
	
	public function getdescEstLarga($id_flujo,$etapa_actual)
	{
		$strSQL = 	"select PKG_SELECCION.trae_desc_estado_etapa ('TRAS',".$id_flujo.",".$etapa_actual.") as EST_DESC_LARGA from dual";

		$resultado = $this->bd_desarrollo->query($strSQL)->result();
		return $resultado;		
	}//fin funcion getdescEstLarga
	
	public function putinsertarTraslado($datosSolic)
	{
		$conn = $this->bd_desarrollo->conn_id;
		if (!$conn) {
			echo "Conexion es invalida". var_dump(OCIError);
			die();
		}
		$sql = ' BEGIN ';
		$sql .= '	PKG_SELECCION.INSERTAR_TRASLADO ( ';
		$sql .= '		:var1_in,';
		$sql .= '		:var2_in,';
		$sql .= '		:var3_in,';
		$sql .= '		:var4_in,';
		$sql .= '		:var5_in,';
		$sql .= '		:var6_in,';
		$sql .= '	 	:var7_in,';
		$sql .= '		:var8_in,';
		$sql .= '		:var9_in,';
		$sql .= '		:var10_in,';
		$sql .= '		:var11_in,';
		$sql .= '		:var12_in,';
		$sql .= '		:var13_in,';
		$sql .= '		:var14_in,';
		$sql .= '		:var15_in,';
		$sql .= '		:var16_in,';
		$sql .= '		:var17_in,';
		$sql .= '       :var18_in,';
		$sql .= '       :var19_in,';
		$sql .= '       :var20_in,';
		$sql .= '       :var21_in,';
		$sql .= '       :var22_in,';
		$sql .= '		:var23_in,';
		$sql .= '		:var24_in,';
		$sql .= '		:var25_in,';
		$sql .= '		:var26_in,';
		$sql .= '		:var27_in,';
		$sql .= '		:var28_in,';
		$sql .= '		:var29_in,';
		$sql .= '		:var30_in,';
		$sql .= '		:var31_in,';
		$sql .= '		:var32_in,';
		$sql .= '		:var33_in,';
		$sql .= '		:var34_in,';
		$sql .= '		:var35_in,';
		$sql .= '		:var36_in,';
		$sql .= '		:var37_in,';
		$sql .= '		:var38_in,';

		$sql .= '		:var39_in,';
		$sql .= '		:var40_in,';
		$sql .= '		:var41_in,';
		$sql .= '		:var42_in,';

		$sql .= '		:var43_in,';
		
		$sql .= '		:var44_in,';
		$sql .= '		:var45_in,';
		$sql .= '		:var46_in,';

		$sql .= '		:p_id_solicitud, ';
		$sql .= '		:p_respon_etapa, ';
		$sql .= '		:p_correo_respon, ';
		$sql .= '		:p_proxima_etapa, ';
		$sql .= '		:p_desc_etapa ';
		$sql .= '		);';
		$sql .= ' END;';
		
		$stmt = oci_parse($conn, $sql) or die("Error: Package 1");

		oci_bind_by_name($stmt,':var1_in',$var1_in,32) or die ('No puede enlazar a var1_in');
		oci_bind_by_name($stmt,':var2_in',$var2_in,32) or die ('No puede enlazar a var2_in');
		oci_bind_by_name($stmt,':var3_in',$var3_in,32) or die ('No puede enlazar a var3_in');
		oci_bind_by_name($stmt,':var4_in',$var4_in,32) or die ('No puede enlazar a var4_in');
		oci_bind_by_name($stmt,':var5_in',$var5_in,80) or die ('No puede enlazar a var5_in');
		oci_bind_by_name($stmt,':var6_in',$var6_in,32)  or die ('No puede enlazar a var6_in');
		oci_bind_by_name($stmt,':var7_in',$var7_in,32)  or die ('No puede enlazar a var7_in');
		oci_bind_by_name($stmt,':var8_in',$var8_in,32)  or die ('No puede enlazar a var8_in');
		oci_bind_by_name($stmt,':var9_in',$var9_in,32)  or die ('No puede enlazar a var9_in');
		oci_bind_by_name($stmt,':var10_in',$var10_in,32)  or die ('No puede enlazar a var10_in');
		oci_bind_by_name($stmt,':var11_in',$var11_in,32)  or die ('No puede enlazar a var11_in');
		oci_bind_by_name($stmt,':var12_in',$var12_in,32)  or die ('No puede enlazar a var12_in');
		oci_bind_by_name($stmt,':var13_in',$var13_in,32)  or die ('No puede enlazar a var13_in');
		oci_bind_by_name($stmt,':var14_in',$var14_in,32)  or die ('No puede enlazar a var14_in');
		oci_bind_by_name($stmt,':var15_in',$var15_in,32)  or die ('No puede enlazar a var15_in');
		oci_bind_by_name($stmt,':var16_in',$var16_in,32)  or die ('No puede enlazar a var16_in');
		oci_bind_by_name($stmt,':var17_in',$var17_in,32)  or die ('No puede enlazar a var17_in');
		oci_bind_by_name($stmt,':var18_in',$var18_in,32)  or die ('No puede enlazar a var18_in');
		oci_bind_by_name($stmt,':var19_in',$var19_in,32)  or die ('No puede enlazar a var19_in');
		oci_bind_by_name($stmt,':var20_in',$var20_in,32)  or die ('No puede enlazar a var20_in');
		oci_bind_by_name($stmt,':var21_in',$var21_in,32)  or die ('No puede enlazar a var21_in');
		oci_bind_by_name($stmt,':var22_in',$var22_in,32)  or die ('No puede enlazar a var22_in');
		oci_bind_by_name($stmt,':var23_in',$var23_in,100)  or die ('No puede enlazar a var23_in');
		oci_bind_by_name($stmt,':var24_in',$var24_in,32)  or die ('No puede enlazar a var24_in');
		oci_bind_by_name($stmt,':var25_in',$var25_in,32)  or die ('No puede enlazar a var25_in');
		oci_bind_by_name($stmt,':var26_in',$var26_in,32)  or die ('No puede enlazar a var26_in');
		oci_bind_by_name($stmt,':var27_in',$var27_in,32)  or die ('No puede enlazar a va_27_in');
		oci_bind_by_name($stmt,':var28_in',$var28_in,32)  or die ('No puede enlazar a var28_in');
		oci_bind_by_name($stmt,':var29_in',$var29_in,32)  or die ('No puede enlazar a var29_in');
		oci_bind_by_name($stmt,':var30_in',$var30_in,32)  or die ('No puede enlazar a var30_in');
		oci_bind_by_name($stmt,':var31_in',$var31_in,32)  or die ('No puede enlazar a var31_in');
		oci_bind_by_name($stmt,':var32_in',$var32_in,32)  or die ('No puede enlazar a var32_in');
		oci_bind_by_name($stmt,':var33_in',$var33_in,32)  or die ('No puede enlazar a var33_in');
		oci_bind_by_name($stmt,':var34_in',$var34_in,32)  or die ('No puede enlazar a var34_in ');
		oci_bind_by_name($stmt,':var35_in',$var35_in,32)  or die ('No puede enlazar a var35_in');
		oci_bind_by_name($stmt,':var36_in',$var36_in,32)  or die ('No puede enlazar a var36_in');
		oci_bind_by_name($stmt,':var37_in',$var37_in,32)  or die ('No puede enlazar a var37_in');
		oci_bind_by_name($stmt,':var38_in',$var38_in,32)  or die ('No puede enlazar a var38_in');

		oci_bind_by_name($stmt,':var39_in',$var39_in,32)  or die ('No puede enlazar a var39_in');
		oci_bind_by_name($stmt,':var40_in',$var40_in,32)  or die ('No puede enlazar a var40_in');
		oci_bind_by_name($stmt,':var41_in',$var41_in,32)  or die ('No puede enlazar a var41_in');
		oci_bind_by_name($stmt,':var42_in',$var42_in,32)  or die ('No puede enlazar a var42_in');

		oci_bind_by_name($stmt,':var43_in',$var43_in,32)  or die ('No puede enlazar a var43_in');
		
		oci_bind_by_name($stmt,':var44_in',$var44_in,32)  or die ('No puede enlazar a var44_in');
		oci_bind_by_name($stmt,':var45_in',$var45_in,32)  or die ('No puede enlazar a var45_in');
		
		oci_bind_by_name($stmt,':var46_in',$var46_in,100)  or die ('No puede enlazar a var46_in');
		
		oci_bind_by_name($stmt,':p_id_solicitud',$p_id_solicitud,50)  or die ('No puede enlazar a p_id_solicitud');
		oci_bind_by_name($stmt,':p_respon_etapa',$p_respon_etapa,200)  or die ('No puede enlazar a p_respon_etapa');
		oci_bind_by_name($stmt,':p_correo_respon',$p_correo_respon,200)  or die ('No puede enlazar a p_correo_respon');
		oci_bind_by_name($stmt,':p_proxima_etapa',$p_proxima_etapa,200)  or die ('No puede enlazar a p_proxima_etapa');
		oci_bind_by_name($stmt,':p_desc_etapa', $p_desc_etapa,500)  or die ('No puede enlazar a p_desc_etapa');
		
		if ($datosSolic['p_proveedor'] == '---') {
			$datosSolic['p_proveedor'] = '';
		}
		if ($datosSolic['p_desc_proveedor'] == '------') {
			$datosSolic['p_desc_proveedor'] = '';
		}
		//llena las variables
		$var1_in  = $datosSolic['p_created']; //p_created  // el que se loguea(rafa dice que usaremos un rut fijo)
		$var2_in  = $datosSolic['p_colaborador']; //p_colaborador //a quien estan trasladando
		$var3_in  = $datosSolic['p_empresa'];//p_empresa
		$var4_in  = $datosSolic['p_cargo'];//p_cargo
		$var5_in  = $datosSolic['p_desc_cargo'];//p_desc_cargo
		$var6_in  = $datosSolic['p_cargo_especifico'];//p_cargo_especifico //cargo jerarquico cui
		$var7_in  = $datosSolic['p_comision_garantizada'];//p_comision_garantizada // no esta ahi por defecto enviarle al rafa un 0
		$var8_in  = $datosSolic['p_origen'];//p_origen //cc origen
		$var9_in  = null;//p_unidad_origen //unidad origenv(unidad)
		$var10_in = $datosSolic['p_destino'];//p_destino (id c costo destino)
		$var11_in = null;//p_unidad_destino (unidad al lado de c costo destino )
		$var12_in = $datosSolic['p_etapa'];//p_etapa //por defecto enviar 1
		$var13_in = $datosSolic['p_fecha_cambio'];//p_fecha_cambio
		$var14_in = $datosSolic['p_zonal_origen'];//p_zonal_origen strin json sol_zonal_rut_origen_hidden
		$var15_in = $datosSolic['p_zonal_destino'];//p_zonal_destino sol_zonal_dest_hidden
		$var16_in = $datosSolic['p_procesos_origen'];//p_procesos_origen sol_asist_proc_compe ////NO SE ESTA ENVIANDO (SE ESTA ENVIANDO )
		$var17_in = $datosSolic['p_procesos_destino'];//p_procesos_destino sol_asis_proc_comp_dest_hidden
		$var18_in = $datosSolic['p_selec_origen'];//p_selec_origen ::::Asistente Reclut. y Seleccion:(ESTE DEVERIA SER EL RUT PERO OCULTO)
		$var19_in = $datosSolic['p_selec_destino'];//p_selec_destino sol_asis_reclut_selec_dest_hidden
		$var20_in = $datosSolic['p_motivo'];//p_motivo ID sol_motivo
		$var21_in = $datosSolic['p_cui_origen'];//p_cui CUI
		$var22_in = $datosSolic['p_proveedor'];//p_proveedor ID sol_proveedor
		$var23_in = $datosSolic['p_observacion'];//p_observacion ::sol_observacion
		$var24_in = $datosSolic['p_dot_teor_origen'];//p_dot_teor_origen ID sol_dot_teo
		$var25_in = $datosSolic['p_dot_mae_origen'];//p_dot_mae_origen ID sol_dot_mae
		$var26_in = $datosSolic['p_pend_egre_origen'];//p_pend_egre_origen :::sol_tras_pend_egreso
		$var27_in = $datosSolic['p_pend_ingre_origen'];//p_pend_ingre_origen :::sol_tras_pend_ingreso
		$var28_in = $datosSolic['p_dot_teor_destino'];//p_dot_teor_destino :::ID sol_dot_teo_dest
		$var29_in = $datosSolic['p_dot_mae_destino'];//p_dot_mae_destino OK
		$var30_in = $datosSolic['p_pend_egre_destino'];//p_pend_egre_destino OK
		$var31_in = $datosSolic['p_pend_ingre_destino'];//p_pend_ingre_destino OK
		$var32_in = $datosSolic['p_id_flujo'];//p_id_flujo  strin json sol_id_flujo_hidden
		$var33_in = $datosSolic['p_inicio_contrato'];//p_inicio_contrato ID sol_fech_inic_contr
		$var34_in = $datosSolic['p_termino_contrato'];//p_termino_contrato ID sol_fech_term_contr
		$var35_in = $datosSolic['p_sit_contractual'];//p_sit_contractual :ID sol_situac_contr
		$var36_in = $datosSolic['p_trasl_aprob_12_meses'];//p_trasl_aprob_12_meses ID sol_trasl_apro12
		$var37_in = $datosSolic['p_trasl_pend_12_meses'];//p_trasl_pend_12_meses ID sol_trasl_pend12
		$var38_in = $datosSolic['p_desc_proveedor'];//p_desc_proveedor ID sol_proveedor_desc

		$var39_in = $datosSolic['p_rebaja_lic_origen'];//p_rebaja_lic_origen cantidad de personas que suman 30 dias de licencia los ultimos 45 dias
		$var40_in = $datosSolic['p_rebaja_per_origen'];//p_rebaja_per_origen dias con permisos (no es la cantidad de dias -hay que preguntar al rafa si es lo mismo que la licencia)
		$var41_in = $datosSolic['p_rebaja_lic_dest'];//p_rebaja_lic_dest cantidad de personas que suman 30 dias de licencia los ultimos 45 dias
		$var42_in = $datosSolic['p_rebaja_per_dest'];//p_rebaja_per_dest dias con permiso (no es la cantidad de dias -hay que preguntar al rafa si es lo mismo que la licencia)
		
		$var43_in = $datosSolic['p_cui_destino'];//p_rebaja_per_dest dias con permiso (no es la cantidad de dias -hay que preguntar al rafa si es lo mismo que la licencia)

		$var44_in = $datosSolic['p_tiene_comi'];//p_monto_comi indica si corresponde informar comision
		$var45_in = $datosSolic['p_monto_comi'];//p_monto_comi es el monto de la nueva comision
		
		$var46_in = $datosSolic['p_desc_prom_comi'];//p_desc_prom_comi descripcion de la comision
		oci_execute($stmt);
		
		//return $datosSolic['p_id_flujo'];

		$resultado = array($p_id_solicitud,$p_respon_etapa,$p_correo_respon,$p_proxima_etapa, $p_desc_etapa) ;
		oci_free_statement($stmt);
		return $resultado;
	}
	
	public function getFlujo($empresa,$rutLogueado)
	{
		$strQuery = "select pkg_seleccion.trae_id_flujo_traslado(pkg_seleccion.valida_r1_r4(".$empresa.",".$rutLogueado.")) FLUJO from dual";

		$resultado = $this->bd_desarrollo->query($strQuery)->result();
		return $resultado;		
	}
	
	//traslados aprovados para la persona que se va ha trasladar en los ultimos 12 meses
	public function getTrasAprobUlt12mes($rutColab)
	{
		$strSQL = "
        	SELECT wf_key,creation_date,cc_origen,saca_desc_unidad (empresa,cc_origen) desc_unidad_origen,
				cc_Destino,saca_desc_unidad (empresa,cc_destino)  desc_unidad_destino
			FROM SEL_WF_SOLICITUD_TRASLADO 
			WHERE rut = ".$rutColab." AND 
			trunc(creation_date) BETWEEN add_months(sysdate, -12) and sysdate AND 
			estado =  ('Aprobado')
				";

		$resultado = $this->bd_desarrollo->query($strSQL)->result();
		return $resultado;		
	}
	
	//traslados pendientes para la persona que se va ha trasladar en los ultimos 12 meses
	public function getTrasPendUlt12mes($rutColab)
	{
		$strSQL = "
		    SELECT wf_key,creation_date,cc_origen,saca_desc_unidad (empresa,cc_origen) desc_unidad_origen,
		           cc_Destino,saca_desc_unidad (empresa,cc_destino)  desc_unidad_destino,
		           etapa_Actual,pkg_seleccion.trae_estado_etapa('TRAS',id_flujo,etapa_actual) estado,
		           pkg_seleccion.trae_desc_estado_etapa('TRAS',id_flujo,etapa_actual) accion_estado
		    FROM SEL_WF_SOLICITUD_TRASLADO 
		    WHERE rut = ".$rutColab." AND 
		          trunc(creation_date) BETWEEN add_months(sysdate, -12) and sysdate AND 
		          estado NOT IN  ('Aprobado','Anulado','Rechazado')
				";
		
		$resultado = $this->bd_desarrollo->query($strSQL)->result();
		return $resultado;		
	}

	public function getDotacionMaestra($p_empresa,$ccosto,$p_cargo)
	{
		/*
		$strSQL = "
			SELECT initcap(emp_a_nombre)||' '||initcap(emp_a_apellpater)||' '||initcap(emp_a_apellmater) NOMBRE,
				EMP_F_INICICONTR,
				EMP_F_TERMICONTR,
				trae_desc_sys_code(782,sys_c_sitcontrac) SITUACION,
				SACA_DESC_CARGO(cia_k_empresa,car_k_codigcargo) CARGO       
				FROM MAE_EMPLEADO m
				WHERE cia_k_empresa = " . $p_empresa . " AND 
				uni_k_codunidad = " . $ccosto . " AND
				car_k_codigcargo = " . $p_cargo . " AND 
				sys_c_codestado   = 1 AND 
				pkg_seleccion.valida_rebaja_licencia(m.cia_k_empresa,m.emp_k_rutemplead) = 'N' AND
				pkg_seleccion.valida_rebaja_permiso(m.cia_k_empresa,m.emp_k_rutemplead) = 'N'
				";
		*/
		$strSQL = "
			SELECT emp_k_rutemplead, initcap(emp_a_nombre)||' '||initcap(emp_a_apellpater)||' '||initcap(emp_a_apellmater) NOMBRE,
				EMP_F_INICICONTR,
				EMP_F_TERMICONTR,
				trae_desc_sys_code(782,sys_c_sitcontrac) SITUACION,
				SACA_DESC_CARGO(cia_k_empresa,car_k_codigcargo) CARGO  
				FROM MAE_EMPLEADO m
				WHERE cia_k_empresa = ".$p_empresa." AND 
				uni_k_codunidad = ".$ccosto." AND
				sys_c_codestado   = 1 AND 
				pkg_seleccion.valida_rebaja_licencia(m.cia_k_empresa,m.emp_k_rutemplead) = 'N' AND
				pkg_seleccion.valida_rebaja_permiso(m.cia_k_empresa,m.emp_k_rutemplead) = 'N' and
        (
          (m.car_k_codigcargo 	in (select codigo_param from sel_wf_parametros where tipo_param = pkg_seleccion.trae_tipo_homologacion(".$p_cargo.")) and
            ".$p_cargo." 			in (select codigo_param from sel_wf_parametros where tipo_param = pkg_seleccion.trae_tipo_homologacion(".$p_cargo.")) 
          ) or
          m.car_k_codigcargo = ".$p_cargo.")	
		";
		
		$resultado =  $this->bd_desarrollo->query($strSQL)->result();
		return $resultado;		
	}
	
	public function getTrasPendEgre($p_empresa,$ccosto,$p_cargo)
	{
		$strSQL = "
				SELECT 
					WF_KEY,
					trunc(creation_date) CREATION_DATE,
					rut,TRAE_NOMBRE_RESPONSABLE(rut) NOMBRE,
					cc_origen,saca_desc_unidad (empresa,cc_origen) DESC_UNIDAD_ORIGEN,
					cc_Destino,saca_desc_unidad (empresa,cc_destino)  DESC_UNIDAD_DESTINO,
					etapa_Actual,pkg_seleccion.trae_estado_etapa('TRAS',id_flujo,etapa_actual) ESTADO,
					pkg_seleccion.trae_desc_estado_etapa('TRAS',id_flujo,etapa_actual) ACCION_ESTADO
				FROM SEL_WF_SOLICITUD_TRASLADO
				WHERE 
					empresa    = " . $p_empresa . " AND
					cc_origen  = " . $ccosto . " AND
					cargo      = " . $p_cargo . " AND
					etapa_actual NOT IN (77,88,99)
				";
		
		$resultado =  $this->bd_desarrollo->query($strSQL)->result();
		return $resultado;
	}
	
	public function getTrasPendIngre($p_empresa,$ccosto,$p_cargo)
	{
		$strSQL = "
				SELECT 
					WF_KEY,
					trunc(creation_date) CREATION_DATE,
					rut,TRAE_NOMBRE_RESPONSABLE(rut) NOMBRE,
					cc_origen,saca_desc_unidad (empresa,cc_origen) DESC_UNIDAD_ORIGEN,
					cc_Destino,saca_desc_unidad (empresa,cc_destino)  DESC_UNIDAD_DESTINO,
					etapa_Actual,pkg_seleccion.trae_estado_etapa('TRAS',id_flujo,etapa_actual) ESTADO,
					pkg_seleccion.trae_desc_estado_etapa('TRAS',id_flujo,etapa_actual) ACCION_ESTADO
				FROM SEL_WF_SOLICITUD_TRASLADO 
				WHERE empresa    = " . $p_empresa . " AND
					cc_destino = " . $ccosto . " AND 
					cargo      = " . $p_cargo . " AND 
					etapa_actual NOT IN (77,88,99)
				";
		
		$resultado =  $this->bd_desarrollo->query($strSQL)->result();
		return $resultado;		
	}
	
	public function getTrasLic($p_empresa,$ccosto,$p_cargo)
	{
		/*
		$strSQL = "
			select emp_k_rutemplead RUT, SACA_DESC_CARGO(".$p_empresa.",".$p_cargo.") DESC_CARGO ,TRAE_NOMBRE_RESPONSABLE(emp_k_rutemplead) NOMBRE, lme_f_inicio INICIO_LIC,lme_f_termino TERM_LIC,lme_n_diaslicen NRO_DIAS_LIC 
			from mae_lmedica 
			where cia_k_Empresa    = ".$p_empresa." and 
			TRUNC(SYSDATE)	between lme_f_inicio and lme_f_termino and 	
			( 
				( lme_f_inicio  between (TRUNC(SYSDATE) - 45) and TRUNC(SYSDATE) ) or 
				( lme_f_termino between (TRUNC(SYSDATE) - 45) and TRUNC(SYSDATE) ) or
				( (TRUNC(SYSDATE) - 45) between lme_f_inicio and lme_f_termino   ) or
				( TRUNC(SYSDATE) between lme_f_inicio and lme_f_termino   )
			) and
			pkg_seleccion.valida_rebaja_licencia(cia_k_empresa,emp_k_rutemplead) = 'S' and
			emp_k_rutemplead in (select m.emp_k_rutemplead from mae_empleado m
			where m.cia_k_empresa = ".$p_empresa." and uni_k_codunidad = ".$ccosto." and car_k_codigcargo = ".$p_cargo.")
			order by 2
				";
		*/
		$strSQL = "
            select emp_k_rutemplead RUT, pkg_seleccion.trae_cargo(cia_k_empresa,emp_k_rutemplead) DESC_CARGO,
            TRAE_NOMBRE_RESPONSABLE(emp_k_rutemplead) NOMBRE, lme_f_inicio INICIO_LIC,lme_f_termino TERM_LIC,lme_n_diaslicen NRO_DIAS_LIC 
            from mae_lmedica 
            where cia_k_Empresa    = ".$p_empresa." and 
            TRUNC(SYSDATE)    between lme_f_inicio and lme_f_termino and     
            ( 
                ( lme_f_inicio  between (TRUNC(SYSDATE) - 45) and TRUNC(SYSDATE) ) or 
                ( lme_f_termino between (TRUNC(SYSDATE) - 45) and TRUNC(SYSDATE) ) or
                ( (TRUNC(SYSDATE) - 45) between lme_f_inicio and lme_f_termino   ) or
                ( TRUNC(SYSDATE) between lme_f_inicio and lme_f_termino   )
            ) and
            pkg_seleccion.valida_rebaja_licencia(cia_k_empresa,emp_k_rutemplead) = 'S' and
            emp_k_rutemplead in
      (select m.emp_k_rutemplead from mae_empleado m
            where m.cia_k_empresa = ".$p_empresa." and uni_k_codunidad = $ccosto and
        (
          (m.car_k_codigcargo     in (select codigo_param from sel_wf_parametros where tipo_param = pkg_seleccion.trae_tipo_homologacion(".$p_cargo.")) and
            ".$p_cargo."            in (select codigo_param from sel_wf_parametros where tipo_param = pkg_seleccion.trae_tipo_homologacion(".$p_cargo.")) 
          ) or
          m.car_k_codigcargo = ".$p_cargo.") 
        )      
            order by 2		
		";
		
		$resultado =  $this->bd_desarrollo->query($strSQL)->result();
		return $resultado;	
	}
	
	public function getTrasPer($p_empresa,$ccosto,$p_cargo)
	{
		/*
		$strSQL = "
			select 	emp_k_rutemplead, 
					SACA_DESC_CARGO(".$p_empresa.",".$p_cargo.") DESC_CARGO, 
					TRAE_NOMBRE_RESPONSABLE(emp_k_rutemplead) NOMBRE,
					aus_f_inicio FECHA_INICIO_PERM, 
					aus_f_termino FECHA_TERMINO_PERM,
					((aus_f_termino - aus_f_inicio) + 1) NRO_DIAS_PERM
			from mae_ausentismo 
			where cia_k_Empresa    = ".$p_empresa." and 
			TRUNC(SYSDATE)	between aus_f_inicio and aus_f_termino and 
			( 
				( aus_f_inicio  between (TRUNC(SYSDATE) - 45) and TRUNC(SYSDATE) ) or 
				( aus_f_termino between (TRUNC(SYSDATE) - 45) and TRUNC(SYSDATE) ) or
				( (TRUNC(SYSDATE) - 45) between aus_f_inicio and aus_f_termino   ) or
				( TRUNC(SYSDATE) between aus_f_inicio and aus_f_termino   )
			) and
			pkg_seleccion.valida_rebaja_permiso(cia_k_empresa,emp_k_rutemplead) = 'S' and
			emp_k_rutemplead in (select m.emp_k_rutemplead from mae_empleado m
			where m.cia_k_empresa = ".$p_empresa." and uni_k_codunidad = ".$ccosto." and car_k_codigcargo = ".$p_cargo.")
			order by 2
				";
		*/
		$strSQL = "
            select 	emp_k_rutemplead, 
					pkg_seleccion.trae_cargo(cia_k_empresa,emp_k_rutemplead) DESC_CARGO,
                    TRAE_NOMBRE_RESPONSABLE(emp_k_rutemplead) NOMBRE,
                    aus_f_inicio FECHA_INICIO_PERM, 
                    aus_f_termino FECHA_TERMINO_PERM,
                    ((aus_f_termino - aus_f_inicio) + 1) NRO_DIAS_PERM
            from mae_ausentismo 
            where cia_k_Empresa    = ".$p_empresa." and 
            TRUNC(SYSDATE)    between aus_f_inicio and aus_f_termino and 
            ( 
                ( aus_f_inicio  between (TRUNC(SYSDATE) - 45) and TRUNC(SYSDATE) ) or 
                ( aus_f_termino between (TRUNC(SYSDATE) - 45) and TRUNC(SYSDATE) ) or
                ( (TRUNC(SYSDATE) - 45) between aus_f_inicio and aus_f_termino   ) or
                ( TRUNC(SYSDATE) between aus_f_inicio and aus_f_termino   )
            ) and
            pkg_seleccion.valida_rebaja_permiso(cia_k_empresa,emp_k_rutemplead) = 'S' and
            emp_k_rutemplead in (select m.emp_k_rutemplead from mae_empleado m
            where m.cia_k_empresa = ".$p_empresa." and uni_k_codunidad = ".$ccosto." and 
            (
          (m.car_k_codigcargo     in (select codigo_param from sel_wf_parametros where tipo_param = pkg_seleccion.trae_tipo_homologacion(".$p_cargo.")) and
            ".$p_cargo."            in (select codigo_param from sel_wf_parametros where tipo_param = pkg_seleccion.trae_tipo_homologacion(".$p_cargo.")) 
          ) or
          m.car_k_codigcargo = ".$p_cargo.") )
            order by 2
		";
		
		$resultado =  $this->bd_desarrollo->query($strSQL)->result();
		return $resultado;		
	}

	public function getCorreo($rut)
	{
		$srtQuery = "SELECT pkg_seleccion.trae_correo (".$rut.") CORREO FROM DUAL";
		
		$resultado =  $this->bd_desarrollo->query($srtQuery)->result();
		return $resultado;		
	}	
	public function getValidarMailFlujo( $nroCCosto, $codempresa, $flujo )
	{
		
		//$srtQuery = "SELECT pkg_seleccion.valida_existe_correos ('ORI',". $nroCCosto .",". $codempresa .") CORREO FROM DUAL";
		$srtQuery = "SELECT pkg_seleccion.valida_existe_correos ('ORI',". $nroCCosto .",". $codempresa .",". $flujo .") CORREO FROM DUAL";
		//$srtQuery = "SELECT pkg_seleccion.trae_correo (".$rut.") CORREO FROM DUAL";
		
		$resultado =  $this->bd_desarrollo->query($srtQuery)->result();
		return $resultado;		
	}	
	public function getValidarMailFlujoDestino( $nroCCosto, $codempresa, $flujo )
	{
		
		//$srtQuery = "SELECT pkg_seleccion.valida_existe_correos ('ORI',". $nroCCosto .",". $codempresa .") CORREO FROM DUAL";
		$srtQuery = "SELECT pkg_seleccion.valida_existe_correos ('DES',". $nroCCosto .",". $codempresa .",". $flujo .") CORREO FROM DUAL";
		//$srtQuery = "SELECT pkg_seleccion.trae_correo (".$rut.") CORREO FROM DUAL";
		
		$resultado =  $this->bd_desarrollo->query($srtQuery)->result();
		return $resultado;		
	}	
	
	public function getCtrlValidarCarenciaFecha($rut, $empresa, $cargoDbnet, $solicitud_fecha_cambio){
		//$srtQuery = "select trae_diferencia_traslado ( $rut, $empresa, $cargoDbnet, '$solicitud_fecha_cambio') diferenciaFechas from dual";
		$srtQuery = "select pkg_seleccion.trae_diferencia_traslado ($rut, $empresa, $cargoDbnet, '$solicitud_fecha_cambio') DIFERENCIAFECHAS from dual";
		//$srtQuery = "select restar ( 70, 2) DIFERENCIAFECHAS from dual";
		
		$resultado = $this->bd_desarrollo->query($srtQuery)->result();
		return $resultado;		
	}
	
	//obtener nombre teniendo solo el rut
	public function getNombre($rut)
	{
		$srtQuery = "SELECT trae_nombre_colaborador(".$rut.") NOMBRE FROM DUAL";
		$resultado = $this->bd_desarrollo->query($srtQuery)->result();
		return $resultado;		
	}
	
	//obtener nombre centro de costo
	public function get_ccosto_desc($empresa, $ccosto)
	{
		$srtQuery = "select saca_desc_unidad(".$empresa.",".$ccosto.") as CCOSTO_DESC from dual";
		
		$resultado = $this->bd_desarrollo->query($srtQuery)->result();
		return $resultado;
	}	
	
	public function __destruct() {
		$this->bd_desarrollo->close();
	}
}