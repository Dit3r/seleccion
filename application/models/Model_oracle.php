<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Model_oracle extends CI_Model{
 
 private $_dbRRHH  = null;
 private $_dbIntra = null;
 private $_result;
  
   function __construct() {
      parent::__construct();
      $this->_dbRRHH  = $this->load->database('dbRRHH', TRUE);
      $this->_dbIntra = $this->load->database('default', TRUE);
      $this->load->library('session');     

   } 
   
   public function getPersonas($strRut){
      $this->_dbRRHH->db_select();
      $query = $this->_dbRRHH->query('SELECT * 
                                      from MAE_EMPLEADO 
                                      where emp_k_rutemplead = '.$strRut.' 
                                        and sys_c_codestado=1');
      $_result[] = $query->result();
      $this->_dbRRHH->close();     
      return $_result;
   }
   
   public function strUF(){
    $this->_dbRRHH->db_select();   
    $query = $this->_dbRRHH->query("Select TUF_K_FECHAUF fecha, 
                                           round(TUF_N_VALORUF) valor
                                    from TAB_TUF where tuf_k_fechaUF=to_date('".gmdate('d-m-Y')."','dd/mm/yyyy')");
    $_result = $query->row_array();
    $this->_dbRRHH->close();    
    return $_result;
   }
    
    
    public function santoral(){
      $this->_dbIntra->db_select();   
      $query = $this->_dbIntra->query("select santo_desc from santoral where to_char(fecha_id,'dd/mm')='".gmdate('d/m')."'");
      $_result = $query->row_array();   
      $this->_dbIntra->close();                              
      return $_result;
    }
    
    public function resultSQL($array){
         
       if(count($array) > 0){    
            foreach($array as $row){
               $_result[] = $row;
            }
       }else{
          $_result[] = array();
       }
       
        return $_result;
    }
    
     public function strBotones(){
      $this->_dbIntra->db_select();   
      $query = $this->_dbIntra->query(" Select ID_BOTONES,
                        					  IMAGEN,
                        					  URL
                        				from intra_botones
                        				where estado = 1
                                        and id_botones not in(361)");
      $_result = $query->result_array();   
      $this->_dbIntra->close();                              
      return $_result;
    }
    
     public function sliderFunc($date){
      $this->_dbIntra->db_select();   
      $query = $this->_dbIntra->query(" select decode(upper(URL),upper('http://'),'#',URL)url,imagen
                                        from in_slider 
                                        where estado = '1' 
                                        and to_date('".$date."','dd/mm/yyyy') between desde and hasta 
                                        order by 1 asc");
      $_result = $query->result_array(); 
      $this->_dbIntra->close();                                
      return $_result;
    }
    
    
    public function strModulos(){
      $this->_dbIntra->db_select();   
      $query = $this->_dbIntra->query(" select B.CABECERA,
                            				   B.ID_TIPO,
                            				   A.TITULO,
                            				   A.CONTENIDO,
                            				   A.IMAGEN,
                            				   A.FECHA_PUBLICACION,
                            				   A.ESTADO,
                            				   B.POSICION,
                            				   A.ID_CONTENEDOR,
                            				   B.estado_contenedor
                            			from INTRA_CONTENEDOR a,
                            				 INTRA_CONTENEDOR_TIPO b
                            			WHERE A.ID_TIPO = B.ID_TIPO
                            			and a.estado = '1'
                            			and b.estado_contenedor = '1'
                            			ORDER BY B.posicion asc");
      $_result = $query->result_array(); 
      $this->_dbIntra->close();                                
      return $_result;
    }

    
       
    public function strFechaEspiracion($rut){
      $this->_dbIntra->db_select();         
      $query = $this->_dbIntra->query(" Select  id_password,
                                                to_char(ID_FECHAEXPIRACION,'yyyymmdd') 
                                                fecha,
                                                ID_ESTADO 
                                        from mae_passwords 
                                        where id_rut=".$rut);                                        
      $_result = $query->result_array();   
      $this->_dbIntra->close();                              
      return $_result;
    }    
    public function strColaborador($rut){
      $this->_dbRRHH->db_select();         
                                        
      $query = $this->_dbRRHH->query(" SELECT  a.emp_k_rutemplead  as rutcolab,
                                               A.UNI_K_CODUNIDAD as ccosto, 
                                               INITCAP(A.EMP_A_NOMBRE||' '||A.EMP_A_APELLPATER) AS NOMBRE,
                                               INITCAP(D.UNI_A_NOMBUNIDAD)as desc_ccosto ,
                                               INITCAP(C.VAL_A_DESCARACT)as desc_jerar,
                                               a.car_k_codigcargo as cargo,
                                               b.def_c_codcaract as desc_cargo ,
                                               A.CIA_K_EMPRESA as empresa
                                         FROM MAE_EMPLEADO A,
                                              MAE_GRCARACT B,
                                              MAE_VALCARAC C,
                                              MAE_UNIDADES D
                                         WHERE A.EMP_K_RUTEMPLEAD = B.EMP_K_RUTEMPLEAD
                                          AND A.CIA_K_EMPRESA    = C.CIA_K_EMPRESA
                                          AND B.CIA_K_EMPRESA    = C.CIA_K_EMPRESA
                                          AND A.UNI_K_CODUNIDAD  = D.UNI_K_CODUNIDAD
                                          AND A.CIA_K_EMPRESA    = D.CIA_K_EMPRESA
                                          AND B.CIA_K_EMPRESA    = D.CIA_K_EMPRESA
                                          AND A.EMP_K_RUTEMPLEAD =".$rut."
                                          AND C.TIP_K_TIPOCARACT = 8
                                          AND A.SYS_C_CODESTADO  = 1
                                          AND B.DEF_C_CODCARACT  = C.VAL_C_CODCARACT ");                                                                               
      $_result = $query->result_array(); 
      $this->_dbRRHH->close();                                 
      return $_result;
    } 
       
    public function strBloqueo($rut,$blockeo){
      $this->_dbIntra->db_select();         
      $query = $this->_dbIntra->query(" update mae_passwords 
                                        set id_estado=0, 
                                        pc_block='".$blockeo."' 
                                        where id_rut=".$rut);
      if($query){
        $_result = true;
      } else{
         $_result = false;
      }                    
      $this->_dbIntra->close();                                                                                             
      return $_result;
    }    

    public function strValidaClave($strRUT,$pwdChangeKey){
      $this->_dbIntra->db_select();         
      $query = $this->_dbIntra->query(" Select count(*) as nfila 
                                        from mae_passwords 
                                        where id_rut=".$strRUT." 
                                        and id_password='".$pwdChangeKey."'");
      $_result = $query->result_array();   
      $this->_dbIntra->close();                                                                                                          
      return $_result;
    }    

    public function strCambioClave($strRUT,$NewPassword,$fechamodificacion,$fechadeexpiracion){
      $this->_dbIntra->db_select();         
      $query = $this->_dbIntra->query(" Update mae_passwords set id_password='".$NewPassword."' ,
                                        ID_FECHAMODIFICACION = to_date('".$fechamodificacion."','dd/mm/yyyy'),
                                        ID_FECHAEXPIRACION = to_date('".$fechadeexpiracion."','dd/mm/yyyy') 
                                        where id_rut=".$strRUT);
      if($query){
        $_result = true;
      } else{
         $_result = false;
      }                   
      $this->_dbIntra->close();                                                                                         
      return $_result;
            
    }    
    
   public function strFechas(){
      $this->_dbIntra->db_select();         
      $query = $this->_dbIntra->query(" SELECT TO_CHAR(SYSDATE,'DD-MM-YYYY') FECHA,
                                               TO_CHAR(ADD_MONTHS(SYSDATE,2),'DD-MM-YYYY') FECHA_HASTA 
                                        FROM DUAL ");
      $_result = $query->result_array();     
      $this->_dbIntra->close();                                                                                                        
      return $_result;            
   }    
   
   public function strCambioPregunta($Preg,$Resp,$Rut){
      $this->_dbIntra->db_select();         
      $query = $this->_dbIntra->query(" Update mae_passwords 
                                        set DESC_PREGUNTA=UPPER('".$Preg."'),
                                            DESC_RESPUESTA=UPPER('".$Resp."') 
                                        where id_rut=".$Rut);
      if($query){
        $_result = true;
      } else{
         $_result = false;
      }                  
      $this->_dbIntra->close();                                                                                                
      return $_result;            
   }    

   public function strBuscarPregunta($Rut){
      $this->_dbIntra->db_select();         
      $query = $this->_dbIntra->query(" SELECT DESC_PREGUNTA, 
                                        DESC_RESPUESTA
                                        FROM mae_passwords 
                                        where id_rut=".$Rut);
      $_result = $query->result_array();     
      $this->_dbIntra->close();                                                                                                        
      return $_result;           
   }    
  
   public function strClave($Rut){
      $this->_dbIntra->db_select();         
      $query = $this->_dbIntra->query(" Select * 
                                        from mae_passwords 
                                        where id_rut=".$Rut);
      $_result = $query->result_array();          
      $this->_dbIntra->close();                                                                                                   
      return $_result;           
   }    

   public function strIngresoUsuarrio($Rut,$pwdChangeKey,$PREG,$RESP,$fecha,$fechaAsta){
      $this->_dbIntra->db_select();

      $query = $this->_dbIntra->query(" Insert into mae_passwords
                                                                    (id_usuario,
                                                                     id_rut,
                                                                     id_password,
                                                                     id_estado,
                                                                     id_fechacreacion,
                                                                     id_fechamodificacion,
                                                                     id_fechaexpiracion,
                                                                     desc_pregunta,
                                                                     desc_respuesta)
                                                    values  (get_nextval_pwd,
                                                             ".$RUT.",
                                                             '".$pwdChangeKey."',
                                                             1,
                                                             to_date(SYSDATE,'dd/mm/yyyy'),
                                                             to_date('".$fecha."','dd/mm/yyyy'),
                                                             to_date('".$fechaAsta."','dd/mm/yyyy'),
                                                             Upper('".$PREG."'),Upper('".$RESP."')
                                                             )   
                                      ");
      if($query){
        $_result = true;
      } else{
         $_result = false;
      } 
               
      $this->_dbIntra->close();                                                                                                          
      return $_result;           
   }    
    public function strConsultaPregunta($Preg,$Resp,$Rut){
      $this->_dbIntra->db_select();         
      $query = $this->_dbIntra->query(" Select Upper(DESC_RESPUESTA) 
                                        from mae_passwords 
                                        where Upper(desc_respuesta) = Upper('".$Resp."') 
                                        and id_rut=".$Rut);
      $_result = $query->result_array();                 
      $this->_dbIntra->close();                                                                                               
      return $_result;            
   }   

    public function strCambioPass($strRUT,$NewPassword,$fechamodificacion,$fechadeexpiracion,$cambio){
      $this->_dbIntra->db_select();         
      $query = $this->_dbIntra->query(" Update mae_passwords set id_password='".$NewPassword."' ,
                                        ID_FECHAMODIFICACION = to_date('".$fechamodificacion."','dd/mm/yyyy'),
                                        ID_FECHAEXPIRACION = to_date('".$fechadeexpiracion."','dd/mm/yyyy'),
                                        PC_CPASS='".$cambio."',ID_ESTADO=1  
                                        where id_rut=".$strRUT);
      if($query){
        $_result = true;
      } else{
         $_result = false;
      }                   
      $this->_dbIntra->close();                                                                                         
      return $_result;
            
    } 
}


	