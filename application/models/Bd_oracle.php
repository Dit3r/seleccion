<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bd_oracle extends CI_Model
{

  private $_dbRRHH  = null;
  private $_dbIntra = null;
  private $_dbIntraDesa = null;
  private $_dbDesaRRHH = null;

  private $_result;
  private $_i = 0;
  private $array;
  private $_strSQL;


  function __construct()
  {

    parent::__construct();
    $this->_dbRRHH      = $this->load->database('dbRRHH', TRUE);
    $this->_dbIntra     = $this->load->database('default', TRUE);
    /*$this->_dbIntraDesa = $this->load->database('desaIntra',TRUE);
      $this->_dbDesaRRHH  = $this->load->database('desarrolloRRHH',TRUE);*/

    $this->load->library('session');
  }

  public function getTipoping()
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " SELECT ID_PIN,
                                               PIN_DESC
                                        FROM BIENESTAR_R_TIPOPIN
                                        WHERE ESTADO = 1 
                                        AND ID_PIN NOT IN('DPC')"
    );
    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }

  public function getTipoping2()
  {
    $this->_dbIntra->db_select();
    /*$query = $this->_dbIntra->query(" SELECT ID_PIN,
                                               PIN_DESC
                                        FROM BIENESTAR_R_TIPOPIN
                                        WHERE ESTADO = 1
                                        ORDER BY 1 DESC"
                                     );*/

    $query = $this->_dbIntra->query(
      " SELECT ID_PIN,
                                               PIN_DESC
                                        FROM INTRANET.BIENESTAR_R_TIPOPIN
                                        WHERE ID_PIN NOT IN('ADN','EOD','SPC','DAD')"
    );
    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }

  public function getDetalleColab($rut)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " SELECT DISTINCT
                                               A.ID,
                                               INITCAP(C.EMP_A_NOMBRE)NOMBRE,
                                               INITCAP( C.EMP_A_APELLPATER)APEPATER,
                                               INITCAP( C.EMP_A_APELLMATER)APEMATER,
                                               TO_CHAR(A.FECHA,'DD-MM-YYYY') FECHA,
                                               B.PIN_DESC,
                                               INITCAP(A.SECCION)SECCION,
                                               DECODE(A.ESTADO,'1','Ingresado','2','Validado') estado,
                                               A.OBS_RESPONSABLE as OBS_SUP,
                                               TO_CHAR(A.FECHA,'YYYYMMDD') FECH
                                        FROM BIENESTAR_PR_COLABORADOR A,
                                             BIENESTAR_R_TIPOPIN B,
                                             MAE_EMPLEADO C
                                        WHERE B.ID_PIN = A.TIPO
                                        AND C.EMP_K_RUTEMPLEAD = A.RUT_RESP
                                        --AND C.EMP_K_RUTEMPLEAD = A.RUT_COLAB
                                        --AND C.SYS_C_CODESTADO=1
                                        AND C.EMP_F_TERMICONTR = ( select max(EMP_F_TERMICONTR) FROM MAE_EMPLEADO WHERE emp_k_rutemplead = A.RUT_COLAB)
                                        AND A.RUT_COLAB = " . $rut . "
                                        ORDER BY 10 DESC  "
    );
    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }

  public function getResponsables($rut)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " SELECT distinct A.ID,
                                               INITCAP(C.EMP_A_NOMBRE)NOMBRE,
                                               INITCAP( C.EMP_A_APELLPATER)APEPATER,
                                               INITCAP( C.EMP_A_APELLMATER)APEMATER,
                                               TO_CHAR(A.FECHA,'DD-MM-YYYY') FECHA,
                                               B.PIN_DESC,
                                               INITCAP(A.SECCION)SECCION,
                                               DECODE(A.ESTADO,'1','Ingresado','2','Validado') estado,
                                               A.OBS_RESPONSABLE as OBS_SUP,
                                               TO_CHAR(A.FECHA,'YYYY')ANO
                                        FROM BIENESTAR_PR_COLABORADOR A,
                                             BIENESTAR_R_TIPOPIN B,
                                             MAE_EMPLEADO C
                                        WHERE B.ID_PIN = A.TIPO
                                        AND C.EMP_K_RUTEMPLEAD = A.RUT_COLAB                                       
                                        AND A.RUT_RESP = " . $rut . "
                                        ORDER BY A.ID DESC  "
    );
    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }

  public function getResponsablesPorAno($rut, $ano)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " SELECT distinct A.ID,
                                                   INITCAP(C.EMP_A_NOMBRE)NOMBRE,
                                                   INITCAP( C.EMP_A_APELLPATER)APEPATER,
                                                   INITCAP( C.EMP_A_APELLMATER)APEMATER,
                                                   TO_CHAR(A.FECHA,'DD-MM-YYYY') FECHA,
                                                   B.PIN_DESC,
                                                   INITCAP(A.SECCION)SECCION,
                                                   DECODE(A.ESTADO,'1','Ingresado','2','Validado') estado,
                                                   A.OBS_RESPONSABLE AS OBS_SUP,
                                                   TO_CHAR(A.FECHA,'YYYY')ANO,
                                                   TO_CHAR(A.FECHA,'YYYYMMDD') FECH
                                        FROM BIENESTAR_PR_COLABORADOR A,
                                             BIENESTAR_R_TIPOPIN B,
                                             MAE_EMPLEADO C
                                        WHERE B.ID_PIN = A.TIPO
                                        AND C.EMP_K_RUTEMPLEAD = A.RUT_COLAB                                       
                                        AND A.RUT_RESP = " . $rut . "
                                        AND TO_CHAR(A.FECHA,'YYYY')  =" . $ano . "
                                        AND C.EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_COLAB)
                                        ORDER BY 11 DESC  "
    );
    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }
  public function getResponsablesAnual($rut)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " SELECT distinct TO_CHAR(A.FECHA,'YYYY') ANO    
                                            FROM BIENESTAR_PR_COLABORADOR A,
                                                 BIENESTAR_R_TIPOPIN B
                                            WHERE B.ID_PIN = A.TIPO                          
                                            AND A.RUT_RESP = " . $rut . "
                                            ORDER BY 1 DESC"
    );

    if ($query) {
      $_result = $query->result();
    } else {
      $_result = '';
    }
    $this->_dbIntra->close();
    return $_result;
  }


  public function getDependienteResponsables($rut)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " SELECT distinct A.ID,
                                               INITCAP(C.EMP_A_NOMBRE)NOMBRE,
                                               INITCAP( C.EMP_A_APELLPATER)APEPATER,
                                               INITCAP( C.EMP_A_APELLMATER)APEMATER,
                                               TO_CHAR(A.FECHA,'DD-MM-YYYY') FECHA,
                                               B.PIN_DESC,
                                               INITCAP(A.SECCION)SECCION,
                                               DECODE(A.ESTADO,'1','Ingresado','2','Validado') estado,
                                               A.OBS_RESPONSABLE AS OBS_SUP
                                        FROM BIENESTAR_PR_COLABORADOR A,
                                             BIENESTAR_R_TIPOPIN B,
                                             MAE_EMPLEADO C
                                        WHERE B.ID_PIN = A.TIPO
                                        AND C.EMP_K_RUTEMPLEAD = A.RUT_COLAB                                       
                                        AND A.RUT_COLAB in(" . $rut . ")
                                        AND C.EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_COLAB)
                                        ORDER BY A.ID DESC  "
    );

    if ($query) {
      $_result = $query->result();
    } else {
      $_result = '';
    }

    $this->_dbIntra->close();
    return $_result;
  }
  public function getPINColab($id)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " SELECT A.ID,
                                               INITCAP(C.EMP_A_NOMBRE)NOMBRE,
                                               INITCAP( C.EMP_A_APELLPATER)APEPATER,
                                               INITCAP( C.EMP_A_APELLMATER)APEMATER,
                                               TO_CHAR(A.FECHA,'DD-MM-YYYY') FECHA,
                                               B.PIN_DESC,
                                               INITCAP(A.SECCION)SECCION,
                                               DECODE(A.ESTADO,'1','Ingresado','2','Validado') estado,
                                               A.OBS_RESPONSABLE AS OBS_SUP,
                                               INITCAP(A.OBS_COLAB)OBS_COLAB
                                        FROM BIENESTAR_PR_COLABORADOR A,
                                             BIENESTAR_R_TIPOPIN B,
                                             MAE_EMPLEADO C
                                        WHERE B.ID_PIN = A.TIPO
                                        AND C.EMP_K_RUTEMPLEAD = A.RUT_RESP
                                        AND C.SYS_C_CODESTADO=1
                                        AND A.id =" . $id . "
                                        ORDER BY A.ID DESC"
    );

    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }

  public function getPINColaborador($id)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " SELECT A.ID,
                                               INITCAP(C.EMP_A_NOMBRE)NOMBRE,
                                               INITCAP( C.EMP_A_APELLPATER)APEPATER,
                                               INITCAP( C.EMP_A_APELLMATER)APEMATER,
                                               TO_CHAR(A.FECHA,'DD-MM-YYYY') FECHA,
                                               B.PIN_DESC,
                                               INITCAP(A.SECCION)SECCION,
                                               DECODE(A.ESTADO,'1','Ingresado','2','Validado') estado,
                                               A.OBS_RESPONSABLE AS OBS_SUP,
                                               (SELECT INITCAP(EMP_A_NOMBRE||' '||EMP_A_APELLPATER||' '||EMP_A_APELLMATER) 
                                                FROM MAE_EMPLEADO 
                                                WHERE EMP_K_RUTEMPLEAD = A.RUT_RESP 
                                                AND ROWNUM=1)OBS_COLAB,
                                                A.RUT_RESP                                                
                                        FROM BIENESTAR_PR_COLABORADOR A,
                                             BIENESTAR_R_TIPOPIN B,
                                             MAE_EMPLEADO C
                                        WHERE B.ID_PIN = A.TIPO
                                        AND C.EMP_K_RUTEMPLEAD = A.RUT_COLAB
                                        AND C.SYS_C_CODESTADO=1
                                        AND A.id =" . $id . "
                                        ORDER BY A.ID DESC"

    );

    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }


  public function getDetalleSUPColab($id)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " SELECT distinct
                                                   A.ID,
                                                   INITCAP(C.EMP_A_NOMBRE)NOMBRE,
                                                   INITCAP( C.EMP_A_APELLPATER)APEPATER,
                                                   INITCAP( C.EMP_A_APELLMATER)APEMATER,
                                                   TO_CHAR(A.FECHA,'DD-MM-YYYY') FECHA,
                                                   B.PIN_DESC,
                                                   INITCAP(A.SECCION)SECCION,
                                                   DECODE(A.ESTADO,'1','Ingresado','2','Validado') estado,
                                                   A.OBS_RESPONSABLE as OBS_SUP,
                                                   --INITCAP(A.OBS_COLAB)OBS_COLAB
                                                   (SELECT INITCAP(EMP_A_NOMBRE||' '||EMP_A_APELLPATER||' '||EMP_A_APELLMATER) 
                                                FROM MAE_EMPLEADO 
                                                WHERE EMP_K_RUTEMPLEAD = A.RUT_RESP 
                                                AND ROWNUM=1)OBS_COLAB,
                                                A.RUT_RESP
                                            FROM BIENESTAR_PR_COLABORADOR A,
                                                 BIENESTAR_R_TIPOPIN B,
                                                 MAE_EMPLEADO C
                                            WHERE B.ID_PIN = A.TIPO
                                            AND C.EMP_K_RUTEMPLEAD = A.RUT_COLAB
                                            AND A.id =" . $id . "
                                            ORDER BY A.ID DESC"
    );

    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }

  public function getPIN_Colab($id)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " SELECT A.ID,
                                               INITCAP(C.EMP_A_NOMBRE)NOMBRE,
                                               INITCAP( C.EMP_A_APELLPATER)APEPATER,
                                               INITCAP( C.EMP_A_APELLMATER)APEMATER,
                                               TO_CHAR(A.FECHA,'DD-MM-YYYY') FECHA,
                                               B.PIN_DESC,
                                               INITCAP(A.SECCION)SECCION,
                                               DECODE(A.ESTADO,'1','Ingresado','2','Validado') estado,
                                               A.OBS_RESPONSABLE AS OBS_SUP,
                                               (SELECT INITCAP(EMP_A_NOMBRE||' '||EMP_A_APELLPATER||' '||EMP_A_APELLMATER) 
                                                FROM MAE_EMPLEADO 
                                                WHERE EMP_K_RUTEMPLEAD = A.RUT_RESP 
                                                AND ROWNUM=1)OBS_COLAB,
                                                A.RUT_RESP
                                        FROM BIENESTAR_PR_COLABORADOR A,
                                             BIENESTAR_R_TIPOPIN B,
                                             MAE_EMPLEADO C
                                        WHERE B.ID_PIN = A.TIPO
                                        AND C.EMP_K_RUTEMPLEAD = A.RUT_COLAB
                                        AND C.SYS_C_CODESTADO=1
                                        AND A.id =" . $id . "
                                        ORDER BY A.ID DESC"
    );

    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }

  public function getUpdatePINColab($estado, $id, $obs)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " UPDATE BIENESTAR_PR_COLABORADOR 
                                        SET ESTADO='" . $estado . "', 
                                        OBS_COLAB='" . $obs . "',
                                        FECHA_VALID = SYSDATE 
                                        WHERE ID=" . $id
    );
    if ($query) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function getUpdatePINColaborador($id)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " UPDATE BIENESTAR_PR_COLABORADOR 
                                        SET ESTADO='2',
                                        FECHA_VALID = SYSDATE  
                                        WHERE ID=" . $id
    );
    if ($query) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function getContador($id)
  {
    $this->_dbIntra->db_select();
    /*$query = $this->_dbIntra->query(" SELECT contador 
                                        FROM BIENESTAR_PR_COLABORADOR 
                                        where id =".$id."
                                          and SECCION ='PIN RECONOCIMIENTO'
                                          and fecha BETWEEN to_date('01-01-'||to_char(sysdate,'YYYY'),'dd-mm-yy') and  to_date('31-12-'||to_char(sysdate,'YYYY'),'dd-mm-yy')
                                          AND ESTADO_PERIODO IS NULL "
                                        
                                     );*/

    $query = $this->_dbIntra->query(" SELECT contador 
                                        FROM BIENESTAR_PR_COLABORADOR 
                                        where id =" . $id . "
                                          and SECCION in('PIN RECONOCIMIENTO','PIN CSI','PIN IGS')
                                          and fecha BETWEEN to_date('01-01-'||to_char(fecha,'YYYY'),'dd-mm-yy') and  to_date('31-12-'||to_char(fecha,'YYYY'),'dd-mm-yy')
                                          AND ESTADO_PERIODO IS NULL ");
    $_result = $query->result();

    $this->_dbIntra->close();
    return $_result;
  }

  public function sliderArchivo($url, $fechaI, $fechaT, $nombreFile)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(" INSERT INTO BIENESTAR_PR_SLIDER VALUES (get_nextval_SLIDER,
                                                                                '" . $nombreFile . "',
                                                                                '" . $url . "',
                                                                                SYSDATE,
                                                                                1,
                                                                                TO_DATE('" . $fechaI . "','DD-MM-YY'),
                                                                                TO_DATE('" . $fechaT . "','DD-MM-YY'),
                                                                                ''
                                                                                ) ");

    if ($query) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  /*public function pdfArchivo($titulo,$fechaI,$fechaT,$nombreFile,$estado){
      
      $this->_dbIntra->db_select();
     
      $query = $this->_dbIntra->query(" INSERT INTO BIENESTAR_PR_ARCHIVOS VALUES (GER_NEXTVAL_BIEN_FILE,
                                                                                        'A',
                                                                                        '".$titulo."',
                                                                                        '".$nombreFile."',
                                                                                        SYSDATE,
                                                                                        TO_DATE('".$fechaI."','DD-MM-YY'),
                                                                                        TO_DATE('".$fechaT."','DD-MM-YY'),
                                                                                        '".$estado."'
                                                                                        ) ");
      
       if($query){
         $_result = true;
       }else{
         $_result = false;
       }
       
      $this->_dbIntra->close();      
      return $_result;
   }*/

  /* public function editardocumentosPDF(){
      $this->_dbIntra->db_select();
      $query = $this->_dbIntra->query("  select TITULO,
                                                   ARCHIVO
                                             from BIENESTAR_PR_ARCHIVOS
                                             WHERE ESTADO='P'"
                                         );
                                                                          
       if ($query->num_rows() > 0)
        {
            $_result = $query->result();
        }else{
            $_result = array();
        }
      $this->_dbIntra->close();      
      return $_result;
   }*/

  public function editar_slider()
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      "  SELECT ID,
                                                NOM_IMAGEN,
                                                URL,
                                                ESTADO,
                                                TO_CHAR(FINICIO,'DD-MM-YYYY')INICIO,
                                                TO_CHAR(FTERMINO,'DD-MM-YYYY')TERMINO
                                         FROM BIENESTAR_PR_SLIDER
                                         "
    );

    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }

  public function sliderHOME()
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      "  SELECT DISTINCT
                                                NOM_IMAGEN,
                                                URL,
                                                ID
                                         FROM BIENESTAR_PR_SLIDER
                                         WHERE ESTADO=1
                                         AND TO_CHAR(FTERMINO,'YYYYMMDD') >= TO_CHAR(SYSDATE,'YYYYMMDD')
                                         ORDER BY NOM_IMAGEN ASC"
    );

    $_result = $query->result_array();
    $this->_dbIntra->close();

    return $_result;
  }

  /*public function editar_pdfs(){
      $this->_dbIntra->db_select();
      $query = $this->_dbIntra->query("  SELECT ID,
                                                ARCHIVO,
                                                TITULO,
                                                decode(ESTADO,'P','Publicado','B','Bloqueado')ESTADO,
                                                TO_CHAR(FECHA_INICIO,'DD-MM-YYYY')INICIO,
                                                TO_CHAR(FECHA_TERMINO,'DD-MM-YYYY')TERMINO
                                         FROM BIENESTAR_PR_ARCHIVOS"
                                     );
                                                                          
      $_result = $query->result();
      $this->_dbIntra->close();      
      return $_result;
   }*/

  public function slider()
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      "  SELECT NOM_IMAGEN,
                                                URL,
                                                ID
                                         FROM BIENESTAR_PR_SLIDER
                                         WHERE ESTADO=1
                                         AND TO_CHAR(FTERMINO,'DD-MM-YYYY') >= TO_CHAR(SYSDATE,'DD-MM-YYYY')
                                         ORDER BY ID ASC"
    );

    $_result = $query->result_array();
    $this->_dbIntra->close();

    return $_result;
  }
  public function update_slider($id, $desde, $hasta, $estado, $url)
  {
    $this->_dbIntra->db_select();

    $this->_strSQL = " UPDATE BIENESTAR_PR_SLIDER SET 
                                                          URL='" . $url . "',
                                                          ESTADO='" . $estado . "',
                                                          FINICIO  = TO_DATE('" . $desde . "','DD-MM-YY'),
                                                          FTERMINO = TO_DATE('" . $hasta . "','DD-MM-YY')
                          WHERE ID = " . $id;

    $query = $this->_dbIntra->query($this->_strSQL);

    if ($query) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }


  public function cargaPINE($codPin)
  {
    $this->_dbIntra->db_select();

    $this->_strSQL = " SELECT * FROM BIENESTAR_PR_ASIGNAR 
                         WHERE COD_PIN = '" . $codPin . "' ";
    $query = $this->_dbIntra->query($this->_strSQL);
    if ($query->num_rows() > 0) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function revisaPINES($tipo, $codPin)
  {
    $this->_dbIntra->db_select();

    $this->_strSQL = " SELECT * FROM BIENESTAR_PR_ASIGNAR 
                         WHERE TIPO='" . $tipo . "'
                         AND COD_PIN = '" . $codPin . "' ";
    $query = $this->_dbIntra->query($this->_strSQL);
    if ($query->num_rows() > 0) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function ingresoPIN($rutResp, $motivo, $codPin, $tipo, $cui, $observ)
  {

    $this->_dbIntra->db_select();

    $strSQL = " INSERT INTO BIENESTAR_PR_ASIGNAR VALUES(  get_nextval_TIPO_PIN,
                                                            '" . trim($rutResp) . "',
                                                            '" . trim($motivo) . "',
                                                            '" . trim($codPin) . "',
                                                            '',
                                                            SYSDATE,
                                                            '" . trim($tipo) . "',
                                                            '" . trim($cui) . "',
                                                            '',
                                                            '" . $observ . "'
                                                         ) ";

    $query = $this->_dbIntra->query($strSQL);
    if ($query) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function cambioCUIAsignar($codPin, $cui, $OBS)
  {

    $this->_dbIntra->db_select();

    $strSQL = " UPDATE BIENESTAR_PR_ASIGNAR 
                  SET NIVEL        ='" . $cui . "',
                      OBSERVACION  ='" . $OBS . "',
                      FECHA_UPDATE = SYSDATE
                  WHERE COD_PIN ='" . $codPin . "'";

    $query = $this->_dbIntra->query($strSQL);
    if ($query) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function cambioCUIColaborador($codPin, $cui)
  {

    $this->_dbIntra->db_select();

    $strSQL = " UPDATE BIENESTAR_PR_COLABORADOR 
                  SET NIVEL_SUP ='" . $cui . "' 
                  WHERE PIN     ='" . $codPin . "'";
    $query = $this->_dbIntra->query($strSQL);
    if ($query) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function validaPINColaborador($codPin)
  {

    $this->_dbIntra->db_select();

    $strSQL = " SELECT *FROM BIENESTAR_PR_COLABORADOR 
                  WHERE PIN = '" . $codPin . "'";
    $query = $this->_dbIntra->query($strSQL);

    if ($query->num_rows() > 0) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }


  public function cambioRUTAsignar($rutResp, $codPin, $OBS)
  {

    $this->_dbIntra->db_select();

    $strSQL = " UPDATE BIENESTAR_PR_ASIGNAR 
                  SET    RUT          ='" . $rutResp . "',
                         FECHA_UPDATE = SYSDATE,
                         OBSERVACION  = '" . $OBS . "'
                  WHERE COD_PIN ='" . $codPin . "'";
    $query = $this->_dbIntra->query($strSQL);
    if ($query) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function eliminarPINAsignar($codPin)
  {

    $this->_dbIntra->db_select();

    $strSQL = " DELETE FROM BIENESTAR_PR_ASIGNAR 
                  WHERE COD_PIN = '" . $codPin . "'";
    $query = $this->_dbIntra->query($strSQL);
    if ($query) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function eliminarPINColaborador($codPin)
  {

    $this->_dbIntra->db_select();

    $strSQL = " DELETE FROM BIENESTAR_PR_COLABORADOR 
                  WHERE PIN = '" . $codPin . "'";
    $query = $this->_dbIntra->query($strSQL);
    if ($query) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function excelcolaboradorpinfull()
  {

    $this->_dbIntra->db_select();
    $this->load->dbutil();
    $delimitador = ";";
    $nueva_linea = "\r\n";

    $strSQL = " SELECT   RUT,
                           TIPO,
                           COD_PIN,
                           PROPIEDAD,
                           '''' ||NIVEL AS CUI,
                           DECODE(ESTADO,NULL,'',1,'ENTREGADO')ESTADO,
                           TO_CHAR(FECHA,'DD-MM-YYYY') FECHA_CARGA
                    FROM BIENESTAR_PR_ASIGNAR ";
    $query = $this->_dbIntra->query($strSQL);


    $this->_dbIntra->close();
    return $this->dbutil->csv_from_result($query, $delimitador, $nueva_linea);
  }

  public function reporteExcel($session, $tipo, $rut, $rutcol, $finicio, $ftermino)
  {

    $this->_dbIntra->db_select();
    $this->load->dbutil();
    $delimitador = ";";
    $nueva_linea = "\r\n";

    $strSQL = " SELECT  DISTINCT
                             A.RUT_RESP AS RUT_RESPONSABLE,
                             (SELECT EMP_A_NOMBRE||' '||EMP_A_APELLPATER ||' '||EMP_A_APELLMATER FROM MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_RESP AND ROWNUM=1)NOMBRE_RESPONSABLE,
                             (SELECT I.CIA_K_EMPRESA||'-'||INITCAP(I.CIA_G_RAZONSOCIA)EMPRESA
                              FROM MAE_EMPLEADO H,
                                   MAE_CIAEMPRESA I
                              WHERE H.EMP_K_RUTEMPLEAD = A.RUT_RESP 
                              AND H.SYS_C_CODESTADO IN(1,5)
                              AND H.CIA_K_EMPRESA    = I.CIA_K_EMPRESA
                              AND H.EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_RESP)
                              AND ROWNUM=1) EMPRESA,
                             (SELECT H.UNI_K_CODUNIDAD||'-'||INITCAP(I.UNI_A_NOMBUNIDAD)CCOSTO
                              FROM MAE_EMPLEADO H,
                                   MAE_UNIDADES I
                              WHERE H.EMP_K_RUTEMPLEAD = A.RUT_RESP 
                              AND H.SYS_C_CODESTADO IN(1,5)
                              AND H.CIA_K_EMPRESA    = I.CIA_K_EMPRESA
                              AND H.UNI_K_CODUNIDAD  = I.UNI_K_CODUNIDAD
                              AND H.EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_RESP)
                              AND ROWNUM=1)CCOSTO,
                             A.RUT_COLAB,
                             INITCAP(C.EMP_A_NOMBRE||' '||C.EMP_A_APELLPATER ||' '||C.EMP_A_APELLMATER)NOMBRE_COLABORADOR,
                             (SELECT I.CIA_K_EMPRESA||'-'||INITCAP(I.CIA_G_RAZONSOCIA)EMPRESA
                              FROM MAE_EMPLEADO H,
                                   MAE_CIAEMPRESA I
                              WHERE H.EMP_K_RUTEMPLEAD = A.RUT_COLAB 
                              AND H.SYS_C_CODESTADO IN(1,5)
                              AND H.CIA_K_EMPRESA    = I.CIA_K_EMPRESA
                              AND H.EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_COLAB)
                              AND ROWNUM=1) EMPRESA_COLAB,
                             (SELECT H.UNI_K_CODUNIDAD||'-'||INITCAP(I.UNI_A_NOMBUNIDAD)CCOSTO
                              FROM MAE_EMPLEADO H,
                                   MAE_UNIDADES I
                              WHERE H.EMP_K_RUTEMPLEAD = A.RUT_COLAB 
                              AND H.SYS_C_CODESTADO IN(1,5)
                              AND H.CIA_K_EMPRESA    = I.CIA_K_EMPRESA
                              AND H.UNI_K_CODUNIDAD  = I.UNI_K_CODUNIDAD
                              AND H.EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_COLAB)
                              AND ROWNUM=1)CCOSTO_COLAB,       
                             A.SECCION,
                             A.TIPO,
                             B.PIN_DESC,
                             A.PIN CODIGO_PIN,
                             TO_CHAR(A.FECHA,'DD-MM-YYYY') FECHA_INGRESO, 
                             TO_CHAR(A.FECHA_VALID,'DD-MM-YYYY') FECHA_VALIDACION, 
                             INITCAP(A.OBS_RESPONSABLE)OBS_RESPONSABLE,
                             A.CONTADOR CONTADOR_DIA,
                             DECODE(A.ESTADO,'1','Ingresado','2','Validado') estado                              
                    FROM BIENESTAR_PR_COLABORADOR A,
                         BIENESTAR_R_TIPOPIN B,
                         MAE_EMPLEADO C
                    WHERE B.ID_PIN = A.TIPO
                    AND C.EMP_K_RUTEMPLEAD = A.RUT_COLAB
                    --AND C.SYS_C_CODESTADO=1
                    AND C.EMP_F_TERMICONTR = TO_DATE((SELECT max(TO_CHAR(EMP_F_TERMICONTR,'DD-MM-YYYY')) FECHA 
                                              FROM MAE_EMPLEADO 
                                              WHERE EMP_K_RUTEMPLEAD = A.RUT_COLAB 
                                              ),'DD-MM-YY')";
    if ($session != "") {
      $strSQL .= " AND A.SECCION = '" . $session . "' ";
    }

    if ($rutcol != "") {
      $strSQL .= " AND A.RUT_COLAB = '" . $rutcol . "' ";
    }

    if ($tipo != "") {
      $strSQL .= " AND A.TIPO ='" . $tipo . "'";
    }

    if ($rut != "") {
      $strSQL .= " AND A.RUT_RESP=" . $rut;
    }

    if ($finicio != "" && $ftermino != "") {
      $strSQL .= " AND A.FECHA BETWEEN to_date('" . $finicio . "','dd-mm-yyyy') AND to_date('" . $ftermino . "','dd-mm-yyyy')";
    }

    $strSQL .= " ORDER BY 13 DESC";
    $query = $this->_dbIntra->query($strSQL);
    $this->_dbIntra->close();
    if ($query) {
      if ($query->num_rows() > 0) {
        return $this->dbutil->csv_from_result($query, $delimitador, $nueva_linea);
      } else {
        return "";
      }
    } else {
      return "";
    }
  }

  public function reporteExcelFull($session, $tipo, $rut, $rutcol, $finicio, $ftermino)
  {

    $this->_dbIntra->db_select();
    $this->load->dbutil();
    $delimitador = ";";
    $nueva_linea = "\r\n";

    /* $strSQL = " SELECT  DISTINCT
                             A.RUT_RESP AS RUT_RESPONSABLE,
                             (SELECT EMP_A_NOMBRE||' '||EMP_A_APELLPATER ||' '||EMP_A_APELLMATER FROM VM_MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_RESP AND ROWNUM=1)NOMBRE_RESPONSABLE,
                             (SELECT I.CIA_K_EMPRESA||'-'||INITCAP(I.CIA_G_RAZONSOCIA)EMPRESA
                              FROM VM_MAE_EMPLEADO H,
                                   MAE_CIAEMPRESA I
                              WHERE H.EMP_K_RUTEMPLEAD = A.RUT_RESP 
                              AND H.SYS_C_CODESTADO IN(1,5)
                              AND H.CIA_K_EMPRESA    = I.CIA_K_EMPRESA
                              AND H.EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM VM_MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_RESP)
                              AND ROWNUM=1) EMPRESA,
                              (SELECT FN_GERENCIA_CUI(A.RUT_RESP) FROM DUAL)GERENCIA,
                             (SELECT H.UNI_K_CODUNIDAD||'-'||INITCAP(I.UNI_A_NOMBUNIDAD)CCOSTO
                              FROM VM_MAE_EMPLEADO H,
                                   mae_unidades_2 I
                              WHERE H.EMP_K_RUTEMPLEAD = A.RUT_RESP 
                              AND H.SYS_C_CODESTADO IN(1,5)
                              AND H.CIA_K_EMPRESA    = I.CIA_K_EMPRESA
                              AND H.UNI_K_CODUNIDAD  = I.UNI_K_CODUNIDAD
                              AND H.EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM VM_MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_RESP)
                              AND ROWNUM=1)CCOSTO,
                              (SELECT EMP_A_NOMBRE||' '||EMP_A_APELLPATER ||' '||EMP_A_APELLMATER FROM VM_MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD =(select fn_jefe_directo_cui(A.RUT_RESP) from dual) AND ROWNUM=1)NOMBRE_JEFE,
                             A.RUT_COLAB,
                             INITCAP(C.EMP_A_NOMBRE||' '||C.EMP_A_APELLPATER ||' '||C.EMP_A_APELLMATER)NOMBRE_COLABORADOR,
                             (SELECT I.CIA_K_EMPRESA||'-'||INITCAP(I.CIA_G_RAZONSOCIA)EMPRESA
                              FROM VM_MAE_EMPLEADO H,
                                   MAE_CIAEMPRESA I
                              WHERE H.EMP_K_RUTEMPLEAD = A.RUT_COLAB 
                              AND H.SYS_C_CODESTADO IN(1,5)
                              AND H.CIA_K_EMPRESA    = I.CIA_K_EMPRESA
                              AND H.EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM VM_MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_COLAB)
                              AND ROWNUM=1) EMPRESA_COLAB,
                              (SELECT FN_GERENCIA_CUI(A.RUT_COLAB) FROM DUAL)GERENCIA_COLAB,        
                             (SELECT H.UNI_K_CODUNIDAD||'-'||INITCAP(I.UNI_A_NOMBUNIDAD)CCOSTO
                              FROM VM_MAE_EMPLEADO H,
                                   mae_unidades_2 I
                              WHERE H.EMP_K_RUTEMPLEAD = A.RUT_COLAB 
                              AND H.SYS_C_CODESTADO IN(1,5)
                              AND H.CIA_K_EMPRESA    = I.CIA_K_EMPRESA
                              AND H.UNI_K_CODUNIDAD  = I.UNI_K_CODUNIDAD
                              AND H.EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM VM_MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_COLAB)
                              AND ROWNUM=1)CCOSTO_COLAB,  
                              (SELECT EMP_A_NOMBRE||' '||EMP_A_APELLPATER ||' '||EMP_A_APELLMATER FROM VM_MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD =(select fn_jefe_directo_cui(A.RUT_COLAB) from dual) AND ROWNUM=1)NOMBRE_JEFE_COLAB,              
                             A.SECCION,
                             A.TIPO,
                             B.PIN_DESC,
                             A.PIN CODIGO_PIN,
                             TO_CHAR(A.FECHA,'DD-MM-YYYY') FECHA_INGRESO, 
                             TO_CHAR(A.FECHA_VALID,'DD-MM-YYYY') FECHA_VALIDACION, 
                             INITCAP(A.OBS_RESPONSABLE)OBS_RESPONSABLE,
                             A.CONTADOR CONTADOR_DIA,
                             DECODE(A.ESTADO,'1','Ingresado','2','Validado') estado                              
                    FROM BIENESTAR_PR_COLABORADOR A,
                         BIENESTAR_R_TIPOPIN B,
                         VM_MAE_EMPLEADO C
                    WHERE B.ID_PIN = A.TIPO
                    AND C.EMP_K_RUTEMPLEAD = A.RUT_COLAB
                    --AND C.SYS_C_CODESTADO=1
                    AND C.EMP_F_TERMICONTR = TO_DATE((SELECT max(TO_CHAR(EMP_F_TERMICONTR,'DD-MM-YYYY')) FECHA 
                                              FROM VM_MAE_EMPLEADO 
                                              WHERE EMP_K_RUTEMPLEAD = A.RUT_COLAB 
                                              ),'DD-MM-YY')";        
                    if($session!=""){
                          $strSQL .= " AND A.SECCION = '".$session."' ";
                    }      
                    
                    if($rutcol!=""){
                          $strSQL .= " AND A.RUT_COLAB = '".$rutcol."' ";
                    }
                    
                    if($tipo!=""){
                        $strSQL .= " AND A.TIPO ='".$tipo."'";
                    }
                    
                    if($rut!=""){
                        $strSQL .= " AND A.RUT_RESP=".$rut;
                    }
                    
                    if($finicio!="" && $ftermino !=""){
                        $strSQL .= " AND A.FECHA BETWEEN to_date('".$finicio."','dd-mm-yyyy') AND to_date('".$ftermino."','dd-mm-yyyy')";
                    }
                     
                  $strSQL .= " ORDER BY 13 DESC";*/

    $strSQL = " SELECT  DISTINCT
                             A.RUT_RESP AS RUT_RESPONSABLE,
                             (SELECT EMP_A_NOMBRE||' '||EMP_A_APELLPATER ||' '||EMP_A_APELLMATER 
                             FROM MAE_EMPLEADO 
                             WHERE EMP_K_RUTEMPLEAD = A.RUT_RESP 
                               AND EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_RESP)
                             AND ROWNUM=1)NOMBRE_RESPONSABLE,
                             (SELECT I.CIA_K_EMPRESA||'-'||INITCAP(I.CIA_G_RAZONSOCIA)EMPRESA
                              FROM MAE_EMPLEADO H,
                                   MAE_CIAEMPRESA I
                              WHERE H.EMP_K_RUTEMPLEAD = A.RUT_RESP 
                              AND H.SYS_C_CODESTADO IN(1,5)
                              AND H.CIA_K_EMPRESA    = I.CIA_K_EMPRESA
                              AND H.EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_RESP)
                              AND ROWNUM=1) EMPRESA,
                             (SELECT H.UNI_K_CODUNIDAD||'-'||INITCAP(I.UNI_A_NOMBUNIDAD)CCOSTO
                              FROM MAE_EMPLEADO H,
                                   MAE_UNIDADES I
                              WHERE H.EMP_K_RUTEMPLEAD = A.RUT_RESP 
                              AND H.SYS_C_CODESTADO IN(1,5)
                              AND H.CIA_K_EMPRESA    = I.CIA_K_EMPRESA
                              AND H.UNI_K_CODUNIDAD  = I.UNI_K_CODUNIDAD
                              AND H.EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_RESP)
                              AND ROWNUM=1)CCOSTO,
                             A.RUT_COLAB,
                             INITCAP(C.EMP_A_NOMBRE||' '||C.EMP_A_APELLPATER ||' '||C.EMP_A_APELLMATER)NOMBRE_COLABORADOR,
                             (SELECT I.CIA_K_EMPRESA||'-'||INITCAP(I.CIA_G_RAZONSOCIA)EMPRESA
                              FROM MAE_EMPLEADO H,
                                   MAE_CIAEMPRESA I
                              WHERE H.EMP_K_RUTEMPLEAD = A.RUT_COLAB 
                              AND H.SYS_C_CODESTADO IN(1,5)
                              AND H.CIA_K_EMPRESA    = I.CIA_K_EMPRESA
                              AND H.EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_COLAB)
                              AND ROWNUM=1) EMPRESA_COLAB,
                             (SELECT H.UNI_K_CODUNIDAD||'-'||INITCAP(I.UNI_A_NOMBUNIDAD)CCOSTO
                              FROM MAE_EMPLEADO H,
                                   MAE_UNIDADES I
                              WHERE H.EMP_K_RUTEMPLEAD = A.RUT_COLAB 
                              AND H.SYS_C_CODESTADO IN(1,5)
                              AND H.CIA_K_EMPRESA    = I.CIA_K_EMPRESA
                              AND H.UNI_K_CODUNIDAD  = I.UNI_K_CODUNIDAD
                              AND H.EMP_F_TERMICONTR = (SELECT max(EMP_F_TERMICONTR) FECHA FROM MAE_EMPLEADO WHERE EMP_K_RUTEMPLEAD = A.RUT_COLAB)
                              AND ROWNUM=1)CCOSTO_COLAB,       
                             A.SECCION,
                             A.TIPO,
                             B.PIN_DESC,
                             A.PIN CODIGO_PIN,
                             TO_CHAR(A.FECHA,'DD-MM-YYYY') FECHA_INGRESO, 
                             TO_CHAR(A.FECHA_VALID,'DD-MM-YYYY') FECHA_VALIDACION, 
                             INITCAP(A.OBS_RESPONSABLE)OBS_RESPONSABLE,
                             A.CONTADOR CONTADOR_DIA,
                             DECODE(A.ESTADO,'1','Ingresado','2','Validado') estado                              
                    FROM BIENESTAR_PR_COLABORADOR A,
                         BIENESTAR_R_TIPOPIN B,
                         MAE_EMPLEADO C
                    WHERE B.ID_PIN = A.TIPO
                    AND C.EMP_K_RUTEMPLEAD = A.RUT_COLAB
                    --AND C.SYS_C_CODESTADO=1
                    AND C.EMP_F_TERMICONTR = TO_DATE((SELECT max(TO_CHAR(EMP_F_TERMICONTR,'DD-MM-YYYY')) FECHA 
                                              FROM MAE_EMPLEADO 
                                              WHERE EMP_K_RUTEMPLEAD = A.RUT_COLAB 
                                              ),'DD-MM-YY')";
    if ($session != "") {
      $strSQL .= " AND A.SECCION = '" . $session . "' ";
    }

    if ($rutcol != "") {
      $strSQL .= " AND A.RUT_COLAB = '" . $rutcol . "' ";
    }

    if ($tipo != "") {
      $strSQL .= " AND A.TIPO ='" . $tipo . "'";
    }

    if ($rut != "") {
      $strSQL .= " AND A.RUT_RESP=" . $rut;
    }

    if ($finicio != "" && $ftermino != "") {
      $strSQL .= " AND A.FECHA BETWEEN to_date('" . $finicio . "','dd-mm-yyyy') AND to_date('" . $ftermino . "','dd-mm-yyyy')";
    }

    $strSQL .= " ORDER BY 13 DESC";
    $query = $this->_dbIntra->query($strSQL);
    $this->_dbIntra->close();

    if ($query) {
      if ($query->num_rows() > 0) {
        return $this->dbutil->csv_from_result($query, $delimitador, $nueva_linea);
      } else {
        return "";
      }
    } else {
      return "";
    }
  }

  public function strTraNombreSup($rsRut)
  {
    $this->_dbIntra->db_select();

    $strSQL = " SELECT EMP_A_NOMBRE||' '||EMP_A_APELLPATER ||' '||EMP_A_APELLMATER 
			    FROM MAE_EMPLEADO 
				 WHERE EMP_K_RUTEMPLEAD = " . $rsRut . " 
				   AND ROWNUM=1 ";
    $this->_dbIntra->close();
  }
  public function strGerencia($rsRut)
  {
    $this->_dbRRHH->db_select();
    $strSQL = "SELECT FN_GERENCIA_CUI(" . $rsRut . ") FROM DUAL";
    $this->_dbRRHH->close();
  }

  public function strTraeSup($rsRut)
  {
    $this->_dbRRHH->db_select();
    $strSQL = " select fn_jefe_directo_cui(" . $rsRut . ") from dual";
    $this->_dbRRHH->close();
  }

  //actualizarPIn
  public function buscarPinAsignado($select, $tipo, $codigoPin, $cui)
  {

    $this->_dbIntra->db_select();

    $where = "WHERE";
    $Vand  = "AND";
    $Tip   = "";
    $strSQL = " SELECT ID,
                             RUT,
                             TIPO,
                             COD_PIN,
                             decode(ESTADO,null,'',1,'Entregado') as estado,
                             PROPIEDAD,
                             NIVEL                                
                        FROM BIENESTAR_PR_ASIGNAR ";

    if ($select != "") {
      if ($Tip == "") {
        $Tip = 1;
        $strSQL .= $where . " PROPIEDAD = '" . $select . "' ";
      } else {
        $strSQL .=  $Vand . " PROPIEDAD = '" . $select . "' ";
      }
    }

    if ($tipo != "") {
      if ($Tip == "") {
        $Tip = 1;
        $strSQL .= $where . " TIPO = '" . $tipo . "' ";
      } else {
        $strSQL .= $Vand . " TIPO = '" . $tipo . "' ";
      }
    }

    if ($codigoPin != "") {
      if ($Tip == "") {
        $Tip = 1;
        $strSQL .= $where . " COD_PIN = '" . $codigoPin . "' ";
      } else {
        $strSQL .= $Vand . " COD_PIN = '" . $codigoPin . "' ";
      }
    }

    if ($cui != "") {
      if ($Tip == "") {
        $Tip = 1;
        $strSQL .= $where . " NIVEL = '" . $cui . "' ";
      } else {
        $strSQL .= $Vand . " NIVEL = '" . $cui . "' ";
      }
    }

    $strSQL .= " ORDER BY 1 DESC";
    $query = $this->_dbIntra->query($strSQL);
    $this->_dbIntra->close();

    if ($query) {

      if ($query->num_rows() > 0) {
        return $query->result();
      } else {
        return array();
      }
    } else {
      return array();
    }
  }


  public function buscarPINES($PIN)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " SELECT *
                                            FROM BIENESTAR_PR_ASIGNAR 
                                            WHERE COD_PIN='" . $PIN . "'"
    );


    if ($query->num_rows() > 0) {
      $_result = $query->result();
    } else {
      $_result = array();
    }
    $this->_dbIntra->close();

    return $_result;
  }

  public function actualizaCUIPIN($idPin, $tipo, $codigoPin, $cui, $rut, $IdSelect, $idEstado)
  {

    $this->_dbIntra->db_select();

    $strSQL = " UPDATE BIENESTAR_PR_ASIGNAR 
                       SET PROPIEDAD='" . $tipo . "', 
                           RUT ='" . $rut . "',
                           TIPO='" . $IdSelect . "',
                           COD_PIN='" . $codigoPin . "', 
                           NIVEL='" . $cui . "',
                           ESTADO='" . $idEstado . "' 
                           where ID =" . $idPin;
    $query = $this->_dbIntra->query($strSQL);
    $this->_dbIntra->close();
    if ($query) {
      return true;
    } else {
      return false;
    }
  }

  public function consultaPINColab($id_pin)
  {
    $this->_dbIntra->db_select();

    $strSQL = "SELECT *
                    FROM BIENESTAR_PR_COLABORADOR
                    WHERE PIN='" . $id_pin . "'";
    $query = $this->_dbIntra->query($strSQL);
    $this->_dbIntra->close();

    if ($query->num_rows() > 0) {
      return true;
    } else {
      return false;
    }
  }

  public function updateContadorColaborado($id_pin, $num)
  {
    $this->_dbIntra->db_select();

    $strSQL = " UPDATE BIENESTAR_PR_COLABORADOR 
                      SET CONTADOR ='" . $num . "' 
                      WHERE ID = '" . $id_pin . "'";
    $query = $this->_dbIntra->query($strSQL);
    $this->_dbIntra->close();

    if ($query) {
      return true;
    } else {
      return false;
    }
  }

  public function consultaRUTPINColaborador($id_pin)
  {
    $this->_dbIntra->db_select();

    $strSQL = "SELECT RUT_COLAB,
                            TO_CHAR(FECHA,'YYYY')FECHA
                    FROM BIENESTAR_PR_COLABORADOR
                    WHERE PIN='" . $id_pin . "'";
    $query = $this->_dbIntra->query($strSQL);
    $this->_dbIntra->close();

    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        $array[0] = $row->RUT_COLAB;
        $array[1] = $row->FECHA;
        return $array;
      }
    } else {
      return false;
    }
  }

  public function updateContadorPinColaborado($rut, $ano)
  {
    $this->_dbIntra->db_select();

    $strSQL = " SELECT ID FROM BIENESTAR_PR_COLABORADOR 
                      WHERE RUT_COLAB = " . $rut . " 
                        AND SECCION='PIN RECONOCIMIENTO' 
                        AND FECHA BETWEEN TO_DATE('01-01-" . $ano . "', 'DD-MM-YYYY') AND TO_DATE('31-12-" . $ano . "', 'DD-MM-YYYY')
                      ORDER BY 1 ASC ";
    $query = $this->_dbIntra->query($strSQL);
    $this->_dbIntra->close();

    if ($query->num_rows() > 0) {
      $_result = $query->result();
    } else {
      $_result = array();
    }

    return $_result;
  }
  public function updatePINColaborador($id_pin, $tipo, $cod_pinNew, $seccion, $cui)
  {
    $this->_dbIntra->db_select();

    $strSQL = " UPDATE BIENESTAR_PR_COLABORADOR SET TIPO='" . $tipo . "',
                                                          PIN ='" . $cod_pinNew . "',
                                                          SECCION='" . $seccion . "',
                                                          NIVEL_SUP='" . $cui . "'
                      WHERE PIN ='" . $id_pin . "'";
    $query = $this->_dbIntra->query($strSQL);
    $this->_dbIntra->close();

    if ($query) {
      return true;
    } else {
      return false;
    }
  }

  public function validaColab_BD($rutColab)
  {
    $this->_dbIntra->db_select();

    $strSQL = " SELECT EMP_K_RUTEMPLEAD
                        FROM MAE_EMPLEADO 
                        WHERE EMP_K_RUTEMPLEAD = " . $rutColab . " 
                        AND SYS_C_CODESTADO=1";
    $query = $this->_dbIntra->query($strSQL);
    $this->_dbIntra->close();

    if ($query->num_rows() > 0) {
      return true;
    } else {
      return false;
    }
  }


  public function validaPIN_BD($pin)
  {
    $this->_dbIntra->db_select();

    $strSQL = " SELECT ESTADO 
                        FROM BIENESTAR_PR_ASIGNAR 
                        where COD_PIN='" . $pin . "'";
    $query = $this->_dbIntra->query($strSQL);
    $this->_dbIntra->close();

    if ($query->num_rows() > 0) {
      $_result = $query->result();
    } else {
      $_result = array();
    }
    return $_result;
  }

  public function validaPINTIPO_BD($pin, $tipo, $propiedad, $cui)
  {
    $this->_dbIntra->db_select();

    if ($propiedad == 'PIN') {
      $strSQL = " SELECT ESTADO 
                        FROM BIENESTAR_PR_ASIGNAR 
                        where COD_PIN='" . $pin . "'
                        AND TIPO = '" . $tipo . "'
                        AND PROPIEDAD ='" . $propiedad . "'
                        AND NIVEL = '" . $cui . "'";
    } else {
      $strSQL = " SELECT ESTADO 
                        FROM BIENESTAR_PR_ASIGNAR 
                        where COD_PIN='" . $pin . "'
                        AND NIVEL = '" . $cui . "'";
    }
    $query = $this->_dbIntra->query($strSQL);
    $this->_dbIntra->close();

    $_result = $query->result();
    return $_result;
  }

  public function validaPINADMINISTRADOR($pin, $tipo, $propiedad, $cui)
  {
    $this->_dbIntra->db_select();

    if ($propiedad == 'PIN') {
      $strSQL = " SELECT ESTADO 
                        FROM BIENESTAR_PR_ASIGNAR 
                        where COD_PIN='" . $pin . "'
                        AND TIPO = '" . $tipo . "'
                        AND PROPIEDAD ='" . $propiedad . "'
                        AND NIVEL = '" . $cui . "'";
    } else {
      $strSQL = " SELECT ESTADO 
                        FROM BIENESTAR_PR_ASIGNAR 
                        where COD_PIN='" . $pin . "'
                        AND PROPIEDAD ='TAR'
                        AND NIVEL = '" . $cui . "'";
    }
    $query = $this->_dbIntra->query($strSQL);
    $this->_dbIntra->close();

    $_result = $query->result();
    return $_result;
  }


  public function contadorPINRECONOCIMIENTO($rut, $ano)
  {

    $this->_dbIntra->db_select();

    $strSQL = " SELECT CONTADOR
                        FROM BIENESTAR_PR_COLABORADOR
                        WHERE RUT_COLAB = " . $rut . "
                        AND ID IN(SELECT MAX(ID) 
                                 FROM BIENESTAR_PR_COLABORADOR
                                 WHERE RUT_COLAB = " . $rut . "
                                 AND SECCION IN('PIN RECONOCIMIENTO','PIN IGS','PIN CSI')    
                                 AND FECHA BETWEEN TO_DATE('01-01-" . $ano . "', 'DD-MM-YYYY') 
                                               AND TO_DATE('31-12-" . $ano . "', 'DD-MM-YYYY')
                         ) 
                        AND SECCION IN('PIN RECONOCIMIENTO','PIN IGS','PIN CSI') ";
    $query = $this->_dbIntra->query($strSQL);

    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }


  public function ingresoPINColaboradorESPECIAL($RutResp, $RutColab, $Tipo, $CodPin, $obs, $count, $pagina, $NIVEL_SUP, $tipoReconocimiento, $fecha)
  {

    $this->_dbIntra->db_select();

    $strSQL = " INSERT INTO BIENESTAR_PR_COLABORADOR VALUES(  get_nextval_BPR_COLAB,
                                                                '" . $RutResp . "',
                                                                '" . $RutColab . "',
                                                                '" . $Tipo . "',
                                                                '" . $CodPin . "',
                                                                '" . $pagina . "',
                                                                TO_DATE('" . $fecha . "','DD-MM-YYYY'),
                                                                '1',
                                                                '" . $obs . "',
                                                                '',
                                                                '" . $count . "',
                                                                '" . $NIVEL_SUP . "',
                                                                '',
                                                                '',
                                                                '')";
    $query = $this->_dbIntra->query($strSQL);
    if ($query) {
      $_result = $this->update_PIN_ASIGNAR($CodPin);
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function update_PIN_ASIGNAR($CodPin)
  {

    $this->_dbIntra->db_select();

    $strSQL = " UPDATE BIENESTAR_PR_ASIGNAR SET ESTADO ='1'
                          WHERE COD_PIN   = '" . $CodPin . "'";
    $query = $this->_dbIntra->query($strSQL);
    if ($query) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function elimarPINColaborador($id_pin)
  {
    $this->_dbIntra->db_select();

    $strSQL = " DELETE
                      FROM BIENESTAR_PR_COLABORADOR
                      WHERE PIN='" . $id_pin . "'";
    $query = $this->_dbIntra->query($strSQL);
    $this->_dbIntra->close();

    if ($query) {
      return true;
    } else {
      return false;
    }
  }
}
