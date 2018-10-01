<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Modelo para interactuar en la BD
 * SELECT E.ID_EDIFICIO, E.NOMBRE, E.CALLE, E.NUMERO, E.COLONIA, E.ALCALDIA, E.ESTADO, E.LATITUD, E.LONGITUD, E.CUOTA_MANTO, E.DIA_CORTE, E.TIPO_PENALIZACION, E.PENALIZACION, E.NUM_TORRES, E.NUM_VIVIENDAS, E.LOGOTIPO,  E.FECHA_ALTA FROM EDIFICIOS WHERE 1
 */
 
class MDL_edificio extends CI_Model {

    function __construct()
    {
        $this->load->database();
    }

    function __destruct() 
    {
        $this->db->close();
    }


    public function update_edificio( $id_edi, $data)
    {	$this->db->trans_start();
        $this->db->where ('ID_EDIFICIO',  $id_edi);
        $qry = $this->db->update('EDIFICIOS', $data);        
        $this->db->trans_complete();

        return $qry;	               
    }
    
    public function update_depto($param)
    {	$this->db->trans_start();
        $this->db->where ('TORRE'        ,  $param['torre']);
        $this->db->where ('ID_DEPTO'     ,  $param['id_depto']);
        $this->db->where ('ID_EDIFICIO'  ,  $param['id_edificio']);
        $qry = $this->db->update('DEPARTAMENTOS', array("NUMERACION"=>$param['num']));                   
        $this->db->trans_complete();

        return $qry;    	
    }
    
    public function insert_edificio($data)
    {	$this->db->trans_start();        
        $qry = $this->db->insert('EDIFICIOS',$data);
        $this->db->trans_complete();

        return $qry;        
    }
    
    public function insert_deptos($data)
    {	$this->db->trans_start();        
        $qry = $this->db->insert('DEPARTAMENTOS',$data);
        $this->db->trans_complete();

        return $qry; 	        
    }
    
    public function insert_detalle_deptos($data)
    {	$this->db->trans_start();
        $qry = $this->db->insert('DETALLE_DEPTO',$data);
        $this->db->trans_complete();

        return $qry;        
    }
    
    public function update_deptos( $id_depto, $data)
    {	$this->db->trans_start();
	    $this->db->select('D.ID_DEPTO');        
        $this->db->where ('D.ID_DEPTO',  $id_depto);
        $query = $this->db->get('DEPARTAMENTOS D');        
        if( empty($query->result()) )        
        {   $data['ID_DEPTO'] = $id_depto;
            $qry = $this->db->insert('DEPARTAMENTOS',$data);        
        }
        else
        {
            $this->db->where ('ID_DEPTO',  $id_depto);
            $qry = $this->db->update('DEPARTAMENTOS', $data);
        }
        $this->db->trans_complete();

        return $qry;        
    }
    
    public function delete_edificio( $id_edi)
    {	$this->db->trans_start();		
	    $this->db->where('ID_EDIFICIO',  $id_edi);
        $qry = $this->db->update('EDIFICIOS', array('ACTIVO' => 0));
        $this->db->trans_complete();

        return $qry;
    }
    
    public function traeIdEdificio($param)
    {   $this->db->trans_start();
        $resp  = array();
					
        $this->db->select('E.ID_EDIFICIO');
        $this->db->where (' E.NOMBRE'    ,  $param['NOMBRE']);
        $this->db->where (' E.CALLE'     ,  $param['CALLE']);
        $this->db->where (' E.NUMERO'    ,  $param['NUMERO']);
        $this->db->where (' E.COLONIA'   ,  $param['COLONIA']);
        $this->db->where (' E.CREADO_MOD',  $param['CREADO_MOD']);
        $query = $this->db->get('EDIFICIOS E');
        $resp  = ( empty($query->result()) ? NULL : $query->result_array()[0]['ID_EDIFICIO'] );

        $this->db->trans_complete();
        return $resp;          
    }
    

