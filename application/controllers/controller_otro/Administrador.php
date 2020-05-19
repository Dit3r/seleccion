<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

set_time_limit(0);
ob_start();
class Administrador extends CI_Controller
{
  private $_controlador;
  private $_metodo;
  private $_segmento;
  private $_image;
  private $_fechaI;
  private $_fechaT;
  private $_url;
  private $_estado;
  private $_mensaje;
  private $_array;
  private $_arr;
  private $_count;
  private $_columna;
  private $_fila;
  private $select;
  private $tipo;
  private $rutsup;
  private $digsup;
  private $rutcol;
  private $digcol;
  private $desde;
  private $hasta;
  private $_idEstado;
  private $_result;
  private $_resultElim;
  private $_pinOriginal;
  private $_seccion;
  private $_jerar;
  private $_jer_auxjefe;
  private $_cui;
  private $_nivel_cui;
  private $_pagina;
  private $_contdia;
  private $_ano;
  private $_bdMSG;
  private $_estadoMSG;
  private $RutResponsable;
  private $_obsMSG;
  private $_countDia;
  private $_codMSG;
  private $tipoReconocimiento;
  private $bolean;
  private $buscaRut;
  private $contadorUpdate;
  private $NumbertCienti;
  private $MensajeNumericCUI;
  private $MensajeNumericRUT;
  private $_BodyMail;
  private $_nameColab;
  private $_nombreColaborador;
  private $_rutsuperior;
  private $_nombre;
  private $_nombreSup;
  private $_mailAlt;
  private $_mailAlt2;
  private $_MngMail;
  private $_MngMail2;
  private $_maildep;
  private $_mail;
  private $_mailsup;



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
    ini_set('memory_limit', '-1');

