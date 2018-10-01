<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Modelo para interactuar en la BD
 * SELECT ID_CUOTA,RUTA_RECIBO,RUTA_FORMATO_PAGO,STATUS,CUOTA_DEL_MES,FECHA_PAGO,FECHA_FORMATO_PAGO,FECHA_VIGENCIA_FORMATO,IMPORTE,ID_EDIFICIO,ID_DEPTO,TORRE,REFERENCIA,REFERENCIA_OPENPAY,ID_TRANSACCION_OPEN_PAY,TIPO_PAGO` FROM `CUOTAS
 * 
 */
 
class MDL_cuotas extends CI_Model {

    function __construct()
    {
        $this->load->database();
    }

    function __destruct() 
    {
        $this->db->close();
    }
    

    public function update_cuota($param)
    {	$this->db->trans_start();
        $this->db->where ('TORRE'        ,  $param['torre']                   );
        $this->db->where ('ID_DEPTO'     ,  $param['id_depto']                );
        $this->db->where ('ID_EDIFICIO'  ,  $param['id_edificio']             );
        $qry = $this->db->update('CUOTAS', array("NUMERACION"=>$param['num']) );        
        $this->db->trans_complete();

        return $qry;
    }
    
    public function insert_cuota($data)
    {	$this->db->trans_start();
        $qry = $this->db->insert('CUOTAS',$data);
        $this->db->trans_complete();

        return $qry;
    }
                
    
    public function delete_edificio( $id_edi)
    {   $this->db->trans_start();
        $this->db->where('ID_EDIFICIO',  $id_edi);
        $qry = $this->db->update('EDIFICIOS', array('ACTIVO' => 0));           
        $this->db->trans_complete();

        return $qry; 	   
    }
    
    public function traeCuotas($param)
    {   $this->db->trans_start();
        $resp  = array();

        $this->db->select('*');
        $this->db->where ('TORRE'      ,  $param['torre']);
        $this->db->where ('ID_DEPTO'   ,  $param['id_depto']);
        $this->db->where ('ID_EDIFICIO',  $param['id_edificio']);
        $query = $this->db->get('CUOTAS C');
        $resp  = ( empty($query->result()) ? NULL : $query->result_array());   

        $this->db->trans_complete();
        return $resp;        
    }
    
    public function poblarSelect()
    {   $this->db->trans_start();

        $this->db->select  ('E.ID_EDIFICIO, E.NOMBRE, E.CALLE, E.NUMERO, E.COLONIA, E.LOGOTIPO');
        $this->db->order_by("E.FECHA_ALTA","ASC");

        $query = $this->db->get('EDIFICIOS E');
	    $options = array();
        if($query -> num_rows() > 0 )
            {   $options[0] = '::Seleccione una opción::';
                foreach ($query->result() as $row)							 
                    { $options[$row->ID_EDIFICIO] = $row->NOMBRE." ".$row->CALLE." ".$row->NUMERO." ".$row->COLONIA; }
            }

        $this->db->trans_complete();        
        return $options;  
    }
    
    public function traeEdificio($id_edificio)
    {   $this->db->trans_start();
        $resp  = array();
 					
        $this->db->select('E.ID_EDIFICIO, E.NOMBRE, E.CALLE, E.NUMERO, E.COLONIA, E.ALCALDIA, E.ESTADO, E.CP, E.LATITUD, E.LONGITUD, E.CUOTA_MANTO, E.DIA_CORTE, E.TIPO_PENALIZACION, E.PENALIZACION, E.NUM_TORRES, E.NUM_VIVIENDAS, E.LOGOTIPO');
        $this->db->where ('ID_EDIFICIO',  $id_edificio);
        $query = $this->db->get('EDIFICIOS E');
        $resp  = ( empty($query->result()) ? array() : $query->result_array());   

        $this->db->trans_complete();
        return $resp; 
    }
   
