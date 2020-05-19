<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Etapa extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Etapa_model');
		$this->load->helper('rut');
	}
	
	public function index()
	{
	}
	public function procSolicitud()
	{
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
		die();
	}
	
	public function fillFields()
	{
		//select pkg_seleccion.trae_informacion_general('ORI',11,11477812,1) as INFOSOLICITUD from dual
		$tipo = $this->input->post('tipo');
		$empresa = $this->input->post('empresa');
		$rut = $this->input->post('rut');
		$ccosto = $this->input->post('ccosto');
		$motivo = $this->input->post('sol_motivo');
		$flujo = $this->input->post('flujo');
		/*
		$tipo = 'ORI';
		$empresa = 11;
		$rut = '11477812';
		$ccosto = 1;
		*/
		
		$this->load->model('Traslado_model');
		$strJson = $this->Traslado_model->autocompletarSolicitudTrasladoOri($tipo,$empresa,$rut,$ccosto,$motivo,$flujo);
        //print_r($strJson[0]->INFOSOLICITUD);
        //exit; 
		if (isset($strJson[0]->INFOSOLICITUD)) {
			echo $strJson[0]->INFOSOLICITUD;
		} else {
			echo $strJson;
		}
		
		
	}
		
	public function pendiente($idSolicitud = FALSE, $paso = 1)
	{
		//$idSolicitud = 33333;
		//-->$vista = "p".$idSolicitud."_view";
		$data['titlePage'] = "Solicitudes Pendientes";
		//-->$dataContenido['paso'] = $paso;
		$dataContenido['idSolicitud'] = $idSolicitud;
		$dataContenido['titlePage'] = $data['titlePage'];
		$stringEtapas = $this->Etapa_model->getOvalos($idSolicitud);
		$dataContenido['ovalos'] = json_decode($stringEtapas[0]->STRING_ETAPAS);
		$this->load->view("template/tpl2-sb/header",$data);
		//$this->load->view("contenidos/pendiente/$vista",$dataContenido);
		$this->load->view("contenidos/pendiente/etapa_pend_view",$dataContenido);
		//$this->load->view("contenidos/pendiente/p22623_view");
		$this->load->view("template/tpl2-sb/footer");
		/*
		$data['titlePage'] = "INGRESO";
		$this->load->view("template/tpl2-sb/header",$data);
		$this->load->view("contenidos/inicio_view");
		$this->load->view("template/tpl2-sb/footer");
		*/
		//$consulta = $this->db_b->query('SELECT * FROM netoffice.net_tickets');
	
	}
		
	public function historico($idSolicitud = FALSE, $etapa)
	{
		$data['titlePage'] = "Solicitudes Finalizadas";
		//77 88 o 99
		$dataContenido['estado'] = $this->Etapa_model->getHistEtapa($idSolicitud);
		$dataContenido['estado'] = $dataContenido['estado'][0]->ETAPA_ACTUAL;
		$dataContenido['idSolicitud'] = $idSolicitud;
		$dataContenido['titlePage'] = $data['titlePage'];
		$stringEtapas = $this->Etapa_model->getOvalos($idSolicitud);
		$dataContenido['ovalos'] = json_decode($stringEtapas[0]->STRING_ETAPAS);
		$this->load->view("template/tpl2-sb/header",$data);
		$this->load->view("contenidos/historico/etapa_hist_view",$dataContenido);
		$this->load->view("template/tpl2-sb/footer");

	}
	
	public function fillEtapaLog()
	{
		$solicitud = $this->input->post("solicitud");
		$nroEtapa = $this->input->post("nroEtapa");
		$correlativo = $this->input->post("correlativo");
		/*
		$solicitud = 33333;
		$nroEtapa = 4;
		*/
		$strJson = $this->Etapa_model->getfillEtapaLog($solicitud,$nroEtapa,$correlativo);
		echo $strJson[0]->STRING_ETAPA_LOG;
	}

	public function fillEtapaOri()
	{
		$solicitud = $this->input->post("solicitud");
		$strJson = $this->Etapa_model->getfillEtapaOri($solicitud);
		echo $strJson[0]->STRING_ORIGEN;
	}

	public function fillEtapaDest()
	{
		$solicitud = $this->input->post("solicitud");
		$strJson = $this->Etapa_model->getfillEtapaDest($solicitud);
		echo $strJson[0]->STRING_DESTINO;
	}
	
	public function paso($param = FALSE)
	{
		$vista = "p".$param."_view";
		$this->load->view("contenidos/pendiente/$vista");
	}
	
	public function insertLog()
	{
		$this->load->library('email');
		
		$insertLog = $this->Etapa_model->putInsertLog($this->input->post());
		echo json_encode($insertLog);
	}
	
	public function goEtapa()
	{
		$nroSolicitud = $this->input->post('solicitud');
		$etapa = $this->Etapa_model->getGoEtapa($nroSolicitud);
		echo json_encode($etapa);
	}
	
	/*
	public function imprAnexo()
	{
    	$this->load->library('pdf_invoice'); // Load FPDF
    	$this->pdf_invoice->fontpath = 'fonts/'; // Especifica el directorio font		
	    $this->pdf_invoice->AddPage();
	    $this->pdf_invoice->SetFont('Arial','B',16);
	    $this->pdf_invoice->Cell(40,10,'Hola Mundo!');
	    $this->pdf_invoice->Output();
	}
	*/

	public function imprAnexo($id)
	{
		header("Content-type: text/html; charset=utf-8");
    	$this->load->library('pdf_invoice'); // Load FPDF
    	//$this->pdf_invoice->fontpath = 'fonts/'; // Especifica el directorio font		
    	
    	/*DESARROLLO
    	$ociRRHH = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.200.190)(PORT=1529))(CONNECT_DATA =(SERVICE_NAME = DESArrhh.domc001.cl)))";
		$conn = oci_connect("rrhh","drrhh",$ociRRHH);
		*/
		
    	/*PRODUCCION
    	*/    	
    	$ociRRHH = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=app-rrhh-prod.sb.cl)(PORT=1555))(CONNECT_DATA=(SERVICE_NAME = PRODrrhh.domc001.cl)))";
    	$conn = oci_connect("rrhh","hhrr",$ociRRHH);

    	
   		if (!$conn) {
		    $e = oci_error();   // Para errores de oci_connect errors, no pase un gestor
		    trigger_error(htmlentities($e['message']), E_USER_ERROR);
		}

		//$id =(($_GET['id']-12345678)/36);
		//$id = 24505; 
		//$id = 24079;
		$dia = date('d'); 
		$mes = date('m');
		$ano = date('Y');


		switch($mes){
			case 01:$mes = "Enero";break;
			case 02:$mes = "Febrero";break;
			case 03:$mes = "Marzo";break;
			case 04:$mes = "Abril";break;
			case 05:$mes = "Mayo";break;
			case 06:$mes = "Junio";break;
			case 07:$mes = "Julio";break;
			case 08:$mes = "Agosto";break;
			case 09:$mes = "Septiembre";break;
			case 10:$mes = "Octubre";break;
			case 11:$mes = "Noviembre";break;
			case 12:$mes = "Diciembre";break;
		}

		$strSQL = "
				SELECT 
				sst.RUT RUT,
				trae_dv_colaborador(sst.RUT) DIG_VERIF,
				trae_nombre_colaborador(sst.RUT) NOMBRE,
				sst.CARGO CARGO,
				SACA_DESC_CARGO(sst.EMPRESA,sst.CARGO) CARGO,
				sst.CC_DESTINO CC_DESTINO,
				SACA_DESC_UNIDAD(sst.EMPRESA,sst.CC_DESTINO) DESCLOCDEST,
				TO_CHAR(FECHA_EFECTIVA_CAMBIO,'dd-mm-yyyy') FECHA_CAMBIO,
				pkg_seleccion.trae_datos_empresa(sst.EMPRESA) DATOS_EMPRESA
				FROM SEL_WF_SOLICITUD_TRASLADO sst
				WHERE  WF_KEY = ".$id."
		";
		//$strSQL = "SELECT * FROM SEL_WF_SOLICITUD_TRASLADO WHERE  WF_KEY = 24079";

		$s = oci_parse($conn, $strSQL);
		oci_execute($s);
		/*
		while ($res = oci_fetch_array($s, OCI_ASSOC)) {
			echo (isset($res['OBSERVACION'])) ?  $res['OBSERVACION'].'<br>' : 'Valor nulo <br>';
		}
		*/
		$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
		$pdf ->fontpath = APPPATH.'fonts/'; 

		$y = "30";
		$c = "50";
		$x = "20";

		while ($res = oci_fetch_array($s, OCI_ASSOC)) {
			$pdf->AddPage();
			$pdf->SetXY(25, 10);


			$nombre 	= $res['NOMBRE'];
			//$rut 		= $rsConn->fields[3];
			$rut = $res['RUT'];//jagl este rut con guion no aparece en la tabla SEL_WF_SOLICITUD_TRASLADO
			$run        = $res['RUT'];
			$dig_verif = $res['DIG_VERIF'];
			$rut_formateado = getPuntosRut($res['RUT'].'-'.$res['DIG_VERIF']);

			$strSQL = " SELECT	CIA_K_EMPRESA EMPRESA,
								SYS_C_ESTADCIVIL,
								SYS_C_NACIONALID,
							   EMP_A_DOMICILIO,
							   SYS_C_COMUNA,
							   SYS_C_CIUDAD,
							   TO_CHAR(emp_f_fechanacim,'DD-MM-YYYY') FECHA_NACIMIENTO,
								to_char(EMP_F_INICICONTR,'DD') DIAIN,
								to_char(EMP_F_INICICONTR,'MM') MESIN,
								to_char(EMP_F_INICICONTR,'YYYY') ANOIND
						FROM MAE_EMPLEADO 
						WHERE EMP_K_RUTEMPLEAD = ".$run."
						AND SYS_C_CODESTADO=1 ";
			$s1 = oci_parse($conn, $strSQL);
			oci_execute($s1);
			$numerodefilas = oci_fetch_all($s1, $resMae);
					
			if ($numerodefilas > 0) {
	
				$strSQL = "SELECT trae_nacionalidad('".$resMae['SYS_C_NACIONALID'][0]."') NACIONALIDAD FROM dual";
				$sf = oci_parse($conn, $strSQL);
				oci_execute($sf);	
				$resF = oci_fetch_array($sf, OCI_ASSOC);
				$nacionalidad = $resF['NACIONALIDAD'];
				
				$strSQL = "SELECT trae_estado_civil('".$resMae['SYS_C_ESTADCIVIL'][0]."') ESTADO_CIVIL FROM dual";
				$sf = oci_parse($conn, $strSQL);
				oci_execute($sf);	
				$resF = oci_fetch_array($sf, OCI_ASSOC);
				$estado_civil = $resF['ESTADO_CIVIL'];
	
				//echo "<p style='color:red'> el  valor es".$resMae['SYS_C_COMUNA']."</p>";
				$strSQL   = "SELECT TRAE_DESC_COMUNA('".$resMae['SYS_C_COMUNA'][0]."') COMUNA FROM dual";
				$sf = oci_parse($conn, $strSQL);
				oci_execute($sf);	
				$resF = oci_fetch_array($sf, OCI_ASSOC);
				$comuna = $resF['COMUNA'];	
	
				$strSQL   = "select TRAE_CIUDAD('".$resMae['SYS_C_CIUDAD'][0]."') CIUDAD from dual";
				$sf = oci_parse($conn, $strSQL);
				oci_execute($sf);	
				$resF = oci_fetch_array($sf, OCI_ASSOC);
				$ciudad = $resF['CIUDAD'];	
	
				$domicilio 	= $resMae['EMP_A_DOMICILIO'][0];
				$fecha_nac  = $resMae['FECHA_NACIMIENTO'][0];
				$fecha_cambio  = $res['FECHA_CAMBIO'];
				$cargo      = $res['CARGO'];
				$cc			= $res['CC_DESTINO'];
				$cc_deslocdest = $res['DESCLOCDEST'];
				$datos_empresa = json_decode($res['DATOS_EMPRESA']);
				/*
				echo "<pre>";
				var_dump($datos_empresa);
				echo "</pre>";
				*/
				/*
				$cargo      = $rsConn->fields[6];
				$cc			= $rsConn->fields[11]; //en mi tabla no me aparece el 
				*/
	
				$strSQL = " SELECT 
								   CIA_K_EMPRESA,
								   UNI_K_CODUNIDAD,
								   UNI_A_NOMBUNIDAD,
								   SYS_C_COMUNA, 
								   SYS_C_CIUDAD,
								   SYS_C_UBICFISICA 
							FROM MAE_UNIDADES
							WHERE UNI_K_CODUNIDAD=".$cc."
							AND CIA_K_EMPRESA={$resMae['EMPRESA'][0]}";//::jagl 98789070989766::colocar $resMae['EMPRESA']
				$s2 = oci_parse($conn, $strSQL);
				oci_execute($s2);	
				$resMaeUni = oci_fetch_array($s2, OCI_ASSOC);
				$cc = $resMaeUni['UNI_K_CODUNIDAD'];	
						
				$mes_c = $resMae['MESIN'][0];
				//$mes_c = $rs->fields[7];
	
				switch($mes_c){
					case 01:$mes_c = "Enero";break;
					case 02:$mes_c = "Febrero";break;
					case 03:$mes_c = "Marzo";break;
					case 04:$mes_c = "Abril";break;
					case 05:$mes_c = "Mayo";break;
					case 06:$mes_c = "Junio";break;
					case 07:$mes_c = "Julio";break;
					case 08:$mes_c = "Agosto";break;
					case 09:$mes_c = "Septiembre";break;
					case 10:$mes_c = "Octubre";break;
					case 11:$mes_c = "Noviembre";break;
					case 12:$mes_c = "Diciembre";break;
				}
	
				$ccdesc = $resMaeUni['UNI_A_NOMBUNIDAD'];
				$comunaLocal 	= $resMaeUni['SYS_C_COMUNA'];		
				$ciudadLocal	= $resMaeUni['SYS_C_CIUDAD'];
				$Ubicacion  	= $resMaeUni['SYS_C_UBICFISICA'];	
				$fechaIni		= $resMae['DIAIN'][0]." de ".$mes_c." de ".$resMae['ANOIND'][0];	
	
				$strSQL   = "select TRAE_DESC_COMUNA('".$comunaLocal."') TRAECOMUNA from dual";
				$sf = oci_parse($conn, $strSQL);
				oci_execute($sf);	
				$resF = oci_fetch_array($sf, OCI_ASSOC);
				$comunaLocal = $resF['TRAECOMUNA'];	
				
				
				$strSQL   = "select TRAE_CIUDAD('".$ciudadLocal."') TRAECIUDAD from dual";
				$sf = oci_parse($conn, $strSQL);
				oci_execute($sf);	
				$resF = oci_fetch_array($sf, OCI_ASSOC);
				$ciudadLocal = $resF['TRAECIUDAD'];			
	
				//	echo "<p style='color:red'>".$Ubicacion."</p>";
				//esta funcion la tube que crear a partir de otra bd rrhh jagl
				$strSQL   = "select TRAE_DIRECCION_UNIDAD('".$Ubicacion."') TRAEDIRECCION from dual";
				$sf = oci_parse($conn, $strSQL);
				oci_execute($sf);	
				$resF = oci_fetch_array($sf, OCI_ASSOC);
				$Direcccion = $resF['TRAEDIRECCION'];	
					
				$mes_nacimiento = explode("-",$fecha_nac);
				$dia_nacimiento = $mes_nacimiento[0];
				$nacimiento     = $mes_nacimiento[1];
				$ano_nacimiento = $mes_nacimiento[2];
				$fechaCambio 	= explode("-",$fecha_cambio);//esta es otra pero que no tengo en la tabla jagl
				$diaCambio 		= $fechaCambio[0];
				$MesCambio 		= $fechaCambio[1];
				$AnoCambio 		= $fechaCambio[2];
	
				switch($nacimiento){
					case "01":$nacimiento = "Enero";break;
					case "02":$nacimiento = "Febrero";break;
					case "03":$nacimiento = "Marzo";break;
					case "04":$nacimiento = "Abril";break;
					case "05":$nacimiento = "Mayo";break;
					case "06":$nacimiento = "Junio";break;
					case "07":$nacimiento = "Julio";break;
					case "08":$nacimiento = "Agosto";break;
					case "09":$nacimiento = "Septiembre";break;
					case "10":$nacimiento = "Octubre";break;
					case "11":$nacimiento = "Noviembre";break;
					case "12":$nacimiento = "Diciembre";break;
				}
				switch($MesCambio){
					case "01":$MesCambio = "Enero";break;
					case "02":$MesCambio = "Febrero";break;
					case "03":$MesCambio = "Marzo";break;
					case "04":$MesCambio = "Abril";break;
					case "05":$MesCambio = "Mayo";break;
					case "06":$MesCambio = "Junio";break;
					case "07":$MesCambio = "Julio";break;
					case "08":$MesCambio = "Agosto";break;
					case "09":$MesCambio = "Septiembre";break;
					case "10":$MesCambio = "Octubre";break;
					case "11":$MesCambio = "Noviembre";break;
					case "12":$MesCambio = "Diciembre";break;
				}
	
				$pdf->SetMargins($y, $c , $x);
				$pdf->SetFont('Arial','B',10);
				$pdf->SetXY(25, 20);
				$pdf->Multicell(0,5,utf8_decode($datos_empresa->sol_descr));
				$pdf->SetXY(25, 24);
				$pdf->Multicell(0,5,utf8_decode($datos_empresa->sol_direccion));
				$pdf->SetXY(25, 28);
				$pdf->Multicell(0,5,utf8_decode('R.U.T.:'.getPuntosRut($datos_empresa->sol_rut_empresa)));
				$pdf->SetXY(25, 32);
				$pdf->Multicell(0,5,utf8_decode($datos_empresa->sol_nom_replegal));
				$pdf->SetXY(25, 36);
				$pdf->Multicell(0,5,utf8_decode('R.U.T.:'.getPuntosRut($datos_empresa->sol_rut_replegal)));
	
	
				$pdf->SetFont('Arial','B',20);
				$pdf->SetXY(25, 50);
				$pdf->Cell(0,5,'Anexo de Contrato de Trabajo',0,0,'C');
				$pdf->Ln(20);
				$pdf->SetFont('Arial','B',12);
				$pdf->cell(150,5,'De Sr.(a/ita): '. utf8_decode($nombre),0,0,'L');
				$pdf->Ln(15);
				$pdf->SetFont('Arial','B',11);
				$pdf->Multicell(150,5,utf8_decode('   En Santiago de Chile, '.$dia.' de '.$mes.' de '.$ano.', entre la empresa '. $datos_empresa->sol_descr .', representada legalmente por don CARLOS GONZALEZ CORREA, Rut: 10.156.799-0, en su calidad Gerente Gestión de Personas, con domicilio en '.$datos_empresa->sol_direccion.', en adelante "el Empleador" y don (ña) '.$nombre.' de nacionalidad '.$nacionalidad.', estado civil '.$estado_civil.' ,nacido(a) el día '.$dia_nacimiento.' de '.$nacimiento. ' de '.$ano_nacimiento.' , domiciliado(a) en '.$domicilio.' Comuna '.$comuna.', de esta ciudad, '.$ciudad.', cédula nacional de identidad N° '.$rut_formateado.' ,en adelante "el Trabajador(a)",  se ha convenido  el siguiente Anexo al Contrato de Trabajo: '));
				$pdf->Ln(5);
				$pdf->SetFont('Arial','B',11);
				$pdf->Multicell(150,5,utf8_decode('PRIMERO.- Las partes de común acuerdo dejan constancia que el trabajador a partir del '.$diaCambio.' de '.$MesCambio.' de '.$AnoCambio.' , desempeñará sus labores de '.$cargo.' en el local '.$cc.' - '.$cc_deslocdest.', ubicado en '.$Direcccion.', '.$ciudadLocal.'.'));
				$pdf->Ln(5);
				$pdf->SetFont('Arial','B',11);
				$pdf->Multicell(150,5,utf8_decode('SEGUNDO: Se deja constancia que el trabajador ingresó a prestar servicios para el empleador el día '.$fechaIni));
				$pdf->Ln(5);
				$pdf->SetFont('Arial','B',11);
				$pdf->Multicell(150,5,utf8_decode('Para constancia y aceptación del presente Anexo de Contrato de Trabajo, firman ambas partes conforme.'));
				$pdf->Ln(20);
				$pdf->SetXY(40, 220);
				$pdf->Multicell(150,5,utf8_decode('___________________________'));
				$pdf->SetXY(50, 224);
				$pdf->Multicell(150,5,$datos_empresa->sol_descr);
				$pdf->SetXY(48, 228);
				$pdf->Multicell(150,5,'p.p RUT '. getPuntosRut($datos_empresa->sol_rut_empresa));
				$pdf->SetXY(120, 220);
				$pdf->Multicell(150,5,utf8_decode('___________________________'));
				$pdf->SetXY(133, 224);
				$pdf->Multicell(150,5,utf8_decode('Firma Trabajador'));
				$pdf->SetXY(139, 228);
				$pdf->Multicell(150,5,utf8_decode($rut_formateado));
				$pdf->SetXY(25, 245);
				$pdf->SetFont('Arial','B',16);
				$pdf->Multicell(150,5,utf8_decode('Traslado '.$id));
			} else {
				$pdf->SetMargins($y, $c , $x);
				$pdf->SetFont('Arial','B',10);
				$pdf->SetXY(25, 20);
				$pdf->Multicell(0,5,utf8_decode("No es posible generar anexo de contrato, empleado no se encuentra activo. Por favor comunicarse con GGPP, área de procesos y compensaciones."));
			}
		}

		$pdf->Output('Anexo-de-Contrato.pdf','I');
	}//fin imprAnexo

	public function imprCarta($id)
	{
    	$this->load->library('pdf_invoice'); // Load FPDF
    	$this->pdf_invoice->fontpath = 'font/'; // Especifica el directorio font		
		
		/*DESARROLLO
		 $ociRRHH = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.200.190)(PORT=1529))(CONNECT_DATA =(SERVICE_NAME = DESArrhh.domc001.cl)))";
		 $conn = oci_connect("rrhh","drrhh",$ociRRHH);		
		 */
		
		/*PRODUCCION
		 */
		$ociRRHH = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=app-rrhh-prod.sb.cl)(PORT=1555))(CONNECT_DATA=(SERVICE_NAME = PRODrrhh.domc001.cl)))";
		$conn = oci_connect("rrhh","hhrr",$ociRRHH);		
		 

		if (!$conn) {
		    $e = oci_error();   // Para errores de oci_connect errors, no pase un gestor
		    trigger_error(htmlentities($e['message']), E_USER_ERROR);
		}
		
		/*:::::::codigo para imprimir carta inicio::::::::*/
		//$id = 24079;
		$strSQL = " SELECT 
							sst.EMPRESA EMPRESA,
						   sst.CREATED_BY CREATED_BY,	--::se usa 2::
						   sst.RUT RUT,	--::se usa 3::
						   trae_nombre_colaborador(sst.RUT) NOMBRE,	--::se usa 4, tube que agregar una funcion::
						   trae_dv_colaborador(sst.RUT) DIG_VERIF,
						   --::se usa 5 pero no lo tengo en la tabla::sst.APELLIDO APELLIDO, 
						   sst.CARGO CARGO,	--::se usa 6::
						   SACA_DESC_CARGO(sst.EMPRESA,sst.CARGO) DESC_CARGO,
						   sst.CC_DESTINO CC_DESTINO,	--::se usa 11::
						   TO_CHAR(sst.FECHA_EFECTIVA_CAMBIO,'dd-mm-yyyy') FECHA_EFECTIVA_CAMBIO,	--::se usa 11::
						   pkg_seleccion.trae_datos_empresa(sst.EMPRESA) DATOS_EMPRESA
					FROM SEL_WF_SOLICITUD_TRASLADO sst
					WHERE  sst.WF_KEY = ".$id."";

		$s = oci_parse($conn, $strSQL);
		oci_execute($s);

		$pdf = new PDF_Invoice( 'P', 'mm', 'A4' );
		$pdf ->fontpath = APPPATH.'fonts/';
		
		$y = "30";
		$c = "50";
		$x = "20";

		while ($res = oci_fetch_array($s, OCI_ASSOC)) {
			$pdf->AddPage();
			$strSQL = " SELECT 
							   CIA_K_EMPRESA,
							   UNI_K_CODUNIDAD,
							   UNI_A_NOMBUNIDAD,
							   SYS_C_COMUNA, 
							   SYS_C_CIUDAD,
							   SYS_C_UBICFISICA 
						FROM MAE_UNIDADES
						WHERE UNI_K_CODUNIDAD=".$res['CC_DESTINO']."
						AND CIA_K_EMPRESA={$res['EMPRESA']}";
						//echo $strSQL;
			$s1 = oci_parse($conn, $strSQL);
			oci_execute($s1);
			$resMaeUni = oci_fetch_array($s1, OCI_ASSOC);//$rsConnLocal
			
			$cc = $resMaeUni['UNI_K_CODUNIDAD'];			
			
			$ccdesc 	= $resMaeUni['UNI_A_NOMBUNIDAD'];			
			$comuna 	= $resMaeUni['SYS_C_COMUNA'];			
			$ciudad 	= $resMaeUni['SYS_C_CIUDAD'];
			$Ubicacion  = $resMaeUni['SYS_C_UBICFISICA'];			
	
			$strSQL   = "SELECT TRAE_DESC_COMUNA('".$comuna."') TRAE_COMUNA FROM DUAL";
			$sf = oci_parse($conn, $strSQL);
			oci_execute($sf);
			$resF = oci_fetch_array($sf, OCI_ASSOC);
			$comuna = $resF['TRAE_COMUNA'];
			
			$strSQL   = "SELECT TRAE_CIUDAD('".$ciudad."') TRAE_CIUDAD FROM DUAL";
			$sf = oci_parse($conn, $strSQL);
			oci_execute($sf);
			$resF = oci_fetch_array($sf, OCI_ASSOC);
			$ciudad = $resF['TRAE_CIUDAD'];
			
			$strSQL   = "SELECT trae_direccion_unidad('".$Ubicacion."') TRAE_DIRECCION_UNIDAD FROM DUAL";
			$sf = oci_parse($conn, $strSQL);
			oci_execute($sf);
			$resF = oci_fetch_array($sf, OCI_ASSOC);
			$Direcccion = $resF['TRAE_DIRECCION_UNIDAD'];
					
			
			$nombre 	= $res['NOMBRE'];
			$desc_cargo = $res['DESC_CARGO'];
			$rut 		= $res['RUT'];
			$dig 		= $res['CREATED_BY'];
			$dig_verif = $res['DIG_VERIF'];
			$rut_formateado = getPuntosRut($res['RUT'].'-'.$res['DIG_VERIF']);
			$datos_empresa = json_decode($res['DATOS_EMPRESA']);
			
			//jagl no lo ocupa despues $domicilio 	= $rsConn->fields[3];
			//jagl no lo ocupa despues $CodComuna 	= $rsConn->fields[4];
			//jagl lo comento porque no tengo para colocar el rut completo $Run		= $rut."-".$dig; 
			$Run = $rut;
			$cargo 		= $res['CARGO'];
			
			/*$strSQL = " select initcap(loge_dscr)
						from VIE_COMUNA where loge_codg  =".$CodComuna;
			$rsConn2 	= $dbRRHH->Execute($strSQL)or die("Error: 2");
			$comuna = $rsConn2->fields[0];
			*/	
			$dia = date('d'); 
			$mes = date('m');
			$ano = date('Y');
			
			
			switch($mes){
				case 01:$mes = "Enero";break;
				case 02:$mes = "Febrero";break;
				case 03:$mes = "Marzo";break;
				case 04:$mes = "Abril";break;
				case 05:$mes = "Mayo";break;
				case 06:$mes = "Junio";break;
				case 07:$mes = "Julio";break;
				case 08:$mes = "Agosto";break;
				case 09:$mes = "Septiembre";break;
				case 10:$mes = "Octubre";break;
				case 11:$mes = "Noviembre";break;
				case 12:$mes = "Diciembre";break;
			}
				
			$fecha = explode('-',$res['FECHA_EFECTIVA_CAMBIO']);
			$diaFecha = $fecha[0];
			$anoFecha = $fecha[2];
			$mesFecha = $fecha[1];
			
			switch($mesFecha){
				case 01:$mesFecha = "Enero";break;
				case 02:$mesFecha = "Febrero";break;
				case 03:$mesFecha = "Marzo";break;
				case 04:$mesFecha = "Abril";break;
				case 05:$mesFecha = "Mayo";break;
				case 06:$mesFecha = "Junio";break;
				case 07:$mesFecha = "Julio";break;
				case 08:$mesFecha = "Agosto";break;
				case 09:$mesFecha = "Septiembre";break;
				case 10:$mesFecha = "Octubre";break;
				case 11:$mesFecha = "Noviembre";break;
				case 12:$mesFecha = "Diciembre";break;
			}
			
			
			$fecha = $diaFecha.' de '.$mesFecha.' de '.$anoFecha;
			$pdf->SetFont('Arial','B',13);
			$pdf->SetMargins($y, $c , $x);
			
			$pdf->SetFont('Arial','B',10);
			$pdf->SetXY(25, 20);
			$pdf->Multicell(0,5,utf8_decode($datos_empresa->sol_descr));
			$pdf->SetXY(25, 24);
			$pdf->Multicell(0,5,utf8_decode($datos_empresa->sol_direccion));
			$pdf->SetXY(25, 28);
			$pdf->Multicell(0,5,utf8_decode('R.U.T.:'.getPuntosRut($datos_empresa->sol_rut_empresa)));
			$pdf->SetXY(25, 32);
			$pdf->Multicell(0,5,utf8_decode($datos_empresa->sol_nom_replegal));
			$pdf->SetXY(25, 36);
			$pdf->Multicell(0,5,utf8_decode('R.U.T.:'.getPuntosRut($datos_empresa->sol_rut_replegal)));
			$pdf->SetXY(98, 45);
			$pdf->SetFont('Arial','B',14);
			$pdf->Multicell(0,5,utf8_decode('SANTIAGO '.$dia.' de '.$mes.' de '.$ano));
			
			
			$pdf->SetXY(25, 65);
			$pdf->SetFont('Arial','B',9);
			$pdf->cell(150,5,utf8_decode('Señor (a,ita)'),0,0,'L');
			$pdf->Ln(8);
			$pdf->cell(150,5,utf8_decode(str_replace('?','ñ',$nombre)),0,0,'L');
			$pdf->Ln(5);
			$pdf->cell(150,5,utf8_decode('R.U.T.: '.$rut_formateado),0,0,'L');
			$pdf->Ln(5);
			$pdf->cell(150,5,utf8_decode('P   r   e   s   e   n   t  e.'),0,0,'J');
			$pdf->Ln(15);
			$pdf->Multicell(150,5,utf8_decode('De nuestra consideración:'));
			$pdf->Ln(5);
			$pdf->Multicell(150,5,utf8_decode('                                                 Por intermedio de la presente, me permito comunicar a Ud. que por razones de funcionamiento de la empresa y la necesidad de brindar una eficiente atención a nuestros clientes, se ha decidido que a contar del próximo, '.$fecha.' , deberá prestar sus servicios en Sucursal '.$cc.' ,('.$ccdesc.'), ubicado en '.$Direcccion.' , '.$comuna.' , desempeñando el cargo de '.utf8_encode($desc_cargo).'.'));
			$pdf->Ln(5);
			$pdf->Multicell(150,5,utf8_decode('                                                 Lo anterior, se encuentra en armonía con la cláusula de su contrato de trabajo que permite el traslado a los distintos locales de la empresa.'));
			$pdf->Ln(20);
			$pdf->Multicell(150,5,utf8_decode('      Atentamente,'),'','L',0);
			$pdf->SetXY(40, 200);
			$pdf->Multicell(150,5,utf8_decode('___________________________'));
			$pdf->SetXY(50, 204);
			$pdf->Multicell(150,5,utf8_decode($datos_empresa->sol_descr));
			$pdf->SetXY(50, 207);
			$pdf->Multicell(150,5,'p. p. RUT: '.getPuntosRut($datos_empresa->sol_rut_empresa).'');
			$pdf->SetXY(120, 200);
			$pdf->Multicell(150,5,utf8_decode('___________________________'));
			$pdf->SetXY(118, 204);
			$pdf->Multicell(150,5,utf8_decode('TOMÉ CONOCIMIENTO Y ACEPTO'));
			$pdf->SetXY(135, 207);
			$pdf->Multicell(150,5,utf8_decode($rut_formateado));
			$pdf->SetXY(25, 225);
			$pdf->SetFont('Arial','B',10);
			$pdf->Multicell(150,5,utf8_decode('c.c.: Inspención del Trabajo'));
			$pdf->SetXY(25, 229);
			$pdf->Multicell(150,5,utf8_decode('Carpeta Personal'));
			$pdf->SetXY(25, 233);
			$pdf->Multicell(150,5,utf8_decode('Trabajador'));
			$pdf->SetXY(25, 245);
			$pdf->SetFont('Arial','B',12);
			$pdf->Multicell(150,5,utf8_decode('Traslado '.$id));
		}
		
		$pdf->Output('Carta-de-Aviso.pdf','I');
		/*:::::::codigo para imprimir carta inicio::::::::*/
		
	}//fin imprimir carta
	
	public function subirCarta() 
	{
		$this->load->library('session');
		$solicitud = $this->input->post('solicitud');
		$etapa = $this->input->post('etapa');
		$correlativo = $this->input->post('correlativo');
		$idFlujo = $this->input->post('idFlujo');		

		$config['upload_path'] = dirname($_SERVER["SCRIPT_FILENAME"])."/upload/";
		$config['upload_url'] = base_url()."upload/";
		$config['allowed_types'] = "pdf";
		$config['overwrite'] = TRUE;
		$config['max_size']	= "1000KB";		
		$config['file_name'] = "C".$this->input->post('solicitud');
		$this->load->library('upload', $config);
		
		if($this->upload->do_upload())
		{
			$file_name = $_FILES['userfile']['name'];
			/*
			echo "Solicitud numero : ". $_POST['solicitud']."<br />";
			echo "file upload ".$file_name." success <br />";
			echo $file_name;
			*/
			$this->session->set_flashdata('ejecutada', 'si');
			$this->session->set_flashdata('mensaje', '<span><strong>Felicitaciones!</strong>:</span> CARTA DE AVISO guardada correctamente!');
			$this->session->set_flashdata('tipoDocumento', 'carta');
			$urlAnterior = site_url()."etapa/pendiente/".$solicitud."/".$etapa."/".$correlativo."/".$idFlujo;
			redirect($urlAnterior);
		} else {
			$this->session->set_flashdata('ejecutada', 'no');
			$this->session->set_flashdata('ADVERTENCIA', '<span><strong>ADVERTENCIA !</strong> : </span> NO se ha subido su archivo, recuerde que solo se aceptan archivos con la extension PDF y con menos de 1 Megabyte!');
			$urlAnterior = site_url()."etapa/pendiente/".$solicitud."/".$etapa."/".$correlativo."/".$idFlujo;
			redirect($urlAnterior);
		}
	}//fin subirCarta	
	
	public function subirAnexo()
	{
		$solicitud = $this->input->post('solicitud');
		$etapa = $this->input->post('etapa');
		$correlativo = $this->input->post('correlativo');
		$idFlujo = $this->input->post('idFlujo');
	
		$config['upload_path'] = dirname($_SERVER["SCRIPT_FILENAME"])."/upload/";
		$config['upload_url'] = base_url()."upload/";
		$config['allowed_types'] = "pdf";
		$config['overwrite'] = TRUE;
		$config['max_size']	= "1000KB";
		$config['file_name'] = "A".$this->input->post('solicitud');
		$this->load->library('upload', $config);
	
		if($this->upload->do_upload())
		{
			$file_name = $_FILES['userfile']['name'];
			$this->session->set_flashdata('ejecutada', 'si');
			$this->session->set_flashdata('mensaje', '<span><strong>Felicitaciones!</strong>:</span> ANEXO guardado correctamente!');
			$this->session->set_flashdata('tipoDocumento', 'anexo');
			$urlAnterior = site_url()."etapa/pendiente/".$solicitud."/".$etapa."/".$correlativo."/".$idFlujo;
			redirect($urlAnterior);
		} else {
			
			$error = array('error' => $this->upload->data());

			
			$this->session->set_flashdata('ejecutada', 'no');
			$this->session->set_flashdata('ADVERTENCIA', '<span><strong>ADVERTENCIA !</strong> : </span> NO se ha subido su archivo, recuerde que solo se aceptan archivos con la extension PDF y con menos de 1 Megabyte!');
			$urlAnterior = site_url()."etapa/pendiente/".$solicitud."/".$etapa."/".$correlativo."/".$idFlujo;
			redirect($urlAnterior);
		}
	}//fin subirCarta

	//SUBE BONO ZONA    
	public function subirBonoZona()
	{
		$solicitud = $this->input->post('solicitud');
		$etapa = $this->input->post('etapa');
		$correlativo = $this->input->post('correlativo');
		$idFlujo = $this->input->post('idFlujo');
	
		$config['upload_path'] = dirname($_SERVER["SCRIPT_FILENAME"])."/upload/";
		$config['upload_url'] = base_url()."upload/";
		$config['allowed_types'] = "pdf";
		$config['overwrite'] = TRUE;
		$config['max_size']	= "1000KB";
		$config['file_name'] = "B".$this->input->post('solicitud');
		$this->load->library('upload', $config);
	
		if($this->upload->do_upload())
		{
			$file_name = $_FILES['userfile']['name'];
			$this->session->set_flashdata('ejecutada', 'si');
			$this->session->set_flashdata('mensaje', '<span><strong>Felicitaciones!</strong>:</span> ANEXO BONO guardado correctamente!');
			$this->session->set_flashdata('tipoDocumento', 'bono');
			$urlAnterior = site_url()."etapa/pendiente/".$solicitud."/".$etapa."/".$correlativo."/".$idFlujo;
			redirect($urlAnterior);
		} else {
			$this->session->set_flashdata('ejecutada', 'no');
			$this->session->set_flashdata('ADVERTENCIA', '<span><strong>ATENCION !</strong> : </span> NO se ha subido su archivo, recuerde que solo se aceptan archivos con la extension PDF y con menos de 1 Megabyte!');
			$urlAnterior = site_url()."etapa/pendiente/".$solicitud."/".$etapa."/".$correlativo."/".$idFlujo;
			redirect($urlAnterior);
		}
	}//fin subirCarta	
	//SUBE BONO ZONA FIN
	 
	//SUBE TRASL. VOLUN.
	public function subirTraslVolunt()
	{
		$solicitud = $this->input->post('solicitud');
		$etapa = $this->input->post('etapa');
		$correlativo = $this->input->post('correlativo');
		$idFlujo = $this->input->post('idFlujo');
	
		$config['upload_path'] = dirname($_SERVER["SCRIPT_FILENAME"])."/upload/";
		$config['upload_url'] = base_url()."upload/";
		$config['allowed_types'] = "pdf";
		$config['overwrite'] = TRUE;
		$config['max_size']	= "1000KB";
		$config['file_name'] = "V".$this->input->post('solicitud');
		$this->load->library('upload', $config);
	
		if($this->upload->do_upload())
		{
			$file_name = $_FILES['userfile']['name'];
			$this->session->set_flashdata('ejecutada', 'si');
			$this->session->set_flashdata('mensaje', '<span><strong>Felicitaciones!</strong>:</span> TRASLADO VOLUNTARIO guardado correctamente!');
			$this->session->set_flashdata('tipoDocumento', 'trasl_volunt');
			$urlAnterior = site_url()."etapa/pendiente/".$solicitud."/".$etapa."/".$correlativo."/".$idFlujo;
			redirect($urlAnterior);
		} else {
			$this->session->set_flashdata('ejecutada', 'no');
			$this->session->set_flashdata('ADVERTENCIA', '<span><strong>ATENCION !</strong> : </span> NO se ha subido su archivo, recuerde que solo se aceptan archivos con la extension PDF y con menos de 1 Megabyte!');
			$urlAnterior = site_url()."etapa/pendiente/".$solicitud."/".$etapa."/".$correlativo."/".$idFlujo;
			redirect($urlAnterior);
		}
	}//fin subirCarta
	//SUBE TRASL. VOLUN.
	
	//preguntas si existe los archivos subidos en las etapas
	public function existeArchivo()
	{
		$nombre_fichero = $this->input->post("nombre_fichero");
		//FCPATH
		$ruta = FCPATH . "upload/" . $nombre_fichero;
		if (file_exists($ruta)) {
			echo "si_existe";
			//echo $ruta;
		} else {
			echo "no_existe";
			//echo $ruta;
		}
	}
	
	//mailResponsable se utiliza una persona quiere consultar o apurar a esa persona para que gestione traslado
	public function mailResponsable()
	{
		//variables para despues que envie el mail redireccionar
		$solicitud = $this->input->post('solicitud');
		$etapa = $this->input->post('etapa');
		$correlativo = $this->input->post('correlativo');
		$idFlujo = $this->input->post('idFlujo');
		
		$this->load->library('email');
		
		
		//descomentar cuando este operativo $to = $this->input->post('to');
		$to = $this->input->post('to');
		//$to = MAILUSUARIOTEST;
		
		
		$solicitud = 			$this->input->post('solicitud');
		$asunto = 				$this->input->post('asunto');
		$observParaRespons = 	$this->input->post('observParaRespons');		
		
		$this->email->from('workflow@sb.cl', 'Workflow');
		$this->email->to($to);	
		$this->email->bcc(MAILUSUARIOTEST);

		//mail y nombre usuario logueado
		$correoLogueado = $this->Etapa_model->getCorreo($this->input->post('rutLogeado'));
		$nombreLogueado = $this->Etapa_model->getNombre($this->input->post('rutLogeado'));

		$observParaRespons = "<p>". $observParaRespons . "</p>". "<p>Este correo fue enviado por ". $nombreLogueado[0]->NOMBRE ." - ". $correoLogueado[0]->CORREO ."</p>";

		//indicar en la cabecera de los mail el mail que debe aparecer al momento de pinchar el boton responder
		/*PRODUCCION
		*/
		$this->email->reply_to($correoLogueado[0]->CORREO,$nombreLogueado[0]->NOMBRE);

		/*DESARROLLO
		$this->email->reply_to(MAILUSUARIOTEST);		
		*/

		$this->email->subject($asunto);
		$this->email->message($observParaRespons);

		if($this->email->send())
		{
			$this->session->set_flashdata('ejecutadaMail', 'si');
			$this->session->set_flashdata('mensaje', '<span><strong>Felicitaciones!</strong>:</span> mail enviado Exitosamente!.');
			$urlAnterior = site_url()."etapa/pendiente/".$solicitud."/".$etapa."/".$correlativo."/".$idFlujo;

		//->$correoDestino = $this->input->post('rutLogeado');
			
		$correoDestino = $this->Etapa_model->getCorreo($this->input->post('rutLogeado'));
		$correoDestino = $correoDestino[0]->CORREO;
		//echo $correoDestino;
		//die();
		//$correoDestino = "ffffff";

		$dataLogMail = array(
			"p_id_solicitud" => $solicitud,
			"p_etapa_origen" => $etapa,
			"p_etapa_destino" => $etapa,
			"p_correo_desde" => $correoDestino,
			"p_correo_hasta" => $this->input->post('to'),//cambiar arriba por $to
			"p_texto" => $observParaRespons
			);
		$insertaLogCorreo = $this->Etapa_model->put_graba_log_correo($dataLogMail);

			redirect($urlAnterior);
		} else {
			$this->session->set_flashdata('ejecutadaMail', 'no');
			$this->session->set_flashdata('mensaje', '<span><strong>ATENCION !</strong> : </span> NO se ha logrado enviar su mail, intente nuevamente!');
			$urlAnterior = site_url()."etapa/pendiente/".$solicitud."/".$etapa."/".$correlativo."/".$idFlujo;
			redirect($urlAnterior);
		}

		
	}//fin mailResponsable	
	
	public function graba_log_correo()
	{
		/*
		Array
		(
		    [p_id_solicitud] => 24419
		    [p_etapa_origen] => 6
		    [p_etapa_destino] => 8
		    [p_correo_desde] => workflow@sb.cl
		    [p_correo_hasta] => xvergara@sb.cl
		    [p_texto] => Texto de prueba de la modal
		)
		*/
		$dataLogMail = $this->input->post();	
		$insertaLogCorreo = $this->Etapa_model->put_graba_log_correo($dataLogMail);

		//print_r($dataLogMail);
		//die();
	}

	/*se envian los correos a todos los participantes una vez finalizada la orden*/
	public function enviaMailTodosParticipantes()
	{
		$solicitud = $this->input->post('p_id_solicitud');
		$lstCorreos = $this->Etapa_model->getCorreoParticipantes($solicitud);
		$lstCorreos = $lstCorreos[0]->CORREO_PARTICIPANTES;
		$lstCorreos = json_decode($lstCorreos);

		foreach ($lstCorreos as $k => $obj) {
			if ($obj->correo) {
				$correos[] = $obj->correo;
			}
		}

		$this->load->library('email');
		//luego que aplique la libreria quitar
		//print_r($correos);
		//descomentar cuando este operativo $to = $this->input->post('to');

		$correos = array_unique($correos);
		//-> con esta linea se le estaria enviando mail a todos los involucrados $this->email->to($correos);
		
		$asunto = 		$this->input->post('asunto');
		$contenido = 	$this->input->post('contenido');
		
		//:::::::IMPORTANTE:::::::::://
		//jagl comentar lo de abajo para enviar correos a destinatarios reales y descomentar para test 
		//$correos = MAILUSUARIOTEST;
		
		$this->email->from('workflow@sb.cl', 'Workflow');
		$this->email->to($correos);	
		$this->email->bcc(MAILUSUARIOTEST);
		$this->email->subject($asunto);
		$this->email->message($contenido);
		/*
		if($this->email->send())
		*/
		if (!$this->email->send()) {
			echo 'No se enviaron los mails';
		} else {
			echo 'Si se enviaron los mail';
		}
	}
	
	//actualizar el jefe de local para que quede grabado en los logs quien es el jefe de local que 
	//indico que asistio el colaborador
	public function actualizaLog()
	{
		$insertLog = $this->Etapa_model->putActualizaLog($this->input->post());
		echo json_encode($insertLog);
	}

	public function impBonoZona($idSolicitud) {
		$this->load->helper('rut');
		
		$datos_bono = $this->Etapa_model->getimpBonoZona($idSolicitud);
		
		$dividirFecha = split('-', $datos_bono['p_fecha']);
		
		$dia = $dividirFecha[0];
		$mes = $dividirFecha[1];
		$anio = $dividirFecha[2];
		
		switch($mes){
			case 'JAN':$mes = "Enero";break;
			case 'FEB':$mes = "Febrero";break;
			case 'MAR':$mes = "Marzo";break;
			case 'APR':$mes = "Abril";break;
			case 'MAY':$mes = "Mayo";break;
			case 'JUN':$mes = "Junio";break;
			case 'JUL':$mes = "Julio";break;
			case 'AUG':$mes = "Agosto";break;
			case 'SEP':$mes = "Septiembre";break;
			case 'OCT':$mes = "Octubre";break;
			case 'NOV':$mes = "Noviembre";break;
			case 'DEC':$mes = "Diciembre";break;
		}		
		
		$this->load->library('fpdf'); // Load FPDF
		$pdf = new FPDF('p','pt','A4');
		$pdf ->fontpath = APPPATH.'fonts/';
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','',12);
	
		// Logo
		$image1 = base_url()."assets/template/tpl2-sb/img/logo_salcobrand108.gif";
		$pdf->Image($image1,450,38,81,81);
	
		// Salto de línea
		$pdf->Ln(150);
	
		$fecha = $dia . ' de ' . $mes . ' 20' . $anio;
		$empresa = $datos_bono['p_desc_empresa'];
		$rut_empresa = getPuntosRut( $datos_bono['p_rut_empresa'] );
		$nom_repres = $datos_bono['p_representante'];
		$nom_colab = $datos_bono['p_colaborador'];
		$rut_colab = getPuntosRut( $datos_bono['p_rut_colaborador'] );
		$cargo_colab = $datos_bono['p_desc_cargo'];
		$bono = $datos_bono['p_monto_bono'];
		$ciudad_emp = $datos_bono['p_ciudad'];
		
		$pdf->SetFont('Arial','BU',12);
		$pdf ->Cell (0,14 ,'ANEXO DE CONTRATO DE TRABAJO',0 ,0, 'C' );
		
		$pdf->Ln(100);
		$pdf->SetFont('Arial','',12);
		$text = '
			En Santiago, a '.$fecha.'. entre la empresa '.$empresa.', Rut:  '.$rut_empresa.', representada por su sub.-Gerente de Gestión de Personas, '.$nom_repres.' en adelante "la Empresa" y don/a '.$nom_colab.', Rut:   '.$rut_colab.'  en adelante "el Trabajador", se ha convenido el siguiente anexo de su contrato de trabajo:
	
			PRIMERO:  	Las partes se encuentran vinculadas por un contrato de trabajo mediante el cual el trabajador(a) presta servicios en calidad de '.$cargo_colab.'.
					
			SEGUNDO:  	Por el presente  instrumento se deja constancia que el trabajador(a) tendrá derecho a percibir un "Bono de zona" por la suma de $ '.$bono.' pesos. imponibles mensuales, sólo mientras presta sus servicios a la empresa en la ciudad de '.$ciudad_emp.'.
						
						En consecuencia, si el trabajador es trasladado a una ciudad distinta a prestar sus servicios, dicho bono se extinguirá ipso facto.
						
						El presente anexo se firma en dos ejemplares, recibiendo uno de ellos el trabajador a su entera conformidad.
		';
	
		$pdf->MultiCell(0, 15, utf8_decode($text), 0, "L");
	
		$pdf->Ln(50);
		$pdf->SetXY(50, 640);
		$pdf->Multicell(200,5,utf8_decode('___________________________'));
		$pdf->SetXY(60, 664);
		$pdf->Multicell(150,5,utf8_decode('p.SALCOBRAND S.A..'));
		$pdf->SetXY(60, 690);
		$pdf->Multicell(150,5,utf8_decode('Rut:  76.031.071-9'));
			
		$pdf->SetXY(320, 668);
		$pdf->Multicell(150,5,utf8_decode('p. p. RUT: 76.031.071-9'));
		$pdf->SetXY(300, 640);
		$pdf->Multicell(200,5,utf8_decode('___________________________'));
		$pdf->SetXY(320, 695);
		$pdf->Multicell(150,5,utf8_decode('7.662.611-1'));
		
		$pdf->SetFont('Arial','B',14);
		$pdf->Ln(50);
		$pdf->Multicell(150,5,'Traslado '.$idSolicitud);
	
		//$pdf->Cell(0, 16, "ANEXO DE CONTRATO DE TRABAJO",0 ,0, 'C');
	
		$pdf->Output('Bono-Zona.pdf','I');
	}
	
	//imprimir solicitud de traslado
	public function impSolTraslado($idSolicitud=FALSE) {
		$this->load->helper('rut');
		
		$datos_sol_traslado = $this->Etapa_model->getimpSolTraslado($idSolicitud);

		$dividirFecha = split('-', $datos_sol_traslado['p_fecha_efectiva']);
		
		$dia = $dividirFecha[0];
		$mes = $dividirFecha[1];
		$anio = $dividirFecha[2];
		
		switch($mes){
			case 'JAN':$mes = "Enero";break;
			case 'FEB':$mes = "Febrero";break;
			case 'MAR':$mes = "Marzo";break;
			case 'APR':$mes = "Abril";break;
			case 'MAY':$mes = "Mayo";break;
			case 'JUN':$mes = "Junio";break;
			case 'JUL':$mes = "Julio";break;
			case 'AUG':$mes = "Agosto";break;
			case 'SEP':$mes = "Septiembre";break;
			case 'OCT':$mes = "Octubre";break;
			case 'NOV':$mes = "Noviembre";break;
			case 'DEC':$mes = "Diciembre";break;
		}
		
		$p_desc_empresa = $datos_sol_traslado['p_desc_empresa'];
		$p_local_tienda = $datos_sol_traslado['p_local_tienda'];
		$p_direccion = $datos_sol_traslado['p_direccion'];
		$p_fecha_efectiva = $dia . ' de ' . $mes . ' 20' . $anio;
		$p_nombre = $datos_sol_traslado['p_nombre'];
		$p_rut = getPuntosRut ( $datos_sol_traslado['p_rut'] );
	
		$this->load->library('fpdf'); // Load FPDF
		$pdf = new FPDF('p','pt','A4');
		$pdf ->fontpath = APPPATH.'fonts/';
	
		$pdf->AddPage();
		$pdf->SetFont('Arial','U',12);
	
		$pdf->Cell (0,18 ,'CARTA SOLICITUD DE TRASLADO',0 ,0, 'C' );
	
		$pdf->SetFont('Arial','',12);
	
		$pdf->Ln(100);
	
		$pdf->Cell (0,18 ,'Srs: ('.$p_desc_empresa.')',0 ,0, 'L' );
	
		$pdf->Ln(20);
	
		$parrafo1 = '
			Me dirijo a ustedes,  para solicitar traslado de local / tienda en que actualmente prestos servicios, lo anterior por motivos personales.
		';
		$pdf->MultiCell(0, 14, utf8_decode($parrafo1), 0, "L");
	
		$parrafo2 = 'Asumiendo las condiciones de jornada y horarios  del local/tienda de destino.  Esto es;  local/tienda '.$p_local_tienda.', ubicado en '.$p_direccion.'.';
		$pdf->MultiCell(0, 14, utf8_decode($parrafo2), 0, "L");
	
		$pdf->Ln(20);
		$parrafo2 = 'Dicho traslado sería desde el '.$p_fecha_efectiva.' o en su defecto la fecha en que ustedes estimen conveniente.';
		$pdf->MultiCell(0, 15, utf8_decode($parrafo2), 0, "L");
	
		$pdf->Ln(10);
		$parrafo3 = 'Atenta a su respuesta y desde ya agradeciendo su ayuda, les saluda.';
		$pdf->MultiCell(0, 15, utf8_decode($parrafo3), 0, "L");
	
		$pdf->Ln(200);
		$pdf->MultiCell(0, 15, utf8_decode('Nombre: '.$p_nombre), 0, "L");
		$pdf->Ln(10);
		$pdf->MultiCell(0, 15, utf8_decode('CI: '.$p_rut), 0, "L");
		$pdf->Ln(10);
		$pdf->MultiCell(0, 15, utf8_decode('Firma: ____________________'), 0, "L");
		
		$pdf->SetFont('Arial','B',12);
		$pdf->Ln(80);
		$pdf->MultiCell(0, 15, 'Traslado '.$idSolicitud, 0, "L");
	
		//$pdf->Output()
		$pdf->Output('Solicitud-Traslado-Voluntario.pdf','I');
	}//fin impSolTraslado	

}