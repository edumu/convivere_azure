<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Modelo para interactuar en la BD
 * 
 */
 
class MDL_docs extends CI_Model {
	
    function __construct()
    {
        $this->load->database();
    }

    function __destruct() 
    {
        $this->db->close();
    }




    public function update_doc($param, $datos)
    {	$this->db->trans_start();        
        $this->db->where ('ID_DOC'      , $param['ID_DOC'] );
        $this->db->where ('ID_EDIFICIO' , $param['ID_EDIFICIO']   );        
        $qry = $this->db->update('DOCS', $datos      );
        $this->db->trans_complete();

        return $qry;
    }
    
    public function insert_doc($data)
    {   $this->db->trans_start();
        $qry = $this->db->insert('DOCS', $data);
        $this->db->trans_complete();

        return $qry;        
    }

    public function poblarSelectTiposDoc($tipo)
    {   $this->db->trans_start();

        $this->db->select  ('ID_CATALOGOS, DESCRIPCION');
        $this->db->where   ('CAMPO', $tipo); 
        $this->db->order_by(" DESCRIPCION","ASC");					
        $query = $this->db->get('CATALOGOS');
        $options = NULL;
        
        if( empty($query->result()) )
            { $options = array();   }
        else
            {   $options = array();
                $options[0] = '::Seleccione una opción::';
                foreach ($query->result() as $row)							 
                    { $options[$row->ID_CATALOGOS] = $row->DESCRIPCION; }
            }

        $this->db->trans_complete();    
        return $options;  
    }
    

    public function traeDocsPaginar($param)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();
        
        $off  = (($param['pagina']-1) * $param['registrosPagina']);
        /****** CONTEO INI*************/
    	$this -> db -> select('count(D.ID_DOC) as conteo');
        $this->db->join  ($convSql->getPREFIX_DB_CVR().'CATALOGOS C', 'D.TIPO_DOC = C.ID_CATALOGOS ','inner outer', FALSE);

        if (is_array($param['id_edificio']))
        { $util = new Utils();
          $edis = $util->edificiosString($param['id_edificio']);
          $this->db->where("D.ID_EDIFICIO IN $edis",NULL, FALSE ); 
          unset($util);
        }
        else
        { $this->db->where('D.ID_EDIFICIO', $param['id_edificio']);    }
        
        $query  = $this->db->get('DOCS D')->result();
	    $conteo = $query[0]->conteo;
        /****** CONTEO FIN*************/        

        /****** REGISTROS INI*************/
        $this->db->select('D.ID_DOC, D.ID_EDIFICIO, D.RUTA_DOC, D.TIPO_DOC, D.DESCRIPCION, D.CUENTA, D.PRIVILEGIOS');        
        $this->db->join  ($convSql->getPREFIX_DB_CVR().'CATALOGOS C', 'D.TIPO_DOC = C.ID_CATALOGOS ','inner outer', FALSE);
          
        if (is_array($param['id_edificio']))
        { $util = new Utils();
          $edis = $util->edificiosString($param['id_edificio']);
          $this->db->where($convSql->getINT_FIELD_BD()."D.ID_EDIFICIO".$convSql->getEND_FIELD_BD()." IN $edis",NULL, FALSE ); 
          unset($util);
        }
        else
        { $this->db->where('D.ID_EDIFICIO', $param['id_edificio']);    }
        
        $this->db->order_by("D.ID_DOC","DESC"); 
        $queryReg = $this->db->get('DOCS D', $param['registrosPagina'],$off);			
        /****** REGISTROS FIN*************/
        
        $resp  = ( empty($queryReg->result()) ? FALSE : array("conteo"=>$conteo, "registros"=>$queryReg->result_array(), "offset"=>$off));   

        $this->db->trans_complete();
        return $resp;        

    }catch (Exception $e) { log_message('error', "Excepción traeInventarioPaginar:".$e); }   

    }//traeInventarioFiltros


    public function traeDocbyID($edificio, $doc)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();
  
        $this->db->select('ID_DOC, ID_EDIFICIO ,RUTA_DOC ,TIPO_DOC ,DESCRIPCION ,CUENTA ,PRIVILEGIOS ,FECHA_ALTA ,FECHA_MODIFICACION');
        $this->db->select($convSql->sqlDateFunction()."(FECHA_ALTA,".$convSql->sqlDateFormatdd_MM_yyyy().") as FECHA_ALTA", FALSE);
        $this->db->select("(NULL) AS NUEVO", FALSE);
        $this->db->where ('ID_EDIFICIO',  $edificio);
        $this->db->where ('ID_DOC',  $inv); 
        $query = $this->db->get('DOCS');
        
        $resp  = ( empty($query->result()) ? array("ID_EDIFICIO"=>$edificio,"ID_DOC"=>intval(substr(now(), -7)), "DOCUMENTO"=>NULL, "DESCRIPCION"=>NULL, "NUEVO"=>"SI" ) : $query->result_array()[0]);

        $this->db->trans_complete();
        return $resp;

      }catch (Exception $e) { log_message('error', "Excepción traeDocbyID:".$e); }      
        
    }//traeTbjoMtobyID

    

    

}//MDL_docs
