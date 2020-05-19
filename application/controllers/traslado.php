<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Traslado extends CI_Controller {

	private $db_b;
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Traslado_model');
	}
	public function index()
	{
		//redirect(site_url() . 'traslado/solicitud');

		//$consulta = $this->db_b->query('SELECT * FROM netoffice.net_tickets');

	}
	
	public function solicitud()
	{
		if(!isset($_SESSION)){
			session_start();
		} 
		
		/*para pruebas test inicio*/
		if (!isset($_SESSION["rut"])) {
			redirect(site_url(), 'refresh');
		}
		/*para pruebas test fin*/
		
		//obtener contenido para sol_motivo
		$data['opSolMotivo'] = $this->Traslado_model->opcSolMot();
		$data['titlePage'] = "Solicitud Traslado";
		$data['correoLogueado'] = $this->Traslado_model->getCorreo($_SESSION['rut']);
		$data['correoLogueado'] = $data['correoLogueado'][0]->CORREO;
		$this->load->view("template/tpl2-sb/header",$data);
		$this->load->view("contenidos/solicitud_view");
		$this->load->view("template/tpl2-sb/footer");
		//$consulta = $this->db_b->query('SELECT * FROM netoffice.net_tickets');
	}
	
	//obtener centros de costo
	public function obtenerCC() 
	{
		if(!isset($_SESSION)){
			session_start();
		}
		
		$rut = $this->input->post("rut");
		$cod_empresa = $this->input->post("cod_empresa");
		
		$cod_flujo = $this->flujoBtnEmpresa($cod_empresa,$rut);
		
		$lst_cc = $this->Traslado_model->opcCCostoOrigen($rut, $cod_empresa, $cod_flujo);
		
		$lst_cc_json = json_encode($lst_cc);
		
		echo $lst_cc_json;
	}
	
	public function flujoBtnEmpresa($empresa,$rutLogueado)
	{
		$flujo = $this->Traslado_model->getFlujo($empresa,$rutLogueado);
		return $flujo[0]->FLUJO;
	}
		
	//imprime un array de tipo JSON con los rut
	public function rutSolTras()
	{
		$empresa = $this->input->post('empresa');
		$ccosto = $this->input->post('ccosto');
		$idflujo = $this->input->post('idflujo');
		$rut_logueado = $this->input->post('rut');
		/*
		$empresa = 11;
		$ccosto = 2;
		*/
		$getlstRuts = $this->Traslado_model->getrutSolTras($empresa, $ccosto, $idflujo, $rut_logueado);
		if (count($getlstRuts) > 0) {
			foreach ($getlstRuts as $valObj) {
				$colaborador["rut"][] = $valObj->EMP_K_RUTEMPLEAD;
				$colaborador["nombre"][] = $valObj->NOM_COMPLETO;
			}
			$colaborador = json_encode($colaborador);
			echo $colaborador;
		} else {
			echo '{"existendatos":"no"}';
		}
	}

	public function cCostoDest()
	{
		$empresa = $this->input->post('empresa');
		$ccosto = $this->input->post('ccosto');
		$getLstCCostoDest = $this->Traslado_model->getcCostoDest($empresa,$ccosto);
		$getLstCCostoDest = json_encode($getLstCCostoDest);
		echo $getLstCCostoDest;
	}
	
	public function pendiente()
	{
		if(!isset($_SESSION)){
			session_start();
		}
		
		/*para pruebas test inicio*/
		if (!isset($_SESSION["rut"])) {
			redirect(site_url(), 'refresh');
		}
		/*para pruebas test fin*/	

		//filtros
		$data['filtroRut'] = $this->Traslado_model->solTrasPendFiltroRut($_SESSION['rut']);		
		$data['filtroLocOri'] = $this->Traslado_model->solTrasPendFiltroLocOri($_SESSION['rut']);	
		$data['filtroLocDest'] = $this->Traslado_model->solTrasPendFiltroLocDest($_SESSION['rut']);		
		//filas: todos los registros
		$data['filas'] = $this->Traslado_model->solTrasPend($_SESSION['rut']);

		
		foreach ($data['filas'] as $index => $val) {
			//$descEstLarga[] = $this->Traslado_model->getdescEstLarga('TRAS',$val->ID_FLUJO, $val->ETAPA_ACTUAL);
			//echo $val->ID_FLUJO;
			//echo $val->ETAPA_ACTUAL;
			$descEstLarga[] = $this->Traslado_model->getdescEstLarga($val->ID_FLUJO, $val->ETAPA_ACTUAL);
		}
		
		@$data['descEstLarga'] = $descEstLarga;
		
		$data_header['titlePage'] = "Solicitudes Pendientes";
		$this->load->view("template/tpl2-sb/header",$data_header);
		$this->load->view("contenidos/solicitud_pendiente_view.php", $data);
		$this->load->view("template/tpl2-sb/footer");		
	}
	
	public function SolTrasPendID()
	{
		if(!isset($_SESSION)){
			session_start();
		}		
		$id = $_POST["id"];
		$miarreglo = $this->Traslado_model->getSolTrasPendID($id,$_SESSION['rut']);
		if ($miarreglo) {
			$miarreglo = json_encode($miarreglo);
		} else {
			$miarreglo = '{"registro" : false}';
		}
		echo $miarreglo;
	}	
	
	public function filtrarTriple()
	{
		if(!isset($_SESSION)){
			session_start();
		}		
		$rut = $this->input->post('rut', true);
		$loc_origen = $this->input->post('loc_origen', true);
		$loc_destino = $this->input->post('loc_destino', true);
		
		$arregloFilas = $this->Traslado_model->getfiltrarTriple($rut,$loc_origen,$loc_destino,$_SESSION['rut']);
		echo json_encode($arregloFilas);
	}

	public function historico()
	{
		if(!isset($_SESSION)){
			session_start();
		}
		
		/*para pruebas test inicio*/
		if (!isset($_SESSION["rut"])) {
			redirect(site_url(), 'refresh');
		}
		/*para pruebas test fin*/	
		
		//filtros
		$data['filtroRut'] = $this->Traslado_model->solTrasHistFiltroRut($_SESSION['rut']);
		/*
		echo "<pre>";
		var_dump($data['filtroRut']);
		echo "</pre>";
		die();
		*/
		$data['filtroLocOri'] = $this->Traslado_model->solTrasHistFiltroLocOri($_SESSION['rut']);
		$data['filtroLocDest'] = $this->Traslado_model->solTrasHistFiltroLocDest($_SESSION['rut']);
		//filas: todos los registros
		$data['filas'] = $this->Traslado_model->solTrasHist($_SESSION['rut']);
		
		
		foreach ($data['filas'] as $index => $val) {
			//$descEstLarga[] = $this->Traslado_model->getdescEstLarga('TRAS',$val->ID_FLUJO, $val->ETAPA_ACTUAL);
			//echo $val->ID_FLUJO;
			//echo $val->ETAPA_ACTUAL;
			$descEstLarga[] = $this->Traslado_model->getdescEstLarga($val->ID_FLUJO, $val->ETAPA_ACTUAL);
		}
		
		$data['descEstLarga'] = $descEstLarga;
		$data_header['titlePage'] = "Solicitud Historica";
		$this->load->view("template/tpl2-sb/header",$data_header);
		$this->load->view("contenidos/solicitud_historica_view", $data);
		$this->load->view("template/tpl2-sb/footer");
	}//fin funcion historico
	
	public function flujo()
	{
		$empresa = $this->input->post('empresa');
		$rutLogueado = $this->input->post('rutLogueado');
		$flujo = $this->Traslado_model->getFlujo($empresa,$rutLogueado);
		if (!$flujo[0]->FLUJO) {
			echo '{"id_de_flujo":"nulo"}';
		} else {
			echo $flujo[0]->FLUJO;		
		}
	}
	
	public function insertarTraslado()
	{
		$ccosto_origen = $this->input->post("p_origen");
		$ccosto_destino = $this->input->post("p_destino");
		$p_empresa = $this->input->post("p_empresa");
		
		$ccosto_origen_desc = $this->Traslado_model->get_ccosto_desc($p_empresa, $ccosto_origen);
		$ccosto_origen_desc = $ccosto_origen_desc[0]->CCOSTO_DESC;
		
		$ccosto_destino_desc = $this->Traslado_model->get_ccosto_desc($p_empresa, $ccosto_destino);
		$ccosto_destino_desc = $ccosto_destino_desc[0]->CCOSTO_DESC;
		
		$nombreColabTrasl = $this->Traslado_model->getNombre($this->input->post("p_colaborador"));
		$nombreColabTrasl = $nombreColabTrasl[0]->NOMBRE;
		
		
		
		
		$insertar = $this->Traslado_model->putinsertarTraslado($_POST);

		$this->load->library('email');
		/*
		if ($insertar[1] == "") {
			# code...

		}
		*/
		//para test dejar $to = MAILUSUARIOTEST; para produccion dejar $to = $insertar[2];
		$to = $insertar[2];
		//$to = MAILUSUARIOTEST;
		
		$this->email->from('workflow@sb.cl', 'Workflow');
		$this->email->to($to);
		$this->email->bcc(MAILUSUARIOTEST);
		$this->email->subject('Solicitud de traslado '.$insertar[0].' pendiente');

		$htmlContent = '<h2>Traslado Pendiente</h2>';
		$htmlContent .= '<p style="color:red;">Usted debe continuar con el siguiente Traslado.</p>';
		$htmlContent .= '<table class="table" border="1" align="center" cellpadding="4">';
		$htmlContent .= '	<thead>';
		$htmlContent .= '		<tr>';
		$htmlContent .= '			<th>ID</th>';
		$htmlContent .= '			<th>Colaborador</th>';
		$htmlContent .= '			<th>C. Costo origen</th>';
		$htmlContent .= '			<th>C. Costo destino</th>';
		$htmlContent .= '		</tr>';
		$htmlContent .= '	</thead>';
		$htmlContent .= '	<tbody>';
		$htmlContent .= '		<tr>';
		$htmlContent .= '			<td>'.$insertar[0].'</td>';
		$htmlContent .= '			<td>'.$nombreColabTrasl.'</td>';
		$htmlContent .= '			<td align="center">'.$ccosto_origen.' - '. $ccosto_origen_desc .'</td>';
		$htmlContent .= '			<td align="center">'.$ccosto_destino.' - '. $ccosto_destino_desc .'</td>';
		$htmlContent .= '		</tr>';
		$htmlContent .= '	</tbody>';
		$htmlContent .= '</table>';	
		$htmlContent .= '<p>';
		$htmlContent .= $insertar[4];
		$htmlContent .= '</p>';
		$htmlContent .= '<p>';		
		$htmlContent .= 'Debe ingresar al siguiente link <a href="http://huapi:88/intranet/portal/" target="_blank">http://huapi:88/intranet/portal/</a>';		
		$htmlContent .= '</p>';		
		$htmlContent .= '';		
		
		$this->email->message($htmlContent);
		if (!is_null($insertar[0])) {
			$this->email->send();
			echo json_encode($insertar);
		} 
	}
	
	public function trasAprobUlt12mes()
	{
		$rutColab = $this->input->post('rutColab');
		$resultado = $this->Traslado_model->getTrasAprobUlt12mes($rutColab);
		echo json_encode($resultado);
	}
	
	public function trasPendbUlt12mes()
	{
		$rutColab = $this->input->post('rutColab');
		$resultado = $this->Traslado_model->getTrasPendUlt12mes($rutColab);
		echo json_encode($resultado);
	}
	
	public function dotacionMaestra()
	{
		$p_empresa = 	$this->input->post('p_empresa');
		$ccosto = 		$this->input->post('ccosto');
		$p_cargo = 		$this->input->post('p_cargo');
		
		$resultado = $this->Traslado_model->getDotacionMaestra($p_empresa,$ccosto,$p_cargo);
		echo json_encode($resultado);
		
	}

	public function trasPendEgre()
	{
		$p_empresa = 	$this->input->post('p_empresa');
		$ccosto = 		$this->input->post('ccosto');
		$p_cargo = 		$this->input->post('p_cargo');
		
		$resultado = $this->Traslado_model->getTrasPendEgre($p_empresa,$ccosto,$p_cargo);
		echo json_encode($resultado);
		
	}

	public function trasPendIngre()
	{
		$p_empresa = 	$this->input->post('p_empresa');
		$ccosto = 		$this->input->post('ccosto');
		$p_cargo = 		$this->input->post('p_cargo');
		
		$resultado = $this->Traslado_model->getTrasPendIngre($p_empresa,$ccosto,$p_cargo);
		echo json_encode($resultado);
		
	}

	public function trasLic()
	{
		$p_empresa = 	$this->input->post('p_empresa');
		$ccosto = 		$this->input->post('ccosto');
		$p_cargo = 		$this->input->post('p_cargo');
		
		$resultado = $this->Traslado_model->getTrasLic($p_empresa,$ccosto,$p_cargo);
		echo json_encode($resultado);
	}

	public function trasPer()
	{
		$p_empresa = 	$this->input->post('p_empresa');
		$ccosto = 		$this->input->post('ccosto');
		$p_cargo = 		$this->input->post('p_cargo');
		
		$resultado = $this->Traslado_model->getTrasPer($p_empresa,$ccosto,$p_cargo);
		echo json_encode($resultado);
	}
	
	public function creaPdf() {
		$this->load->library('fpdf'); // Load FPDF
		$pdf = new FPDF('p','pt','A4');
		$pdf ->fontpath = APPPATH.'fonts/';
		
		$pdf->AddPage();
		$pdf->SetFont('Arial','B',12);
		
		$pdf->Cell(150, 16, "texto para el pdf");
		
		$pdf->Output();
	}
	
	public function impBonoZona() {
		
		$this->load->library('fpdf'); // Load FPDF
		$pdf = new FPDF('p','pt','A4');
		$pdf ->fontpath = APPPATH.'fonts/';
		
		$pdf->AddPage();
		$pdf->SetFont('Arial','',12);
		
		// Logo
		$image1 = base_url()."assets/template/tpl2-sb/img/logo_salcobrand108.gif";
		$pdf->Image($image1,450,38,81,81);

		// Salto de línea
		$pdf->Ln(188);
		
		$empresa = 'SALCOBRAND S.A.';
		$rut_empresa = '76.031.071-9';
		$nom_repres = 'Carlos González Correa';
		$nom_colab = 'MERCEDES LILIANA BRAVO CORREA';
		$rut_colab = '7.662.611-1';
		$cargo_colab = 'Jefe de Local Químico Farmacéutico';
		$bono = '100.000';
		$ciudad_emp = 'SANTIAGO';
		$fecha = '29 de OCTOBER de 2014';
		$text = '
			En '.$lugar.', a '.$fecha.'. entre la empresa '.$empresa.', Rut:  '.$rut_empresa.', representada por su sub.-Gerente de Gestión de Personas, '.$nom_repres.' en adelante "la Empresa" y don/a '.$nom_colab.', Rut:   '.$rut_colab.'  en adelante "el Trabajador", se ha convenido el siguiente anexo de su contrato de trabajo:

			PRIMERO:  	Las partes se encuentran vinculadas por un contrato de trabajo mediante el cual el trabajador(a) presta servicios en calidad de '.$cargo_colab.'. 
			SEGUNDO:  	Por el presente  instrumento se deja constancia que el trabajador(a) tendrá derecho a percibir un "Bono de zona" por la suma de $ '.$bono.' pesos. imponibles mensuales, sólo mientras presta sus servicios a la empresa en la ciudad de '.$ciudad_emp.'.
						En consecuencia, si el trabajador es trasladado a una ciudad distinta a prestar sus servicios, dicho bono se extinguirá ipso facto. 
			El presente anexo se firma en dos ejemplares, recibiendo uno de ellos el trabajador a su entera conformidad.
		';
		
		$pdf->MultiCell(0, 15, utf8_decode($text), 0, "L");
		
			$pdf->Ln(50);
			$pdf->SetXY(50, 500);
			$pdf->Multicell(200,5,utf8_decode('___________________________'));
			$pdf->SetXY(60, 524);
			$pdf->Multicell(150,5,utf8_decode('p.SALCOBRAND S.A..'));
			$pdf->SetXY(60, 550);
			$pdf->Multicell(150,5,utf8_decode('Rut:  76.031.071-9'));
			
			$pdf->SetXY(320, 528);
			$pdf->Multicell(150,5,utf8_decode('p. p. RUT: 76.031.071-9'));
			$pdf->SetXY(300, 500);
			$pdf->Multicell(200,5,utf8_decode('___________________________'));	
			$pdf->SetXY(320, 555);
			$pdf->Multicell(150,5,utf8_decode('7.662.611-1'));			
		
		//$pdf->Cell(0, 16, "ANEXO DE CONTRATO DE TRABAJO",0 ,0, 'C');
		
		$pdf->Output();
	}
	
	public function sacarpdf() {
		$mivar = "<p>fjsdñlf jñlkdjflñksdf <b>fsdfsd</b>fsdfsdfsdfdfwerw</p>";
		header("Content-type:application/pdf");
		
		// It will be called downloaded.pdf
		header("Content-Disposition:attachment;filename=downloaded.pdf");
		
		// The PDF source is in original.pdf
		readfile("original.pdf");		
		echo $mivar;
	}

	public function validarMailFlujo() {
		//valida que cuando ingrese un centro de costo y que tendra problema en alguna de las etapas no
		//permitira que ingrese la orden de traslado
		$nroCCosto = 	$this->input->post('nroCCosto');
		$codempresa = 		$this->input->post('codempresa');
		$flujo = 		$this->input->post('flujo');
		
		$resultado = $this->Traslado_model->getValidarMailFlujo( $nroCCosto, $codempresa,$flujo );
		//echo json_encode($resultado);		
		echo ($resultado[0]->CORREO);
	}	
	
	public function validarMailFlujoDest() {
		//valida que cuando ingrese un centro de costo y que tendra problema en alguna de las etapas no
		//permitira que ingrese la orden de traslado
		$nroCCosto = 	$this->input->post('nroCCosto');
		$codempresa = 		$this->input->post('codempresa');
		$flujo = 		$this->input->post('flujo');
	
		$resultado = $this->Traslado_model->getValidarMailFlujoDestino( $nroCCosto, $codempresa, $flujo);
		//echo json_encode($resultado);
		echo ($resultado[0]->CORREO);
	}	
	
	public function ctrlValidarCarenciaFecha () {
		$rut = $this->input->post("rut");
		$empresa = $this->input->post("empresa");
		$cargoDbnet = $this->input->post("cargoDbnet");
		$solicitud_fecha_cambio = $this->input->post("solicitud_fecha_cambio");
		$resultado = $this->Traslado_model->getCtrlValidarCarenciaFecha( $rut, $empresa, $cargoDbnet, $solicitud_fecha_cambio);
		echo ($resultado[0]->DIFERENCIAFECHAS);
	}
	
	public function enviaMailInvolugrados()
	{
	
		$this->load->library('email');
		/*
			if ($insertar[1] == "") {
			# code...
	
			}
		*/
		//para test dejar $to = MAILUSUARIOTEST; para produccion dejar $to = $insertar[2];
		$to = $this->input->post("correo");
		//$to = MAILUSUARIOTEST;
		
		$rutLogueado = 	$this->input->post("rut");
		$correoLogueado = $this->Traslado_model->getCorreo($rutLogueado);
		$nombreLogueado = $this->Traslado_model->getNombre($rutLogueado);
		$body = "<p>".$nombreLogueado[0]->NOMBRE." ".$correoLogueado[0]->CORREO." no pudo generar solicitud de traslado por el siguiente motivo.</p>";
		$body .= '<p style="color:#ff0000;font-weight:bold;">'.$this->input->post("body").'</p>';
		$body .= '<p>Por favor corregir e informar a '. $nombreLogueado[0]->NOMBRE. ' '. $correoLogueado[0]->CORREO .'</p>';
		$this->email->from('workflow@sb.cl', 'Workflow');
		$this->email->to($to);
		$this->email->bcc(MAILUSUARIOTEST);
		$this->email->subject('Problemas al generar solicitud de traslado');
		$this->email->message($body);
		if ($this->email->send()) {
			echo "mail enviado";
		}
	}

	public function genLstPendCsv()
	{
	    if(!isset($_SESSION)){
	        session_start();
	    }

	    /*para pruebas test inicio*/
	    if (!isset($_SESSION["rut"])) {
	        redirect(site_url(), 'refresh');
	    }

	    $data['filas'] = $this->Traslado_model->solTrasPend($_SESSION['rut']);
	    $nombre_archivo = "listado_traslados_pendientes.csv";

	    $salidahtml = '';
	    $salidahtml .=
        	    "NRO SOLICITUD" . ";" .
        	    "CREACION" . ";" .
        	    "CLIENTE" . ";" .
        	    "ORIGEN" . ";" .
        	    "DESCRIPCION" . ";" .
        	    "DESTINO" . ";" .
        	    "DESCRIPCION" . ";" .
        	    "COLABORADOR" . ";" .
        	    "CARGO" . ";" .
        	    "F CAMBIO" . ";" .
        	    "F SISTEMA" . ";" .
        	    "ESTADO" . ";" . "\r\n";

	    foreach ($data['filas'] as $item) {
            $salidahtml .=  $item->WF_KEY . ";".
                            $item->CREATION_DATE . ";".
                            utf8_decode($item->NOMBREBY) . ";".
                            $item->CC_ORIGEN . ";".
                            $item->DESCLOCORIG . ";".
                            $item->CC_DESTINO . ";".
                            $item->DESCLOCDEST . ";".
                            utf8_decode($item->COLABORADOR) . ";".
                            $item->DESC_CARGO . ";".
                            $item->FECHA_EFECTIVA_CAMBIO . ";".
                            $item->FECHA_DBNET . ";".
                            $item->ESTADO . ";". "\r\n";
	    }

	    header('Content-type: text/csv; charset=UTF-8');
	    header('Content-Disposition: attachment; filename="'.$nombre_archivo.'"');

	    echo ($salidahtml);
	}

	public function genLstFinCsv()
	{
	    if(!isset($_SESSION)){
	        session_start();
	    }

	    /*para pruebas test inicio*/
	    if (!isset($_SESSION["rut"])) {
	        redirect(site_url(), 'refresh');
	    }

	    $data['filas'] = $this->Traslado_model->solTrasHist($_SESSION['rut']);
	    $nombre_archivo = "listado_traslados_finalizados.csv";

	    $salidahtml = '';
	    $salidahtml .=
        	    "NRO SOLICITUD" . ";" .
        	    "CREACION" . ";" .
        	    "CLIENTE" . ";" .
        	    "ORIGEN" . ";" .
        	    "DESCRIPCION" . ";" .
        	    "DESTINO" . ";" .
        	    "DESCRIPCION" . ";" .
        	    "COLABORADOR" . ";" .
        	    "CARGO" . ";" .
        	    "F CAMBIO" . ";" .
        	    "F SISTEMA" . ";" .
        	    "ESTADO" . ";" . "\r\n";

	    foreach ($data['filas'] as $item) {
            $salidahtml .=  $item->WF_KEY . ";".
                            $item->CREATION_DATE . ";".
                            utf8_decode($item->NOMBREBY) . ";".
                            $item->CC_ORIGEN . ";".
                            $item->DESCLOCORIG . ";".
                            $item->CC_DESTINO . ";".
                            $item->DESCLOCDEST . ";".
                            utf8_decode($item->COLABORADOR) . ";".
                            $item->DESC_CARGO . ";".
                            $item->FECHA_EFECTIVA_CAMBIO . ";".
                            $item->FECHA_DBNET . ";".
                            $item->ESTADO . ";". "\r\n";
	    }

	    header('Content-type: text/csv; charset=UTF-8');
	    header('Content-Disposition: attachment; filename="'.$nombre_archivo.'"');

	    echo ($salidahtml);
	}
}