<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
error_reporting(0);
class Online extends CI_Controller
{

  private $_controlador;
  private $_metodo;
  private $_segmento;
  private $rut;
  private $mensaje;
  private $_respColab;
  private $_strRut;
  private $_strDiv;
  private $_usuario;
  private $_jerar;
  private $_tabla;
  private $_localPU;
  private $_localSB;
  private $_localMEDCELL;
  private $local;
  private $_array;
  private $_tipo;
  private $_tMSG;
  private $_codMSG;
  private $_estadoMSG;
  private $_obsMSG;
  private $_bdMSG;
  private $_pagina;
  private $_cod;
  private $_obs;
  private $_countDia;
  private $_jer_auxjefe;
  private $_cui;
  private $_nivel_cui;
  private $array;
  private $_mailsup;
  private $_maildep;
  private $_mail;
  private $_rutsuperior;
  private $_nombre;
  private $_nombreSup;
  private $_mailAlt;
  private $_mailAlt2;
  private $_MngMail;
  private $_MngMail2;
  private $_MailOnline;
  private $_Empresa;
  private $_Cargo;
  private $_RutOnlineRec;


  public function __construct()
  {
    parent::__construct();
    $url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
    $url = explode('/', $url);
    $url = array_filter($url);

    $this->_controlador = strtolower(array_shift($url));
    $this->_metodo      = strtolower(array_shift($url));
    $this->_argumentos  = $url;

    $rutaControlador    =  base_url($this->_controlador);
    $this->load->model('bd_colaborador');
    $this->load->model('bd_oracle');
    $this->load->model('smtp');
    $this->load->library('funciones');

    if (!$this->session->userdata('rut')) {
      redirect(base_url() . "indexcontrolador");
    }
  }

  /* public function is_empty($sentence){
            return empty($sentence) ? true : false;
      }
    
    
    public function validaOauth(){
    
        include_once APPPATH . "libraries/Client.php";

        if (!isset($_GET['code'])) {

            $this->authorizationUrl  = $provider->getAuthorizationUrl();
            $this->session->set_userdata('oauth2state', $provider->getState());
            redirect($this->authorizationUrl);
        } 

  }
    
    public function _remap($method, $params = array()){
       if (method_exists(__CLASS__, $method)) {
               $this->$method($params);
       } else {
               $this->test_default();
       }
    }
           
    public function test_default(){
          redirect(base_url());
    }
    
    public function index(){
                      
        if($this->_metodo==""){
           redirect(base_url());
        }        
    }  */


  public function buscarColab()
  {

    $array['lider'] = base_url() . 'online/formonline/';
    $array['url'] = base_url() . 'online/reconline/';
    $array['urlbaner'] = base_url() . 'asset/img/cortina4_1280x800px.jpg';
    $array['urlmas'] = base_url() . 'online/reconlinemas/';
    $this->load->view('header');
    $this->load->view('temp/busquedaonline', $array);
    $this->load->view('footer');
  }

  public function buscarOnline()
  {
    if (isset($_POST["nombre"])) {
      $this->_respColab = $_POST['nombre'];
    } else {
      redirect(base_url());
    }
    $json = $this->bd_colaborador->getBuscador($this->_respColab);

    if (count($json) > 0) {
      $this->_tabla = '<table width="100" class="table" border="0" cellspacing="0" cellpadding="0">';
      $this->_tabla .= '  <tr>';
      $this->_tabla .= '    <td><font class="fontTexto14">NOMBRE</font></td>';
      $this->_tabla .= '    <td><font class="fontTexto14">CENTRO COSTO</font></td>';
      $this->_tabla .= '    <td><font class="fontTexto14">EMPRESA</font></td>';
      $this->_tabla .= '  </tr>';
      foreach ($json as $row) {
        $this->_tabla .= '  <tr>';
        $this->_tabla .= '    <td><a href="javascript:void(0)"  onclick="javascript:ObtenerClase(' . ((($row->RUT + 12345678) * 36) - 87654321) . ');"><font class="fonttexto">' . $row->NOMBRE . '</font></a></td>';
        $this->_tabla .= '    <td><font class="fonttexto">' . $row->NOMBRE_UNIDAD . '</font></td>';
        $this->_tabla .= '    <td><font class="fonttexto">' . $row->EMPRESA . '</font></td>';
        $this->_tabla .= '  </tr>';
      }
      $this->_tabla .= '</table>';
    }


    echo $this->_tabla;
  }


