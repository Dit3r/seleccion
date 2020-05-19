<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Etapa_vac_model extends CI_Model {

	public function __construct() 
	{
		parent::__construct();
		$this->db = $this->load->database('bdseleccion', true);
		$this->load->library('form_validation');
	}
	
	public function getOvalos($idSolicitud) 
	{
		$strQuery ="select Pkg_Seleccion_Vacantes.Genera_String_Detalle(".$idSolicitud.") as STRING_ETAPAS From DUAL";
		return $this->db->query($strQuery)->result();
	}
	
	public function getfillEtapaLog($solicitud,$nroEtapa,$correlativo)
	{
		$srtQuery = "Select Pkg_Seleccion_Vacantes.Trae_Info_Etapa(".$solicitud.", ".$nroEtapa.", ".$correlativo.") as STRING_ETAPA_LOG From DUAL";
		return $this->db->query($srtQuery)->result();
	}	
	
	public function getfillEtapaFrmVac($solicitud)
	{
		$srtQuery = "Select Pkg_Seleccion_Vacantes.Trae_Informacion_Solicitud(".$solicitud.") STRING_FRM From DUAL";
		return $this->db->query($srtQuery)->result();
	}	
	
	public function putInsertLog($datosLog)
	{
		/*
			$ociRRHH = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.200.190)(PORT=1529))(CONNECT_DATA=(SID=DESArrhh)))";
			$conn = oci_connect("RRHH","DRRHH",$ociRRHH, 'AL32UTF8');
	
			if (!$conn) {
			# code...
			echo "Conexion es invalida". var_dump(OCIError);
			die();
			}
			*/
	
		$sql = ' BEGIN ';
		$sql .= '	Pkg_Seleccion_Vacantes.Graba_Log_Vacante ( ';
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
		$sql .= '		:p_respon, ';
		$sql .= '		:p_correo_respon, ';
		$sql .= '		:p_proxima_etapa ';
		$sql .= '		);';
		$sql .= ' END;';
	
		$stmt = oci_parse($this->db->conn_id, $sql) or die("Error: Package 1");
	
		oci_bind_by_name($stmt,':var1_in',$var1_in,32) or die ('No puede enlazar a var1_in');
		oci_bind_by_name($stmt,':var2_in',$var2_in,32) or die ('No puede enlazar a var2_in');
		oci_bind_by_name($stmt,':var3_in',$var3_in,32) or die ('No puede enlazar a var3_in');
		oci_bind_by_name($stmt,':var4_in',$var4_in,32) or die ('No puede enlazar a var4_in');
		oci_bind_by_name($stmt,':var5_in',$var5_in,80) or die ('No puede enlazar a var5_in');
		oci_bind_by_name($stmt,':var6_in',$var6_in,32)  or die ('No puede enlazar a var6_in');
		oci_bind_by_name($stmt,':var7_in',$var7_in,32)  or die ('No puede enlazar a var7_in');
		oci_bind_by_name($stmt,':var8_in',$var8_in,32)  or die ('No puede enlazar a var8_in');
		oci_bind_by_name($stmt,':var9_in',$var9_in,32)  or die ('No puede enlazar a var9_in');
		oci_bind_by_name($stmt,':var10_in',$var10_in,500)  or die ('No puede enlazar a var10_in');
		oci_bind_by_name($stmt,':var11_in',$var11_in,32)  or die ('No puede enlazar a var11_in');
		oci_bind_by_name($stmt,':var12_in',$var12_in,32)  or die ('No puede enlazar a var12_in');
		oci_bind_by_name($stmt,':var13_in',$var13_in,32)  or die ('No puede enlazar a var13_in');
		oci_bind_by_name($stmt,':var14_in',$var14_in,32)  or die ('No puede enlazar a var14_in');
		oci_bind_by_name($stmt,':var15_in',$var15_in,32)  or die ('No puede enlazar a var15_in');
	
		oci_bind_by_name($stmt,':p_respon',$p_respon,200)  or die ('No puede enlazar a p_respon');
		oci_bind_by_name($stmt,':p_correo_respon',$p_correo_respon,190)  or die ('No puede enlazar a p_correo_respon');
		oci_bind_by_name($stmt,':p_proxima_etapa',$p_proxima_etapa,190)  or die ('No puede enlazar a p_correo_respon');
	
		//llena las variables
		$var1_in  = $datosLog['p_id_solicitud'];
		$var2_in  = $datosLog['p_correlativo'];
		$var3_in  = $datosLog['p_etapa'];
		$var4_in  = $datosLog['p_visualiza'];
		//$var5_in  = $datosLog['p_inicio'];
		$var5_in  = date("d-M-Y");
		/*
			$var6_in  = $datosLog['p_termino'];
			$var7_in  = $datosLog['p_responsable1'];
			$var8_in  = $datosLog['p_responsable2'];
		*/
		$var6_in  = null;
		$var7_in  = null;
		$var8_in  = null;
		$var9_in  = $datosLog['p_accion'];
		$var10_in = $datosLog['p_observacion'];
		$var11_in = $datosLog['p_id_flujo'];
		$var12_in = null;
		$var13_in = null;
		$var14_in = null;
		$var15_in = null;
	
		/*
			$var1_in  = 24029;
			$var2_in  = 0;
			$var3_in  = 3;//etapa en la donde estoy parado
			$var4_in  = null;
			$var5_in  = date("d-M-Y");
			$var6_in  = null;
			$var7_in  = null;
			$var8_in  = null;
			$var9_in  = 'AUT';//AUT
			$var10_in = 'MIobs' ;
			$var11_in = 200;
			$var12_in = 7;
			$var13_in = 6;
			$var14_in = 1;
			$var15_in = 1;
		*/
		oci_execute($stmt);
	
		if ($datosLog['p_tipo_local'] == '999') {
			//actualizar traslado con datos del qf que recibe a colaborador
			$sql = ' BEGIN ';
			$sql .= '	Pkg_Seleccion_Vacantes.Actualiza_Jefe_Local ( ';
			$sql .= '		:var1_in,';
			$sql .= '		:var2_in,';
			$sql .= '		:var3_in';
			$sql .= '		);';
			$sql .= ' END;';
				
			$stmt = oci_parse($this->db->conn_id, $sql) or die("Error: Package 1");
	
			oci_bind_by_name($stmt,':var1_in',$var1_in,32) or die ('No puede enlazar a var1_in');
			oci_bind_by_name($stmt,':var2_in',$var2_in,32) or die ('No puede enlazar a var2_in');
			oci_bind_by_name($stmt,':var3_in',$var3_in,32) or die ('No puede enlazar a var3_in');
				
			$var1_in  = $datosLog['p_id_solicitud'];
			$var2_in  = $datosLog['p_rut_jefe'];
			$correo_jefe= $this->getCorreo($datosLog['p_rut_jefe']);
			$var3_in =	$correo_jefe[0]->CORREO;
			oci_execute($stmt);
			//fin actualizar traslado con datos del qf que recibe a colaborador
		}
		
		
		
		
		//+++++inicio nueva procedimiento
		//update_solicitud_vacante (p_id_solicitud in number,p_tipo_interaccion in varchar2,p_variable in varchar2 )
		if ( ( $datosLog['nombre_interaccion'] != '' ) && ( $p_correo_respon != '88' && $p_correo_respon != '99' ) ) {
			$sql = ' BEGIN ';
			$sql .= '	Pkg_Seleccion_Vacantes.update_solicitud_vacante ( ';
			$sql .= '		:var1_iv_in,';
			$sql .= '		:var2_iv_in,';
			$sql .= '		:var3_iv_in,';
			$sql .= '		:var4_iv_in';
			$sql .= '		);';
			$sql .= ' END;';
			
			$stmt = oci_parse($this->db->conn_id, $sql) or die("Error: Al en script para actualizar solicitud de vacante");
			
	
			oci_bind_by_name($stmt,':var1_iv_in',$var1_iv_in,32) or die ('No puede enlazar a var1_iv_in');
			oci_bind_by_name($stmt,':var2_iv_in',$var2_iv_in,32) or die ('No puede enlazar a var2_iv_in');
			oci_bind_by_name($stmt,':var3_iv_in',$var3_iv_in,32) or die ('No puede enlazar a var3_iv_in');	
			oci_bind_by_name($stmt,':var4_iv_in',$var4_iv_in,32) or die ('No puede enlazar a var4_iv_in');	
			
			$var1_iv_in = $datosLog['p_id_solicitud'];
			$var2_iv_in = $datosLog['nombre_interaccion'];
			switch($datosLog['nombre_interaccion']) {
				case "IV_LLV":
					$var3_iv_in = $datosLog['IV_LLV'];
					$var4_iv_in = '';
					break;
				case "IV_RTA":
					$var3_iv_in = $datosLog['rta_ingresada'];
					$var4_iv_in = $datosLog['rta_final'];					
					break;
				case "IV_GGPP":
					$var3_iv_in = $datosLog['rta_ingresada'];
					$var4_iv_in = $datosLog['rta_final'];					
					break;	
				case "IV_CZON":
					$var3_iv_in = $datosLog['rta_ingresada'];
					$var4_iv_in = $datosLog['rta_final'];					
					break;									
				default:
			}
			
			oci_execute($stmt);
		}

		//+++++fin nueva procedimiento
		
		
		oci_close($this->db->conn_id);
		return array($p_respon,$p_correo_respon);
	}
	
	//obtener correo/mail teniendo solo el rut
	public function getCorreo($rut)
	{
		$srtQuery = "SELECT Pkg_Seleccion_Vacantes.Trae_Correo (".$rut.") CORREO FROM DUAL";
		return $this->db->query($srtQuery)->result();
	}	
	
	public function getGoEtapa($nroSolicitud)
	{
		/*
		$srtQuery = "
				SELECT
					SST.WF_KEY,
				  SST.ETAPA_ACTUAL,
				  (pkg_seleccion.trae_correlativo (SST.WF_KEY) -1) as CORRELATIVO,
				  SST.ID_FLUJO
				FROM SEL_WF_SOLICITUD_VACANTE sst
				WHERE
				    ESTADO NOT IN  ('Aprobado','Anulado','Rechazado') AND
				    WF_KEY = ".$nroSolicitud."
				ORDER BY CREATION_DATE DESC
				";
		*/
		$srtQuery = "
				SELECT
					SST.WF_KEY,
				  SST.ETAPA_ACTUAL,
				  (Pkg_Seleccion_Vacantes.trae_correlativo (SST.WF_KEY) -1) as CORRELATIVO,
				  SST.ID_FLUJO
				FROM SEL_WF_SOLICITUD_VACANTE sst
				WHERE
				    ESTADO NOT IN  ('Aprobado','Anulado','Rechazado') AND
				    WF_KEY = ".$nroSolicitud."
				ORDER BY CREATION_DATE DESC				
		";
		return $this->db->query($srtQuery)->result();
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
		$sql .= '	Pkg_Seleccion_Vacantes.Graba_Log_Correo ( ';
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
	}
	
	
	
	public function getCorreoParticipantes($solicitud)
	{
		$srtQuery = "SELECT Pkg_Seleccion_Vacantes.Rescata_Todos_Los_Correos (".$solicitud.") CORREO_PARTICIPANTES FROM DUAL";
		return $this->db->query($srtQuery)->result();
	
	}
	
	public function getHistEtapa($nroSolicitud)
	{
		$srtQuery = "
				SELECT etapa_actual
				FROM SEL_WF_SOLICITUD_VACANTE
				WHERE
				WF_KEY = ".$nroSolicitud."
				";
		return $this->db->query($srtQuery)->result();
	}
	
}