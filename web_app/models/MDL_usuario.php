<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Modelo para interactuar en la BD
 * 
 * SELECT CUENTA, NOMBRE, APELLIDOS, CONTRASENA, NIVEL_ACCESO, TELEFONO_FIJO, CELULAR, CORREO_VERIFICADO, CELULAR_VERIFICADO, ACTIVO FROM USUARIOS WHERE 1
 */
 
class MDL_usuario extends CI_Model {		        
    
    function __construct()
    {
        $this->load->database();
    }

    function __destruct() 
    {
        $this->db->close();
    }

    public function testFunction($usuario,$pwd)
    {
    try{
        $this->db->select('U.NOMBRE, U.APELLIDOS, U.NIVEL_ACCESO');
        $this->db->from  ('USUARIOS U');        
        $this->db->where ('U.CUENTA',$usuario);
        $this->db->where ('U.CONTRASENA',$pwd);
        $this->db->where ('U.ACTIVO',1);        
      
        $query = $this->db->get();
        //$query = $this->db->query("SELECT * FROM usuarios where NIVEL_ACCESO=3"); 
        return ( empty($query->result())? "vacio": "NO VACIO");
        //return ( empty($query->result())? NULL: $query->result_array());
        
        
        /*
        //$query = $this->db->query("SELECT * FROM [usuarios]");      
        $query = $this->db->query("SELECT * FROM data_convivere.convivere.usuarios");  
        var_dump($query);
        //$query=$this->db->get('usuarios'); 
        return $query;
       // return $query->result();
       // return ( $query->num_rows() > 0 )? $query->result_array(): NULL;
       */
        } catch (Exception $e) {echo 'Excepción MDL_usuario/validaUsuario:',  $e, "\n";}	
    }

public function validaUsuario($usuario,$pwd)
{
try{$this->db->trans_start();
    $resp  = array();

    $this->db->select('U.NOMBRE, U.APELLIDOS, U.NIVEL_ACCESO');
    $this->db->from  ('USUARIOS U');        
    $this->db->where ('U.CUENTA',$usuario);
    $this->db->where ('U.CONTRASENA',$pwd);
    $this->db->where ('U.ACTIVO',1);
    $query = $this->db->get();
    $resp  = ( empty($query->result())? NULL : $query->result_array());    

    $this->db->trans_complete();
    return $resp;     

    } catch (Exception $e) {echo 'Excepción MDL_usuario/validaUsuario:',  $e, "\n";}	
}

public function traeEdificiosSuperAdmin()
{   $this->db->trans_start();
    $resp  = array();

    $this->db->select('E.ID_EDIFICIO');
    $this->db->from  ('EDIFICIOS E');    
    $query = $this->db->get();        
    $resp  = ( empty($query->result())? array() : $query->result_array());

    $this->db->trans_complete();
    return $resp;
}


public function traeEdificiosAdmin($cuenta)
{   $this->db->trans_start();
    $resp  = array();

    $this->db->select('E.ID_EDIFICIO');
    $this->db->from  ('EDIFICIOS E');    
    $this->db->where ('E.CUENTA_ADMIN',$cuenta); 
    $query = $this->db->get();        
    $resp  = ( empty($query->result())? array(): $query->result_array());

    $this->db->trans_complete();
    return $resp;    
}

public function traeEdificiosPorDefaultAdmin($cuenta)
{   $this->db->trans_start();
    $resp  = array();

    $this->db->select('E.ID_EDIFICIO');
    $this->db->from  ('EDIFICIOS E');    
    $this->db->where ('E.CUENTA_ADMIN' , $cuenta); 
    $this->db->where ('E.DEFAULT_ADMIN', 1); 
    $query = $this->db->get();        
    $resp  = ( empty($query->result())? 0 : $query->result_array()[0]['ID_EDIFICIO']);

    $this->db->trans_complete();
    return $resp;     
}


public function traeDeptosUser($cuenta,$campo)
{   $this->db->trans_start();
    $resp  = array();

    $this->db->select('DE.'.$campo);
    $this->db->from  ('DETALLE_DEPTO DE');    
    $this->db->where ('DE.CUENTA',$cuenta);        
    $query = $this->db->get();        
    $resp  = ( empty($query->result()) ? array($campo=>NULL) : $query->result_array()[0][$campo] );

    $this->db->trans_complete();
    return $resp;     
}

public function traeUsuario($cuenta)
{   $this->db->trans_start();
    $resp  = array();

    $this->db->select('U.NOMBRE, U.APELLIDOS, U.CELULAR, C.DESCRIPCION AS TIPO');
    $this->db->from  ('USUARIOS U');
    $this->db->join  ('DETALLE_DEPTO DE', 'U.CUENTA = DE.CUENTA','inner outer');
    $this->db->join  ('CATALOGOS C'     , 'DE.CONTACTO = C.ID_CATALOGOS  ','inner outer');
    $this->db->where ('U.CUENTA'        , $cuenta);        
    $query = $this->db->get();        
    $resp  = ( empty($query->result()) ? array() :$query->result_array() );

    $this->db->trans_complete();
    return $resp;     
}


public function traeUsuarioForgotPWD($cuenta)
{   $this->db->trans_start();
    $resp  = array();

    $this->db->select('CONTRASENA');
    $this->db->from  ('USUARIOS');    
    $this->db->where ('CUENTA', $cuenta);        
    $query = $this->db->get();        
    $resp  = ( empty($query->result()) ? NULL :$query->result_array()[0] );

    $this->db->trans_complete();
    return $resp;     
}

 public function poblarSelectUsuario()
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();
    
        $this->db->select  ('CUENTA');
        $this->db->select  ("CONCAT_WS('',".$convSql->getINT_FIELD_BD()."NOMBRE".$convSql->getEND_FIELD_BD().", ".$convSql->getINT_FIELD_BD()."APELLIDOS".$convSql->getEND_FIELD_BD().") as NOMBRE_USR  ", FALSE);
        $this->db->order_by("NOMBRE, APELLIDOS","ASC");
        $this->db->where   ('NIVEL_ACCESO',  USER);
        $query = $this -> db -> get('USUARIOS ');	
	
        $options[] = array("id" => 0, "value" =>'::Seleccione::');
		
        if( empty($query->result())  )
        {   $resp = $options;          }
        else{
            foreach ($query->result() as $row)
               { $options[] = array("id" => $row->CUENTA, "value" => $row->NOMBRE_USR);}
            $resp  = $options;   
            }

        $this->db->trans_complete();
        return $resp;

        }catch (Exception $e) { log_message('error', "Excepción poblarSelectUsuario: $e"); }            
    }
    
    
    public function insert_user($data)
    {	$this->db->trans_start();        
        $qry = $this->db->insert('USUARIOS',$data);
        $this->db->trans_complete();

        return $qry;        
    }

}//MDL_USUARIO
