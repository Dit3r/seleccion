<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Etapa_model extends CI_Model {

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
	}
	
	function getOvalos($idSolicitud) {
		$strQuery ="select pkg_seleccion.genera_string_detalle(".$idSolicitud.") as STRING_ETAPAS from dual";

		$resultado = $this->db->query($strQuery)->result();
		return $resultado;		
	}

	public function getfillEtapaLog($solicitud,$nroEtapa,$correlativo)
	{
		$srtQuery = "select PKG_SELECCION.trae_info_etapa (".$solicitud.",".$nroEtapa.", ".$correlativo.") as STRING_ETAPA_LOG from dual";
		$resultado = $this->db->query($srtQuery)->result();
		return $resultado;		
	}

	public function getfillEtapaOri($solicitud)
	{
		$srtQuery = "select PKG_SELECCION.trae_informacion_traslado('ORI',".$solicitud.") as STRING_ORIGEN from dual";

		$resultado = $this->db->query($srtQuery)->result();
		return $resultado;		
	}

	public function getfillEtapaDEST($solicitud)
	{
		$srtQuery = "select PKG_SELECCION.trae_informacion_traslado('DEST',".$solicitud.") as STRING_DESTINO from dual";
		
		$resultado = $this->db->query($srtQuery)->result();
		return $resultado;		
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
		
		//$conn = $this->db->conn_id;

		if (!$this->db->conn_id) {
			$this->db=$this->load->database('bdseleccion', true);
		}	
		
		$sql = ' BEGIN ';
		$sql .= '	PKG_SELECCION.GRABA_LOG_TRASLADO ( ';
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
		$sql .= '		:p_proxima_etapa, ';
		$sql .= '		:p_desc_etapa';
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
		oci_bind_by_name($stmt,':p_desc_etapa',$p_desc_etapa,190)  or die ('No puede enlazar a p_desc_etapa');
		
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
		$var12_in = $datosLog['p_dot_teor_destino'];
		$var13_in = $datosLog['p_dot_mae_destino'];
		$var14_in = $datosLog['p_pend_egre_destino'];
		$var15_in = $datosLog['p_pend_ingre_destino'];
		
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
		
		
		if ($datosLog['p_tipo_local'] == '999' || $datosLog['p_tipo_local'] == '888') {
			//actualizar traslado con datos del qf que recibe a colaborador
			$sql = ' BEGIN ';
			$sql .= '	PKG_SELECCION.ACTUALIZA_JEFE_LOCAL ( ';
			$sql .= '		:var1_in,';
			$sql .= '		:var2_in,';
			$sql .= '		:var3_in,';
			$sql .= '		:var4_in';
			$sql .= '		);';
			$sql .= ' END;';
			
			$stmt = oci_parse($this->db->conn_id, $sql) or die("Error: Package 1");
	
			oci_bind_by_name($stmt,':var1_in',$var1_in,32) or die ('No puede enlazar a var1_in');
			oci_bind_by_name($stmt,':var2_in',$var2_in,32) or die ('No puede enlazar a var2_in');
			oci_bind_by_name($stmt,':var3_in',$var3_in,32) or die ('No puede enlazar a var3_in');
			oci_bind_by_name($stmt,':var4_in',$var4_in,32) or die ('No puede enlazar a var4_in');
			
			$var1_in  = $datosLog['p_id_solicitud'];
			$var2_in  = $datosLog['p_tipo_local'];
			$var3_in  = $datosLog['p_rut_jefe'];
			$correo_jefe= $this->getCorreo($datosLog['p_rut_jefe']);
			$var4_in =	$correo_jefe[0]->CORREO;
			oci_execute($stmt);
			//fin actualizar traslado con datos del qf que recibe a colaborador
		}
		
		//obtener mail del rut logueado
		//NO LO OCUPARE correoRutLogueado
		$correoRutLogueado = $this->getCorreo( $datosLog['rutLogueado'] );
		//como ejemplo para enviar mail cada vez que pase una etapa inicio
		
		$asunto = 'Solicitud de traslado '. $datosLog['p_id_solicitud'] .' pendiente';

		
		$nro_ccosto_origen = $this->get_ccosto($datosLog['p_id_solicitud']);	
		$nro_ccosto_origen = $nro_ccosto_origen[0]->CC_ORIGEN;	
		
		$nro_ccosto_destino = $this->get_ccosto($datosLog['p_id_solicitud']);	
		$nro_ccosto_destino = $nro_ccosto_destino[0]->CC_DESTINO;	
		
		$nro_empresa = $this->get_ccosto($datosLog['p_id_solicitud']);	
		$nro_empresa = $nro_empresa[0]->EMPRESA;	
		
		
		$ccosto_origen_desc = $this->get_ccosto_desc($nro_empresa, $nro_ccosto_origen);
		$ccosto_origen_desc = $ccosto_origen_desc[0]->CCOSTO_DESC;
		
		$ccosto_destino_desc = $this->get_ccosto_desc($nro_empresa, $nro_ccosto_destino);
		$ccosto_destino_desc = $ccosto_destino_desc[0]->CCOSTO_DESC;
		
		$rut = $this->get_rut($datosLog['p_id_solicitud']);
		$rut = $rut[0]->RUT;
		
		$nombreColabTras = $this->getNombre($rut);
		$nombreColabTras = $nombreColabTras[0]->NOMBRE;
		
		
		
		
		
		$contenido = '<h2>Traslado Pendiente</h2>';
		$contenido .= '<p style="color:red;">Usted debe continuar con el siguiente Traslado.</p>';
		$contenido .= '<table class="table" border="1" align="center" cellpadding="4">';
		$contenido .= '	<thead>';
		$contenido .= '		<tr>';
		$contenido .= '			<th>ID</th>';
		$contenido .= '			<th>Colaborador</th>';
		$contenido .= '			<th>C. Costo origen</th>';
		$contenido .= '			<th>C. Costo destino</th>';
		$contenido .= '		</tr>';
		$contenido .= '	</thead>';
		$contenido .= '	<tbody>';
		$contenido .= '		<tr>';
		$contenido .= '			<td>'. $datosLog['p_id_solicitud'] .'</td>';
		$contenido .= '			<td>'. $nombreColabTras .'</td>';
		$contenido .= '			<td align="center">'.$nro_ccosto_origen .' - '. $ccosto_origen_desc .'</td>';
		$contenido .= '			<td align="center">'.$nro_ccosto_destino .' - '. $ccosto_destino_desc .'</td>';
		$contenido .= '		</tr>';
		$contenido .= '	</tbody>';
		$contenido .= '</table>';
		$contenido .= '<p>';
		$contenido .= $p_desc_etapa;
		$contenido .= '</p>';
		$contenido .= '<p>';
		$contenido .= 'Debe ingresar al siguiente link <a href="http://huapi:88/intranet/portal/" target="_blank">http://huapi:88/intranet/portal/</a>';
		$contenido .= '</p>';
		
		/*
		$contenido = 	"Se envia correo a $p_respon, quien es responsable de la siguiente etapa
		 su mail es $p_correo_respon , y la descripcion de la siguiente etapa es $p_desc_etapa";

		$contenido .='<h2> SOLICITUD NRO '.$datosLog['p_id_solicitud'].'</h2>';
		$contenido .='<p>Responsable : '.$p_respon.'</p>';
		$contenido .='<p>Correo Responsable : '.$p_correo_respon.'</p>';
		$contenido .='<p>Descripción : '.$p_desc_etapa.'</p>';
		$contenido .='<p><a href="http://'.$_SERVER['HTTP_HOST'].'/intranet/seleccion">Enlace a la comunidad<a></p>';
		*/
		
		/*
		 PRODUCCION
		 */
		 $to = $p_correo_respon;
		 
		/*
		 DESARROLLO
		 $to = $MAILUSUARIOTEST;
		 */
		
		$this->email->from('workflow@sb.cl', 'Workflow');
		$this->email->to($to);
		//$this->email->cc($correoRutLogueado);
		$this->email->bcc(MAILUSUARIOTEST);
		
		
		$this->email->subject($asunto);
		$this->email->message($contenido);
		
		if ($p_correo_respon != '77' && $p_correo_respon != '88' && $p_correo_respon != '99') {
			$this->email->send();
		}
		
		
		//como ejemplo para enviar mail cada vez que pase una etapa fin

		return array($p_respon,$p_correo_respon,$p_desc_etapa);
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
		if (!$this->db->conn_id) {
			$this->db=$this->load->database('bdseleccion', true);
		}
		
		
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
	}
	
	public function getGoEtapa($nroSolicitud)
	{
		$srtQuery = "
				SELECT 	
					SST.WF_KEY, 
				  SST.ETAPA_ACTUAL, 
				  (pkg_seleccion.trae_correlativo (SST.WF_KEY) -1) as CORRELATIVO,
				  SST.ID_FLUJO
				FROM SEL_WF_SOLICITUD_TRASLADO sst 
				WHERE 
				    ESTADO NOT IN  ('Aprobado','Anulado','Rechazado') AND 
				    WF_KEY = ".$nroSolicitud."
				ORDER BY CREATION_DATE DESC
				";

		$resultado = $this->db->query($srtQuery)->result();
		return $resultado;		
	}

	public function getHistEtapa($nroSolicitud)
	{
		$srtQuery = "
					SELECT etapa_actual
					FROM SEL_WF_SOLICITUD_TRASLADO  
					WHERE 
					WF_KEY = ".$nroSolicitud." 
				";
		
		$resultado = $this->db->query($srtQuery)->result();
		return $resultado;		
	}

	//obtener correo/mail teniendo solo el rut
	public function getCorreo($rut)
	{
		$srtQuery = "SELECT pkg_seleccion.trae_correo (".$rut.") CORREO FROM DUAL";

		$resultado = $this->db->query($srtQuery)->result();
		return $resultado;		
	}

	public function getCorreoParticipantes($solicitud)
	{
		$srtQuery = "SELECT pkg_seleccion.rescata_todos_los_correos (".$solicitud.") CORREO_PARTICIPANTES FROM DUAL";

		$resultado = $this->db->query($srtQuery)->result();
		return $resultado;		

	}
	
	public function getimpSolTraslado($nro_solicitud)
	{
		//procedure trae_info_solicitud_voluntaria (p_id_solicitud in number,p_local_tienda out number,p_direccion out varchar2,p_fecha_efectiva out date,
		//p_nombre out varchar2,p_rut out varchar2,p_desc_empresa out varchar2)
		
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
		$sql .= '	PKG_SELECCION.TRAE_INFO_SOLICITUD_VOLUNTARIA ( ';
		$sql .= '		:var1_in,';
		$sql .= '		:p_local_tienda, ';
		$sql .= '		:p_direccion, ';
		$sql .= '		:p_fecha_efectiva, ';
		$sql .= '		:p_nombre, ';
		$sql .= '		:p_rut, ';
		$sql .= '		:p_desc_empresa ';
		$sql .= '		);';
		$sql .= ' END;';
		
		$stmt = oci_parse($this->db->conn_id, $sql) or die("Error: Package 1");
		
		oci_bind_by_name($stmt,':var1_in',$var1_in,50) or die ('No puede enlazar a var1_in');
		
		oci_bind_by_name($stmt,':p_local_tienda',$p_local_tienda,50)  or die ('No puede enlazar a p_local_tienda');
		oci_bind_by_name($stmt,':p_direccion',$p_direccion,150)  or die ('No puede enlazar a p_direccion');
		oci_bind_by_name($stmt,':p_fecha_efectiva',$p_fecha_efectiva,150)  or die ('No puede enlazar a p_fecha_efectiva');
		oci_bind_by_name($stmt,':p_nombre',$p_nombre,150)  or die ('No puede enlazar a p_nombre');
		oci_bind_by_name($stmt,':p_rut',$p_rut,150)  or die ('No puede enlazar a p_rut');
		oci_bind_by_name($stmt,':p_desc_empresa',$p_desc_empresa,150)  or die ('No puede enlazar a p_desc_empresa');
		
		//llena las variables
		$var1_in  = $nro_solicitud;
				
		oci_execute($stmt);
		
		return array(
				'p_local_tienda' =>		$p_local_tienda,
				'p_direccion' =>		$p_direccion,
				'p_fecha_efectiva' =>	$p_fecha_efectiva,
				'p_nombre' =>			$p_nombre,
				'p_rut' =>				$p_rut,
				'p_desc_empresa' =>		$p_desc_empresa
		);
	}//fin funcion getimpSolTraslado
	
	public function getimpBonoZona($nro_solicitud)
	{
		if (!$this->db->conn_id) {
			$this->db=$this->load->database('bdseleccion', true);
		}
		
		$sql = ' BEGIN ';
		$sql .= '	PKG_SELECCION.TRAE_INFO_BONO_ZONA ( ';
		$sql .= '		:var1_in,';
		$sql .= '		:p_fecha, ';
		$sql .= '		:p_desc_empresa, ';
		$sql .= '		:p_rut_empresa, ';
		$sql .= '		:p_representante, ';
		$sql .= '		:p_colaborador, ';
		$sql .= '		:p_rut_colaborador, ';
		$sql .= '		:p_desc_cargo, ';
		$sql .= '		:p_monto_bono, ';
		$sql .= '		:p_ciudad ';		
		$sql .= '		);';
		$sql .= ' END;';
		
		$stmt = oci_parse($this->db->conn_id, $sql) or die("Error: Package 1");
		
		oci_bind_by_name($stmt,':var1_in',$var1_in,50) or die ('No puede enlazar a var1_in');

		oci_bind_by_name($stmt,':p_fecha',$p_fecha,50)  or die ('No puede enlazar a p_fecha');
		oci_bind_by_name($stmt,':p_desc_empresa',$p_desc_empresa,150)  or die ('No puede enlazar a p_desc_empresa');
		oci_bind_by_name($stmt,':p_rut_empresa',$p_rut_empresa,150)  or die ('No puede enlazar a p_rut_empresa');
		oci_bind_by_name($stmt,':p_representante',$p_representante,150)  or die ('No puede enlazar a p_representante');
		oci_bind_by_name($stmt,':p_colaborador',$p_colaborador,150)  or die ('No puede enlazar a p_colaborador');
		oci_bind_by_name($stmt,':p_rut_colaborador',$p_rut_colaborador,150)  or die ('No puede enlazar a p_rut_colaborador');
		oci_bind_by_name($stmt,':p_desc_cargo',$p_desc_cargo,150)  or die ('No puede enlazar a p_desc_cargo');
		oci_bind_by_name($stmt,':p_monto_bono',$p_monto_bono,150)  or die ('No puede enlazar a p_monto_bono');
		oci_bind_by_name($stmt,':p_ciudad',$p_ciudad,150)  or die ('No puede enlazar a p_ciudad');
		
		//llena las variables
		$var1_in  = $nro_solicitud;
		
		
		oci_execute($stmt);
		
		return array(
						'p_fecha' =>			$p_fecha,
						'p_desc_empresa' =>		$p_desc_empresa,
						'p_rut_empresa' =>		$p_rut_empresa,
						'p_representante' =>	$p_representante,
						'p_colaborador' =>		$p_colaborador,
						'p_rut_colaborador' =>	$p_rut_colaborador,
						'p_desc_cargo' =>		$p_desc_cargo,
						'p_monto_bono' =>		$p_monto_bono,
						'p_ciudad' =>			$p_ciudad
		);
	}
	
	
	
	
	//obtener nombre centro de costo
	public function get_ccosto_desc($empresa, $ccosto)
	{
		$srtQuery = "select saca_desc_unidad(".$empresa.",".$ccosto.") as CCOSTO_DESC from dual";
	
		$resultado = $this->db->query($srtQuery)->result();
		return $resultado;
	}
	
	//obtener nombre centro de costo
	public function get_ccosto($solicitud)
	{
		$srtQuery = "SELECT CC_ORIGEN,CC_DESTINO,EMPRESA from SEL_WF_SOLICITUD_TRASLADO where wf_key= ".$solicitud."";
	
		$resultado = $this->db->query($srtQuery)->result();
		return $resultado;
	}
	
	//obtener nombre centro de costo
	public function get_rut($solicitud)
	{
		$srtQuery = "SELECT RUT from SEL_WF_SOLICITUD_TRASLADO where wf_key= ".$solicitud."";
	
		$resultado = $this->db->query($srtQuery)->result();
		return $resultado;
	}
	
	//obtener nombre teniendo solo el rut
	public function getNombre($rut)
	{
		$srtQuery = "SELECT trae_nombre_colaborador(".$rut.") NOMBRE FROM DUAL";
		$resultado = $this->db->query($srtQuery)->result();
		return $resultado;
	}
	
	public function __destruct() {
		$this->db->close();
	}
	
}