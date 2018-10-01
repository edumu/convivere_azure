<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Modelo para interactuar en la BD
 * 
 * SELECT CUENTA, NOMBRE, APELLIDOS, CONTRASENA, NIVEL_ACCESO, TELEFONO_FIJO, CELULAR, CORREO_VERIFICADO, CELULAR_VERIFICADO, ACTIVO FROM USUARIOS WHERE 1
 */
 
class MDL_finanzas extends CI_Model {		    

    function __construct()
    {
        $this->load->database();
    }

    function __destruct() 
    {
        $this->db->close();
    }

public function poblarRadioButtonTiposPago($campo)
    {   $this->db->trans_start();
        $resp = array();

        $this->db->select  ('C.ID_CATALOGOS, C.DESCRIPCION');
        $this->db->order_by("C.ID_CATALOGOS","ASC");
        $this->db->where   ('C.CAMPO',  $campo);
        $query = $this->db->get('CATALOGOS C');
        $options = array();
        
        if( empty($query->result()) )
        { $resp = $options;  }
        else
        {  
            foreach ($query->result() as $row)							 
                { $options[] =  array('value'=> $row->ID_CATALOGOS,'label'=>$row->DESCRIPCION);}
            $resp = $options;                    
        }

        $this->db->trans_complete();
        return $resp;
    }    
    
public function traeCuotas($depto, $hoy)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();

        $this->db->select  ($convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."E".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."FECHA_ALTA".$convSql->getEND_FIELD_BD().",".$convSql->sqlDateFormatYYYY_MM().") as CUOTAS", FALSE);        
        $this->db->from    ('EDIFICIOS E');        
        $this->db->where   ('E.ID_EDIFICIO', $depto[0]['ID_EDIFICIO']);
        $this->db->where   ($convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."E".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."FECHA_ALTAS".$convSql->getEND_FIELD_BD().",".$convSql->sqlDateFormatYYYY_MM().") BETWEEN '".$depto[0]['FECHA_ALTA']."' AND '$hoy'",NULL, FALSE ); 
        $this->db->order_by("E.FECHA_ALTA","DESC");

        $query = $this->db->get();
        $resp  = ( empty($query->result()) ? array() : $query->result_array());

        $this->db->trans_complete();
        return $resp;        
        
       }catch (Exception $e) { log_message('error', "Excepción traeCuotas: $e"); }      
    }  
    
   
    
public function traeUltimaCuota($depto,$añoMes)
    {$this->db->trans_start();
     $resp  = array();
    					
     $this->db->select  ('C.ID_CUOTA, C.NOMBRE, C.FECHA_PAGO, C.IMPORTE');
     $this->db->from    ('CUOTAS C');        
     $this->db->where   ('C.ID_EDIFICIO', $depto[0]['ID_EDIFICIO']);
     $this->db->where   ('C.ID_DEPTO'   , $depto[0]['ID_DEPTO']);
     $this->db->where   ('C.TORRE'      , $depto[0]['TORRE']);
     $this->db->order_by("C.FECHA_PAGO" ,"DESC");

     $query = $this->db->get();
     $resp  = ( empty($query->result()) ? array() : $query->result_array()[0] );   

     $this->db->trans_complete();
     return $resp;     
    }

public function chartResumenEjecutivo($id_edificio, $mesesLLaves)
    {try{
        $this->db->trans_start();

        $edificio = $this->MDL_edificio->traeEdificio($id_edificio);    
        foreach($mesesLLaves as $m)
        {
            $totales = $this->MDL_gastos->traeTotMesEdoCta($id_edificio, $m['ano'], $m['mes'], $edificio[0]['CUOTA_MANTO'] );
            $gastos  = ($totales['gastos']===NULL & $totales['tm']===NULL)?NULL:$totales['gastos'] + $totales['tm'];
            $balance = ($totales['cuotas']===NULL | $gastos===NULL)?NULL:$totales['cuotas'] - $gastos;
            
            $dataCuotas[]  = $totales['cuotas']===NULL?NULL:floatval($totales['cuotas']);
            $dataGastos[]  = $gastos;
            $dataBalance[] = $balance;
        }        
        $series[] = array("name" => 'Cuotas' , "type" => 'column', "data" => $dataCuotas  ,"yAxis"=> 0);
        $series[] = array("name" => 'Gastos' , "type" => 'column', "data" => $dataGastos  ,"yAxis"=> 1);
        $series[] = array("name" => 'Balance', "type" => 'spline', "data"  => $dataBalance,"yAxis"=> 2);        
        
        $this->db->trans_complete();
        return $series;

    }catch (Exception $e) { log_message('error', 'Excepción chartResumenEjecutivo:'+$e); }
            
    }//chartResumenEjecutivo
    
public function chartGaugeCuotas($id_edificio, $ano, $mes )
    {try{
        $this->db->trans_start();
        $resp  = array();

        $edificio  = $this->MDL_edificio->traeEdificio($id_edificio);    
        $maxC      = $edificio[0]['CUOTA_MANTO'] * ($edificio[0]['NUM_TORRES'] * $edificio[0]['NUM_VIVIENDAS'] );
        
        $totales   = $this->MDL_gastos->traeTotMesEdoCta($id_edificio, $ano, $mes, $edificio[0]['CUOTA_MANTO'] );
        $totCuotas = $totales['cuotas']===NULL?0:floatval($totales['cuotas']);            
        $resp      = array("current"=>$totCuotas, "max"=>floatval($maxC));

        $this->db->trans_complete();
        return $resp;
    }catch (Exception $e) {echo 'Excepción chartGaugeCuotas:',  $e;} 
            
    }//chartGaugeCuotas
    
public function chartGanttTM($id_edificio, $ano, $util)
    {
     try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();

        $qTM = $this->db->query("SELECT ".$convSql->getINT_FIELD_BD()."TRABAJO".$convSql->getEND_FIELD_BD().", ".$convSql->getINT_FIELD_BD()."STATUS".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_INICIO".$convSql->getEND_FIELD_BD().",".$convSql->sqlDateFormatdd_MM_yyyy().") AS INI, ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_COMPROMISO".$convSql->getEND_FIELD_BD().",".$convSql->sqlDateFormatdd_MM_yyyy().") AS FIN  FROM ".$convSql->getPREFIX_DB_CVR().$convSql->getINT_FIELD_BD()."TRABAJOS_MANTO".$convSql->getEND_FIELD_BD()." WHERE ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_INICIO".$convSql->getEND_FIELD_BD()." ,".$convSql->sqlDateFormatYYYY().") = ".$ano." AND ".$convSql->getINT_FIELD_BD()."ID_EDIFICIO".$convSql->getEND_FIELD_BD()." = ".$id_edificio." AND ".$convSql->getINT_FIELD_BD()."STATUS".$convSql->getEND_FIELD_BD()." IN (9,10,12) ", FALSE);               
        $y   = -1;
        foreach ($qTM->result_array() as $tm)
        { $y++;
          $categories[] = array($tm['TRABAJO']);
          $partialFill  = $tm['STATUS']=="10"?1:0;
          $trabajos[]   = array("x"=>$util->dateUCT_javaScript($tm['INI']), "x2"=>$util->dateUCT_javaScript($tm['FIN']), "y"=>$y,"partialFill"=>$partialFill);
        }
        $resp = array("categories"=>$categories, "data"=>$trabajos);

        $this->db->trans_complete();
        return $resp;        
        
    }catch (Exception $e) {echo 'Excepción chartGanttTM:',  $e;} 
            
    }//chartGanttTM     
    
}//MDL_finanzas