  public function buscarOnlineLista()
  {
    if (isset($_POST["nombre"])) {
      $this->_respColab = $_POST['nombre'];
    } else {
      redirect(base_url());
    }

    $json = $this->bd_colaborador->getBuscadorOnline($this->_respColab);

    if (count($json) > 0) {

      $this->_tabla = '<table width="150" class="table" border="0" cellspacing="0" cellpadding="0">';
      $this->_tabla .= '  <tr>';
      $this->_tabla .= '    <td><font class="fontTexto14">NOMBRE RESPONSABLE</font></td>';
      $this->_tabla .= '    <td><font class="fontTexto14">NOMBRE RECONOCIDO</font></td>';
      $this->_tabla .= '    <td><font class="fontTexto14">MOTIVO</font></td>';
      $this->_tabla .= '    <td><font class="fontTexto14">FECHA</font></td>';
      $this->_tabla .= '  </tr>';
      foreach ($json as $row) {
        $this->_tabla .= '  <tr>';
        $this->_tabla .= '    <td><a href="javascript:void(0)"  onclick="javascript:strdiv(' . ((($row->ID + 12345678) * 36) - 87654321) . ');"><font class="fonttexto">' . $row->NOMBRE_SUP . '</font></a></td>';
        $this->_tabla .= '    <td><a href="javascript:void(0)"  onclick="javascript:strdiv(' . ((($row->ID + 12345678) * 36) - 87654321) . ');"><font class="fonttexto">' . $row->NOMBRE_DEP . '</font></a></td>';
        $this->_tabla .= '    <td><a href="javascript:void(0)"  onclick="javascript:strdiv(' . ((($row->ID + 12345678) * 36) - 87654321) . ');"><font class="fonttexto">' . $row->MOTIVO . '</font></a></td>';
        $this->_tabla .= '    <td><a href="javascript:void(0)"  onclick="javascript:strdiv(' . ((($row->ID + 12345678) * 36) - 87654321) . ');"><font class="fonttexto">' . $row->DDMMYYYY . '</font></a></td>';
        $this->_tabla .= '  </tr>';
      }
      $this->_tabla .= '</table>';
    } else {
      $this->_tabla = '<div style="width:500px;"><font class="fontTextoBold" style="color:#F00; font-weight:bold">No se encuentra informaci&oacute;n relacionada al tipo de busqueda</font></div>';
    }


    echo $this->_tabla;
  }

  public function pageformonline()
  {

    if (isset($_POST["opt"])) {
      $this->_strRut = $_POST['opt'];
    } else {
      redirect(base_url());
    }
    $this->_strRut = ((($this->_strRut + 87654321) / 36) - 12345678);

    $json = $this->bd_colaborador->getColaborador($this->_strRut);

    if (count($json) > 0) {

      $array['rut']    =  (($json['RUT'] * 36) + 12345678);
      $array['nombre'] = $json['NOMBRE'];

      $array['lider'] = base_url() . 'online/ingre/';
      $this->load->view('temp/formonline', $array);
    }
  }