    if (!$this->session->userdata('rut')) {
      redirect(base_url() . "indexcontrolador");
    }
  }

  public function is_empty($sentence)
  {
    return empty($sentence) ? true : false;
  }

  public function validaOauth()
  {

    include_once APPPATH . "libraries/Client.php";

    if (!isset($_GET['code'])) {

      $this->authorizationUrl  = $provider->getAuthorizationUrl();
      $this->session->set_userdata('oauth2state', $provider->getState());
      redirect($this->authorizationUrl);
    }
  }


  public function _remap($method, $params = array())
  {
    if (method_exists(__CLASS__, $method)) {
      $this->$method($params);
    } else {
      $this->test_default();
    }
  }

  public function test_default()
  {
    redirect(base_url() . "administrador/inicio/");
  }

  public function index()
  {

    if ($this->_metodo == "") {
      redirect(base_url() . "administrador/inicio/");
    }
  }

  public function inicio()
  {
    $array['url']    = base_url() . 'administrador/upload_it/';

    $this->load->view('admin/header');
    $this->load->view('admin/slider', $array);
    $this->load->view('admin/footer');
  }

  public function admContent()
  {
    $array['url']    = base_url() . 'administrador/upload_PDF/';

    $this->load->view('admin/header');
    $this->load->view('admin/Content', $array);
    $this->load->view('admin/footer');
  }

  public function casoEspeciales()
  {
    $array['url']    = base_url() . 'administrador/updateCui/';
    $array['tabla'] = array();
    $this->load->view('admin/header');
    $this->load->view('admin/pageform', $array);
    $this->load->view('admin/footer');
  }


  public function editarCont()
  {
    $array['url']    = base_url() . 'administrador/upload_PDF/';

    $this->load->view('admin/header');
    $this->load->view('admin/editarCont', $array);
    $this->load->view('admin/footer');
  }

  public function consulta()
  {
    $array['url']    = base_url() . 'administrador/excel_consulta/';
    $this->load->view('admin/header');
    $this->load->view('admin/consulta', $array);
    $this->load->view('admin/footer');
  }



  public function editar()
  {
    $array['url']    = base_url() . 'administrador/act_slider/';
    $this->load->view('admin/header');
    $this->load->view('admin/editar', $array);
    $this->load->view('admin/footer');
  }

  public function editarPDF()
  {
    $array['url']    = base_url() . 'administrador/act_slider/';
    $this->load->view('admin/header');
    $this->load->view('admin/editarPDF', $array);
    $this->load->view('admin/footer');
  }


  public function act_slider()
  {

    $this->_array = $this->bd_oracle->editar_slider();
    $i = 0;
    $this->_count = 0;
    foreach ($this->_array as $row) {
      $this->_arr  =  $this->bd_oracle->update_slider(
        $_POST['id_' . $i],
        $_POST['desde_' . $i],
        $_POST['hasta_' . $i],
        $_POST['estado_' . $i],
        $_POST['url_' . $i]
      );

      if ($this->_arr) {
        $this->_count++;
      }

      $i++;
    }

    if ($i != $this->_count) {
      $this->session->set_flashdata('item2', 'Ocurrio un problema, por favor comunicar con Intranet');
    } else {
      $this->session->set_flashdata('item2', 'Los cambios se realizaron correctamente, muchas gracias');
    }

    redirect(base_url() . 'administrador/editar/');
  }


  function upload_it()
  {

    $this->load->helper('form');
    $config['upload_path'] = 'asset/img/';

    //indicamos que tipo de archivos estan permitidos
    $config['allowed_types'] = 'gif|jpg|png';
    //indicamos el tamaño maximo permitido en este caso 1M
    $config['max_size'] = '1024';
    //le indicamos el ancho maximo permitido
    $config['max_width']  = '900';
    //le indicamos el alto maximo permitodo
    $config['max_height']  = '260';

    //cargamos nuestra libreria con nuestra configuracion
    $this->load->library('upload', $config);

    $this->upload->initialize($config);
    //$this->upload->set_allowed_types('*');

    $data['upload_data'] = '';

    if (!$this->upload->do_upload('userfile')) {
      $this->_mensaje = array('msg' => $this->upload->display_errors());
    } else {
      $this->_url     = $_POST['url'];
      $this->_fechaI  = $_POST['desde'];
      $this->_fechaT  = $_POST['hasta'];

      if ($this->_url == '') {
        $this->_url = '#';
      }
      $this->_mensaje = 'OK';
      $this->_array   = $this->upload->data();
    }

    if ($this->_mensaje == 'OK') {

      $this->_mensaje = $this->bd_oracle->sliderArchivo(
        $this->_url,
        $this->_fechaI,
        $this->_fechaT,
        $this->_array['orig_name']
      );
      if ($this->_mensaje) {
        $this->session->set_flashdata('item', 'Se guardaron corretamente los datos');
      } else {
        $this->session->set_flashdata('item', 'Ocurrio un problema, por favor comunicar con Intranet');
      }
    } else {
      $this->session->set_flashdata('item', $this->_mensaje);
    }

    redirect(base_url() . 'administrador/inicio/');
  }

  function upload_PDF()
  {

    $this->load->helper('form');
    $config['upload_path'] = 'asset/uploadArchivo/';

    //indicamos que tipo de archivos estan permitidos
    $config['allowed_types'] = 'pdf';
    //indicamos el tamaño maximo permitido en este caso 1M
    $config['max_size'] = '2048';
    //le indicamos el ancho maximo permitido
    //$config['max_width']  = '800';
    //le indicamos el alto maximo permitodo
    //$config['max_height']  = '251';

    //cargamos nuestra libreria con nuestra configuracion
    $this->load->library('upload', $config);

    $this->upload->initialize($config);
    //$this->upload->set_allowed_types('*');

    $data['upload_data'] = '';

    if (!$this->upload->do_upload('userfile')) {
      $this->_mensaje = array('msg' => $this->upload->display_errors());
    } else {
      $this->_url     = $_POST['titulo'];
      $this->_fechaI  = $_POST['desde'];
      $this->_fechaT  = $_POST['hasta'];
      $this->_estado  = $_POST['strEstado'];

      $this->_mensaje = 'OK';
      $this->_array   = $this->upload->data();
    }

    if ($this->_mensaje == 'OK') {

      $this->_mensaje = $this->bd_oracle->pdfArchivo(
        $this->_url,
        $this->_fechaI,
        $this->_fechaT,
        $this->_array['orig_name'],
        $this->_estado
      );
      if ($this->_mensaje) {
        $this->session->set_flashdata('item', 'Se guardaron corretamente los datos');
      } else {
        $this->session->set_flashdata('item', 'Ocurrio un problema, por favor comunicar con Intranet');
      }
    } else {
      $this->session->set_flashdata('item', $this->_mensaje);
    }

    redirect(base_url() . 'administrador/admContent/');
  }

  public function uploadercsv()
  {
    $array['url']    = base_url() . 'administrador/add_bulk_user/';
    $this->load->view('admin/header');
    $this->load->view('admin/subircsv', $array);
    $this->load->view('admin/footer');
  }

  public function add_bulk_user()
  {

    if ($_FILES['csvfile'] != "") {
      ini_set('memory_limit', '1024M');
      $this->tipo = $_POST['selectForm'];

      $config['upload_path'] = 'asset/upload/';
      $config['allowed_types'] = 'csv';
      //$config['max_size'] = '3072';
      //$config['max_width'] = '1024';

      $this->load->library('upload', $config);

      if ($this->tipo != "") {

        if (!$this->upload->do_upload('csvfile')) {

          $this->_mensaje = $this->upload->display_errors(); // this isn't working  
          $this->session->set_flashdata('msg_file', $this->_mensaje);
          redirect(base_url() . 'administrador/uploadercsv/');
          exit;
        } else {

          $data['qs'] = $this->upload->data();
          $row = 1;
          $nombreFile = $data['qs']['orig_name'];
          $this->_mensaje = "OK";
          $this->_fila    = "";

          if ($this->tipo == "INGPIN") {
            if (($handle = fopen("asset/upload/" . $nombreFile, "r")) !== FALSE) {
              while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {

                $this->bolean = '';
                $num    = count($data);
                $this->_columna = $data;
                $this->NumbertCienti = $this->_columna[4];
                if (!is_numeric($this->NumbertCienti)) {
                  $this->MensajeNumericCUI = "SI";
                }

                if (!is_numeric($this->_columna[0])) {
                  $this->MensajeNumericRUT = "SI";
                }

                $this->bolean = $this->bd_oracle->cargaPINE($this->_columna[2]);

                if ($this->bolean) {
                  $this->_fila .= $row . '-//-' . $this->_columna[2] . '-//-';
                } else {
                  $this->bolean = $this->bd_oracle->revisaPINES($this->_columna[1], $this->_columna[2]);

                  if ($this->bolean) {
                    $this->_fila .= $row . '-//' . $this->_columna[2] . '-//-';
                  }
                }
                //echo $this->_fila."<br>"; 
                //echo $row."<br>"; 
                $row++;
              }
              fclose($handle);
            }
          }

          if ($this->_fila == "" && $this->MensajeNumericCUI == "" && $this->MensajeNumericRUT == "") {

            $numero  = 0;
            $numero2 = 0;
            $row     = 0;
            if (($handle2 = fopen("asset/upload/" . $nombreFile, "r")) !== FALSE) {
              while (($data = fgetcsv($handle2, 1000, ";")) !== FALSE) {
                $this->bolean = '';
                $num = count($data);
                $this->_columna = $data;

                //for ($c = 0; $c < $num; $c++) {

                //$this->_columna = explode(';',$data[$c]);
                if (count($this->_columna) > 0 && count($this->_columna) < 6) {
                  $this->_columna[5] = '';
                }
                if ($this->tipo == "INGPIN") {
                  $this->bolean = $this->bd_oracle->ingresoPIN($this->_columna[0], $this->_columna[1], $this->_columna[2], $this->_columna[3], $this->_columna[4], $this->_columna[5]);
                } elseif ($this->tipo == "MODCUI") {
                  if ($this->bd_oracle->validaPINColaborador($this->_columna[2])) {
                    $this->bolean = $this->bd_oracle->cambioCUIColaborador($this->_columna[2], $this->_columna[4]);

                    if ($this->bolean) {
                      $this->bolean = $this->bd_oracle->cambioCUIAsignar($this->_columna[2], $this->_columna[4], $this->_columna[5]);
                    }
                  } else {
                    $this->bolean = $this->bd_oracle->cambioCUIAsignar($this->_columna[2], $this->_columna[4], $this->_columna[5]);
                  }
                } elseif ($this->tipo == "MODRUT") {
                  $this->bolean = $this->bd_oracle->cambioRUTAsignar($this->_columna[0], $this->_columna[2], $this->_columna[5]);
                } elseif ($this->tipo == "ELIMPIN") {

                  if ($this->bd_oracle->validaPINColaborador($this->_columna[2])) {
                    $this->bolean = $this->bd_oracle->eliminarPINColaborador($this->_columna[2]);
                  }

                  $this->bolean = $this->bd_oracle->eliminarPINAsignar($this->_columna[2]);
                }



                if ($this->bolean) {
                  $numero++;
                } else {
                  $numero2 .= $row . ',';
                }

                // }
                $row++;
              }

              if ($numero == $row) {
                $this->session->set_flashdata('msg_file', 'Se cargo correctamente el archivo.');
              } else {
                $this->session->set_flashdata('msg_file', 'Codigos repetidos, por favor corregir en la fila :' . $numero2);
              }
            }

            fclose($handle2);
          } else {

            if ($this->_fila != "") {
              $this->_fila =  substr($this->_fila, 0, strlen($this->_fila) - 1);
              $this->session->set_flashdata('msg_file', 'Los codigos de las siguientes filas del archivo a cargar ya fueron asignados: ' . $this->_fila);
            } elseif ($this->MensajeNumericCUI != "") {
              $this->session->set_flashdata('msg_file', 'La Columna CUI del archivo que usted esta cargando debe ser numerica.');
            } elseif ($this->MensajeNumericRUT != "") {
              $this->session->set_flashdata('msg_file', 'La Columna RUT del archivo que usted esta cargando debe ser numerica.');
            }
          }
          unlink("asset/upload/" . $nombreFile);
          redirect(base_url() . 'administrador/uploadercsv/');
        }
      } else {
        $this->session->set_flashdata('msg_file', 'Tiene que seleccionar un tipo, para cargar el archivo.');
        redirect(base_url() . 'administrador/uploadercsv/');
      }
    } else {
      $this->session->set_flashdata('msg_file', 'Tiene que ingresa un archivo.');
      redirect(base_url() . 'administrador/uploadercsv/');
    }
  }


  public function excel_pines_colab()
  {

    $nombre_archivo = 'reporteFullPinesColaborador_' . date('d-m-Y') . '.csv';
    $sCSV           = $this->bd_oracle->excelcolaboradorpinfull();
    header('Content-type: application/csv');
    header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');
    echo $sCSV;
  }

  public function admcui()
  {

    $array['url']    = base_url() . 'administrador/updateCui/';
    $array['tabla'] = array();
    $this->load->view('admin/header');
    $this->load->view('admin/pageupdate', $array);
    $this->load->view('admin/footer');
  }

  public function updatePin()
  {

    if ($this->_argumentos[0] != '') {
      $this->_array = $this->bd_oracle->buscarPINES($this->_argumentos[0]);
    }
    if (count($this->_array) > 0) {
      foreach ($this->_array as $row) {
        $array['valorArray'][0] = $row->ID;
        $array['valorArray'][1] = $row->RUT;
        $array['valorArray'][2] = $row->TIPO;
        $array['valorArray'][3] = $row->COD_PIN;
        $array['valorArray'][4] = $row->ESTADO;
        $array['valorArray'][5] = $row->PROPIEDAD;
        $array['valorArray'][6] = $row->NIVEL;
      }
    }

    $this->load->view('admin/header');
    $this->load->view('admin/m-page', $array);
    $this->load->view('admin/footer');
  }

  public function updateCui()
  {

    $select    = (isset($_POST['select'])    ? $_POST['select'] : '');
    $tipo      = (isset($_POST['tipo'])      ? $_POST['tipo'] : '');
    $codigoPin = (isset($_POST['codigoPin']) ? $_POST['codigoPin'] : '');
    $cui       = (isset($_POST['cui'])       ? $_POST['cui'] : '');

    $this->_array = $this->bd_oracle->buscarPinAsignado($select, $tipo, $codigoPin, $cui);

    if (count($this->_array) > 0) {
      $array['tabla'] = $this->_array;
    } else {
      $array['tabla'] = array();
    }
    $array['url']    = base_url() . 'administrador/updateCui/';
    $array['pin_update']    = base_url() . 'administrador/updatePin/';
    $this->load->view('admin/header');
    $this->load->view('admin/pageupdate', $array);
    $this->load->view('admin/footer');
  }


  public function updatePINCUI()
  {

    $idPin              = (isset($_POST['idPin'])     ? $_POST['idPin']      : '');
    $tipo               = (isset($_POST['strTipo'])   ? $_POST['strTipo']    : '');
    $codigoPin          = (isset($_POST['codPin'])    ? $_POST['codPin']     : '');
    $cui                = (isset($_POST['strCui'])    ? $_POST['strCui']     : '');
    $rut                = (isset($_POST['Rut'])       ? $_POST['Rut']        : '');
    $IdSelect           = (isset($_POST['strMotivo']) ? $_POST['strMotivo']  : '');
    $this->_idEstado    = (isset($_POST['Estado'])    ? $_POST['Estado']  : '');
    $this->_pinOriginal = (isset($_POST['pinOrigin'])    ? $_POST['pinOrigin']  : '');


    if ($this->_idEstado == 'ELIMINAR') {

      $this->_array    = $this->bd_oracle->validaPINADMINISTRADOR($codigoPin, $IdSelect, $tipo, $cui);
      if (count($this->_array) > 0) {
        $this->_result = $this->bd_oracle->consultaPINColab($codigoPin);
        if ($this->_result) {
          $this->buscaRut = $this->bd_oracle->consultaRUTPINColaborador($codigoPin);

          $this->_resultElim = $this->bd_oracle->elimarPINColaborador($codigoPin);

          if ($this->_resultElim) {

            if ($this->buscaRut) {
              $this->contadorUpdate = $this->bd_oracle->updateContadorPinColaborado($this->buscaRut[0], $this->buscaRut[1]);

              if (count($this->contadorUpdate) > 0) {
                $this->_count = 1;
                foreach ($this->contadorUpdate as $row) {
                  if ($this->bd_oracle->updateContadorColaborado($row->ID, $this->_count)) {
                    if ($this->_count == 3) {
                      $this->_count = 1;
                    } else {
                      $this->_count++;
                    }
                  }
                }
              }
            }
          }

          if ($this->_resultElim) {
            $this->_array = $this->bd_oracle->actualizaCUIPIN($idPin, $tipo, $codigoPin, $cui, $rut, $IdSelect, '');

            if ($this->_array) {
              $respuesta[] = array('salida' => 'OK', 'proceso' => 'Elim');
            } else {
              $respuesta[] = array('salida' => 'NO');
            }
          } else {
            $respuesta[] = array('salida' => 'NO');
          }
        } else {
          $respuesta[] = array('salida' => 'NO');
        }
      } else {
        $respuesta[] = array('salida' => 'NO', 'proceso' => 'pinnoval');
      }
    } elseif ($this->_idEstado == 'NOENTREGADO') {
      $this->_array = $this->bd_oracle->actualizaCUIPIN($idPin, $tipo, $codigoPin, $cui, $rut, $IdSelect, '');

      if ($this->_array) {
        $respuesta[] = array('salida' => 'OK', 'proceso' => 'Act');
      } else {
        $respuesta[] = array('salida' => 'NO');
      }
    } elseif ($this->_idEstado == 'ENTREGADO') {
      $this->_array    = $this->bd_oracle->validaPINADMINISTRADOR($codigoPin, $IdSelect, $tipo, $cui);
      if (count($this->_array) > 0) {
        $this->_result = $this->bd_oracle->consultaPINColab($this->_pinOriginal);
        if ($this->_result) {

          if (strtoupper($tipo) == 'PIN') {
            $this->_seccion = 'PIN RECONOCIMIENTO';
          } elseif (strtoupper($tipo) == 'TAR') {
            $this->_seccion = 'TARJETA RECONOCIMIENTO';
          }

          $this->_resultElim = $this->bd_oracle->updatePINColaborador($this->_pinOriginal, $IdSelect, $codigoPin, $this->_seccion, $cui);

          if ($this->_resultElim) {
            $this->_array = $this->bd_oracle->actualizaCUIPIN($idPin, $tipo, $codigoPin, $cui, $rut, $IdSelect, '1');

            if ($this->_array) {
              $respuesta[] = array('salida' => 'OK', 'proceso' => 'Act');
            } else {
              $respuesta[] = array('salida' => 'NO');
            }
          } else {
            $respuesta[] = array('salida' => 'NO');
          }
        } else {
          $respuesta[] = array('salida' => 'NO');
        }
      } else {
        $respuesta[] = array('salida' => 'NO', 'proceso' => 'pinnoval');
      }
    }
    echo json_encode($respuesta);
  }


  public function ingrePinEspecial()
  {


    $RutResponsable      = (isset($_POST['RutResponsable'])    ? $_POST['RutResponsable']  : '');
    $RutColaborado       = (isset($_POST['RutColaborado'])     ? $_POST['RutColaborado']    : '');
    $tipoReconocimiento  = (isset($_POST['selectLoad2'])       ? $_POST['selectLoad2']     : '');
    $selectLoad          = (isset($_POST['selectLoad'])        ? $_POST['selectLoad']     : '');
    $codigoPINEspecial   = (isset($_POST['codigoPINEspecial']) ? $_POST['codigoPINEspecial']     : '');
    $datepicker          = (isset($_POST['datepicker'])        ? $_POST['datepicker']        : '');
    $Justificar          = (isset($_POST['Justificar'])        ? $_POST['Justificar']  : '');

    if (strlen($Justificar) > MAX_CARACT_COM) {
      $this->_obsMSG = 'El comentario supera el limite de lo permitido';
    }

    $this->_jerar = $this->bd_colaborador->buscarCUI($RutResponsable);
    if (count($this->_jerar) > 0) {
      foreach ($this->_jerar as $row) {
        $this->_jer_auxjefe = $row->JER_AUX_JEFE;
        $this->_nivel_cui   = $row->JER_COD_NIVEL;
        $this->_cui         = $row->UNIDAD;
      }
    }

    $this->_array    = $this->bd_oracle->validaPINTIPO_BD($codigoPINEspecial, $selectLoad, $tipoReconocimiento, $this->_cui);


    if (count($this->_array) > 0) {

      foreach ($this->_array as $row) {
        $estado = $row->ESTADO;
      }
      if (!is_null($estado)) {
        $this->_estadoMSG = 'Este c&oacute;digo ya fue utilizado';
      }
    } else {
      $this->_codMSG = " VALIDE QUE EL CODIGO SEA VALIDO";
    }

    if ($datepicker) {
      $mes = explode('-', $datepicker);

      if ($mes[1] == '01') {
        $nameMes = "Enero";
      } elseif ($mes[1] == '02') {
        $nameMes = "Febrero";
      } elseif ($mes[1] == '03') {
        $nameMes = "Marzo";
      } elseif ($mes[1] == '04') {
        $nameMes = "Abril";
      } elseif ($mes[1] == '05') {
        $nameMes = "Mayo";
      } elseif ($mes[1] == '06') {
        $nameMes = "Junio";
      } elseif ($mes[1] == '07') {
        $nameMes = "Julio";
      } elseif ($mes[1] == '08') {
        $nameMes = "Agosto";
      } elseif ($mes[1] == '09') {
        $nameMes = "Septiembre";
      } elseif ($mes[1] == '10') {
        $nameMes = "Octubre";
      } elseif ($mes[1] == '11') {
        $nameMes = "Noviembre";
      } elseif ($mes[1] == '12') {
        $nameMes = "Diciembre";
      }
    }

    $this->_nameColab = $this->bd_colaborador->getColaborador($RutColaborado);
    if (count($this->_nameColab) > 0) {

      foreach ($this->_nameColab as $row) {
        $this->_nombreColaborador = $row->NOMBRE;
      }
      if (is_null($this->_nombreColaborador)) {
        $this->_estadoMSG = ' Ocurrio un error, no existe información del colaborador';
      }
    } else {
      $this->_codMSG = " VALIDE QUE CORRESPONDA EL CODIGO SEA VALIDO";
    }


    if ($this->_estadoMSG == '' && $this->_codMSG == '' && $this->_obsMSG == '') {
      if ($tipoReconocimiento == 'PIN' || $tipoReconocimiento == 'IGS' || $tipoReconocimiento == 'CSI') {
        $this->_ano = explode('-', $datepicker);
        $this->_ano = $this->_ano[2];
        $diaArr = $this->bd_oracle->contadorPINRECONOCIMIENTO($RutColaborado, $this->_ano);
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

        if ($tipoReconocimiento == 'PIN') {
          $this->_pagina = "PIN RECONOCIMIENTO";
        } elseif ($tipoReconocimiento == 'IGS') {
          $this->_pagina = "PIN IGS";
        } elseif ($tipoReconocimiento == 'CSI') {
          $this->_pagina = "PIN CSI";
        }
      } else {
        $this->_countDia = '';
        $this->_pagina = "TARJETA RECONOCIMIENTO";
      }

      $this->_bdMSG = $this->bd_oracle->ingresoPINColaboradorESPECIAL(
        $RutResponsable,
        $RutColaborado,
        $selectLoad,
        $codigoPINEspecial,
        $Justificar,
        $this->_countDia,
        $this->_pagina,
        $this->_cui,
        $tipoReconocimiento,
        $datepicker
      );
    } else {
      $this->_bdMSG = 'NO';
    }



    if ($this->_bdMSG == true && $tipoReconocimiento == 'IGS') {

      $this->_array = $this->bd_colaborador->buscarSuperiorMail($RutColaborado);

      if (count($this->_array) > 0) {

        foreach ($this->_array as $row) {
          $this->_rutsuperior = $row->JEFE_DIRECTO;
          $this->_maildep     = $row->EMAIL;
        }
      } else {
        $this->_maildep = 'lantilen@sb.cl';
      }

      if ($this->_rutsuperior != "" && $this->_rutsuperior != "NO_DEF") {
        if ($this->_rutsuperior != $RutColaborado) {
          $this->_array = $this->bd_colaborador->getSupMail($this->_rutsuperior);

          if (count($this->_array) > 0) {
            foreach ($this->_array as $row) {
              $this->_mailsup =   $row->MAE_C_EMAIL;
            }
          }
        }
      } else {
        $this->_mailsup = 'lantilen@sb.cl';
      }


      $this->_BodyMail = '<!doctype html>
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
										<table border="0" cellpadding="0" cellspacing="0" class="body" align="left">
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
																<img src="http://servicios.salcobrand.cl/bienestar/desa/Pin-dorado.png"  align="left" class="img-responsive"/>
															</p>
														  </td>
														</tr>	

														<tr> 
														 <td>
															 <p><h3>Estimados Jefes de Local /Tienda</h3></p>
														  </td>
														</tr>

														<tr>	
														  <td align="justify">
															  <p>Felicitaciones, ' . $this->_nombreColaborador . ' obtuvo un 100% en la Evaluaci&oacute;n de Calidad de Servicio del mes de ' . $nameMes . '. Por tanto, hemos ingresado al Portal de Reconocimiento el Pin Dorado Piensa en el Cliente Primero ' . $codigoPINEspecial . ', que reciben una vez en el a&ntilde;o los colaboradores de la compa&ntilde;&iacute;a por entregar un servicio excepcional a sus clientes (internos y externos), a trav&eacute;s de la medici&oacute;n IGS (Cliente Inc&oacute;gnito) y CSI (Calidad de Servicio Interno).<br></p>
														 </td>
														</tr>
														<tr>
															<td align="justify">
																<p>Para IGS este Pin tiene las siguientes caracter&iacute;sticas:<br></p>
															</td>
														</tr>
														<tr>
														</tr>
														<tr>
															<td align="justify">
																<p>
																<ul>
																	<li style="list-style-position: outside">Se entrega una vez en el a&ntilde;o, cuando el colaborador evaluado obtiene un 100% en la medici&oacute;n.</li>
																	<li style="list-style-position: outside">Equivale a un Pin de Reconocimiento entregado por el l&iacute;der, es decir, suma para la acumulaci&oacute;n de 3 pines para obtener un d&iacute;a libre (enero a diciembre de cada a&ntilde;o).</li>
																	<li style="list-style-position: outside">Est&aacute; pensado de manera equitativa, para que todos los colaboradores destacados en esta medici&oacute;n reciban el merecido reconocimiento.</li>
																	<li style="list-style-position: outside">Es ingresado al Portal de Reconocimiento por el Equipo de Empresas SB me Reconoce.</li>
																</ul>
																</p>
															</td>
														</tr>
														 <tr>
															<td align="justify">
																<p>Agradecemos compartir esta informaci&oacute;n y entregar al colaborador reconocido el Pin indicado en este correo, el que se encuentra disponible en su Kit de Reconocimiento. Luego deben  ingresar al Portal de Reconocimiento para validar este reconocimiento.</p>
															</td>
                            </tr>
                            <tr>
															<td align="justify">
																<p>Les dejamos extendida la invitaci&oacute;n para que durante el año contin&uacute;en gestionando el reconocimiento a trav&eacute;s de su Kit, y con toda la organizaci&oacute;n a trav&eacute;s del Reconocimiento online.</p>
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
																<p style="style="font-size: 10px;">
																	   Quieres ver los reconocimientos que ha recibido tu Equipo, haz clic <a href="http://192.168.200.19:88/intranet/portalreconocimiento/ingreso/principal/">ac&aacute;</a>
																</p>
															</td>
														</tr>
														<tr>
															<td align="justify">
																<p style="style="font-size: 10px;">
																	   Tienes preguntas y quieres tomar contacto, haz clic <a href="http://192.168.200.19:88/intranet/portalreconocimiento/ingreso/contacto/">ac&aacute;</a>
																</p>
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


      //  $this->load->library('email');
      $smtp = new SMTP();
      $this->email = $smtp->get();
      $this->email->from("esbmereconoce@sb.cl", "Empresa SB me Reconoce");
      $this->email->to($this->_maildep);
      $this->email->cc($this->_mailsup . ',lantilen@sb.cl, rtropa@sb.cl,ncordova@sb.cl,alufi@sb.cl');
      $this->email->subject("¡Hemos registrado el Pin de Excelencia en Servicio!");
      $this->email->message($this->_BodyMail);
      if ($this->email->send()) {
        $this->_bdMSG = true;
      } else {
        $this->_bdMSG     = 'NO';
        $this->_estadoMSG = 'No se envío el correo';
      }
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

  public function validaColab()
  {

    $RutColaborado  = (isset($_POST['RutColaborado'])   ? $_POST['RutColaborado']    : '');

    $this->_result = $this->bd_oracle->validaColab_BD($RutColaborado);
    echo json_encode($this->_result);
  }

  public function validaPIN()
  {

    $codigoPINEspecial  = (isset($_POST['codigoPINEspecial'])   ? $_POST['codigoPINEspecial']    : '');

    $this->_result = $this->bd_oracle->validaPIN_BD($codigoPINEspecial);

    if (count($this->_result) == 0) {
      $this->_result['Salida'] = "PIN o TARJETA no existe.";
      $this->_result['val']    = false;
    } else {
      foreach ($this->_result as $row) {
        if ($row->ESTADO == 1) {
          $this->_result['Salida'] = "PIN o TARJETA se encuentra entregado.";
          $this->_result['val']    = false;
        } else {
          $this->_result['Salida'] = "";
          $this->_result['val']    = true;
        }
      }
    }
    echo json_encode($this->_result);
  }


  public function excel_consulta()
  {
    //header("Content-Type: text/html;charset=utf-8");
    $select = (isset($_POST['select']) ? $_POST['select'] : '');
    $tipo   = (isset($_POST['tipo']) ? $_POST['tipo'] : '');
    $rutsup = (isset($_POST['textfield3']) ? $_POST['textfield3'] : '');
    $digsup = (isset($_POST['textfield4']) ? $_POST['textfield4'] : '');
    $rutcol = (isset($_POST['RUT']) ? $_POST['RUT'] : '');
    $digcol = (isset($_POST['strDiv']) ? $_POST['strDiv'] : '');
    $desde  = (isset($_POST['desde']) ? $_POST['desde'] : '');
    $hasta  = (isset($_POST['hasta']) ? $_POST['hasta'] : '');

    $nombre_archivo = 'reportePortalreconociemto_' . date('d-m-Y') . '.csv';
    //$sCSV           = $this->bd_oracle->reporteExcel($select,$tipo,$rutsup,$rutcol,$desde,$hasta);
    $sCSV           = $this->bd_oracle->reporteExcelFull($select, $tipo, $rutsup, $rutcol, $desde, $hasta);

    if ($sCSV != "") {
      // header('Content-Type: text/html; charset=UTF-8');
      header('Content-type: application/csv; charset=UTF-8');
      header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');
      echo $sCSV;
    } else {
      $this->session->set_flashdata('Error_file', 'No se encontro informaci&oacute;n relacionada a su consulta');
      redirect(base_url() . 'administrador/consulta/');
    }
  }

  public function excel_consulta2()
  {
    header("Content-Type: text/html;charset=utf-8");

    $array['select'] = (isset($_POST['select']) ? $_POST['select'] : '');
    $array['tipo']   = (isset($_POST['tipo']) ? $_POST['tipo'] : '');
    $array['rutsup'] = (isset($_POST['textfield3']) ? $_POST['textfield3'] : '');
    $array['digsup'] = (isset($_POST['textfield4']) ? $_POST['textfield4'] : '');
    $array['rutcol'] = (isset($_POST['RUT']) ? $_POST['RUT'] : '');
    $array['digcol'] = (isset($_POST['strDiv']) ? $_POST['strDiv'] : '');
    $array['desde']  = (isset($_POST['desde']) ? $_POST['desde'] : '');
    $array['hasta']  = (isset($_POST['hasta']) ? $_POST['hasta'] : '');

    $this->load->view('admin/bajardepCSVAdmin', $array);
  }
}//fin de la clase
