<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Tarjeta extends CI_Controller
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
  private $_arrayDep;
  //private $_jerar;
  //private $_localPU;
  //private $_localSB;  
  //private $_localMEDCELL; 
  private $_nom;
  private $local;
  private $_array;
  private $_cod;
  private $_obs;
  private $_tipo;
  private $_tMSG;
  private $_codMSG;
  private $_estadoMSG;
  private $_obsMSG;
  private $_bdMSG;
  private $_pagina;

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
    $this->load->library('funciones');

    if (!$this->session->userdata('rut')) {
      redirect(base_url() . "indexcontrolador");
    }
  }
  /*
      public function is_empty($sentence){
            return empty($sentence) ? true : false;
      }
*/
  /*public function validaOauth(){
    
        include_once APPPATH . "libraries/Client.php";

        if (!isset($_GET['code'])) {

            $this->authorizationUrl  = $provider->getAuthorizationUrl();
            $this->session->set_userdata('oauth2state', $provider->getState());
            redirect($this->authorizationUrl);
        } 

  }
    */
  /*public function _remap($method, $params = array()){
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

  public function selectLoad()
  {
    $this->_array = $this->bd_oracle->getTipoping();
    $local = '<option value="" selected disabled>Seleccione...</option>';
    if (count($this->_array) > 0) {
      foreach ($this->_array as $row) {
        $local .= '<option value="' . $row->ID_PIN . '">' . $row->PIN_DESC . '</option>';
      }
    }

    print_r($local);
    exit;
  }

  public function selectLoadTIPO()
  {
    $this->_array = $this->bd_oracle->getTipoping2();
    $local = '<option value="" selected disabled>Seleccione...</option>';
    if (count($this->_array) > 0) {
      foreach ($this->_array as $row) {
        $local .= '<option value="' . $row->ID_PIN . '">' . $row->PIN_DESC . '</option>';
      }
    }

    print_r($local);
    exit;
  }

  public function pagelogin()
  {
    $array['lider'] = base_url() . 'tarjeta/pagecolab/';
    $array['urlbaner'] = base_url() . '/asset/img/cortina3_1280x800px.jpg';
    $this->load->view('header');
    $this->load->view('temp/ingresorutcolab', $array);
    $this->load->view('footer');
  }

  public function pagecolab()
  {


    $this->_strRut = $this->session->userdata('rut');

    $array['rut']    =  $this->_strRut;
    $array['nombre'] =  $this->session->userdata('name');
    $array['lider']  = base_url() . 'tarjeta/pageformonline/';

    $array['urlbaner'] = base_url() . '/asset/img/cortina3_1280x800px.jpg';


    $this->_array = $this->bd_oracle->getDetalleColab($this->_strRut);
    $this->_nom[]   = $this->bd_colaborador->getColaborador($this->_strRut);
    if (count($this->_nom) > 0) {
      foreach ($this->_nom as $row) {
        $this->_usuario = $row['NOMBRE'];
      }
    } else {
      $this->_usuario = "";
    }
    $array['nombre'] = $this->_usuario;
    $array['datos'] = $this->_array;
    $this->load->view('header');
    $this->load->view('temp/pagetarjetacolab', $array);
    $this->load->view('footer');
  }

  public function principal()
  {

    $array['colab']    = base_url() . 'tarjeta/pagecolab/';
    $array['lider']    = base_url() . 'tarjeta/pageFormulario/';
    $array['urlbaner'] = base_url() . '/asset/img/cortina3_1280x800px.jpg';

    $this->load->view('header');
    $this->load->view('temp/ingresopin', $array);
    $this->load->view('footer');
  }

  public function pageFormulario()
  {

    $this->_strRut = $this->session->userdata('rut');
    $this->_respColab = $this->bd_colaborador->getColaborador($this->_strRut);

    if (count($this->_respColab) > 0) {
      foreach ($this->_respColab as  $valor) {
        $arregloColab[] = $valor;
      }
    }

    $array['prueba'] = array(
      'rut'      => $this->rut,
      'colab'    => $arregloColab,
      'strRut'   => $this->rut,
      'ruta'     => base_url() . 'tarjeta/pagetarjeta/',
      'ruta_sup' => base_url() . 'tarjeta/pageformsup/',
      'urlbaner' => base_url() . '/asset/img/cortina3_1280x800px.jpg'
    );

    $this->load->view('header');
    $this->load->view('temp/pinreconocimiento', $array);
    $this->load->view('footer');
  }


  public function pageingre()
  {

    $array['lider']    = base_url() . 'tarjeta/pagelider/';
    $array['urlbaner'] = base_url() . '/asset/img/cortina3_1280x800px.jpg';

    $this->load->view('header');
    $this->load->view('temp/ingresorut', $array);
    $this->load->view('footer');
  }


  public function pagetarjeta()
  {

    if (isset($_POST["hiddenField"])) {
      $this->_respColab = $_POST['idnom'];
      $this->_strRut    = $_POST['hiddenField'];
      $this->_usuario   = $_POST['idUSuario'];
    } else {
      redirect(base_url() . 'tarjeta/pageingre/');
    }

    $arr = explode('-', $this->_usuario);
    $array['sup'] = $arr[0];


    $json[] = $this->bd_colaborador->getColaborador($this->_strRut);

    if (count($json) > 0) {
      foreach ($json as $row) {
        $array['rut']    =  (($row['RUT'] * 36) + 12345678);
        $array['nombre'] = $row['NOMBRE'];
      }
      $array['lider'] = base_url() . 'tarjeta/pagelider/';
      $array['urlbaner'] = base_url() . '/asset/img/cortina3_1280x800px.jpg';

      $this->load->view('header');
      $this->load->view('temp/pagetarjeta', $array);
      $this->load->view('footer');
    } else {
    }
  }


  public function pagelider()
  {

    if (isset($_POST["RUT"])) {
      $this->_strRut = $_POST['RUT'];
      $this->_strDiv = strtoupper($_POST['DIG']);
    } else {
      redirect(base_url() . 'tarjeta/pageingre/');
    }

    if (!$this->funciones->ValidaRut($this->_strRut, $this->_strDiv)) {
      $this->rut = "Rut Incorrecto";
    } else {
      if (is_numeric($this->_strRut)) {
        if (strlen($this->_strRut) < 7) {
          $this->rut = "Rut Incorrecto";
        } else {
          $this->_respColab = $this->bd_colaborador->getColaborador($this->_strRut);

          if (count($this->_respColab) > 0) {
            foreach ($this->_respColab as  $valor) {
              $arregloColab[] = $valor;
            }
          } else {
            $this->rut = "Colaborador no existe";
          }
        }
      } else {
        $this->rut = "Rut Incorrecto";
      }
    }
    $array['prueba'] = array(
      'rut'      => $this->rut,
      'colab'    => $arregloColab,
      'div'      => $_POST['DIG'],
      'strRut'   => $_POST['RUT'],
      'ruta'     => base_url() . 'tarjeta/pagetarjeta/',
      'ruta_sup' => base_url() . 'tarjeta/pageformsup/',
      'urlbaner' => base_url() . '/asset/img/cortina3_1280x800px.jpg'
    );

    $this->load->view('header');
    $this->load->view('temp/pinreconocimiento', $array);
    $this->load->view('footer');
  }


  public function ingresoformulario()
  {
    if (isset($_POST["dep"])) {
      $this->_strRut  = $_POST['dep'];
      $this->_usuario = $_POST['su'];
      $this->_tipo    = $_POST['se'];
      $this->_cod     = 'T' . trim(strtoupper($_POST['cod']));
      $this->_obs     = $_POST['obs'];

      $this->_usuario  = (($this->_usuario - 12345678) / 36);
      $this->_strRut   = (($this->_strRut  - 12345678) / 36);


      if (strlen($this->_obs) > MAX_CARACT_COM) {
        $this->_obsMSG = 'El comentario supera el limite de lo permitido';
      }

      $this->_jerar = $this->bd_colaborador->buscarCUI($this->_usuario);
      if (count($this->_jerar) > 0) {
        foreach ($this->_jerar as $row) {
          $this->_jer_auxjefe = $row->JER_AUX_JEFE;
          $this->_nivel_cui   = $row->JER_COD_NIVEL;
          $this->_cui         = $row->UNIDAD;
        }
      }

      $this->_array    = $this->bd_colaborador->validaTarjetaCui($this->_usuario, $this->_tipo, $this->_cod, $this->_cui);

      if (count($this->_array) > 0) {

        foreach ($this->_array as $row) {
          $estado = $row->ESTADO;
        }
        if (!is_null($estado)) {
          $this->_estadoMSG = 'Este c&oacute;digo ya fue utilizado';
        }
      } else {
        $this->_codMSG = "N&uacute;mero de tarjeta no est&aacute; asignado a su &aacute;rea";
      }

      if ($this->_estadoMSG == '' && $this->_codMSG == '' && $this->_obsMSG == '') {

        $this->_countDia = 0;
        $this->_pagina = "TARJETA RECONOCIMIENTO";
        $this->_bdMSG = $this->bd_colaborador->ingresoTARJETAColaborador(
          $this->_usuario,
          $this->_strRut,
          $this->_tipo,
          $this->_cod,
          $this->_obs,
          $this->_countDia,
          $this->_pagina,
          $this->_cui
        );
      } else {
        $this->_bdMSG = 'NO';
      }

      $respuesta[]   =   array(
        'bdMSG'     => $this->_bdMSG,
        'estadoMSG' => $this->_estadoMSG,
        'codMSG'    => $this->_codMSG,
        'obsMSG'    => $this->_obsMSG
      );
      echo json_encode($respuesta);
    }
  }


  public function pageformonline()
  {

    if (isset($_POST["x"])) {
      $this->_cod = $_POST['x'];
    } else {
      redirect(base_url());
    }


    $this->_cod = ((($this->_cod + 87654321) / 36) - 12345678);
    $json = $this->bd_oracle->getPINColaborador($this->_cod);

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
      $array['urlbaner'] = base_url() . '/asset/img/cortina3_1280x800px.jpg';
      $this->load->view('header');
      $this->load->view('temp/formtarjetadep', $array);
      $this->load->view('footer');
    }
  }


  public function ingresoformulariocolab()
  {
    if (isset($_POST["cod"])) {
      $this->_cod     = $_POST['cod'];


      if ($this->_cod == "") {
        $this->_codMSG = 'Seleccione un codigo';
      } else {
        $this->_cod = ((($this->_cod + 87654321) / 36) - 12345678);
      }


      if ($this->_codMSG == '') {

        $diaArr = $this->bd_oracle->getUpdatePINColaborador($this->_cod);

        if ($diaArr) {
          $this->_bdMSG = true;
        } else {
          $this->_bdMSG = false;
        }
      }
    } else {
      $this->_bdMSG = false;
    }
    $respuesta[]   =   array(
      'bdMSG'     => $this->_bdMSG,
      'estadoMSG' => $this->_estadoMSG,
      'codMSG'    => $this->_codMSG,
      'obsMSG'    => $this->_obsMSG
    );
    echo json_encode($respuesta);
  }

  public function muestraColab()
  {

    if (isset($_POST["x"])) {
      $this->_cod = $_POST['x'];
    } else {
      redirect(base_url());
    }


    $this->_cod = ((($this->_cod + 87654321) / 36) - 12345678);
    $json = $this->bd_oracle->getDetalleSUPColab($this->_cod);

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
      $array['urlbaner'] = base_url() . '/asset/img/cortina3_1280x800px.jpg';
      $this->load->view('header');
      $this->load->view('temp/detallesuperiorcolab', $array);
      $this->load->view('footer');
    }
  }

  public function pageformsup()
  {
    if (isset($_POST["idUSuario"])) {
      $this->_strRut = $_POST['idUSuario'];
    } else {
      redirect(base_url() . 'tarjeta/principal/');
    }

    $this->_strRut = (($this->_strRut - 12345678) / 36);
    $jsonAno       = $this->bd_oracle->getResponsablesAnual($this->_strRut);

    if (count($jsonAno) > 0) {
      foreach ($jsonAno as $row) {
        $ano  = $this->bd_oracle->getResponsablesPorAno($this->_strRut, $row->ANO);
        if (count($ano) > 0) {
          $json[$row->ANO] = $ano;
        }
      }
    }

    $_arrayDep     = $this->buscarColaboradorDep($this->_strRut); //13241374
    $_arrayDep     = $this->bd_oracle->getDependienteResponsables($_arrayDep);

    $array = array(
      'rut'      => $this->_strRut,
      'ruta'     => base_url() . 'tarjeta/muestraColab/',
      'urlbaner' => base_url() . 'asset/img/cortina3_1280x800px.jpg',
      'datos'    => $json,
      'rutDep'   => $_arrayDep,
      'ano'      => $jsonAno
    );

    $this->load->view('header');
    $this->load->view('temp/registro_sup', $array);
    $this->load->view('footer');
  }

  public function buscarColaboradorDep($idRut)
  {
    $this->_i = 0;
    $this->_jerar = $this->bd_colaborador->buscarCUI($idRut);

    if (count($this->_jerar) > 0) {
      foreach ($this->_jerar as $row) {
        $this->_jer_auxjefe = $row->JER_AUX_JEFE;
        $this->_nivel_cui   = $row->JER_COD_NIVEL;
        $this->_cui         = $row->UNIDAD;
      }
    }

    $json = $this->bd_colaborador->traerDependientesRutSUP($this->_cui);
    if (count($json) > 0) {
      foreach ($json as $row) {
        if ($this->_i == 0) {
          $this->_arrayDep = $row->RUT;
        } else {
          $this->_arrayDep .= "," . $row->RUT;
        }
        $this->_i++;
      }
    }


    $json = $this->bd_colaborador->traerRutDependientes($this->_cui, $this->_nivel_cui);

    if (count($json) > 0) {
      foreach ($json as $row) {
        if ($this->_i == 0) {
          $this->_arrayDep = $row->RUT;
        } else {
          $this->_arrayDep .= "," . $row->RUT;
        }
        $this->_i++;
      }
    }

    return $this->_arrayDep;
  }
}