  public function ingresoformulario()
  {

    $this->session->sess_destroy();
    if ($this->session->userdata('rut') == "") {
      $this->_session = false;
      $respuesta[]   =   array(
        'bdMSG'     => $this->_bdMSG,
        'estadoMSG' => $this->_estadoMSG,
        'obsMSG'    => $this->_obsMSG,
        'runMSG'    => $this->rut,
        'obsMail'   => $this->_MngMail,
        'obsMail2'  => $this->_MngMail2,
        'session'   => $this->_session
      );
      echo json_encode($respuesta);
      exit;
    } else {

      if (isset($_POST["dep"])) {

        $this->_RutOnlineRec   = $_POST['dep'];
        $this->_usuario  = $this->session->userdata('rut');
        //$this->_strDiv   = strtoupper($_POST['dig']);
        $this->_tipo     = $_POST['se'];
        $this->_cod      = '';
        $this->_obs      = $_POST['obs'];
        $this->_mailAlt  = $_POST['emailalt'];
        $this->_mailAlt2 = $_POST['emailalt2'];
        $this->_pagina   = "RECONOCIMIENTO ONLINE";
        $array           = array();

        $this->_RutOnlineRec   = (($this->_RutOnlineRec  - 12345678) / 36);


        /*if(!$this->funciones->ValidaRut($this->_usuario, $this->_strDiv)){
             $this->rut = "Rut Incorrecto";
          }else{*/

        if (is_numeric($this->_usuario)) {
          if (strlen($this->_usuario) < 7) {
            $this->rut = "Rut Incorrecto";
          } else {

            if ($this->_RutOnlineRec != $this->_usuario) {

              $this->_respColab = $this->bd_colaborador->getColaborador($this->_usuario);
              $this->_array     = (array) $this->_respColab;

              if (count($this->_respColab) > 0) {

                $this->_jerar = $this->bd_colaborador->buscarCUI($this->_usuario);
                if (count($this->_jerar) > 0) {
                  foreach ($this->_jerar as $row) {
                    $this->_jer_auxjefe = $row->JER_AUX_JEFE;
                    $this->_nivel_cui   = $row->JER_COD_NIVEL;
                    $this->_cui         = $row->UNIDAD;
                  }
                }

                if ($this->_nivel_cui > 0) {
                  //validamos que no sea dependientes directo a la persona a evaluar                            
                  $json = $this->bd_colaborador->buscarCUIDEP(
                    $this->_cui,
                    $this->_usuario
                  );
                  if (count($json) > 0) {
                    foreach ($json as $row) {
                      $array[] = $row->JER_RUT;
                    }
                  }

                  $json = $this->bd_colaborador->buscarCUIDEP2(
                    $this->_cui,
                    $this->_nivel_cui
                  );
                  if (count($json) > 0) {
                    foreach ($json as $row) {
                      $array[] = $row->JER_RUT;
                    }
                  }
                }

                if (count($array) == 0) {
                  //$this->rut = "Colaborador sin dependencia";
                } else {
                  for ($i = 0; $i < count($array); $i++) {
                    if ($array[$i] == $this->_RutOnlineRec) {
                      $this->_estadoMSG = "Usted no puede reconocer a un dependiente directo";
                      $i = count($array) + 1;
                    }
                  }
                }
              } else {
                $this->rut = "Colaborador no existe";
              }
            } else {
              $this->rut = "Rut ingresado corresponde al beneficiario";
            }
          }
        } else {
          $this->rut = "Rut Incorrecto";
        }
        //}



        if (strlen($this->_obs) > MAX_CARACT_COM) {
          $this->_obsMSG = 'El comentario supera el limite de lo permitido';
        }

        if ($this->_mailAlt != '') {
          if ($this->funciones->comprobar_email($this->_mailAlt) == 0) {
            $this->_MngMail = "El correo electr&oacute;nico introducido no es correcto.";
          }
        }

        if ($this->_mailAlt2 != '') {
          if ($this->funciones->comprobar_email($this->_mailAlt2) == 0) {
            $this->_MngMail2 = "El correo electr&oacute;nico introducido no es correcto.";
          }
        }

        if ($this->_obsMSG == '' && $this->rut == '' && $this->_estadoMSG == '' && $this->_MngMail2 == '' && $this->_MngMail == '') {

          $this->_countDia    = 0;
          $this->_cod         = '';
          $this->_rutsuperior = '';
          $this->_maildep     = '';
          $this->_mailsup     = '';



          $this->_bdMSG = $this->bd_colaborador->ingresoOnlineColaborador(
            $this->_usuario,
            $this->_RutOnlineRec,
            $this->_tipo,
            $this->_cod,
            $this->_obs,
            $this->_countDia,
            $this->_pagina,
            $this->_cui
          );




          if ($this->_bdMSG == true) {

            $this->_array = $this->bd_colaborador->buscarSuperiorMail($this->_RutOnlineRec);

            if (count($this->_array) > 0) {

              foreach ($this->_array as $row) {
                $this->_rutsuperior = $row->JEFE_DIRECTO;
                $this->_maildep     = $row->EMAIL;
              }
            }

            if ($this->_rutsuperior != "" && $this->_rutsuperior != "NO_DEF") {
              if ($this->_rutsuperior != $this->_RutOnlineRec) {
                $this->_array = $this->bd_colaborador->getSupMail($this->_rutsuperior);

                if (count($this->_array) > 0) {
                  foreach ($this->_array as $row) {
                    $this->_mailsup =   $row->MAE_C_EMAIL;
                  }
                }
              }
            }

            if ($this->_rutsuperior != "" && $this->_rutsuperior != "NO_DEF") {
              if ($this->_rutsuperior != $this->_RutOnlineRec) {
                $this->_array = $this->bd_colaborador->getSupMail($this->_usuario);

                if (count($this->_array) > 0) {
                  foreach ($this->_array as $row) {
                    $this->_mail =   $row->MAE_C_EMAIL;
                  }
                }
              }
            }

            $this->_array = $this->bd_colaborador->getTipo($this->_tipo);
            if (count($this->_array) > 0) {
              foreach ($this->_array as $row) {
                $this->_localSB = $row->PIN_DESC;
              }
            }

            $this->_array = $this->bd_colaborador->traeNombre($this->_RutOnlineRec);
            if (count($this->_array) > 0) {
              foreach ($this->_array as $row) {
                $this->_nombre = $row->NOMBRE;
              }
            }

            $this->_array = $this->bd_colaborador->traeNombre($this->_usuario);
            if (count($this->_array) > 0) {
              foreach ($this->_array as $row) {
                $this->_nombreSup = $row->NOMBRE;
              }
            }

            $this->_array = $this->bd_colaborador->getSupMail($this->_RutOnlineRec);
            if (count($this->_array) > 0) {
              foreach ($this->_array as $row) {
                $this->_MailOnline = $row->MAE_C_EMAIL;
              }
            }

            $this->_array = $this->bd_colaborador->getCargoMailOnline($this->_usuario);
            if (count($this->_array) > 0) {
              foreach ($this->_array as $row) {
                $this->_Empresa = $row->EMPRESA;
                $this->_Cargo   = $row->CARGO;
              }
            } else {
              $this->_Empresa = '';
              $this->_Cargo   = '';
            }
            $this->_localMEDCELL = $this->_obs;

            $this->_obs  = '<!doctype html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Simple Transactional Email</title>
    <style>
      /* -------------------------------------
          GLOBAL RESETS
      ------------------------------------- */
      img {
        border: none;
        -ms-interpolation-mode: bicubic;
        max-width: 100%; }

      body {
        background-color: #f6f6f6;
        font-family: sans-serif;
        -webkit-font-smoothing: antialiased;
        font-size: 14px;
        line-height: 1.4;
        margin: 0;
        padding: 0;
        -ms-text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%; }

      table {
        border-collapse: separate;
        mso-table-lspace: 0pt;
        mso-table-rspace: 0pt;
        width: 100%; }
        table td {
          font-family: sans-serif;
          font-size: 14px;
          vertical-align: top; }

      /* -------------------------------------
          BODY & CONTAINER
      ------------------------------------- */

      .body {
        background-color: #f6f6f6;
        width: 100%; }

      /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
      .container {
        display: block;
        /*Margin: 0 auto !important;
         makes it centered */
		width: 800px; 
        max-width: 800px;
        padding: 10px;
        width: 780px; }

      /* This should also be a block element, so that it will fill 100% of the .container */
      .content {
        box-sizing: border-box;
        display: block;
        Margin: 0 auto;
		width: 780px;
        max-width: 780px;
        padding: 10px; }

      /* -------------------------------------
          HEADER, FOOTER, MAIN
      ------------------------------------- */
      .main {
        background: #ffffff;
        border-radius: 3px;
        width: 100%; }

      .wrapper {
        box-sizing: border-box;
        padding: 20px; }

      .content-block {
        padding-bottom: 10px;
        padding-top: 10px;
      }

      .footer {
        clear: both;
        Margin-top: 10px;
        text-align: center;
        width: 100%; }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
          color: #999999;
          font-size: 12px;
          text-align: center; }

      /* -------------------------------------
          TYPOGRAPHY
      ------------------------------------- */
      h1,
      h2,
      h3,
      h4 {
        color: #000000;
        font-family: sans-serif;
        font-weight: 400;
        line-height: 1.4;
        margin: 0;
        Margin-bottom: 30px; }

      h1 {
        font-size: 35px;
        font-weight: 300;
        text-align: center;
        text-transform: capitalize; }

      p,
      ul,
      ol {
        font-family: sans-serif;
        font-size: 16px;
        font-weight: normal;
        margin: 0;
        Margin-bottom: 15px; }
        p li,
        ul li,
        ol li {
          list-style-position: inside;
          margin-left: 5px; }

      a {
        color: #3498db;
        text-decoration: underline; }

      /* -------------------------------------
          BUTTONS
      ------------------------------------- */
      .btn {
        box-sizing: border-box;
        width: 100%; }
        .btn > tbody > tr > td {
          padding-bottom: 15px; }
        .btn table {
          width: auto; }
        .btn table td {
          background-color: #ffffff;
          border-radius: 5px;
          text-align: center; }
        .btn a {
          background-color: #ffffff;
          border: solid 1px #3498db;
          border-radius: 5px;
          box-sizing: border-box;
          color: #3498db;
          cursor: pointer;
          display: inline-block;
          font-size: 14px;
          font-weight: bold;
          margin: 0;
          padding: 12px 25px;
          text-decoration: none;
          text-transform: capitalize; }

      .btn-primary table td {
        background-color: #3498db; }

      .btn-primary a {
        background-color: #3498db;
        border-color: #3498db;
        color: #ffffff; }

      /* -------------------------------------
          OTHER STYLES THAT MIGHT BE USEFUL
      ------------------------------------- */
      .last {
        margin-bottom: 0; }

      .first {
        margin-top: 0; }

      .align-center {
        text-align: center; }

      .align-right {
        text-align: right; }

      .align-left {
        text-align: left; }

      .clear {
        clear: both; }

      .mt0 {
        margin-top: 0; }

      .mb0 {
        margin-bottom: 0; }

      .preheader {
        color: transparent;
        display: none;
        height: 0;
        max-height: 0;
        max-width: 0;
        opacity: 0;
        overflow: hidden;
        mso-hide: all;
        visibility: hidden;
        width: 0; }

      .powered-by a {
        text-decoration: none; }

      hr {
        border: 0;
        border-bottom: 1px solid #f6f6f6;
        Margin: 20px 0; }

      /* -------------------------------------
          RESPONSIVE AND MOBILE FRIENDLY STYLES
      ------------------------------------- */
      @media only screen and (max-width: 620px) {
        table[class=body] h1 {
          font-size: 28px !important;
          margin-bottom: 10px !important; }
        table[class=body] p,
        table[class=body] ul,
        table[class=body] ol,
        table[class=body] td,
        table[class=body] span,
        table[class=body] a {
          font-size: 16px !important; }
        table[class=body] .wrapper,
        table[class=body] .article {
          padding: 10px !important; }
        table[class=body] .content {
          padding: 0 !important; }
        table[class=body] .container {
          padding: 0 !important;
          width: 100% !important; }
        table[class=body] .main {
          border-left-width: 0 !important;
          border-radius: 0 !important;
          border-right-width: 0 !important; }
        table[class=body] .btn table {
          width: 100% !important; }
        table[class=body] .btn a {
          width: 100% !important; }
        table[class=body] .img-responsive {
          height: auto !important;
          max-width: 100% !important;
          width: auto !important; }}

      /* -------------------------------------
          PRESERVE THESE STYLES IN THE HEAD
      ------------------------------------- */
      @media all {
        .ExternalClass {
          width: 100%; }
        .ExternalClass,
        .ExternalClass p,
        .ExternalClass span,
        .ExternalClass font,
        .ExternalClass td,
        .ExternalClass div {
          line-height: 100%; }
        .apple-link a {
          color: inherit !important;
          font-family: inherit !important;
          font-size: inherit !important;
          font-weight: inherit !important;
          line-height: inherit !important;
          text-decoration: none !important; }
        .btn-primary table td:hover {
          background-color: #34495e !important; }
        .btn-primary a:hover {
          background-color: #34495e !important;
          border-color: #34495e !important; } }

    </style>
  </head>
  <body class="">
    <table border="0" cellpadding="0" cellspacing="0" class="body" align="left">
      <tr>
        <td>&nbsp;</td>
        <td class="container" style="display: block;
        /*Margin: 0 auto !important;
         makes it centered */
		width: 800px; 
        max-width: 800px;
        padding: 10px;
        width: 780px; ">
          <div class="content">

            <!-- START CENTERED WHITE CONTAINER -->
            <table class="main">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <p>
							<img src="http://servicios.salcobrand.cl/bienestar/desa/Online.png"  align="left" class="img-responsive"/>
						</p>
					  </td>
					</tr>	
					<tr>	
					  <td>&nbsp;</td>
					</tr>
					<tr> 
					 <td>
						 <p><h3>Felicitaciones ' . $this->_nombre . '</h3></p>
                      </td>
                    </tr>
					
					<tr>	
					  <td align="justify">
						  <p>Has recibido un Reconocimiento Online por el atributo: <b> ' . $this->_localSB . '</b> y dice lo siguiente:<br></p>
					 </td>
					</tr>
					<tr>
						<td align="justify" style="border: 1px solid; padding: 6px;">
							<br>
							<p>' . $this->_localMEDCELL . '</p>
							<p style="font-style: italic;">' . $this->_nombreSup . ', ' . $this->_Cargo . ', ' . $this->_Empresa . '</p>
						</td>
					</tr>
					<tr>
						<td align="justify">
						<br>
							<p>El Equipo de Empresas SB me Reconoce se suma a este reconocimiento y te invita a seguir viviendo d&iacute;a a d&iacute;a nuestro Prop&oacute;sito Com&uacute;n "Hacemos que las personas se sientan bien, est&eacute;n donde est&eacute;n"<br></p>
						</td>
					</tr>
					<tr>
						<td align="justify">
							<p><br>
								<img src="http://servicios.salcobrand.cl/bienestar/desa/huincha-separador.png"  align="left" class="img-responsive"/>
							</p>
						</td>
					</tr>
					
					 <tr>
						<td align="justify">
							<p>¿Quieres revisar los reconocimientos online que has recibido  durante los &uacute;ltimos 6 meses? <br> Haz clic <a href="http://192.168.200.19:88/intranet/portalreconocimiento/online/reconlinemas ">ac&aacute;</a></p>
						</td>
					</tr>
					<tr>
						<td align="justify">
							<p><br><br>
								<img src="http://servicios.salcobrand.cl/bienestar/desa/bienestar-pg.jpg"  align="left" class="img-responsive"/>
							</p>
						</td>
					</tr>
                  </table>
                </td>
              </tr>

            <!-- END MAIN CONTENT AREA -->
            </table>


          <!-- END CENTERED WHITE CONTAINER -->
          </div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>';

            // $this->load->library('email');
            $smtp = new SMTP();
            $this->email = $smtp->get();
            $this->email->from("esbmereconoce@sb.cl", "Empresa SB me Reconoce");
            $this->email->to($this->_maildep);
            $this->email->cc($this->_mailsup . "," . $this->_mailAlt . "," . $this->_mailAlt2 . "," . $this->_MailOnline);
            $this->email->subject("Recibiste un Reconocimiento Online");
            $this->email->message($this->_obs);

            if (!$this->email->send()) {
              $this->_bdMSG = 'NO';
              $this->_estadoMSG = "No se pudo enviar el correo, por favor comunicarse con Intranet";
            }
          }
        } else {
          $this->_bdMSG = 'NO';
        }

        $respuesta[]   =   array(
          'bdMSG'     => $this->_bdMSG,
          'estadoMSG' => $this->_estadoMSG,
          'obsMSG'    => $this->_obsMSG,
          'runMSG'    => $this->rut,
          'obsMail'   => $this->_MngMail,
          'obsMail2'  => $this->_MngMail2
        );
      } else {
        $respuesta[]   =   array(
          'bdMSG'     => "dddddd",
          'estadoMSG' => "Ocurrio un error por favor comunicar con Intranet",
          'obsMSG'    => "",
          'runMSG'    => "",
          'obsMail'   => "",
          'obsMail2'  => ""
        );
      }
    }
    echo json_encode($respuesta);
  }

  public function reconline()
  {

    if (isset($_POST["x"])) {
      $this->_cod = $_POST['x'];
    } else {
      redirect(base_url());
    }

    $this->_cod = ((($this->_cod + 87654321) / 36) - 12345678);
    $json = $this->bd_oracle->getPIN_Colab($this->_cod);

    if (count($json) > 0) {
      foreach ($json as $row) {
        $array['id']       =  ((($row->ID + 12345678) * 36) - 87654321);
        $array['nombre']   = $row->NOMBRE;
        $array['apepater'] = $row->APEPATER;
        $array['apemater'] = $row->APEMATER;
        $array['fecha']    = $row->FECHA;
        $array['pindesc']  = $row->PIN_DESC;
        $array['session']  = $row->SECCION;
        $array['estado']   = $row->ESTADO;
        $array['obsup']    = $row->OBS_SUP;
        $array['obscolab'] = $row->OBS_COLAB;
        $this->rut         = $row->RUT_RESP;
      }

      $json = $this->bd_colaborador->getCargoMailOnline($this->rut);
      if (count($json) > 0) {
        foreach ($json as $row) {
          $array['UNI_K_CODUNIDAD']   = $row->UNI_K_CODUNIDAD;
          $array['NOMBRE_UNIDAD']     = $row->NOMBRE_UNIDAD;
          $array['EMPRESA']           = $row->EMPRESA;
          $array['CARGO']             = $row->CARGO;
        }
      }
      $array['lider'] = base_url() . 'tarjeta/ingredep/';
      $array['urlbaner'] = base_url() . '/asset/img/cortina4_1280x800px.jpg';
      $this->load->view('header');
      $this->load->view('temp/reconocimiento_online', $array);
      $this->load->view('footer');
    }
  }

  public function reconlinemas()
  {

    $array['lider']     = base_url() . 'tarjeta/ingredep/';
    $array['urlbaner']  = base_url() . '/asset/img/cortina4_1280x800px.jpg';
    $array['url']       = base_url() . 'online/reconline/';
    $this->load->view('header');
    $this->load->view('temp/onlinemas', $array);
    $this->load->view('footer');
  }
}