    public function update_inventario($param, $datos)
    {	$this->db->trans_start();        
        $this->db->where ('ID_INVENTARIO', $param['ID_INVENTARIO'] );
        $this->db->where ('ID_EDIFICIO'  , $param['ID_EDIFICIO']   );        
        $qry = $this->db->update('INVENTARIO_ACTIVOS', $datos      );
        $this->db->trans_complete();

        return $qry;
    }
    
    public function insert_inventario($data)
    {   $this->db->trans_start();
        $qry = $this->db->insert('INVENTARIO_ACTIVOS', $data);
        $this->db->trans_complete();

        return $qry;        
    }

    public function poblarSelect()
    {   $this->db->trans_start();

        $this->db->select  ('E.ID_EDIFICIO, E.NOMBRE, E.CALLE, E.NUMERO, E.COLONIA, E.LOGOTIPO');
        $this->db->order_by("E.FECHA_ALTA","ASC");					
        $query = $this->db->get('EDIFICIOS E');
        $options = NULL;
        
        if( empty($query->result()) )
            { $options = array();   }
        else
            {   $options = array();
                $options[0] = '::Seleccione una opción::';
                foreach ($query->result() as $row)							 
                    { $options[$row->ID_EDIFICIO] = $row->NOMBRE." ".$row->CALLE." ".$row->NUMERO." ".$row->COLONIA; }
            }

        $this->db->trans_complete();    
        return $options;  
    }
    
    public function poblarSelectPresupuestos($id_edificio)
    {   $this->db->trans_start();

        $this->db->select  ('P.PRESUPUESTO_ID, P.NOMBRE_PTO');
        $this->db->where   ('P.ID_EDIFICIO',  $id_edificio);
        $this->db->order_by("P.FECHA_ALTA" ,"ASC");					
        $query = $this->db->get('PRESUPUESTO P');
	    $options = array();
        
        if( empty($query->result()) )
           { $options[0] = 'Nuevo Presupuesto';   }
        else
           {   $options[0] = 'Nuevo Presupuesto';
               foreach ($query->result() as $row)							 
                { $options[$row->PRESUPUESTO_ID] = $row->NOMBRE_PTO; }
           }        
        
        $this->db->trans_complete();    
        return $options;   
    }
    
    public function traeEdificio($id_edificio)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();

        $this->db->select('E.ID_EDIFICIO, E.NOMBRE, E.CALLE, E.NUMERO, E.COLONIA, E.ALCALDIA, E.ESTADO, E.CP, E.LATITUD, E.LONGITUD, E.CUOTA_MANTO, E.DIA_CORTE, E.TIPO_PENALIZACION, E.PENALIZACION, E.NUM_TORRES, E.NUM_VIVIENDAS, E.LOGOTIPO, E.TIENE_AMENIDADES');
        $convSql->agregaFechaAlta($this->db);
        $convSql->agregaPrimeraCuotaEdificio($this->db);
        $this->db->where ('ID_EDIFICIO',  $id_edificio);
        $query = $this->db->get('EDIFICIOS E');        
        unset($convSql); 
        $resp  = ( empty($query->result()) ? array() : $query->result_array());

