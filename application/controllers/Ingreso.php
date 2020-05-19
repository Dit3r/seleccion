<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ingreso extends CI_Controller
{

  private $_controlador;
  private $_metodo;
  private $_segmento;
  private $rut;
  private $mensaje;
  private $_arrayDep;
  private $_respColab;
  private $_strRut;
  private $_strDiv;
  private $_usuario;
  private $_jerar;
  private $_jerarDEP;
  private $_jerarDEP2;
  private $_localPU;
  private $_localSB;
  private $_localMEDCELL;
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
  private $_session = true;
  private $_pagina;
  private $_asunto;
  private $_mail;
  private $_nom;
  private $_RUTMAIL;
  private $_DIGMAIL;
  private $_NOMBREMAIL;
  private $_EMPMAIL;
  private $_jer_auxjefe;
  private $_cui;
  private $_nivel_cui;
  private $cont;
  private $_contdia;
  private $_nombre;
  private $_i;
  private $_ccosto;
  private $json;
  private $incBD;



  public function __construct()
  {

    parent::__construct();
    //$url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
    //$url = explode('/', $url);
    //$url = array_filter($url);

    //$this->_controlador = strtolower(array_shift($url));
    //$this->_metodo      = strtolower(array_shift($url));
    //$this->_argumentos  = $url;

    //$rutaControlador    =  base_url($this->_controlador);  
    $this->load->model('bd_colaborador');
    $this->load->model('bd_oracle');
    $this->load->model('smtp');
    $this->load->library('funciones');

    $this->load->library('session');
    $this->load->helper('url');

    //$this->session->sess_destroy();
    /*if ($this->is_empty($this->session->userdata('session_state'))) {
            $this->validaOauth();
      } */

    // $this->load->library('email');


    if (!$this->session->userdata('rut')) {
      redirect(base_url() . "indexcontrolador");
    }
  }

  public function is_empty($sentence)
  {
    return empty($sentence) ? true : false;
  }

  /*public function validaOauth(){
    
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

  public function principal($mensaje = '')
  {
    $array['mensaje']  = $mensaje;
    $array['colab']    = base_url() . 'ingreso/pagecolab/';
    $array['lider']    = base_url() . 'ingreso/pageFormulario/';
    $array['urlbaner'] = base_url() . 'asset/img/cortina2_1280x800px.jpg';
    $this->load->view('header');
    $this->load->view('temp/ingresopin', $array);
    $this->load->view('footer');
  }


  public function pagelogin()
  {
    $array['lider'] = base_url() . 'ingreso/pagecolab/';
    $array['urlbaner'] = base_url() . 'asset/img/cortina2_1280x800px.jpg';
    $this->load->view('header');
    $this->load->view('temp/ingresorutcolab', $array);
    $this->load->view('footer');
  }

  public function pagecolab()
  {

    //echo $this->session->userdata('rut');
    //exit;
    $this->_strRut     = $this->session->userdata('rut');

    $array['rut']      = $this->_strRut;
    $array['nombre']   = $this->session->userdata('name');
    $array['lider']    = base_url() . 'ingreso/pageformulariocolab/';
    $array['urlbaner'] = base_url() . 'asset/img/cortina2_1280x800px.jpg';

    $this->_array = $this->bd_oracle->getDetalleColab($this->_strRut);
    $this->_nom[]   = $this->bd_colaborador->getColaborador($this->_strRut);

    if (count($this->_nom) > 0) {
      foreach ($this->_nom as $row) {
        $this->_usuario = $row['NOMBRE'];
      }
    } else {
      $this->_usuario = "";
    }

    $array['datos']  = $this->_array;
    $array['nombre'] = $this->_usuario;

    $this->load->view('header');
    $this->load->view('temp/pagecolab', $array);
    $this->load->view('footer');
  }

  public function pageingre()
  {

    $array['lider']     = base_url() . 'ingreso/pagelider/';
    $array['urlbaner'] = base_url() . '/asset/img/cortina2_1280x800px.jpg';

    $this->load->view('header');
    $this->load->view('temp/ingresorut', $array);
    $this->load->view('footer');
  }

  public function contacto()
  {

    $array['urlbaner'] = base_url() . '/asset/img/contactanos_1280x800px.jpg';

    $this->load->view('header');
    $this->load->view('temp/contacto', $array);
    $this->load->view('footer');
  }

  public function pageformulariocolab()
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

      $array['lider'] = base_url() . 'ingreso/ingredep/';
      $array['urlbaner'] = base_url() . '/asset/img/cortina2_1280x800px.jpg';
      $this->load->view('header');
      $this->load->view('temp/formpindep', $array);
      $this->load->view('footer');
    }
  }


  public function pageform()
  {

    if (isset($_POST["hiddenField"])) {
      $this->_respColab = $_POST['idnom'];
      $this->_strRut    = $_POST['hiddenField'];
      $this->_usuario   = $_POST['idUSuario'];
    } else {
      redirect(base_url() . 'ingreso/pageingre/');
    }

    $arr = explode('-', $this->_usuario);
    $array['sup'] = $arr[0];


    $json[] =  $this->bd_colaborador->getColaborador($this->_strRut);

    if (count($json) > 0) {
      foreach ($json as $row) {
        $array['rut']    =  (($row['RUT'] * 36) + 12345678);
        $array['nombre'] = $row['NOMBRE'];
      }

      $array['lider'] = base_url() . 'ingreso/pagelider/';
      $this->load->view('header');
      $this->load->view('temp/pageform', $array);
      $this->load->view('footer');
    } else {
    }
  }

  /**
   * validamos el colaborador si existe como dependiente
   */


  public function validaColaborador()
  {
    if (isset($_POST["nombre"])) {
      $this->_respColab = $_POST['nombre'];
      $this->_strRut    = $_POST['rut'];
      $this->_usuario   = $_POST['strSup'];
    } else {
      redirect(base_url() . 'ingreso/pageingre/');
    }


    $arr = explode('-', $this->_usuario);
    $arr[0] = (($arr[0] - 12345678) / 36);

    $this->_jerar = $this->bd_colaborador->buscarCUI($arr[0]);

    if (count($this->_jerar) > 0) {
      foreach ($this->_jerar as $row) {
        $this->_jer_auxjefe = $row->JER_AUX_JEFE;
        $this->_nivel_cui   = $row->JER_COD_NIVEL;
        $this->_cui         = $row->UNIDAD;
      }
    }

    $json = $this->bd_colaborador->traerDependientesSUP(
      $this->_cui,
      $this->_strRut
    );

    if (count($json) == 0) {
      $json = $this->bd_colaborador->traerDependientes(
        $this->_cui,
        $this->_strRut
      );
    }

    $respuesta[] = array('salida' => $json);

    echo json_encode($respuesta);
    exit;
  }



  /**
   * buscamos el nombre del colaborador
   */
  public function buscarColab()
  {
    if (isset($_POST["idNombre"])) {
      $this->_respColab = $_POST['idNombre'];
      $this->_usuario   = $_POST['idColab'];
    } else {
      redirect(base_url() . 'ingreso/pageingre/');
    }

    $array = array();
    $arr = explode('-', $this->_usuario);
    $arr[0] = (($arr[0] - 12345678) / 36);

    $this->_jerar = $this->bd_colaborador->buscarCUI($arr[0]);

    if (count($this->_jerar) > 0) {
      foreach ($this->_jerar as $row) {
        $this->_jer_auxjefe = $row->JER_AUX_JEFE;
        $this->_nivel_cui   = $row->JER_COD_NIVEL;
        $this->_cui         = $row->UNIDAD;
      }
    }

    $this->_ccosto = $this->bd_colaborador->getCCosto($arr[0]);

    $json = $this->bd_colaborador->traerDependientesNombreSUP(
      $arr[0],
      $this->_respColab
    );

    if (count($json) > 0) {
      foreach ($json as $row) {
        $array[] = array('value' => $row->RUT, 'label' => $row->NOMBRE);
        $this->incBD .=   $row->RUT . ',';
      }

      $this->incBD = substr($this->incBD, 0, strlen($this->incBD) - 1);
    }


    $json = $this->bd_colaborador->traerNombreDependientes(
      $this->_cui,
      $this->_respColab,
      $this->_nivel_cui,
      $this->incBD
    );

    if (count($json) > 0) {
      foreach ($json as $row) {
        $array[] = array('value' => $row->RUT, 'label' => $row->NOMBRE);
      }
    }

    echo json_encode($array);
  }

  public function buscarColab2()
  {
    if (isset($_POST["idNombre"])) {
      $this->_respColab = $_POST['idNombre'];
      $this->_usuario   = $_POST['idColab'];
    } else {
      redirect(base_url() . 'ingreso/pageingre/');
    }

    $arr = explode('-', $this->_usuario);
    $arr[0] = (($arr[0] - 12345678) / 36);

    if ($arr[1] == '05P012' || $arr[1] == '05P013' || $arr[1] == '12P005'  || $arr[1] == '05P016' || $arr[1] == '07P003') {
      $local = $this->bd_colaborador->getCCosto($arr[0]);
    } else {
      $local = false;
    }
    $this->_jerar        = $this->bd_colaborador->buscarDepCodjerar($arr[1]);
    $this->_localPU      = $this->bd_colaborador->buscarLocalesPU($arr[0]);
    $this->_localSB      = $this->bd_colaborador->buscarLocalesSB($arr[0]);
    $this->_localMEDCELL = $this->bd_colaborador->buscarLocalesMEDCELL($arr[0]);


    $json              = $this->bd_colaborador->getJerarquiaDEP($this->_respColab, $this->_jerar, $this->_localPU, $this->_localSB, $this->_localMEDCELL, $local);

    echo json_encode($json);
  }



  public function pageformsup()
  {
    if (isset($_POST["idUSuario"])) {
      $this->_strRut = $_POST['idUSuario'];
    } else {
      redirect(base_url() . 'ingreso/pageFormulario/');
    }

    $this->_strRut = (($this->_strRut - 12345678) / 36);
    $jsonAno       = $this->bd_oracle->getResponsablesAnual($this->_strRut);
    $json          = array();

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
      'ruta'     => base_url() . 'ingreso/muestraColab/',
      'urlbaner' => base_url() . 'asset/img/cortina2_1280x800px.jpg',
      'datos'    => $json,
      'rutDep'   => $_arrayDep,
      'ano'      => $jsonAno
    );

    $this->load->view('header');
    $this->load->view('temp/registro_sup', $array);
    $this->load->view('footer');
  }

  //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
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


      $array['lider']    = base_url() . 'ingreso/ingredep/';
      $array['urlbaner'] = base_url() . '/asset/img/PORTADAINGRESOTARJETAWEB-NUESTRO-VIAJE-601x251px.gif';

      $this->load->view('header');
      $this->load->view('temp/detallesuperiorcolab', $array);
      $this->load->view('footer');
    } else {
      $array['lider']    = base_url() . 'ingreso/ingredep/';
      $array['urlbaner'] = base_url() . '/asset/img/PORTADAINGRESOTARJETAWEB-NUESTRO-VIAJE-601x251px.gif';

      $this->load->view('header');
      $this->load->view('temp/detallesuperiorcolab', $array);
      $this->load->view('footer');
    }
  }



  public function pagelider()
  {

    $this->_strRut = $this->session->userdata('rut');
    $superior = $this->validaColaboradorSuperior($this->_strRut);

    echo json_encode($superior);
  }

  public function pageFormulario()
  {

    /*if ($this->session->userdata('rut')!="") {      
          $this->_strRut = $this->session->userdata('rut');

          $array['colab']    = base_url().'ingreso/pagelogin/';
          $array['lider']    = base_url().'ingreso/pagelider/';
          $array['urlbaner'] = base_url().'asset/img/cortina2_1280x800px.jpg';  
          $this->load->view('header');
          $this->load->view('temp/ingresopin',$array);
          $this->load->view('footer');
        exit;
      }  
    */
    $this->_strRut    = $this->session->userdata('rut');
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
      'ruta'     => base_url() . 'ingreso/pageform/',
      'ruta_sup' => base_url() . 'ingreso/pageformsup/',
      'urlbaner' => base_url() . 'asset/img/cortina2_1280x800px.jpg'
    );

    $this->load->view('header');
    $this->load->view('temp/pinreconocimiento', $array);
    $this->load->view('footer');
  }

  public function validaColab()
  {

    if (isset($_POST["strRut"])) {
      $this->_strRut  = $_POST['strRut'];
      $strDiv         = strtoupper($_POST['dv']);
    } else {
      redirect(base_url() . 'ingreso/principal/');
    }



    if (!$this->funciones->ValidaRut($this->_strRut, $strDiv)) {
      $this->rut = "Rut Incorrecto";
    } else {
      if (is_numeric($this->_strRut)) {
        if (strlen($this->_strRut) < 7) {
          $this->rut = "Rut Incorrecto";
        } else {
          $this->_respColab = $this->bd_colaborador->getColaborador($this->_strRut);

          $this->_array = (array) $this->_respColab;

          //Agregando jerarquia nueva************************************************    

          if (count($this->_array) > 0) {
            $this->_jerar = $this->bd_colaborador->buscarCUI($this->_strRut);

            if (count($this->_jerar) > 0) {
              foreach ($this->_jerar as $row) {
                $this->_jer_auxjefe = $row->JER_AUX_JEFE;
                $this->_nivel_cui   = $row->JER_COD_NIVEL;
                $this->_cui         = $row->UNIDAD;
              }


              if ($this->_jer_auxjefe > 0) {
                $this->_jerarDEP  = $this->bd_colaborador->buscarCUIDEP($this->_cui);

                $this->_jerarDEP2 = $this->bd_colaborador->buscarCUIDEP2($this->_cui, $this->_nivel_cui);

                if (count($this->_jerarDEP) == 0 && count($this->_jerarDEP2) == 0) {
                  $this->rut = "No es lider";
                }
              } else {
                $this->rut = "No es lider";
              }
            } else {
              $this->rut = "No es lider";
            }
          } else {
            $this->rut = "Colaborador no existe";
          }
        }
      } else {
        $this->rut = "Rut Incorrecto";
      }
    }

    $respuesta[] = array(
      'rut'      => $this->rut,
      'colab'    => $this->_respColab
    );
    echo json_encode($respuesta);
    exit;
  }


  public function validaColaboradorSuperior($rut = '')
  {
    if ($rut != '') {
      $this->_strRut  = $rut;
    } else {
      $respuesta = array(
        'rut'      => 'hdhdhhdhd',
        'colab'    => '',
        'error'    => false
      );
      return $respuesta;
      exit;
    }

    if (is_numeric($this->_strRut)) {
      if (strlen($this->_strRut) < 7) {
        $this->rut = "Rut Incorrecto";
      } else {


        $this->_respColab = $this->bd_colaborador->getColaborador($this->_strRut);
        $this->_array = $this->_respColab;

        //Agregando jerarquia nueva************************************************    

        if (count($this->_array) > 0) {

          $this->_jerar = $this->bd_colaborador->buscarCUI($this->_strRut);


          if (count($this->_jerar) > 0) {

            foreach ($this->_jerar as $row) {
              $this->_jer_auxjefe = $row->JER_AUX_JEFE;
              $this->_nivel_cui   = $row->JER_COD_NIVEL;
              $this->_cui         = $row->UNIDAD;
            }

            if ($this->_jer_auxjefe > 0) {
              $this->_jerarDEP  = $this->bd_colaborador->buscarCUIDEP($this->_cui);

              $this->_jerarDEP2 = $this->bd_colaborador->buscarCUIDEP2($this->_cui, $this->_nivel_cui);

              if (count($this->_jerarDEP) == 0 && count($this->_jerarDEP2) == 0) {
                $this->rut = "Solo se permite acceso a los líderes";
              }
            } else {
              $this->rut = "Solo se permite acceso a los líderes";
            }
          } else {
            $this->rut = "Solo se permite acceso a los líder";
          }
        } else {
          $this->rut = "Colaborador no existe";
        }
      }
    } else {
      $this->rut = "Rut Incorrecto";
    }

    $respuesta = array(
      'rut'      => $this->rut,
      'colab'    => $this->_respColab,
      'error'    => true
    );
    return ($respuesta);
  }





  public function validaColabdep()
  {

    $this->_strRut  = $this->session->userdata('rut');;

    if (is_numeric($this->_strRut)) {
      if (strlen($this->_strRut) < 7) {
        $this->rut = "Rut Incorrecto";
      } else {
        $this->_respColab = $this->bd_colaborador->getColaborador($this->_strRut);
        $this->_array = (array) $this->_respColab;

        if (count($this->_respColab) > 0) {
        } else {
          $this->rut = "Colaborador no existe";
        }
      }
    } else {
      $this->rut = "Rut Incorrecto";
    }


    $respuesta[] = array(
      'rut'      => $this->rut,
      'colab'    => $this->_respColab
    );

    echo json_encode($respuesta);
    exit;
  }

  //Ingreso pin formulario superior
  public function ingresoformulario()
  {
    if (isset($_POST["dep"])) {

      $this->_strRut  = $_POST['dep'];
      $this->_usuario = $_POST['su'];
      $this->_tipo    = $_POST['se'];
      $this->_cod     = trim(strtoupper($this->_tipo . $_POST['cod']));
      $this->_obs     = $_POST['obs'];

      $this->_usuario  = (($this->_usuario - 12345678) / 36);
      $this->_strRut   = (($this->_strRut  - 12345678) / 36);
      $this->_countDia = "";

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
      $this->_array    = $this->bd_colaborador->validaPINCui($this->_usuario, $this->_tipo, $this->_cod, $this->_cui);

      if (count($this->_array) > 0) {

        foreach ($this->_array as $row) {
          $estado = $row->ESTADO;
        }
        if (!is_null($estado)) {
          $this->_estadoMSG = 'Este c&oacute;digo ya fue utilizado';
        }
      } else {
        $this->_codMSG = " N&uacute;mero de pin no est&aacute; asignado a su &aacute;rea";
      }

      if ($this->_estadoMSG == '' && $this->_codMSG == '' && $this->_obsMSG == '') {
        $diaArr = $this->bd_colaborador->contadorPIN($this->_strRut);

        if (count($diaArr) > 0) {
          foreach ($diaArr as $row) {
            $this->_countDia = $row->CONTADOR;
          }

          if ($this->_countDia == 3) {
            $this->_countDia = 0;
          }
        } else {
          $this->_countDia = 0;
        }


        $this->_countDia++;
        $this->_pagina = "PIN RECONOCIMIENTO";
        $this->_bdMSG = $this->bd_colaborador->ingresoPINColaborador(
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
        'obsMSG'    => $this->_obsMSG,
        'contDia'   => $this->_countDia
      );
      echo json_encode($respuesta);
    }
  }
  //fin del ingreso pin formulario superior   

  public function destroy()
  {
    $this->session->sess_destroy();
  }

  public function enviacontacto()
  {
    if ($this->is_empty($this->session->userdata('rut'))) {
      $this->_session = false;
      $this->_bdMSG   = false;
      $this->rut = 'Inicie session nuevamente';
      $respuesta[]   =   array(
        'bdMSG'     => $this->_bdMSG,
        'mailMSG'   => $this->_estadoMSG,
        'asuMSG'    => $this->_codMSG,
        'obsMSG'    => $this->_obsMSG,
        'nomMSG'    => $this->_tMSG,
        'obsRUT'    => $this->rut,
        'session'   => $this->_session
      );
      echo json_encode($respuesta);
    } else {

      if (isset($_POST["nombre"])) {
        $this->_nom    = $_POST['nombre'];
        $this->_mail   = $_POST['email'];
        $this->_asunto = $_POST['asunto'];
        $this->_obs    = $_POST['observ'];
        $this->_strRut = $this->session->userdata('rut');
        $arrayRut = explode("EXT", $this->session->userdata('rut'));
        $this->_strRut = count($arrayRut) > 1 ? $arrayRut[1] : $arrayRut[0];

        $this->_DIGMAIL    = "";
        $this->_EMPMAIL    = "";
        $this->_NOMBREMAIL = "";
        $this->_RUTMAIL    = "";

        if (is_numeric($this->_strRut)) {
          if (strlen($this->_strRut) < 7) {
            $this->rut = "Rut Incorrecto";
          } else {
            $this->_respColab = $this->bd_colaborador->getColaboradorMail($this->_strRut);

            $this->_array = json_decode(json_encode((array) $this->_respColab), true);

            if (count($this->_respColab) > 0) {
              for ($ii = 0; $ii < count($this->_array); $ii++) {

                $this->_DIGMAIL    = $this->_array[$ii]['EMP_A_DIGVERRUT'];
                $this->_EMPMAIL    = $this->_array[$ii]['MAE_C_EMAIL'];
                $this->_NOMBREMAIL = $this->_array[$ii]['NOMBRE'];
                $this->_RUTMAIL    = $this->_array[$ii]['RUT'];
              }
            } else {
              $this->rut = "Colaborador no existe";
            }
          }
        } else {
          $this->rut = "Rut Incorrecto";
        }

        if ($this->_obs == '') {
          $this->_obsMSG = 'Ingrese Comentario';
        } elseif (strlen($this->_obs) > MAX_CARACT_COM) {
          $this->_obsMSG = 'El comentario supera el limite de lo permitido';
        }

        if ($this->_mail == '') {
          $this->_estadoMSG = 'Ingrese un correo';
        } else {
          if ($this->funciones->comprobar_email($this->_mail) == 0) {
            $this->_estadoMSG = 'Mail incorrecto';
          }
        }

        if ($this->_asunto == '') {
          $this->_codMSG = 'Ingrese un asunto';
        } else {
          if (strlen($this->_asunto) < 5) {
            $this->_codMSG = 'Minimo de caracteres no permitidos';
          }
        }

        if ($this->_nom == '') {
          $this->_tMSG = 'Ingrese un nombressss';
        } else {
          if (strlen($this->_nom) < 9) {
            $this->_tMSG = 'Ingrese nombre y apellido';
          }
        }


        if ($this->_estadoMSG == '' && $this->_codMSG == '' && $this->_obsMSG == '' && $this->_tMSG == '' && $this->rut == '') {

          $this->_bdMSG = $this->bd_colaborador->ingresoComentario(
            $this->_nom,
            $this->_mail,
            $this->_asunto,
            $this->_obs,
            $this->_strRut
          );
          if ($this->_bdMSG) {

            $this->_obs .= "<br><br>";
            $this->_obs .= "<br><br>";
            $this->_obs .= "<br><br>";
            $this->_obs .= "Datos Colaborador Adjunto:";
            $this->_obs .= "<br>";
            $this->_obs .= "                           1.- " . $this->_RUTMAIL . "-" . $this->_DIGMAIL . "<br>";
            $this->_obs .= "                           2.- " . $this->_NOMBREMAIL . "<br> ";
            $this->_obs .= "                           3.- " . $this->_EMPMAIL . " <br>";
            $this->_obs .= "                           4.- Correo alternativo: " . $this->_mail . " <br>";

            // $this->load->library('email');
            $smtp = new SMTP();
            $this->email = $smtp->get();
            $this->email->from("esbmereconoce@sb.cl", "Empresa SB me Reconoce");
            $this->email->to('lantilen@sb.cl');
            $this->email->cc('cbottari@sb.cl,rtropa@sb.cl,kmorales@sb.cl');
            $this->email->subject($this->_asunto);
            $this->email->message($this->_obs);
            $this->email->send();
          } else {
            echo "no pase";
          }
        } else {
          $this->_bdMSG = false;
        }



        $respuesta[]   =   array(
          'bdMSG'     => $this->_bdMSG,
          'mailMSG'   => $this->_estadoMSG,
          'asuMSG'    => $this->_codMSG,
          'obsMSG'    => $this->_obsMSG,
          'nomMSG'    => $this->_tMSG,
          'obsRUT'    => $this->rut,
          'session'   => $this->_session
        );
        echo json_encode($respuesta);
      } else {
        redirect(base_url());
      }
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
        $cont   = $this->bd_oracle->getContador($this->_cod);

        if (count($cont) > 0) {
          foreach ($cont as $row) {
            $this->_contdia = $row->CONTADOR;
          }

          if ($this->_contdia == 3) {
            $this->enviaMailPines($this->_cod);
          }
        }

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
      'codMSG'    => $this->_codMSG,
      'countDia'  => $this->_contdia
    );
    echo json_encode($respuesta);
  }



  public function enviaMailPines($codigoPin)
  {

    $this->_array = $this->bd_colaborador->traePINSUP($codigoPin);

    if (count($this->_array) > 0) {
      foreach ($this->_array as $row) {
        $this->rut     = $row->RUT_RESP;
        $this->_strRut = $row->RUT_COLAB;
      }
    }


    $this->_array = $this->bd_colaborador->getSupMail($this->rut);

    if (count($this->_array) > 0) {
      foreach ($this->_array as $row) {
        $this->_mailsup =   $row->MAE_C_EMAIL;
      }
    }

    $this->_array = $this->bd_colaborador->getSupMail($this->_strRut);

    if (count($this->_array) > 0) {
      foreach ($this->_array as $row) {
        $this->_maildep =   $row->MAE_C_EMAIL;
      }
    }

    $this->_array = $this->bd_colaborador->traeNombre($this->_strRut);
    if (count($this->_array) > 0) {
      foreach ($this->_array as $row) {
        $this->_nombre = $row->NOMBRE;
      }
    }
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
        max-width: 800px;
        padding: 10px;
        width: 780px; }

      /* This should also be a block element, so that it will fill 100% of the .container */
      .content {
        box-sizing: border-box;
        display: block;
        Margin: 0 auto;
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
    <table border="0" cellpadding="0" cellspacing="0" class="body">
      <tr>
        <td>&nbsp;</td>
        <td class="container">
          <div class="content">

            <!-- START CENTERED WHITE CONTAINER -->
            <span class="preheader">This is preheader text. Some clients will show this text as a preview.</span>
            <table class="main">

              <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper">
                  <table border="0" cellpadding="0" cellspacing="0">
                    <tr>
                      <td>
                        <p>
							<img src="http://servicios.salcobrand.cl/bienestar/desa/Dia-Libre.png"  align="left" class="img-responsive"/>
						</p>
					  </td>
					</tr>	
					<tr>	
					  <td>&nbsp;</td>
					</tr>
					<tr> 
					 <td>
						 <p><h3>Estimado(a) ' . $this->_nombre . '</h3></p>
                      </td>
                    </tr>
					
					<tr>	
					  <td align="justify">
						  <p>Felicitaciones, gracias a tus acciones alineadas a la Cultura de nuestra organizaci&oacute;n, de manera permanente en el tiempo, has recibido tu tercer Pin de Reconocimiento por lo tanto disfrutar&aacute;s de 1 d&iacute;a libre.<br></p>
					 </td>
					</tr>
					<tr>
						<td align="justify">
							<p>Este beneficio puede ser tomado dentro de los siguientes 30 d&iacute;as corridos, coordinando previamente con tu Jefe Directo, quien debe reenviar este mail a la ejecutiva de Procesos y a <a href="mailto:lantilen@sb.cl">rtropa@sb.cl</a>, para que el d&iacute;a sea debidamente ingresado.<br></p>
						</td>
					</tr>
					<tr>
					<td align="justify">
						<p><br>
							<img src="http://servicios.salcobrand.cl/bienestar/desa/huincha-separador.png"  align="left" class="img-responsive"/><br>
						</p>
					</td>
					</tr>
					<tr>
						<td align="justify">
							<p>El equipo de <b>Empresas SB me Reconoce</b>, te invita a seguir viviendo nuestro prop&oacute;sito com&uacute;n: "Hacemos que las personas se sientan bien, est&eacute;n donde est&eacute;n".</p>
						</td>
					</tr>
					 <tr>
						<td align="justify">
							<p>¿Quieres saber m&aacute;s del Programa de Reconocimiento o contactarnos? Ingresa al Portal <a href="http://192.168.200.19:88/intranet/portalreconocimiento/ ">ac&aacute;</a></p>
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
    $config['charset'] = 'utf-8';
    $config['wordwrap'] = TRUE;
    $config['mailtype'] = 'html';
    $this->email->initialize($config);
    $this->email->from("esbmereconoce@sb.cl", "Empresa SB me Reconoce");
    $this->email->to($this->_maildep);
    $this->email->cc($this->_mailsup . ',lantilen@sb.cl,cbottari@sb.cl,rtropa@sb.cl,kmorales@sb.cl');
    $this->email->subject("Felicitaciones! Ganaste 1 día libre");
    $this->email->message($this->_obs);
    $this->email->set_mailtype('html');

    $this->email->send();
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

  public function pageContenido()
  {
    //$array['lider'] = base_url().'tarjeta/pagecolab/';
    //$array['urlbaner'] = base_url().'/asset/img/cortina3_1280x800px.jpg';
    $array['bdPDF'] = $this->bd_oracle->editardocumentosPDF();
    $array['lider'] = base_url() . 'ingreso/pagecolab/';
    $array['urlbaner'] = base_url() . 'asset/img/cortina2_1280x800px.jpg';
    $this->load->view('header');
    $this->load->view('temp/contenidosdoc', $array);
    $this->load->view('footer');
  }
}
