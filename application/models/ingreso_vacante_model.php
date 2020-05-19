<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ingreso_vacante_model extends CI_Model {
	
	protected $table;
	protected $id;
	
	/*
	 Constructor del modelo, aqui establecemos
	 que tabla utilizamos y cual es su llave primaria.
	 */
	function __construct() {
		parent::__construct();
		$this->db = $this->load->database('bdseleccion', true);
		$this->load->library('form_validation');
		//$this->table = 'ASIMILA_TMP';
		$this->table = 'ACTIVOS';
	}
	
	//rellena grilla de pendientes
	public function solVacantPend($rut)
	{
		//para llenar las solicitudes de ingreso de vacantes
		$strQuery = "
			SELECT       
				WF_KEY,
				CCOSTO,
				ID_FLUJO,
				ETAPA_ACTUAL,
				pkg_seleccion_vacantes.trae_rut_respon_etapa(WF_KEY) RESPONSABLE,
				(pkg_seleccion_vacantes.trae_correlativo (WF_KEY) -1) as CORRELATIVO,
				tipo_solicitud,
	            trunc(creation_date) creacion,
	            empresa,
	            SACA_DESC_EMPRESA(empresa) desc_empresa,
	            cargo,
	            desc_cargo,
	            cui_unidad,
	            pkg_seleccion_vacantes.trae_desc_cui(cui_unidad) desc_cui,
	            posicion,
	            TRAE_NOMBRE_COLABORADOR(user_zonal) zonal,
	            TRAE_NOMBRE_COLABORADOR(user_proceso) proceso,
	            TRAE_NOMBRE_COLABORADOR(user_seleccion) seleccion,
	            TRAE_NOMBRE_COLABORADOR(user_jefe_unidad) jefe_unidad,
				estado
	            FROM SEL_WF_SOLICITUD_vacante 
			WHERE 
				--estado not in ('Aprobado','Anulado','Rechazado'), 
				etapa_actual not in (77,88,99) and 
					(
		              user_zonal        = ".$rut." or 
		              user_proceso      = ".$rut." or 
		              user_seleccion    = ".$rut." or 
		              user_jefe_unidad  = ".$rut." or 
		              user_ggpp         = ".$rut." or
		              user_jefa_exp     = ".$rut." or
		              user_supervisor_exp = ".$rut." or
		              user_r1_adm       = ".$rut." or    
		              ".$rut." in (
		                            select correlativo_param 
		                            from SEL_WF_PARAMETROS 
		                            where tipo_param = 'ACCESO' and 
		                            desc_codigo_param = 'VAC'
		                      )      or
		                                 ".$rut." in (     select emp_k_rutemplead
		                                                     from mae_empleado
		                                                      where cia_k_Empresa   = EMPRESA and 
		                                                            uni_k_codunidad in (ccosto) and 
		                                                            sys_c_codestado = 1 and
		                                                            pkg_seleccion_vacantes.valida_rut_jefatura (EMPRESA,".$rut." ,ccosto) = 'S'
		                                          )
		            )
		            ORDER BY CREATION_DATE DESC
		";
		return $this->db->query($strQuery)->result();
	}
	
	public function solVacPendFiltroCargo($rut)
	{
		$strQuery = "
		SELECT
		distinct sst.cargo nroCargo,
		sst.desc_cargo as descripcion
		FROM SEL_WF_SOLICITUD_vacante sst
		WHERE
		--estado not in ('Aprobado','Anulado','Rechazado'),
		sst.etapa_actual not in (77,88,99) and
		(
		sst.created_by       = ".$rut." or
		sst.user_zonal       = ".$rut." or
		sst.user_proceso     = ".$rut." or
		sst.user_seleccion   = ".$rut." or
		sst.user_ggpp        = ".$rut." or
		sst.user_jefe_unidad = ".$rut." or
		".$rut." in (
		select correlativo_param
		from SEL_WF_PARAMETROS
		where tipo_param = 'ACCESO' and
		desc_codigo_param = 'VAC'
				)  or
				".$rut." in (select emp_k_rutemplead
				from mae_empleado
				where  cia_k_Empresa   = sst.EMPRESA and
				uni_k_codunidad = sst.ccosto and
				sys_c_codestado = 1 and
				pkg_seleccion.valida_rut_jefatura (sst.EMPRESA,".$rut.",sst.ccosto) = 'S'
						)
		)
		ORDER BY descripcion ASC
		";
		
		return $this->db->query($strQuery)->result();
	}//fin funcion solVacPendFiltroCargo
	
	public function solVacPendFiltroUnidad($rut)
	{
		$strQuery = "
		SELECT
			distinct sst.cui_unidad,
			pkg_seleccion_vacantes.trae_desc_cui(cui_unidad) desc_cui,
			ccosto
		FROM SEL_WF_SOLICITUD_vacante sst 
		WHERE
		--estado not in ('Aprobado','Anulado','Rechazado'),
		sst.etapa_actual not in (77,88,99) and
		(
		sst.created_by       = ".$rut." or
		sst.user_zonal       = ".$rut." or
		sst.user_proceso     = ".$rut." or
		sst.user_seleccion   = ".$rut." or
		sst.user_ggpp        = ".$rut." or
		sst.user_jefe_unidad = ".$rut." or
		".$rut." in (
		select correlativo_param
		from SEL_WF_PARAMETROS
		where tipo_param = 'ACCESO' and
		desc_codigo_param = 'VAC'
				)  or
				".$rut." in (select emp_k_rutemplead
				from mae_empleado
				where  cia_k_Empresa   = sst.EMPRESA and
				uni_k_codunidad = sst.ccosto and
				sys_c_codestado = 1 and
				pkg_seleccion.valida_rut_jefatura (sst.EMPRESA,".$rut.",sst.ccosto) = 'S'
						)
		)
		ORDER BY desc_cui ASC
		";
	
		return $this->db->query($strQuery)->result();
	}

	public function getdescEstLarga($id_flujo,$etapa_actual)
	{
		$strSQL = 	"select pkg_seleccion_vacantes.trae_desc_estado_etapa ('IVAC',".$id_flujo.",".$etapa_actual.") as EST_DESC_LARGA from dual";
		return $this->db->query($strSQL)->result();
	}	
	
	public function put_graba_log_correo($dataLogMail)
	{
	
		/*
			$ociRRHH = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.200.190)(PORT=1529))(CONNECT_DATA=(SID=DESArrhh)))";
			$conn = oci_connect("RRHH","DRRHH",$ociRRHH,'AL32UTF8');
	
			if (!$conn) {
			# code...
			echo "Conexion es invalida". var_dump(OCIError);
			die();
			}
			*/
	
		$sql = ' BEGIN ';
		$sql .= '	PKG_SELECCION.graba_log_correo ( ';
		$sql .= '		:var1_in,';
		$sql .= '		:var2_in,';
		$sql .= '		:var3_in,';
		$sql .= '		:var4_in,';
		$sql .= '		:var5_in,';
		$sql .= '		:var6_in';
		$sql .= '		);';
		$sql .= ' END;';
	
		$stmt = oci_parse($this->db->conn_id, $sql) or die("Error: Package 1");
	
		oci_bind_by_name($stmt,':var1_in',$var1_in,32) or die ('No puede enlazar a var1_in');
		oci_bind_by_name($stmt,':var2_in',$var2_in,32) or die ('No puede enlazar a var2_in');
		oci_bind_by_name($stmt,':var3_in',$var3_in,32) or die ('No puede enlazar a var3_in');
		oci_bind_by_name($stmt,':var4_in',$var4_in,32) or die ('No puede enlazar a var4_in');
		oci_bind_by_name($stmt,':var5_in',$var5_in,80) or die ('No puede enlazar a var5_in');
		oci_bind_by_name($stmt,':var6_in',$var6_in,400)  or die ('No puede enlazar a var6_in');
	
		//llena las variables
		$var1_in  = $dataLogMail['p_id_solicitud'];
		$var2_in  = $dataLogMail['p_etapa_origen'];
		$var3_in  = $dataLogMail['p_etapa_destino'];
		$var4_in  = $dataLogMail['p_correo_desde'];
		$var5_in  = $dataLogMail['p_correo_hasta'];
		$var6_in  = $dataLogMail['p_texto'];
	
		oci_execute($stmt);
		oci_close($this->db->conn_id);
	}//fin funcion put_graba_log_correo	
	
	/*:::::::::::::::::::::::::::::::::::::::::::*/
	/*::::::::::::::::HISTORICO::::::::::::::::::*/
	
	public function solTrasHistFiltroRut($rut)
	{
		$strQuery = "
				SELECT
					WF_KEY,
					CCOSTO,
					ID_FLUJO,
					ETAPA_ACTUAL,
					pkg_seleccion_vacantes.trae_rut_respon_etapa(WF_KEY) RESPONSABLE,
					(pkg_seleccion_vacantes.trae_correlativo (WF_KEY) -1) as CORRELATIVO,
					tipo_solicitud,
		            trunc(creation_date) creacion,
		            empresa,
		            SACA_DESC_EMPRESA(empresa) desc_empresa,
		            cargo,
		            desc_cargo,
		            cui_unidad,
		            pkg_seleccion_vacantes.trae_desc_cui(cui_unidad) desc_cui,
		            posicion,
		            TRAE_NOMBRE_COLABORADOR(user_zonal) zonal,
		            TRAE_NOMBRE_COLABORADOR(user_proceso) proceso,
		            TRAE_NOMBRE_COLABORADOR(user_seleccion) seleccion,
		            TRAE_NOMBRE_COLABORADOR(user_jefe_unidad) jefe_unidad,
					estado
				FROM SEL_WF_SOLICITUD_VACANTE 
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
				                desc_codigo_param = 'VAC'
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
	
	
	
		return $this->db->query($strQuery)->result();
	}//fin solTrasHistFiltroRut

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
				                desc_codigo_param = 'VAC'
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
		return $this->db->query($strQuery)->result();
	}//fin funcion solTrasPendFiltroLocDest	
	
	//rellena la grilla
	public function solTrasHist($rut)
	{
		$strQuery = "
			SELECT       
				WF_KEY,
				CCOSTO,
				ID_FLUJO,
				ETAPA_ACTUAL,
				pkg_seleccion_vacantes.trae_rut_respon_etapa(WF_KEY) RESPONSABLE,
				(pkg_seleccion_vacantes.trae_correlativo (WF_KEY) -1) as CORRELATIVO,
				tipo_solicitud,
	            trunc(creation_date) creacion,
	            empresa,
	            SACA_DESC_EMPRESA(empresa) desc_empresa,
	            cargo,
	            desc_cargo,
	            cui_unidad,
	            pkg_seleccion_vacantes.trae_desc_cui(cui_unidad) desc_cui,
	            posicion,
	            TRAE_NOMBRE_COLABORADOR(user_zonal) zonal,
	            TRAE_NOMBRE_COLABORADOR(user_proceso) proceso,
	            TRAE_NOMBRE_COLABORADOR(user_seleccion) seleccion,
	            TRAE_NOMBRE_COLABORADOR(user_jefe_unidad) jefe_unidad,
				estado
	            FROM SEL_WF_SOLICITUD_vacante 
			WHERE 
				--estado not in ('Aprobado','Anulado','Rechazado'), 
				etapa_actual in (77,88,99) and 
					(
		              user_zonal        = ".$rut." or 
		              user_proceso      = ".$rut." or 
		              user_seleccion    = ".$rut." or 
		              user_jefe_unidad  = ".$rut." or 
		              user_ggpp         = ".$rut." or
		              user_jefa_exp     = ".$rut." or
		              user_supervisor_exp = ".$rut." or
		              user_r1_adm       = ".$rut." or    
		              ".$rut." in (
		                            select correlativo_param 
		                            from SEL_WF_PARAMETROS 
		                            where tipo_param = 'ACCESO' and 
		                            desc_codigo_param = 'VAC'
		                      )      or
		                                 ".$rut." in (     select emp_k_rutemplead
		                                                     from mae_empleado
		                                                      where cia_k_Empresa   = EMPRESA and 
		                                                            uni_k_codunidad in (ccosto) and 
		                                                            sys_c_codestado = 1 and
		                                                            pkg_seleccion_vacantes.valida_rut_jefatura (EMPRESA,".$rut." ,ccosto) = 'S'
		                                          )
		            )
		            ORDER BY CREATION_DATE DESC
		";
		return $this->db->query($strQuery)->result();
	}//fin funcion solTrasHist	
	
	public function solVacHistFiltroCargo($rut)
	{
		$strQuery = "
		SELECT
			distinct sst.cargo nroCargo,
			sst.desc_cargo as descripcion
		FROM SEL_WF_SOLICITUD_vacante sst
		WHERE
		--estado not in ('Aprobado','Anulado','Rechazado'),
		sst.etapa_actual in (77,88,99) and
		(
		sst.created_by       = ".$rut." or
		sst.user_zonal       = ".$rut." or
		sst.user_proceso     = ".$rut." or
		sst.user_seleccion   = ".$rut." or
		sst.user_ggpp        = ".$rut." or
		sst.user_jefe_unidad = ".$rut." or
		".$rut." in (
		select correlativo_param
		from SEL_WF_PARAMETROS
		where tipo_param = 'ACCESO' and
		desc_codigo_param = 'VAC'
				)  or
				".$rut." in (select emp_k_rutemplead
				from mae_empleado
				where  cia_k_Empresa   = sst.EMPRESA and
				uni_k_codunidad = sst.ccosto and
				sys_c_codestado = 1 and
				pkg_seleccion.valida_rut_jefatura (sst.EMPRESA,".$rut.",sst.ccosto) = 'S'
						)
		)
		ORDER BY descripcion ASC
		";
	
		return $this->db->query($strQuery)->result();
	}//fin funcion solVacPendFiltroCargo	

	public function solVacHistFiltroUnidad($rut)
	{
		$strQuery = "
		SELECT
			distinct sst.cui_unidad,
			pkg_seleccion_vacantes.trae_desc_cui(cui_unidad) desc_cui,
			ccosto
		FROM SEL_WF_SOLICITUD_vacante sst
		WHERE
		--estado not in ('Aprobado','Anulado','Rechazado'),
		sst.etapa_actual in (77,88,99) and
		(
		sst.created_by       = ".$rut." or
		sst.user_zonal       = ".$rut." or
		sst.user_proceso     = ".$rut." or
		sst.user_seleccion   = ".$rut." or
		sst.user_ggpp        = ".$rut." or
		sst.user_jefe_unidad = ".$rut." or
		".$rut." in (
		select correlativo_param
		from SEL_WF_PARAMETROS
		where tipo_param = 'ACCESO' and
		desc_codigo_param = 'VAC'
				)  or
				".$rut." in (select emp_k_rutemplead
				from mae_empleado
				where  cia_k_Empresa   = sst.EMPRESA and
				uni_k_codunidad = sst.ccosto and
				sys_c_codestado = 1 and
				pkg_seleccion.valida_rut_jefatura (sst.EMPRESA,".$rut.",sst.ccosto) = 'S'
						)
		)
		ORDER BY desc_cui ASC
		";
	
		return $this->db->query($strQuery)->result();
	}
	
}