        $this->db->trans_complete();
        return $resp;

     }catch (Exception $e) { log_message('error', "Excepción traeEdificio: $e"); }
    }
    
    public function traeE_Depto($id_edificio, $id_depto, $cuenta, $nivelacceso)
    {   
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();

        $nivelAccesioQry = ( ($nivelacceso == USER)? " AND  DD.CUENTA ='".$cuenta."' " : "" ); 
        $query = $this->db->query( "SELECT DEP.TORRE, DEP.NUMERACION, DEP.STATUS, DEP.CUMPLIMIENTO, DEP.ID_DEPTO, E.ID_EDIFICIO, E.NOMBRE, E.CALLE, E.NUMERO, E.COLONIA, E.ALCALDIA, E.ESTADO, E.CP, E.LATITUD, E.LONGITUD, E.CUOTA_MANTO, E.DIA_CORTE, E.TIPO_PENALIZACION, E.PENALIZACION, E.LOGOTIPO
                                    ".$convSql->agregaFechaAltaSQL()."
                                    FROM ".$convSql->getPREFIX_DB_CVR()."EDIFICIOS E
                                    INNER JOIN ".$convSql->getPREFIX_DB_CVR()."DEPARTAMENTOS DEP ON E.ID_EDIFICIO = DEP.ID_EDIFICIO
                                    INNER JOIN ".$convSql->getPREFIX_DB_CVR()."DETALLE_DEPTO DD  ON DEP.ID_DEPTO  = DD.ID_DEPTO AND DEP.TORRE = DD.TORRE 
                                    WHERE E.ID_EDIFICIO = ".$id_edificio." 
                                        AND DEP.ID_DEPTO = ".$id_depto."                                                   
                                        ".$nivelAccesioQry."
                                ");
        $resp  = ( empty($query->result()) ? array() : $query->result_array());

        $this->db->trans_complete();
        return $resp;            

    }catch (Exception $e) { log_message('error', "Excepción traeE_Depto: $e"); }

    }
    
      public function traeDeptoXEdTor($id_edificio,$torre,$id_depto)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();

        $query = $this->db->query( "SELECT DEP.TORRE, DEP.NUMERACION, DEP.STATUS, DEP.CUMPLIMIENTO, DEP.ID_DEPTO, E.ID_EDIFICIO, E.NOMBRE, E.CALLE, E.NUMERO, E.COLONIA, E.ALCALDIA, E.ESTADO, E.CP, E.LATITUD, E.LONGITUD, E.CUOTA_MANTO, E.DIA_CORTE, E.TIPO_PENALIZACION, E.PENALIZACION, E.LOGOTIPO, FORMAT(E.FECHA_ALTA,'yyyy-MM') as FECHA_ALTA
                                    FROM ".$convSql->getPREFIX_DB_CVR()."EDIFICIOS E
                                    INNER JOIN ".$convSql->getPREFIX_DB_CVR()."DEPARTAMENTOS DEP ON E.ID_EDIFICIO = DEP.ID_EDIFICIO
                                    INNER JOIN ".$convSql->getPREFIX_DB_CVR()."DETALLE_DEPTO DD  ON DEP.ID_DEPTO  = DD.ID_DEPTO AND DEP.TORRE = DD.TORRE 
                                    WHERE E.ID_EDIFICIO = ".$id_edificio." 
                                      AND DEP.ID_DEPTO  = ".$id_depto." 
                                      AND DD.TORRE      = '".$torre."'
                                   ");
        $resp  = ( empty($query->result()) ? array() : $query->result_array());

        $this->db->trans_complete();
        return $resp; 

     }catch (Exception $e) { log_message('error', "Excepción traeDeptoXEdTor: $e"); }

    }
    
    public function traeDeptoDetalle($param)
    {
    try{$convSql = new ConverterSQL();   
        $this->db->trans_start();
        $resp  = array();
     
        $this->db->select('DD.ID_DETALLE_DEPTO, DD.CUENTA, DD.CONTACTO, C.DESCRIPCION AS TIPO');
        $this->db->select("CONCAT_WS(' ',U.nombre, U.apellidos) as CONTACTO_NOMBRE", FALSE);
        $this->db->join  ($convSql->getPREFIX_DB_CVR().'USUARIOS U' , 'U.CUENTA = DD.CUENTA'         ,'inner outer', FALSE);        
        $this->db->join  ($convSql->getPREFIX_DB_CVR().'CATALOGOS C', 'DD.CONTACTO = C.ID_CATALOGOS ','inner outer', FALSE);
        $this->db->where ('DD.ID_EDIFICIO', $param['id_edificio']);
        $this->db->where ('DD.ID_DEPTO'   , $param['id_depto']);
        $this->db->where ('DD.TORRE'      , $param['torre']);
        $query = $this->db->get('DETALLE_DEPTO DD');
        $resp  = ( empty($query->result()) ? array() : $query->result_array());

        $this->db->trans_complete();
        return $resp;

    }catch (Exception $e) { log_message('error', "Excepción traeDeptoDetalle: $e"); }

    }
    
    public function traeDepto($param)
    {   $this->db->trans_start();
        $resp  = array();
					
        $this->db->select('D.ID_EDIFICIO, D.ID_DEPTO, D.TORRE, D.NUMERACION, D.STATUS, D.CUMPLIMIENTO');
        $this->db->where ( 'D.ID_EDIFICIO', $param['id_edificio']);
        $this->db->where ( 'D.ID_DEPTO'   , $param['id_depto']);
        $this->db->where ( 'D.TORRE'      , $param['torre']);
        $query = $this -> db -> get('DEPARTAMENTOS D');
        $resp  = ( empty($query->result()) ? array() : $query->result_array());   

        $this->db->trans_complete();
        return $resp;
    }
    
    public function traeTorres($id_edificio)
    {   $this->db->trans_start();
        $resp  = array();
 					
        $this->db->select  ('D.TORRE');
        $this->db->group_by('D.TORRE'); 
        $this->db->order_by('D.TORRE', 'ASC'); 
        $this->db->where   ('ID_EDIFICIO',  $id_edificio);
        $query = $this->db->get('DEPARTAMENTOS D');
        $resp  = ( empty($query->result()) ? array() : $query->result_array() );   

        $this->db->trans_complete();
        return $resp;          
    }
    
    
    public function poblarSelectCat($campo, $initValue)
    {   $this->db->trans_start();

        $this->db->select  ('C.ID_CATALOGOS, C.DESCRIPCION');
        $this->db->order_by("C.ID_CATALOGOS","ASC");
        $this->db->where   ('C.CAMPO'       , $campo);
        $query = $this->db->get('CATALOGOS C');
	    $options = array();
        if($initValue) { $options[0] = '::Seleccione::'; }
          
        foreach ($query->result() as $row)							 
            { $options[$row->ID_CATALOGOS] = $row->DESCRIPCION; }
        
        $this->db->trans_complete();
        return $options;  
    }

    public function traePagDeptosFiltros($param)
    {   $this->db->trans_start();
        $resp  = array();
        
        $off  = (($param['pagina']-1) * $param['registrosPagina']);
        
    	$this -> db -> select('count(D.ID_DEPTO) as conteo');
        
        if (!empty($param['f1']))
            { $this -> db -> or_like(array('i.num_file' => $param['f1'] ) ); }
        if (!empty($param['f2']))
            { $this -> db -> or_like(array('i.master' => $param['f2'])); }
        if (!empty($param['f3']))
            { $this -> db -> or_like(array('i.shipper' => $param['f3'])); }

        $this->db->where('D.TORRE'      , $param['torre']);
        $this->db->where('D.ID_EDIFICIO', $param['id_edificio']);
        $query = $this->db->get('DEPARTAMENTOS D')->result();
	    $conteo = $query[0]->conteo;
        
        $this->db->select(' D.ID_DEPTO, D.TORRE, D.NUMERACION, D.STATUS, D.CUMPLIMIENTO, D.ID_EDIFICIO');
        //$this -> db -> select("DATE_FORMAT(`i`.`fecha_exp`,'%d %b %Y') as fecha_exp, CONCAT_WS(' ',`u`.`nombre`,`u`.`apellidos`) as creadoPor  ", FALSE);        
        
        if (!empty($param['f1']))
            { $this -> db -> or_like(array('i.num_file' => $param['f1'] ) ); }
        if (!empty($param['f2']))
            { $this -> db -> or_like(array('i.master' => $param['f2'])); }
        if (!empty($param['f3']))
            { $this -> db -> or_like(array('i.shipper' => $param['f3'])); }                            
        
        $this->db->order_by("D.NUMERACION" ,"asc");
        $this->db->where   ('D.TORRE'      , $param['torre']);
        $this->db->where   ('D.ID_EDIFICIO', $param['id_edificio']);
        $queryReg = $this->db->get('DEPARTAMENTOS D',$param['registrosPagina'],$off);			
        $resp     = ( empty($queryReg->result()) ? FALSE : array("conteo"=>$conteo, "registros"=>$this->usuariosPorDepto($queryReg->result_array()), "offset"=>$off));

        $this->db->trans_complete();
        return $resp; 
    }
    
    private function usuariosPorDepto($deptos)
    {   $this->db->trans_start();
        $result = array();

        foreach ($deptos as $t) 
           {  
            $this->db->select('U.NOMBRE,U.APELLIDOS, C.DESCRIPCION AS TIPO');            
            $this->db->from  ('USUARIOS U');
            $this->db->join  ('DETALLE_DEPTO DE', 'U.CUENTA = DE.CUENTA','inner outer');
            $this->db->join  ('CATALOGOS C'     , 'DE.CONTACTO = C.ID_CATALOGOS  ','inner outer');
            $this->db->where ('DE.ID_EDIFICIO'  , $t['ID_EDIFICIO']);
            $this->db->where ('DE.TORRE'        , $t['TORRE']);
            $this->db->where ('DE.ID_DEPTO'     , $t['ID_DEPTO']);
            $querydDET = $this->db->get();
            if( empty($querydDET->result()) )  
            { $contactos = "<ul id='cl".$t['ID_EDIFICIO'].$t['TORRE'].$t['ID_DEPTO']."' class='contactoLis'></ul>";     }
            else
            { $contactos = "<ul id='cl".$t['ID_EDIFICIO'].$t['TORRE'].$t['ID_DEPTO']."' class='contactoList'>";
              foreach ($querydDET->result_array() as $d) 
                { $contactos .= "<li class='fa fa-user'>".$d['TIPO']." - ".$d['NOMBRE']." ".$d['APELLIDOS']."</li>";}
              $contactos   .= "</ul>";
            }                       

            $result[] = array('ID_DEPTO'=>$t['ID_DEPTO'],'TORRE'=>$t['TORRE'],'NUMERACION'=>$t['NUMERACION'],'STATUS'=>$t['STATUS'],'CUMPLIMIENTO'=>$t['CUMPLIMIENTO'],'ID_EDIFICIO'=>$t['ID_EDIFICIO'],"CONTACTOS"=>$contactos);

           }

        $this->db->trans_complete();
        return $result;
    }

    public function traePagDeptosFinFiltros($param)
    {   $this->db->trans_start();
        $resp = array();
        $off  = (($param['pagina']-1) * $param['registrosPagina']);
        
    	$this->db->select('count(D.ID_DEPTO) as conteo');
        
        if (!empty($param['f1']))
            { $this -> db -> or_like(array('i.num_file' => $param['f1'] ) ); }
        if (!empty($param['f2']))
            { $this -> db -> or_like(array('i.master' => $param['f2'])); }
        if (!empty($param['f3']))
            { $this -> db -> or_like(array('i.shipper' => $param['f3'])); }

        $this->db->where('D.TORRE'      , $param['torre']);
        $this->db->where('D.ID_EDIFICIO', $param['id_edificio']);
    
        $query  = $this->db->get('DEPARTAMENTOS D')->result();
	    $conteo = $query[0]->conteo;

        $this -> db -> select(' D.ID_DEPTO, D.TORRE, D.NUMERACION, D.STATUS, D.CUMPLIMIENTO, D.ID_EDIFICIO');

        if (!empty($param['f1']))
            { $this -> db -> or_like(array('i.num_file' => $param['f1'] ) ); }
        if (!empty($param['f2']))
            { $this -> db -> or_like(array('i.master' => $param['f2'])); }
        if (!empty($param['f3']))
            { $this -> db -> or_like(array('i.shipper' => $param['f3'])); }                            
        
        $this->db->order_by("D.NUMERACION" ,"asc");
        $this->db->where   ('D.TORRE'      , $param['torre']);
        $this->db->where   ('D.ID_EDIFICIO', $param['id_edificio']);
        $queryReg = $this->db->get('DEPARTAMENTOS D',$param['registrosPagina'],$off);			

        $util     = new Utils();        
        if( empty($queryReg->result()) ) {  $resp  = FALSE;  } 
        else{
            foreach ($queryReg->result_array() as $t) 
            {
                $cuotasPen = $this->MDL_cuotas->traeCuotasPendientes($t['ID_EDIFICIO'], FALSE, $t['TORRE'], $t['ID_DEPTO']);
                $status    = $util->statusDepto($t, $cuotasPen, date("d") );
            
                $result[] = array('ID_DEPTO'=>$t['ID_DEPTO'],'TORRE'=>$t['TORRE'],'NUMERACION'=>$t['NUMERACION'],'STATUS'=>$status,'CUMPLIMIENTO'=>$t['CUMPLIMIENTO'],'ID_EDIFICIO'=>$t['ID_EDIFICIO']);           
            }
            unset($util); 
            $resp  = array("conteo"=>$conteo, "registros"=>$result, "offset"=>$off);
           }

        $this->db->trans_complete();
        return $resp;    
    }    
    
    public function traeDeptoAX($param)
    {   $this->db->trans_start();
        $resp  = array();

        $this->db->select('D.NUMERACION');        
        $this->db->where ('D.TORRE'      , $param['torre']);
        $this->db->where ('D.ID_EDIFICIO', $param['id_edificio']);
        $this->db->where ('D.ID_DEPTO'   , $param['id_depto']);
        $queryReg = $this->db->get('DEPARTAMENTOS D');
        $resp     = ( empty($query->result()) ? array() : $query->result_array());

        $this->db->trans_complete();
        return $resp;               
    }

    public function traeCondominosAX($edificio)
    {   
     try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();

        $query = $this->db->query( "SELECT DD.CUENTA, CONCAT('DEPTO ', COALESCE(DEP.TORRE,''), ' ', COALESCE(DEP.NUMERACION,'')) AS DEPTO, CONCAT('', COALESCE(U.NOMBRE,''), ' ', COALESCE(U.APELLIDOS,'')) AS CONDOMINO
                                    FROM ".$convSql->getPREFIX_DB_CVR()."DEPARTAMENTOS DEP 
                                    INNER JOIN ".$convSql->getPREFIX_DB_CVR()."DETALLE_DEPTO DD ON DEP.ID_DEPTO  = DD.ID_DEPTO AND DEP.TORRE = DD.TORRE 
                                    INNER JOIN ".$convSql->getPREFIX_DB_CVR()."USUARIOS U ON DD.CUENTA  = U.CUENTA 
                                    WHERE DD.ID_EDIFICIO = ".$edificio."
                                    GROUP BY DD.CUENTA, CONCAT('DEPTO ', COALESCE(DEP.TORRE,''), ' ', COALESCE(DEP.NUMERACION,'')), CONCAT('', COALESCE(U.NOMBRE,''), ' ', COALESCE(U.APELLIDOS,'')) 
                                   ");
        $resp  = ( empty($query->result()) ? array() : $query->result_array());

         $this->db->trans_complete();
         return $resp;

        }catch (Exception $e) { log_message('error', "Excepción traeCondominosAX: $e"); }
    }
    

    public function traeInventarioFiltros($param)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();
        
        $off  = (($param['pagina']-1) * $param['registrosPagina']);
        /****** CONTEO INI*************/
    	$this -> db -> select('count(ID_INVENTARIO) as conteo');
        if (!empty($param['f1']) & !empty($param['f2']))        
           { $this->db->where($convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_ALTA".$convSql->getEND_FIELD_BD().",".$convSql->sqlDateFormatyyyy__MM__dd().") BETWEEN '".$param['f1']."' AND '".$param['f2']."'",NULL, FALSE ); }
        if (!empty($param['f3']))
            { $this -> db -> or_like(array('i.shipper' => $param['f3'])); }
 
        if (is_array($param['id_edificio']))
        { $util = new Utils();
          $edis = $util->edificiosString($param['id_edificio']);
          $this->db->where("ID_EDIFICIO IN $edis",NULL, FALSE ); 
          unset($util);
        }
        else
        { $this->db->where('ID_EDIFICIO', $param['id_edificio']);    }
        
        $query  = $this->db->get('INVENTARIO_ACTIVOS')->result();
	    $conteo = $query[0]->conteo;
        /****** CONTEO FIN*************/        

        /****** REGISTROS INI*************/
        $this->db->select('ID_INVENTARIO, ACTIVO, DESCRIPCION, CANTIDAD, FOTO, ID_EDIFICIO, STATUS');
        $this->db->select($convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_ALTA".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatdd_MMM_yyyy().") as FECHA_ALTA", FALSE);

        if (!empty($param['f1']) & !empty($param['f2']))
           { $this->db->where($convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_ALTA".$convSql->getEND_FIELD_BD().",".$convSql->sqlDateFormatyyyy__MM__dd().") BETWEEN '".$param['f1']."' AND '".$param['f2']."'",NULL, FALSE ); }           
        if (!empty($param['f3']))
            { $this -> db -> or_like(array('i.shipper' => $param['f3'])); }
            
        if (is_array($param['id_edificio']))
        { $util = new Utils();
          $edis = $util->edificiosString($param['id_edificio']);
          $this->db->where($convSql->getINT_FIELD_BD()."ID_EDIFICIO".$convSql->getEND_FIELD_BD()." IN $edis",NULL, FALSE ); 
          unset($util);
        }
        else
        { $this->db->where('ID_EDIFICIO', $param['id_edificio']);    }
        
        $this->db->order_by("ID_INVENTARIO","DESC"); 
        $queryReg = $this->db->get('INVENTARIO_ACTIVOS', $param['registrosPagina'],$off);			
        /****** REGISTROS FIN*************/
        
        $resp  = ( empty($queryReg->result()) ? FALSE : array("conteo"=>$conteo, "registros"=>$queryReg->result_array(), "offset"=>$off));   

        $this->db->trans_complete();
        return $resp;        

    }catch (Exception $e) { log_message('error', "Excepción traeInventarioFiltros:".$e); }   

    }//traeInventarioFiltros


    public function traeInventariobyID($edificio, $inv)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();
  
        $this->db->select('ID_INVENTARIO, ACTIVO, DESCRIPCION, CANTIDAD, FOTO, STATUS, ID_EDIFICIO');
        $this->db->select($convSql->sqlDateFunction()."(FECHA_ALTA,".$convSql->sqlDateFormatdd_MM_yyyy().") as FECHA_ALTA", FALSE);
        $this->db->select("(NULL) AS NUEVO", FALSE);
        $this->db->where ('ID_EDIFICIO',  $edificio);
        $this->db->where ('ID_INVENTARIO',  $inv); 
        $query = $this->db->get('INVENTARIO_ACTIVOS');
        
        $resp  = ( empty($query->result()) ? array("ID_EDIFICIO"=>$edificio,"ID_INVENTARIO"=>intval(substr(now(), -7)), "ACTIVO"=>NULL, "DESCRIPCION"=>NULL, "CANTIDAD"=>NULL, "STATUS"=>1, "FOTO"=>NULL, "NUEVO"=>"SI" ) : $query->result_array()[0]);

        $this->db->trans_complete();
        return $resp;

      }catch (Exception $e) { log_message('error', "Excepción traeInventariobyID:".$e); }      
        
    }//traeTbjoMtobyID

    public function traeInventario($edificio)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();
  
        $this->db->select('ID_INVENTARIO, ACTIVO, DESCRIPCION, CANTIDAD, FOTO, STATUS, ID_EDIFICIO');
        $this->db->select($convSql->sqlDateFunction()."(FECHA_ALTA,".$convSql->sqlDateFormatdd_MMM_yyyy_hh_mm().") as FECHA_ALTA", FALSE);        
        $this->db->where ('ID_EDIFICIO',  $edificio);        
        $query = $this->db->get('INVENTARIO_ACTIVOS');
        
        $resp  = ( empty($query->result()) ? array() : $query->result_array());

        $this->db->trans_complete();
        return $resp;

      }catch (Exception $e) { log_message('error', "Excepción traeInventario:".$e); }      
        
    }//traeTbjoMtobyID

}//MDL_edificio