    public function traePagCuotasFiltros($param)
    {   
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();
 		
        $off  = (($param['pagina']-1) * $param['registrosPagina']);
    	$this->db->select('count(C.ID_CUOTA) as conteo');        
        $this->db->join  ($convSql->getPREFIX_DB_CVR().'CATALOGOS CAT','CAT.ID_CATALOGOS = C.TIPO_PAGO AND CAT.CAMPO=\'tipo_pago\' ','inner outer', FALSE);         
        
        if (!empty($param['f1']))
            { $this -> db -> or_like(array('i.num_file' => $param['f1'] ) ); }
        if (!empty($param['f2']))
            { $this -> db -> or_like(array('i.master' => $param['f2']));     }
        if (!empty($param['f3']))
            { $this -> db -> or_like(array('i.shipper' => $param['f3']));    }

        $this->db->where('C.ID_DEPTO'   , $param['id_depto']);
        $this->db->where('C.TORRE'      , $param['torre']);
        $this->db->where('C.ID_EDIFICIO', $param['id_edificio']);
        $query = $this->db->get('CUOTAS C')->result();
	    $conteo = $query[0]->conteo;
        
        $this -> db -> select('C.ID_CUOTA, C.RUTA_RECIBO, C.RUTA_FORMATO_PAGO, C.STATUS, C.CUOTA_DEL_MES, C.IMPORTE, C.REFERENCIA, C.REFERENCIA_OPENPAY, C.ID_TRANSACCION_OPEN_PAY, C.TIPO_PAGO, CAT.DESCRIPCION AS TIPOPAGO, C.ID_DEPTO, C.TORRE, C.ID_EDIFICIO');
        $this -> db -> select($convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."C".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."FECHA_PAGO".$convSql->getEND_FIELD_BD().",".$convSql->sqlDateFormatdd_MMM_yyyy().") as FECHA_PAGO, ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."C".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."FECHA_FORMATO_PAGO".$convSql->getEND_FIELD_BD().",".$convSql->sqlDateFormatdd_MMM_yyyy().") as FECHA_FORMATO_PAGO, ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."C".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."FECHA_VIGENCIA_FORMATO".$convSql->getEND_FIELD_BD().",".$convSql->sqlDateFormatdd_MMM_yyyy().") as FECHA_VIGENCIA_FORMATO", FALSE);
        $this -> db -> join  ($convSql->getPREFIX_DB_CVR().'CATALOGOS CAT','CAT.ID_CATALOGOS = C.TIPO_PAGO AND CAT.CAMPO=\'tipo_pago\' ','inner outer', FALSE);
        
        if (!empty($param['f1']))
            { $this -> db -> or_like(array('i.num_file' => $param['f1'] ) ); }
        if (!empty($param['f2']))
            { $this -> db -> or_like(array('i.master' => $param['f2']));     }
        if (!empty($param['f3']))
            { $this -> db -> or_like(array('i.shipper' => $param['f3']));    }                            
        
        $this->db->order_by("C.ID_CUOTA"   ,"DESC");        
        $this->db->where   ('C.ID_DEPTO'   , $param['id_depto']);
        $this->db->where   ('C.TORRE'      , $param['torre']);
        $this->db->where   ('C.ID_EDIFICIO', $param['id_edificio']);

        $queryReg = $this->db->get('CUOTAS C',$param['registrosPagina'],$off);			
        $resp     = ( empty($queryReg->result()) ? FALSE : array("conteo"=>$conteo, "registros"=>$queryReg->result_array(), "offset"=>$off) );   

        $this->db->trans_complete();
        return $resp; 
        
       }catch (Exception $e) { log_message('error', "Excepción traePagCuotasFiltros: $e"); }
    }
    
    public function traeCuotasAX($param)
    {   $this->db->trans_start();
        $resp  = array();
 		
        $this->db->select(' D.NUMERACION');        
        $this->db->where ('D.TORRE'      , $param['torre']);
        $this->db->where ('D.ID_EDIFICIO', $param['id_edificio']);
        $this->db->where ('D.ID_DEPTO'   , $param['id_depto']);

        $queryReg = $this->db->get('DEPARTAMENTOS D');
        $resp     = ( empty($queryReg->result()) ? array() : $queryReg->result_array());   

        $this->db->trans_complete();
        return $resp;         
    }
    
    
    public function traeCuotasPendientes($id_edificio, $agruparPorDepto, $torre, $id_depto)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();

        $cuotasQRY = $convSql->queryCuotasPendientes($this->db, $id_edificio, $agruparPorDepto, $torre, $id_depto);
        $cuotas    = ( is_array($cuotasQRY->result_array()) ? $cuotasQRY->result_array() : array() );

        $this->db->trans_complete();
        return $cuotas;

       }catch (Exception $e) { log_message('error', "Excepción traeCuotasPendientes: $e"); }  
     
    }//traeCuotasPendientes 
    


    public function traeTotCuotasTasa($id_edificio, $id_depto)
    {     
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();
 		
        $cuotasQRY = $convSql->queryTotCuotasTasa($this->db, $id_edificio, $id_depto);
        $resp      = empty($cuotasQRY->result()) ? array() : $cuotasQRY->result_array()[0];

        $this->db->trans_complete();
        return $resp;

       }catch (Exception $e) { log_message('error', "Excepción traeTotCuotasTasa: $e"); }     

    }//traeTotCuotasTasa
    
    
}//MDL_cuotas
