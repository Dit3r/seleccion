<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Bd_colaborador extends CI_Model
{

  private $_dbRRHH  = null;
  private $_dbIntra = null;
  //private $_dbIntraDesa = null;

  private $_result;
  private $_i = 0;
  private $_jerar;
  private $_localPU;
  private $_localSB;
  private $_localMEDCELL;
  private $local;
  private $array;
  private $_ccosto;

  function __construct()
  {
    parent::__construct();
    $this->_dbRRHH  = $this->load->database('dbRRHH', TRUE);
    $this->_dbIntra = $this->load->database('default', TRUE);
    //$this->_dbIntraDesa = $this->load->database('desaIntra',TRUE);
    $this->load->library('session');
  }

  public function getColaborador($strRut)
  {

    $this->_dbRRHH->db_select();

    $query = $this->_dbRRHH->query(
      " SELECT A.EMP_K_RUTEMPLEAD RUT,
                                                INITCAP(A.EMP_A_NOMBRE||' '||A.EMP_A_APELLPATER||' '||A.EMP_A_APELLMATER)NOMBRE
                                         FROM MAE_EMPLEADO A
                                         WHERE  A.SYS_C_CODESTADO  = 1
                                         AND A.EMP_K_RUTEMPLEAD = " . $strRut
    );

    $_result = $query->result_array();
    $this->_dbRRHH->close();
    return array_shift($_result);
  }

  public function getCCosto($strRut)
  {
    $this->_dbRRHH->db_select();
    $query = $this->_dbRRHH->query(
      " SELECT                                               
                                              UNI_K_CODUNIDAD
                                       FROM MAE_EMPLEADO                                          
                                       WHERE SYS_C_CODESTADO  = 1
                                         AND EMP_K_RUTEMPLEAD = " . $strRut
    );
    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        $this->_ccosto = $row->UNI_K_CODUNIDAD;
      }
    }

    $this->_dbRRHH->close();
    return $this->_ccosto;
  }

  public function buscarDepCodjerar($jerarQDep)
  {
    $this->_dbRRHH->db_select();
    $query = $this->_dbRRHH->query(" SELECT distinct id_jerarquia_dep 
                        				 FROM rrhh_jerarquias_cargos 
                        				 WHERE id_jerarquia_sup in('" . $jerarQDep . "')                        				  
                        				 order by 1 asc");

    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        if ($this->_i == 0) {
          $this->_jerar  = $row->ID_JERARQUIA_DEP;
        } else {
          $this->_jerar .= "," . $row->ID_JERARQUIA_DEP;
        }

        $this->_i++;
      }
    }

    $this->_dbRRHH->close();
    return $this->_jerar;
  }


  public function buscarCUI($rutSup)
  {
    $this->_dbRRHH->db_select();
    $query = $this->_dbRRHH->query(" SELECT RRHH.JER_EMPLEADO.JER_RUT as RUT, 
                                                RRHH.JER_EMPLEADO.JER_UNIDAD as UNIDAD, 
                                                RRHH.JER_EMPLEADO.JER_AUX_JEFE as JER_AUX_JEFE, 
                                                RRHH.JER_EMPLEADO.JER_COD_NIVEL AS JER_COD_NIVEL
                                        FROM RRHH.JER_EMPLEADO 
                                        WHERE ((RRHH.JER_EMPLEADO.JER_RUT = " . $rutSup . ")) ");

    if ($query->num_rows() > 0) {
      $array = $query->result();
    } else {
      $array = array();
    }

    $this->_dbRRHH->close();
    return $array;
  }

  public function buscarSuperiorMail($rut)
  {
    $this->_dbRRHH->db_select();
    $query = $this->_dbRRHH->query("    select 
                                                  jer_rut,
                                                  initcap(b.emp_a_nombre || ' ' || b.emp_a_apellpater || ' ' || b.emp_a_apellmater) nombre,
                                                  (case when jer_aux_jefe = 0 then DEP.JEFE_UNIDAD_DEP else (select JEFE_UNIDAD_DEP from jer_cui_dependencia DEP_JEFE where DEP_JEFE.CUI_DEP = DEP.CUI_SUP)    end) Jefe_Directo,
                                                  case when (case when jer_aux_jefe = 0 then DEP.JEFE_UNIDAD_DEP else (select JEFE_UNIDAD_DEP from jer_cui_dependencia DEP_JEFE where DEP_JEFE.CUI_DEP = DEP.CUI_SUP)    end)  is not null and (case when jer_aux_jefe = 0 then DEP.JEFE_UNIDAD_DEP else (select JEFE_UNIDAD_DEP from jer_cui_dependencia DEP_JEFE where DEP_JEFE.CUI_DEP = DEP.CUI_SUP AND rownum < 1)    end)<> 'NO_DEF' then  
                                                  (select  initcap(MAE_JEFE.emp_a_nombre || ' ' || MAE_JEFE.emp_a_apellpater || ' ' || MAE_JEFE.emp_a_apellmater)  from mae_empleado MAE_JEFE where  MAE_JEFE.sys_c_codestado = 1 and MAE_JEFE.emp_k_rutemplead = 
                                                  (case when jer_aux_jefe = 0 then DEP.JEFE_UNIDAD_DEP else (select JEFE_UNIDAD_DEP from jer_cui_dependencia DEP_JEFE where DEP_JEFE.CUI_DEP = DEP.CUI_SUP)end))  
                                                  else null end as Nombre_Jefe,
                                                  b.mae_c_email email
                                            from JER_EMPLEADO A,
                                                 mae_empleado B,
                                                 jer_cui_dependencia DEP
                                            where  B.sys_c_codestado = 1
                                               and A.jer_rut = B.emp_k_rutemplead 
                                               and a.jer_rut = " . $rut . "
                                               and DEP.CUI_DEP = A.JER_UNIDAD ");

    if ($query->num_rows() > 0) {
      $array = $query->result();
    } else {
      $array = array();
    }

    $this->_dbRRHH->close();
    return $array;
  }

  public function buscarCUIDEP($cuiSup)
  {
    $this->_dbRRHH->db_select();
    /*
        //SE MODIFICO 05-09-2019 -- DIEGO LOPEZ
        $query = $this->_dbRRHH->query(" SELECT 
                                              RRHH.JER_CUI_DEPENDENCIA.CUI_DEP,
                                              RRHH.JER_EMPLEADO.JER_RUT, 
                                              RRHH.JER_EMPLEADO.JER_AUX_JEFE, 
                                              MAE_EMPLEADO.EMP_A_NOMBRE, 
                                              MAE_EMPLEADO.EMP_A_APELLPATER 
                                        FROM 
                                              RRHH.JER_CUI_DEPENDENCIA,
                                              RRHH.JER_EMPLEADO, 
                                              MAE_EMPLEADO 
                                        WHERE ((RRHH.JER_CUI_DEPENDENCIA.CUI_SUP = '".$cuiSup."') 
                                            AND (MAE_EMPLEADO.SYS_C_CODESTADO = '1') 
                                            AND (RRHH.JER_EMPLEADO.JER_AUX_JEFE = 1)) 
                                            AND RRHH.JER_EMPLEADO.JER_UNIDAD = RRHH.JER_CUI_DEPENDENCIA.CUI_DEP 
                                            AND RRHH.JER_EMPLEADO.JER_RUT    = MAE_EMPLEADO.EMP_K_RUTEMPLEAD ");*/
    $query = $this->_dbRRHH->query(" SELECT 
                                              RRHH.jer_emp_dependencia.CUI_DEP,
                                              RRHH.JER_EMPLEADO.JER_RUT, 
                                              RRHH.JER_EMPLEADO.JER_AUX_JEFE, 
                                              MAE_EMPLEADO.EMP_A_NOMBRE, 
                                              MAE_EMPLEADO.EMP_A_APELLPATER 
                                        FROM 
                                              RRHH.jer_emp_dependencia,
                                              RRHH.JER_EMPLEADO, 
                                              RRHH.MAE_EMPLEADO 
                                        WHERE RRHH.jer_emp_dependencia.CUI_SUP = '" . $cuiSup . "'
                                            AND MAE_EMPLEADO.SYS_C_CODESTADO =1 
                                            AND RRHH.jer_emp_dependencia.dep_directo = 1
                                            AND rrhh.jer_emp_dependencia.rut_dep = RRHH.JER_EMPLEADO.jer_rut
                                             AND rrhh.jer_emp_dependencia.rut_dep = RRHH.MAE_EMPLEADO.emp_k_rutemplead");

    if ($query->num_rows() > 0) {
      $array = $query->result();
    } else {
      $array = array();
    }

    $this->_dbRRHH->close();
    return $array;
  }

  public function traerDependientesSUP($cuiSup, $rutdep)
  {
    $this->_dbRRHH->db_select();


    $query = $this->_dbRRHH->query(" SELECT 
                                                RRHH.JER_EMPLEADO.JER_RUT as RUT ,
                                                InitCap(MAE_EMPLEADO.emp_a_nombre) || ' ' || InitCap(MAE_EMPLEADO.emp_a_apellpater)  || ' ' || InitCap(MAE_EMPLEADO.emp_a_apellmater )NOMBRE 
                                        FROM 
                                              RRHH.JER_CUI_DEPENDENCIA,
                                              RRHH.JER_EMPLEADO, 
                                              MAE_EMPLEADO 
                                        WHERE ((RRHH.JER_CUI_DEPENDENCIA.CUI_SUP = '" . $cuiSup . "') 
                                            AND (MAE_EMPLEADO.SYS_C_CODESTADO = '1') 
                                            AND (RRHH.JER_EMPLEADO.JER_AUX_JEFE = 1)) 
                                            AND RRHH.JER_EMPLEADO.JER_UNIDAD = RRHH.JER_CUI_DEPENDENCIA.CUI_DEP 
                                            AND RRHH.JER_EMPLEADO.JER_RUT    = MAE_EMPLEADO.EMP_K_RUTEMPLEAD
                                            AND RRHH.JER_EMPLEADO.JER_RUT = " . $rutdep);
    if ($query->num_rows() > 0) {
      $array = $query->result();
    } else {
      $array = array();
    }

    $this->_dbRRHH->close();
    return $array;
  }

  //******************************************************************************************************
  //******************************************************************************************************
  //*********************TRAE EL NOMBRE DEL COLABORADOR DEPENDIENTE EN LA BUSQUEDA POR NOMBRE*************

  public function traerDependientesNombreSUP($rutSup, $nom)
  {
    $this->_dbRRHH->db_select();

    /*$query = $this->_dbRRHH->query(" SELECT  JER_RUT as RUT ,
                                                    InitCap(emp_a_nombre) || ' ' || InitCap(emp_a_apellpater)  || ' ' || InitCap(emp_a_apellmater )NOMBRE 
                                         FROM VW_DEPENDENCIA2 
                                         WHERE JEFE_DIRECTO = '".$rutSup."' 
                                           AND JER_RUT != '".$rutSup."'
                                           AND (emp_a_nombre || emp_a_apellpater || emp_a_apellmater) like  UPPER('%".preg_replace("[ ]","%",$nom)."%')
                                           and (case when (select jer_unidad from jer_empleado where jer_rut = ".$rutSup.") = jer_unidad then 1 else 0 end = 0 
                                            or (jer_aux_jefe = 0 or jer_cod_nivel > nivel_jefe))");   */
    $query = $this->_dbRRHH->query("   SELECT
                                                rut_dep as RUT,
                                                InitCap(C.emp_a_nombre) || ' ' || InitCap(C.emp_a_apellpater)  || ' ' || InitCap(C.emp_a_apellmater )NOMBRE
                                            FROM
                                                RRHH.jer_emp_dependencia   a,
                                                RRHH.jer_empleado          b,
                                                RRHH.mae_empleado          c
                                            WHERE
                                                a.rut_dep = c.emp_k_rutemplead
                                                AND c.sys_c_codestado = 1
                                                AND b.jer_rut = a.rut_dep
                                                AND dep_directo = 1
                                                AND a.rut_sup = '" . $rutSup . "'
                                                AND (emp_a_nombre || emp_a_apellpater || emp_a_apellmater) like  UPPER('%" . preg_replace("[ ]", "%", $nom) . "%')");




    if ($query->num_rows() > 0) {
      $array = $query->result();
    } else {
      $array = array();
    }

    $this->_dbRRHH->close();
    return $array;
  }



  public function traerDependientesNombreSUP2($cuiSup, $nom)
  {
    $this->_dbRRHH->db_select();
    /*$query = $this->_dbRRHH->query(" SELECT 
                                                RRHH.JER_EMPLEADO.JER_RUT as RUT ,
                                                InitCap(MAE_EMPLEADO.emp_a_nombre) || ' ' || InitCap(MAE_EMPLEADO.emp_a_apellpater)  || ' ' || InitCap(MAE_EMPLEADO.emp_a_apellmater )NOMBRE 
                                        FROM 
                                              RRHH.JER_CUI_DEPENDENCIA,
                                              RRHH.JER_EMPLEADO, 
                                              MAE_EMPLEADO 
                                        WHERE ((RRHH.JER_CUI_DEPENDENCIA.CUI_SUP = '".$cuiSup."') 
                                            AND (MAE_EMPLEADO.SYS_C_CODESTADO = '1') 
                                            AND (RRHH.JER_EMPLEADO.JER_AUX_JEFE = 1)) 
                                            AND RRHH.JER_EMPLEADO.JER_UNIDAD = RRHH.JER_CUI_DEPENDENCIA.CUI_DEP 
                                            AND RRHH.JER_EMPLEADO.JER_RUT    = MAE_EMPLEADO.EMP_K_RUTEMPLEAD
                                            and (MAE_EMPLEADO.emp_a_nombre || MAE_EMPLEADO.emp_a_apellpater || MAE_EMPLEADO.emp_a_apellmater) like  UPPER('%".preg_replace("[ ]","%",$nom)."%')");*/

    $query = $this->_dbRRHH->query("   SELECT DISTINCT
                                                    rut_dep as RUT,
                                                   InitCap(C.emp_a_nombre) || ' ' || InitCap(C.emp_a_apellpater)  || ' ' || InitCap(C.emp_a_apellmater )NOMBRE
                                                FROM
                                                    RRHH.jer_emp_dependencia   a,
                                                    RRHH.jer_empleado          b,
                                                    RRHH.mae_empleado          c
                                                WHERE
                                                    a.rut_dep = c.emp_k_rutemplead
                                                    AND c.sys_c_codestado = 1
                                                    AND b.jer_rut = a.rut_dep
                                                    AND dep_directo = 1
                                                    AND a.cui_sup = '" . $cuiSup . "'
                                                    AND (emp_a_nombre || emp_a_apellpater || emp_a_apellmater) like  UPPER('%" . preg_replace("[ ]", "%", $nom) . "%'))");


    if ($query->num_rows() > 0) {
      $array = $query->result();
    } else {
      $array = array();
    }

    $this->_dbRRHH->close();
    return $array;
  }
  public function traerNombreDependientes($cuiSup, $nom, $nivel, $incBD)
  {

    $this->_dbRRHH->db_select();

    $strSQL = "    SELECT 
							RRHH.JER_EMPLEADO.JER_RUT AS RUT,     
							InitCap(MAE_EMPLEADO.emp_a_nombre) || ' ' || InitCap(MAE_EMPLEADO.emp_a_apellpater)  || ' ' || InitCap(MAE_EMPLEADO.emp_a_apellmater )NOMBRE
						FROM
							RRHH.JER_EMPLEADO,
							MAE_EMPLEADO    
						WHERE 
							  RRHH.JER_EMPLEADO.JER_UNIDAD = '" . $cuiSup . "'
							  AND (MAE_EMPLEADO.emp_a_nombre || MAE_EMPLEADO.emp_a_apellpater || MAE_EMPLEADO.emp_a_apellmater) like  UPPER('%" . preg_replace("[ ]", "%", $nom) . "%')
							  AND MAE_EMPLEADO.SYS_C_CODESTADO = '1' 
							  AND RRHH.JER_EMPLEADO.JER_RUT = MAE_EMPLEADO.EMP_K_RUTEMPLEAD
							  AND (RRHH.JER_EMPLEADO.JER_AUX_JEFE = 0 OR RRHH.JER_EMPLEADO.JER_COD_NIVEL >" . $nivel . ")";
    if ($incBD != "") {
      $strSQL .= "and  RRHH.JER_EMPLEADO.JER_RUT not in(" . $incBD . ") ";
    }
    $query = $this->_dbRRHH->query($strSQL);

    if ($query->num_rows() > 0) {
      $array = $query->result();
    } else {
      $array = array();
    }

    $this->_dbRRHH->close();
    return $array;
  }
  //******************************************************************************************************
  //******************************************************************************************************
  //******************************************************************************************************




  //******************************************************************************************************
  //******************************************************************************************************
  //************************************TRAE NOMBRE Y RUT DE LOS DEPENDIENTES***********************************************

  public function traerDependientes($cuiSup, $rutdep)
  {
    $this->_dbRRHH->db_select();
    /*$query = $this->_dbRRHH->query("   SELECT 
    	/*$query = $this->_dbRRHH->query("   SELECT 
                                                RRHH.JER_EMPLEADO.JER_RUT,     
                                                InitCap(MAE_EMPLEADO.emp_a_nombre) || ' ' || InitCap(MAE_EMPLEADO.emp_a_apellpater)  || ' ' || InitCap(MAE_EMPLEADO.emp_a_apellmater )NOMBRE
                                            FROM
                                                RRHH.JER_EMPLEADO,
                                                MAE_EMPLEADO    
                                            WHERE 
                                                  RRHH.JER_EMPLEADO.JER_UNIDAD = '".$cuiSup."'
                                                  and RRHH.JER_EMPLEADO.JER_RUT = ".$rutdep."
                                                  and RRHH.JER_EMPLEADO.JER_AUX_JEFE = 0 
                                                  and MAE_EMPLEADO.SYS_C_CODESTADO   = '1'
                                                  AND RRHH.JER_EMPLEADO.JER_RUT      = MAE_EMPLEADO.EMP_K_RUTEMPLEAD");*/

    $query = $this->_dbRRHH->query("SELECT DISTINCT
                                            rut_dep as RUT,
                                            InitCap(C.emp_a_nombre) || ' ' || InitCap(C.emp_a_apellpater)  || ' ' || InitCap(C.emp_a_apellmater )NOMBRE
                                        FROM
                                            RRHH.jer_emp_dependencia   a,
                                            RRHH.jer_empleado          b,
                                            RRHH.mae_empleado          c
                                        WHERE
                                            a.rut_dep = c.emp_k_rutemplead
                                            AND c.sys_c_codestado = 1
                                            AND b.jer_rut = a.rut_dep
                                            AND dep_directo = 1
                                            AND a.rut_dep = " . $rutdep . "
                                            AND A.CUI_SUP='" . $cuiSup . "'");

    if ($query->num_rows() > 0) {
      $array = $query->result();
    } else {
      $array = array();
    }

    $this->_dbRRHH->close();
    return $array;
  }

  public function buscarCUIDEP2($cuiSup, $nivel)
  {
    $this->_dbRRHH->db_select();
    $query = $this->_dbRRHH->query(" SELECT 
                                                RRHH.JER_EMPLEADO.JER_RUT, 
                                                RRHH.JER_EMPLEADO.JER_UNIDAD, 
                                                RRHH.JER_EMPLEADO.JER_AUX_JEFE, 
                                                RRHH.JER_EMPLEADO.JER_COD_NIVEL
                                         FROM
                                                RRHH.JER_EMPLEADO
                                         WHERE 
                                                (
                                                    (
                                                        RRHH.JER_EMPLEADO.JER_UNIDAD = '" . $cuiSup . "'
                                                    )
                                                    and
                                                    
                                                    (jer_aux_jefe =0 or jer_cod_nivel > " . $nivel . " )
                                                ) ");

    /*  echo " SELECT 
                                                RRHH.JER_EMPLEADO.JER_RUT, 
                                                RRHH.JER_EMPLEADO.JER_UNIDAD, 
                                                RRHH.JER_EMPLEADO.JER_AUX_JEFE, 
                                                RRHH.JER_EMPLEADO.JER_COD_NIVEL
                                         FROM
                                                RRHH.JER_EMPLEADO
                                         WHERE 
                                                (
                                                    (
                                                        RRHH.JER_EMPLEADO.JER_UNIDAD = '".$cuiSup."'
                                                    )
                                                    and
                                                    
                                                    (jer_aux_jefe =0 or jer_cod_nivel > ".$nivel." )
                                                ) ";
        exit;*/

    if ($query->num_rows() > 0) {
      $array = $query->result();
    } else {
      $array = array();
    }

    $this->_dbRRHH->close();
    return $array;
  }

  public function buscarLocalesPU($rutJefe)
  {
    $this->_dbRRHH->db_select();
    $this->_i = 0;
    $query = $this->_dbRRHH->query(" SELECT distinct uni_k_codunidad 
                        				 FROM RRHH_JERARQUIA_CCOSTO_PU 
                        				 WHERE emp_k_rutemplead in('" . $rutJefe . "')                        				  
                        				 order by 1 asc");

    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        if ($this->_i == 0) {
          $this->_localPU  = $row->UNI_K_CODUNIDAD;
        } else {
          $this->_localPU .= "," . $row->UNI_K_CODUNIDAD;
        }

        $this->_i++;
      }
    }

    $this->_dbRRHH->close();
    return $this->_localPU;
  }

  public function buscarLocalesSB($rutJefe)
  {
    $this->_dbIntra->db_select();
    $this->_i = 0;
    $query = $this->_dbIntra->query(" SELECT FILIAL_ID 
                                         FROM MAE_DATOS_SUCURSALES
                                         WHERE JEFE_DIST_ID in('" . $rutJefe . "') ");

    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {
        if ($this->_i == 0) {
          $this->_localSB  = $row->FILIAL_ID;
        } else {
          $this->_localSB .= "," . $row->FILIAL_ID;
        }

        $this->_i++;
      }
    }

    $this->_dbIntra->close();
    return $this->_localSB;
  }


  public function buscarLocalesMEDCELL($rutJefe)
  {
    $this->_dbRRHH->db_select();
    $this->_i = 0;
    $query = $this->_dbRRHH->query(" SELECT UNI_K_CODUNIDAD
                                         FROM RRHH_JERARQUIAS_COSTO
                                         WHERE EMP_K_RUTEMPLEAD in('" . $rutJefe . "') ");

    if ($query->num_rows() > 0) {

      foreach ($query->result() as $row) {
        if ($this->_i == 0) {
          $this->_localMEDCELL  = $row->UNI_K_CODUNIDAD;
        } else {
          $this->_localMEDCELL .= "," . $row->UNI_K_CODUNIDAD;
        }

        $this->_i++;
      }
    }

    $this->_dbRRHH->close();
    return $this->_localMEDCELL;
  }


  public function getJerarquiaDEP($nom, $jerarquia, $localPU, $localSB, $localMedcell, $local)
  {


    if ($localPU != "" && $localSB != "") {
      $local = $localPU . ',' . $localSB;
    } elseif ($localPU != "" && $localSB == "") {
      $local = $localPU;
    } elseif ($localSB != "" && $localPU == "") {
      $local = $localSB;
    } elseif ($localMedcell != "") {
      $local = $localMedcell;
    }

    $this->_dbRRHH->db_select();




    $strSQL = "   SELECT  DISTINCT a.EMP_K_RUTEMPLEAD RUT,
                    InitCap(b.emp_a_nombre) || ' ' || InitCap(b.emp_a_apellpater)  || ' ' || InitCap(b.emp_a_apellmater )NOMBRE
                    FROM    MAE_GRCARACT A, 
                            MAE_EMPLEADO B, 
                            MAE_VALCARAC C, 
                            MAE_UNIDADES D 
                    WHERE   A.EMP_K_RUTEMPLEAD =B.EMP_K_RUTEMPLEAD 
                    AND     B.SYS_C_CODESTADO  = 1 
                    AND     A.DEF_C_CODCARACT  = C.VAL_C_CODCARACT 
                    AND     A.TIP_K_TIPOCARACT = 8 
                    AND     A.CIA_K_EMPRESA    = B.CIA_K_EMPRESA 
                    AND     A.CIA_K_EMPRESA    = C.CIA_K_EMPRESA 
                    AND	    A.CIA_K_EMPRESA    = D.CIA_K_EMPRESA 
                    AND	    B.UNI_K_CODUNIDAD  = D.UNI_K_CODUNIDAD 
					AND     B.CIA_K_EMPRESA    NOT IN(25,26)
                    and     (b.emp_a_nombre || b.emp_a_apellpater || b.emp_a_apellmater) like  UPPER('%" . preg_replace("[ ]", "%", $nom) . "%')";

    if ($local) {
      $strSQL .= "  AND  B.UNI_K_CODUNIDAD  IN(" . $local . ")";
    }

    $strSQL .= "  AND     A.DEF_C_CODCARACT  IN('" . preg_replace("[,]", "','", $jerarquia) . "')                    
                    ORDER BY 2 ASC ";

    $query = $this->_dbRRHH->query($strSQL);

    if ($query->num_rows() > 0) {
      foreach ($query->result() as $row) {

        $array[] = array('value' => $row->RUT, 'label' => $row->NOMBRE);
      }
    }

    if (isset($array) == false) {
      $array = 0;
    }

    $this->_dbRRHH->close();
    return $array;
  }



  /**
   * validamos si existe el colaborador como dependiente
   */
  public function getJerarDEP($nom, $jerarquia, $localPU, $localSB, $localMedcell, $local, $rutColab)
  {


    if ($localPU != "" && $localSB != "") {
      $local = $localPU . ',' . $localSB;
    } elseif ($localPU != "" && $localSB == "") {
      $local = $localPU;
    } elseif ($localSB != "" && $localPU == "") {
      $local = $localSB;
    } elseif ($localMedcell != "") {
      $local = $localMedcell;
    }


    $this->_dbRRHH->db_select();

    $strSQL = "   SELECT  DISTINCT a.EMP_K_RUTEMPLEAD RUT,
                    InitCap(b.emp_a_nombre) || ' ' || InitCap(b.emp_a_apellpater)  || ' ' || InitCap(b.emp_a_apellmater )NOMBRE
                    FROM    MAE_GRCARACT A, 
                            MAE_EMPLEADO B, 
                            MAE_VALCARAC C, 
                            MAE_UNIDADES D 
                    WHERE   A.EMP_K_RUTEMPLEAD =B.EMP_K_RUTEMPLEAD 
                    AND     B.SYS_C_CODESTADO  = 1 
                    AND     A.DEF_C_CODCARACT  = C.VAL_C_CODCARACT 
                    AND     A.TIP_K_TIPOCARACT = 8 
                    AND     A.CIA_K_EMPRESA    = B.CIA_K_EMPRESA 
                    AND     A.CIA_K_EMPRESA    = C.CIA_K_EMPRESA 
                    AND	    A.CIA_K_EMPRESA    = D.CIA_K_EMPRESA 
                    AND	    B.UNI_K_CODUNIDAD  = D.UNI_K_CODUNIDAD 
					AND     B.CIA_K_EMPRESA    NOT IN(25,26)
                    and     (b.emp_a_nombre || b.emp_a_apellpater || b.emp_a_apellmater) like  UPPER('%" . preg_replace("[ ]", "%", $nom) . "%')";

    if ($local) {
      $strSQL .= "  AND  B.UNI_K_CODUNIDAD  IN(" . $local . ")";
    }

    $strSQL .= "  AND     A.DEF_C_CODCARACT  IN('" . preg_replace("[,]", "','", $jerarquia) . "')
                    AND     A.EMP_K_RUTEMPLEAD = " . $rutColab . "
                    ORDER BY 2 ASC ";
    $query = $this->_dbRRHH->query($strSQL);

    $this->_dbRRHH->close();
    return $query->num_rows();
  }

  public function getJerarDEPENDIENTE($nom, $cui, $rutColab)
  {

    $this->_dbRRHH->db_select();

    $strSQL = "   SELECT  DISTINCT a.EMP_K_RUTEMPLEAD RUT,
                    InitCap(b.emp_a_nombre) || ' ' || InitCap(b.emp_a_apellpater)  || ' ' || InitCap(b.emp_a_apellmater )NOMBRE
                    FROM    MAE_GRCARACT A, 
                            MAE_EMPLEADO B, 
                            MAE_VALCARAC C, 
                            MAE_UNIDADES D 
                    WHERE   A.EMP_K_RUTEMPLEAD =B.EMP_K_RUTEMPLEAD 
                    AND     B.SYS_C_CODESTADO  = 1 
                    AND     A.DEF_C_CODCARACT  = C.VAL_C_CODCARACT 
                    AND     A.TIP_K_TIPOCARACT = 8 
                    AND     A.CIA_K_EMPRESA    = B.CIA_K_EMPRESA 
                    AND     A.CIA_K_EMPRESA    = C.CIA_K_EMPRESA 
                    AND	    A.CIA_K_EMPRESA    = D.CIA_K_EMPRESA 
                    AND	    B.UNI_K_CODUNIDAD  = D.UNI_K_CODUNIDAD 
					AND     B.CIA_K_EMPRESA    NOT IN(25,26)
                    and     (b.emp_a_nombre || b.emp_a_apellpater || b.emp_a_apellmater) like  UPPER('%" . preg_replace("[ ]", "%", $nom) . "%')";

    if ($local) {
      $strSQL .= "  AND  B.UNI_K_CODUNIDAD  IN(" . $local . ")";
    }

    $strSQL .= "  AND     A.DEF_C_CODCARACT  IN('" . preg_replace("[,]", "','", $jerarquia) . "')
                    AND     A.EMP_K_RUTEMPLEAD = " . $rutColab . "
                    ORDER BY 2 ASC ";
    $query = $this->_dbRRHH->query($strSQL);

    $this->_dbRRHH->close();
    return $query->num_rows();
  }



  public function getBuscador($nom)
  {

    $this->_dbRRHH->db_select();

    $strSQL = "   SELECT  DISTINCT a.EMP_K_RUTEMPLEAD RUT,
                            InitCap(b.emp_a_nombre) || ' ' || InitCap(b.emp_a_apellpater)  || ' ' || InitCap(b.emp_a_apellmater )NOMBRE,
                            B.UNI_K_CODUNIDAD,
                            B.UNI_K_CODUNIDAD ||'-'||INITCAP(D.UNI_A_NOMBUNIDAD) nombre_unidad,
                            E.CIA_K_EMPRESA||'-'||INITCAP(E.CIA_G_RAZONSOCIA)EMPRESA
                    FROM    MAE_GRCARACT A, 
                            MAE_EMPLEADO B, 
                            MAE_VALCARAC C, 
                            MAE_UNIDADES D,
                            MAE_CIAEMPRESA E
                    WHERE   A.EMP_K_RUTEMPLEAD =B.EMP_K_RUTEMPLEAD 
                    AND     B.SYS_C_CODESTADO  = 1 
                    AND     A.DEF_C_CODCARACT  = C.VAL_C_CODCARACT 
                    AND     A.TIP_K_TIPOCARACT = 8 
                    AND     A.CIA_K_EMPRESA    = B.CIA_K_EMPRESA 
                    AND     A.CIA_K_EMPRESA    = C.CIA_K_EMPRESA 
                    AND	    A.CIA_K_EMPRESA    = D.CIA_K_EMPRESA 
                    AND	    B.UNI_K_CODUNIDAD  = D.UNI_K_CODUNIDAD 
                    AND     B.CIA_K_EMPRESA    = E.CIA_K_EMPRESA 
					AND     B.CIA_K_EMPRESA    NOT IN(25,26)
                    and     (b.emp_a_nombre || b.emp_a_apellpater || b.emp_a_apellmater) like  UPPER('%" . preg_replace("[ ]", "%", $nom) . "%')
                    ORDER BY 2 ASC ";
    $query = $this->_dbRRHH->query($strSQL);

    $_result = $query->result();

    $this->_dbRRHH->close();
    return $_result;
  }

  public function getBuscadorOnline($nom)
  {

    $this->_dbIntra->db_select();

    $strSQL = " SELECT DISTINCT A.ID,
                                  (SELECT INITCAP(EMP_A_NOMBRE||' '||EMP_A_APELLPATER||' '||EMP_A_APELLMATER) 
                                    FROM MAE_EMPLEADO 
                                    WHERE EMP_K_RUTEMPLEAD = A.RUT_RESP 
                                    AND ROWNUM=1) NOMBRE_SUP,
                                   (SELECT INITCAP(EMP_A_NOMBRE||' '||EMP_A_APELLPATER||' '||EMP_A_APELLMATER) 
                                    FROM MAE_EMPLEADO 
                                    WHERE EMP_K_RUTEMPLEAD = A.RUT_COLAB 
                                    AND ROWNUM=1) NOMBRE_DEP, 
                                   B.PIN_DESC AS MOTIVO,
                                   TO_CHAR(A.FECHA, 'DD-MM-YYYY') DDMMYYYY,
                                   A.FECHA
                from BIENESTAR_PR_COLABORADOR A,
                     BIENESTAR_R_TIPOPIN B
                WHERE A.SECCION = 'RECONOCIMIENTO ONLINE'
                  AND B.ID_PIN = A.TIPO
                  AND TO_DATE(A.FECHA,'DD/MM/YYYY') BETWEEN ADD_MONTHS(TO_DATE(SYSDATE,'DD/MM/YYYY'),-6) AND TO_DATE(SYSDATE,'DD/MM/YYYY')
                  AND A.RUT_COLAB IN(SELECT DISTINCT EMP_K_RUTEMPLEAD FROM MAE_EMPLEADO WHERE (emp_a_nombre || emp_a_apellpater || emp_a_apellmater) like  UPPER('%" . preg_replace("[ ]", "%", $nom) . "%'))
                ORDER BY A.FECHA DESC ";

    $query = $this->_dbIntra->query($strSQL) or die($strSQL);

    $_result = $query->result();

    $this->_dbIntra->close();
    return $_result;
  }
  public function validPIN($rut, $tipo, $pin)
  {

    $this->_dbIntra->db_select();

    $strSQL = " SELECT *
                  FROM BIENESTAR_PR_ASIGNAR
                  WHERE RUT= " . $rut . "
                    AND TIPO='" . $tipo . "'
                    AND COD_PIN = '" . $pin . "'
                    --AND ESTADO IS NULL";
    $query = $this->_dbIntra->query($strSQL);

    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }

  public function validaPIN($rut, $tipo, $pin)
  {
    $this->_dbIntra->db_select();

    $strSQL = " SELECT *
                  FROM BIENESTAR_PR_ASIGNAR
                  WHERE RUT       = " . $rut . "
                    AND TIPO      = '" . $tipo . "'
                    AND COD_PIN   = '" . $pin . "'
                    AND PROPIEDAD = 'PIN'
                    --AND ESTADO IS NULL";
    $query = $this->_dbIntra->query($strSQL);

    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }

  public function validaPINCui($rut, $tipo, $pin, $cui)
  {
    $this->_dbIntra->db_select();

    $strSQL = " SELECT *
                  FROM BIENESTAR_PR_ASIGNAR
                  WHERE NIVEL       = " . $cui . "
                    AND TIPO      = '" . $tipo . "'
                    AND COD_PIN   = '" . $pin . "'
                    AND PROPIEDAD = 'PIN'
                    --AND TO_CHAR(FECHA,'YYYY') = TO_CHAR(SYSDATE,'YYYY')
                    --AND ESTADO IS NULL";
    $query = $this->_dbIntra->query($strSQL);

    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }

  public function validaTarjetaCui($rut, $tipo, $pin, $cui)
  {
    $this->_dbIntra->db_select();

    $strSQL = " SELECT *
                  FROM BIENESTAR_PR_ASIGNAR
                  WHERE NIVEL     = " . $cui . "
                    AND COD_PIN   = '" . $pin . "'
                    AND PROPIEDAD = 'TAR'
                    --AND TO_CHAR(FECHA,'YYYY') = TO_CHAR(SYSDATE,'YYYY')
                    --AND ESTADO IS NULL";
    $query = $this->_dbIntra->query($strSQL);

    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }

  public function contadorPIN($rut)
  {

    $this->_dbIntra->db_select();

    $strSQL = " SELECT CONTADOR
                  FROM BIENESTAR_PR_COLABORADOR
                  WHERE RUT_COLAB = " . $rut . "
                  AND ID IN( SELECT MAX(ID) 
                             FROM BIENESTAR_PR_COLABORADOR
                             WHERE RUT_COLAB = " . $rut . "
                             AND SECCION in('PIN RECONOCIMIENTO','PIN CSI','PIN IGS')
                             AND ESTADO_PERIODO IS NULL
                            ) 
                  AND SECCION in('PIN RECONOCIMIENTO','PIN CSI','PIN IGS')
                  AND ESTADO_PERIODO IS NULL";
    $query = $this->_dbIntra->query($strSQL);

    $_result = $query->result();
    $this->_dbIntra->close();
    return $_result;
  }

  public function ingresoPINColaborador($RutResp, $RutColab, $Tipo, $CodPin, $obs, $count, $pagina, $NIVEL_SUP)
  {

    $this->_dbIntra->db_select();

    $strSQL = " INSERT INTO BIENESTAR_PR_COLABORADOR VALUES(  get_nextval_BPR_COLAB,
                                                                '" . $RutResp . "',
                                                                '" . $RutColab . "',
                                                                '" . $Tipo . "',
                                                                '" . $CodPin . "',
                                                                '" . $pagina . "',
                                                                SYSDATE,
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
      $_result = $this->update_PIN_Sup($NIVEL_SUP, $Tipo, $CodPin);
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function ingresoTARJETAColaborador($RutResp, $RutColab, $Tipo, $CodPin, $obs, $count, $pagina, $NIVEL_SUP)
  {

    $this->_dbIntra->db_select();

    $strSQL = " INSERT INTO BIENESTAR_PR_COLABORADOR VALUES(  get_nextval_BPR_COLAB,
                                                                '" . $RutResp . "',
                                                                '" . $RutColab . "',
                                                                '" . $Tipo . "',
                                                                '" . $CodPin . "',
                                                                '" . $pagina . "',
                                                                SYSDATE,
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
      $_result = $this->update_TARJETA_Sup($NIVEL_SUP, $CodPin);
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function ingresoOnlineColaborador($RutResp, $RutColab, $Tipo, $CodPin, $obs, $count, $pagina, $NIVEL_SUP)
  {

    $this->_dbIntra->db_select();

    $strSQL = " INSERT INTO BIENESTAR_PR_COLABORADOR VALUES(  get_nextval_BPR_COLAB,
                                                                '" . $RutResp . "',
                                                                '" . $RutColab . "',
                                                                '" . $Tipo . "',
                                                                '" . $CodPin . "',
                                                                '" . $pagina . "',
                                                                SYSDATE,
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
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }


  public function update_PIN_Sup($nivel, $Tipo, $CodPin)
  {

    $this->_dbIntra->db_select();

    $strSQL = " UPDATE BIENESTAR_PR_ASIGNAR SET ESTADO ='1'
                          WHERE NIVEL     =  " . $nivel . "
                            AND TIPO      = '" . $Tipo . "'
                            AND COD_PIN   = '" . $CodPin . "'
                            AND PROPIEDAD = 'PIN'";
    $query = $this->_dbIntra->query($strSQL);
    if ($query) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function update_TARJETA_Sup($nivel, $CodPin)
  {

    $this->_dbIntra->db_select();

    $strSQL = " UPDATE BIENESTAR_PR_ASIGNAR SET ESTADO ='1'
                          WHERE NIVEL     =  " . $nivel . "                          
                            AND COD_PIN   = '" . $CodPin . "'
                            AND PROPIEDAD = 'TAR'";
    $query = $this->_dbIntra->query($strSQL);
    if ($query) {
      $_result = true;
    } else {
      $_result = false;
    }

    $this->_dbIntra->close();
    return $_result;
  }

  public function onlineReconocimiento()
  {

    $this->_dbIntra->db_select();

    $strSQL = " SELECT * FROM (
                                SELECT A.ID,
                                       INITCAP(C.EMP_A_NOMBRE)NOMBRE,
                                       INITCAP(C.EMP_A_APELLPATER)APEPATER,
                                       INITCAP(C.EMP_A_APELLMATER)APEMATER,
                                       TO_DATE(A.FECHA,'DD-MM-YY') FECHA,      
                                       INITCAP(A.OBS_RESPONSABLE)OBS_SUP
                                FROM BIENESTAR_PR_COLABORADOR A,
                                     BIENESTAR_R_TIPOPIN B,
                                     MAE_EMPLEADO C
                                WHERE B.ID_PIN = A.TIPO
                                AND C.EMP_K_RUTEMPLEAD = A.RUT_COLAB
                                AND A.SECCION = 'RECONOCIMIENTO ONLINE'
                                AND C.SYS_C_CODESTADO = 1 
                                AND ESTADO_PERIODO IS NULL                               
                                ORDER BY A.ID DESC )
                 WHERE ROWNUM < 8 ";
    $query = $this->_dbIntra->query($strSQL);

    if ($query) {
      if ($query->num_rows() > 0) {
        $_result = $query->result_array();
      } else {
        $_result = array();
      }
    } else {
      $_result = array();
    }


    $this->_dbIntra->close();
    return $_result;
  }


  public function ingresoComentario($nombre, $mail, $asunto, $obs, $rut)
  {

    $this->_dbIntra->db_select();

    $strSQL = " INSERT INTO BIENESTAR_PR_CONTACTO VALUES(  get_bienestar_contacto,
                                                                '" . $nombre . "',
                                                                '" . $mail . "',
                                                                '" . $asunto . "',
                                                                '" . $obs . "',                                                               
                                                                SYSDATE,
                                                                '" . $rut . "'
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

  public function getDEPENDIENTE($jerarquia, $localPU, $localSB, $localMedcell, $local, $rutColab)
  {


    if ($localPU != "" && $localSB != "") {
      $local = $localPU . ',' . $localSB;
    } elseif ($localPU != "" && $localSB == "") {
      $local = $localPU;
    } elseif ($localSB != "" && $localPU == "") {
      $local = $localSB;
    } elseif ($localMedcell != "") {
      $local = $localMedcell;
    }


    $this->_dbRRHH->db_select();

    $strSQL = "   SELECT  DISTINCT a.EMP_K_RUTEMPLEAD RUT
                    FROM    MAE_GRCARACT A, 
                            MAE_EMPLEADO B, 
                            MAE_VALCARAC C, 
                            MAE_UNIDADES D 
                    WHERE   A.EMP_K_RUTEMPLEAD =B.EMP_K_RUTEMPLEAD 
                    AND     B.SYS_C_CODESTADO  = 1 
                    AND     A.DEF_C_CODCARACT  = C.VAL_C_CODCARACT 
                    AND     A.TIP_K_TIPOCARACT = 8 
                    AND     A.CIA_K_EMPRESA    = B.CIA_K_EMPRESA 
                    AND     A.CIA_K_EMPRESA    = C.CIA_K_EMPRESA 
                    AND	    A.CIA_K_EMPRESA    = D.CIA_K_EMPRESA 
                    AND	    B.UNI_K_CODUNIDAD  = D.UNI_K_CODUNIDAD ";

    if ($local) {
      $strSQL .= "  AND  B.UNI_K_CODUNIDAD  IN(" . $local . ")";
    }

    $strSQL .= "  AND     A.DEF_C_CODCARACT  IN('" . preg_replace("[,]", "','", $jerarquia) . "')                   
                    ORDER BY 1 ASC ";

    $query = $this->_dbRRHH->query($strSQL);

    $_result = $query->result();

    $this->_dbRRHH->close();

    return $_result;
  }

  public function getColaboradorMail($strRut)
  {
    $this->_dbRRHH->db_select();
    $query = $this->_dbRRHH->query(
      " SELECT A.EMP_K_RUTEMPLEAD RUT,
                                              A.EMP_A_DIGVERRUT,  
                                              INITCAP(A.EMP_A_NOMBRE||' '||A.EMP_A_APELLPATER||' '||A.EMP_A_APELLMATER)NOMBRE,
                                              A.MAE_C_EMAIL
                                       FROM MAE_EMPLEADO A,
                                            MAE_GRCARACT B
                                       WHERE B.EMP_K_RUTEMPLEAD = A.EMP_K_RUTEMPLEAD
                                         AND A.CIA_K_EMPRESA    = B.CIA_K_EMPRESA
                                         AND A.SYS_C_CODESTADO  = 1
                                         AND A.EMP_K_RUTEMPLEAD = " . $strRut . "
                                         AND B.TIP_K_TIPOCARACT = 8 "
    );
    $_result[] = $query->result();

    if (count($_result) > 0) {
      return array_shift($_result);
    } else {
      return array();
    }

    $this->_dbRRHH->close();
  }

  public function getSupMail($strRut)
  {
    $this->_dbRRHH->db_select();
    $query = $this->_dbRRHH->query(
      "select mae_c_email 
                                       from mae_empleado 
                                       where emp_k_rutemplead = " . $strRut . "
                                       and sys_c_codestado=1"
    );
    $_result[] = $query->result();
    $this->_dbRRHH->close();
    return array_shift($_result);
  }

  public function getTipo($tipo)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " SELECT PIN_DESC 
                                       FROM BIENESTAR_R_TIPOPIN
                                       WHERE ID_PIN ='" . $tipo . "'"
    );
    $_result[] = $query->result();
    $this->_dbIntra->close();
    return array_shift($_result);
  }

  public function traePINSUP($codigo)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(
      " SELECT RUT_RESP,
                                               RUT_COLAB
                                        FROM BIENESTAR_PR_COLABORADOR
                                        WHERE ID=" . $codigo
    );
    $_result[] = $query->result();
    $this->_dbIntra->close();
    return array_shift($_result);
  }

  public function traeNombre($rut)
  {
    $this->_dbIntra->db_select();
    $query = $this->_dbIntra->query(" select initcap(emp_a_nombre ||' '||emp_a_apellpater ||' '|| emp_a_apellmater) NOMBRE
                                        from mae_empleado
                                        where emp_k_rutemplead = " . $rut . "
                                        and ROWNUM = 1");
    $_result[] = $query->result();
    $this->_dbIntra->close();
    return array_shift($_result);
  }


  public function getCargoMailOnline($rut)
  {

    $this->_dbRRHH->db_select();

    $strSQL = "  SELECT  DISTINCT a.EMP_K_RUTEMPLEAD RUT,
                            InitCap(b.emp_a_nombre) || ' ' || InitCap(b.emp_a_apellpater)  || ' ' || InitCap(b.emp_a_apellmater )NOMBRE,
                            B.UNI_K_CODUNIDAD,
                            B.UNI_K_CODUNIDAD ||' - '||INITCAP(D.UNI_A_NOMBUNIDAD) nombre_unidad,
                            INITCAP(E.CIA_G_RAZONSOCIA)EMPRESA,
                            InitCap(C.VAL_A_DESCARACT)CARGO
                    FROM    MAE_GRCARACT A, 
                            MAE_EMPLEADO B, 
                            MAE_VALCARAC C, 
                            MAE_UNIDADES D,
                            MAE_CIAEMPRESA E
                    WHERE   A.EMP_K_RUTEMPLEAD =B.EMP_K_RUTEMPLEAD 
                      --AND   B.SYS_C_CODESTADO  = 1 
                      AND   A.DEF_C_CODCARACT  = C.VAL_C_CODCARACT 
                      AND   A.TIP_K_TIPOCARACT = 8 
                      AND   A.CIA_K_EMPRESA    = B.CIA_K_EMPRESA 
                      AND   A.CIA_K_EMPRESA    = C.CIA_K_EMPRESA 
                      AND	  A.CIA_K_EMPRESA    = D.CIA_K_EMPRESA 
                      AND	  B.UNI_K_CODUNIDAD  = D.UNI_K_CODUNIDAD 
                      AND   B.CIA_K_EMPRESA    = E.CIA_K_EMPRESA 
                      AND   A.EMP_K_RUTEMPLEAD = " . $rut . "
                      AND   B.EMP_F_TERMICONTR = TO_DATE((select  MAX(TO_CHAR(EMP_F_TERMICONTR,'DD-MM-YYYY'))fecha 
                                                          from MAE_EMPLEADO 
                                                          where EMP_K_RUTEMPLEAD = " . $rut . ") ,'DD-MM-YYYY')
                    ORDER BY 2 ASC ";
    $query = $this->_dbRRHH->query($strSQL);

    if ($query) {
      if ($query->num_rows() > 0) {
        $_result = $query->result();
      } else {
        $_result = array();
      }
    } else {
      $_result = array();
    }

    $this->_dbRRHH->close();
    return $_result;
  }

  public function traerDependientesRutSUP($cuiSup)
  {
    $this->_dbRRHH->db_select();
    $query = $this->_dbRRHH->query(" SELECT 
                                                RRHH.JER_EMPLEADO.JER_RUT as RUT 
                                        FROM 
                                              RRHH.JER_CUI_DEPENDENCIA,
                                              RRHH.JER_EMPLEADO, 
                                              MAE_EMPLEADO 
                                        WHERE ((RRHH.JER_CUI_DEPENDENCIA.CUI_SUP = '" . $cuiSup . "') 
                                            AND (MAE_EMPLEADO.SYS_C_CODESTADO = '1') 
                                            AND (RRHH.JER_EMPLEADO.JER_AUX_JEFE = 1)) 
                                            AND RRHH.JER_EMPLEADO.JER_UNIDAD = RRHH.JER_CUI_DEPENDENCIA.CUI_DEP 
                                            AND RRHH.JER_EMPLEADO.JER_RUT    = MAE_EMPLEADO.EMP_K_RUTEMPLEAD
                                            ");

    if ($query->num_rows() > 0) {
      $array = $query->result();
    } else {
      $array = array();
    }

    $this->_dbRRHH->close();
    return $array;
  }

  public function traerRutDependientes($cuiSup, $nivel)
  {

    $this->_dbRRHH->db_select();
    $query = $this->_dbRRHH->query("   SELECT 
                                                RRHH.JER_EMPLEADO.JER_RUT AS RUT
                                            FROM
                                                RRHH.JER_EMPLEADO,
                                                MAE_EMPLEADO    
                                            WHERE 
                                                  RRHH.JER_EMPLEADO.JER_UNIDAD = '" . $cuiSup . "'                                                  
                                                  AND MAE_EMPLEADO.SYS_C_CODESTADO = '1' 
                                                  AND RRHH.JER_EMPLEADO.JER_RUT = MAE_EMPLEADO.EMP_K_RUTEMPLEAD
                                                  AND (RRHH.JER_EMPLEADO.JER_AUX_JEFE = 0 OR RRHH.JER_EMPLEADO.JER_COD_NIVEL >" . $nivel . ")");

    if ($query->num_rows() > 0) {
      $array = $query->result();
    } else {
      $array = array();
    }

    $this->_dbRRHH->close();
    return $array;
  }
}//fin de la clase
