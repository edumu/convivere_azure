<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Modelo para interactuar en la BD
 * 
 * SELECT CUENTA, NOMBRE, APELLIDOS, CONTRASENA, NIVEL_ACCESO, TELEFONO_FIJO, CELULAR, CORREO_VERIFICADO, CELULAR_VERIFICADO, ACTIVO FROM USUARIOS WHERE 1
 */
 
class MDL_contrataciones extends CI_Model {
    
    function __construct()
    {
        $this->load->database();
    }

    function __destruct() 
    {
        $this->db->close();
    }

	
    public function test()
    {        
        $query = $this->db->get('TEST');

        if($query->num_rows() > 0 )      
            return $query->result();
	else 
            return array();  
    }
    

}//MDL_contrataciones
