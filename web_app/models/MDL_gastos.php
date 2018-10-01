<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Modelo para interactuar en la BD
 * SELECT ID_GASTO,CONCEPTO,MONTO,IVA,TOTAL,COMPROBANTE,FACTURA,FECHA_GASTO,GASTO_FIJO,FECHA_ALTA,ID_EDIFICIO 
 * FROM GASTOS
 */
 
/**
 * SELECT ID_TRABAJOS, TRABAJO, DESCRIPCION, COSTO, TIPO_ANT, ANTICIPO, OBSERVACIONES, EVIDENCIA_ANTES, EVIDENCIA_DESPUES, FECHA_INICIO, DURACION, FECHA_COMPROMISO, FECHA_ALTA, ID_EDIFICIO
 * FROM TRABAJOS_MANTO
 */
class MDL_gastos extends CI_Model {

    function __construct()
    {
        $this->load->database();
    }

    function __destruct() 
    {
        $this->db->close();
    }

    public function update_gasto($param)
    {	$this->db->trans_start();
        $this->db->where ('TORRE'        ,  $param['torre']                    );
        $this->db->where ('ID_DEPTO'     ,  $param['id_depto']                 );
        $this->db->where ('ID_EDIFICIO'  ,  $param['id_edificio']              );
        $qry = $this->db->update('GASTOS',  array("NUMERACION"=>$param['num']) );          
        $this->db->trans_complete();

        return $qry;
    }
    
    public function insert_gasto($data)
    {	$this->db->trans_start();
        $qry = $this->db->insert('GASTOS',$data);        
        $this->db->trans_complete();

        return $qry;
    } 

    public function insert_gasto_ID($data)
    {	$this->db->trans_start();
        $this->db->insert('GASTOS',$data);
        $qry = $this->gastoInsertadoId($data);
        $this->db->trans_complete();

        return $qry;
    } 

    private function gastoInsertadoId($data)
    {
    $this->db->select('ID_GASTO');    
    $this->db->where ('CONCEPTO'   ,  $data['CONCEPTO']   );
    $this->db->where ('MONTO'      ,  $data['MONTO']      );
    $this->db->where ('IVA'        ,  $data['IVA']        );
    $this->db->where ('TOTAL'      ,  $data['TOTAL']      );
    $this->db->where ('FACTURA'    ,  $data['FACTURA']    );
    $this->db->where ('COMPROBANTE',  $data['COMPROBANTE']);
    $this->db->where ('FECHA_GASTO',  $data['FECHA_GASTO']);
    $this->db->where ('GASTO_FIJO' ,  $data['GASTO_FIJO'] );
    $this->db->where ('FECHA_ALTA' ,  $data['FECHA_ALTA'] );    
    $this->db->where ('ID_EDIFICIO',  $data['ID_EDIFICIO']);
    $query = $this->db->get('GASTOS');
    $resp  = ( empty($query->result()) ?  NULL : $query->result_array()[0]['ID_GASTO']);

    return $resp;
    }


    public function insert_gasto_fijo($data)
    {   $this->db->trans_start();
        $this->db->delete('GASTOS_FIJOS',array('ID_GASTOS' => $data['ID_GASTOS']));
        $qry = $this->db->insert('GASTOS_FIJOS',$data);    
        $this->db->trans_complete();

        return $qry;
    }   

    public function delete_gasto($gasto)
    {
        $this->db->trans_start();
        $this->db->where('ID_GASTO',  $gasto);
        $qry = $this->db->update('GASTOS', array('ACTIVO' => 0));
        $this->db->trans_complete();

        return $qry;	   
    }
    
    public function delete_gasto_creado($gasto)
    {   $this->db->trans_start();
        $qry = $this->db->delete('GASTOS', array('parentChild' => $gasto));
        $this->db->trans_complete();

        return $qry;
    }
       
    public function update_gastoEvidencia($param,$datos)
    {	$this->db->trans_start();        
        $this->db->where ('ID_GASTO'     ,  $param['ID_GASTO']    );
        $this->db->where ('ID_EDIFICIO'  ,  $param['ID_EDIFICIO'] );
        $qry = $this->db->update('GASTOS',  $datos                );
        $this->db->trans_complete();

        return $qry;                   
    }
    
    public function update_tbjo_manto($param,$datos)
    {	$this->db->trans_start();        
        $this->db->where ('ID_TRABAJOS'          , $param['ID_TRABAJOS'] );
        $this->db->where ('ID_EDIFICIO'          , $param['ID_EDIFICIO'] );        
        $qry = $this->db->update('TRABAJOS_MANTO', $datos                );
        $this->db->trans_complete();

        return $qry;
    }
    
    public function insert_tbjo_manto($data)
    {   $this->db->trans_start();
        $qry = $this->db->insert('TRABAJOS_MANTO',$data);
        $this->db->trans_complete();

        return $qry;        
    }
    
    public function update_presupuesto($param,$datos)
    {	$this->db->trans_start();        
        $this->db->where ('PRESUPUESTO_ID'    , $param['ID_TRABAJOS'] );
        $this->db->where ('ID_EDIFICIO'       , $param['ID_EDIFICIO'] );              
        $qry = $this->db->update('PRESUPUESTO', $datos                );
        $this->db->trans_complete();

        return $qry;
    }
    

    public function updateDesactivarPtos($datos)
    {   $this->db->trans_start();
        $qry = $this->db->update('PRESUPUESTO' , $datos);
        $this->db->trans_complete();

        return $qry;   
    }
    

    public function insert_presupuesto($tabla,$data)
    {   $this->db->trans_start();
        $qry = $this->db->insert($tabla,$data);
        $this->db->trans_complete();

        return $qry;
    }
    

    public function delete_presupuesto($tabla,$data)
    {   $this->db->trans_start();
        $qry = $this->db->delete($tabla, $data);      
        $this->db->trans_complete();

        return $qry;       
    }
    

    public function traePresupuestobyID($pto_id)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();

        $this->db->select('PRESUPUESTO_ID, NOMBRE_PTO, STATUS');
        $this->db->select($convSql->sqlDateFunction()."(INICIO_PTO,".$convSql->sqlDateFormatMM_dd_yyyy().") as INICIO_PTO, ".$convSql->sqlDateFunction()."(FIN_PTO,".$convSql->sqlDateFormatMM_dd_yyyy().") as FIN_PTO", FALSE);
        $this->db->where ('PRESUPUESTO_ID', $pto_id);        
        $query = $this->db->get('PRESUPUESTO');        

        if( empty($query->result()) )
             {  $resp = array("pto"=>array(), "ptoDet"=>array()); }
        else
             { $pto = $query->result_array()[0];

               $this->db->select  ("MES_PTO, ".$convSql->sqlDateFunction()."(MES_PTO,".$convSql->sqlDateFormatMM().") as CON_PTO_MN, ".$convSql->sqlDateFunction()."(MES_PTO,".$convSql->sqlDateFormatYYYY().") as CON_PTO_YR", FALSE);
               $this->db->where   ('PRESUPUESTO_ID', $pto_id);
               $this->db->group_by('MES_PTO');
               $query = $this->db->get('PRESUPUESTO_DETALLE');
               foreach ($query->result() as $row)							 
               { 
                $this->db->select('CONCEPTO, IMPORTE');
                $this->db->select($convSql->sqlDateFunction()."(MES_PTO,".$convSql->sqlDateFormatMM_dd_yyyy().") as MES_PTO, ".$convSql->sqlDateFunction()."(MES_PTO,".$convSql->sqlDateFormatMM().") as CON_PTO_MN, ".$convSql->sqlDateFunction()."(MES_PTO,".$convSql->sqlDateFormatYYYY().") as CON_PTO_YR", FALSE);
                $this->db->where ('PRESUPUESTO_ID', $pto_id);
                $this->db->where ('MES_PTO'       , $row->MES_PTO);
                $queryPD  = $this->db->get('PRESUPUESTO_DETALLE');                
                $ptoDet[] = array("mes"=>$row->MES_PTO, "CON_PTO_MN"=>$row->CON_PTO_MN, "CON_PTO_YR"=>$row->CON_PTO_YR, "detalle"=>$queryPD->result_array());
               }
               
               $resp = array("pto"=>$pto, "ptoDet"=>$ptoDet);
             }
        $this->db->trans_complete();
        return $resp;

        }catch (Exception $e) { log_message('error', "Excepción traePresupuestobyID: $e"); }         
    }
    
    public function reservaIdGasto($idUnico, $id_edificio, $tiene_iva, $nombreCVR, $fecha_alta)
    {	$this->db->trans_start();
        $resp  = array();

        $tipoCompro = ( $tiene_iva === TRUE?"FACTURA":"COMPROBANTE");
        $data       = array("CONCEPTO"=>$idUnico,$tipoCompro=>$nombreCVR,"ID_EDIFICIO"=>$id_edificio,"MONTO"=>0,"IVA"=>0,"FECHA_GASTO"=>"","GASTO_FIJO"=>0,"FECHA_ALTA"=>$fecha_alta);
        
        $this->db->insert('GASTOS',$data);
         
        $this->db->select('ID_GASTO');        
        $this->db->where ('CONCEPTO'   ,  $idUnico);
        $this->db->where ('ID_EDIFICIO',  $id_edificio);

        $query = $this->db->get('GASTOS');
        $resp  = ( empty($query->result()) ?  NULL : $query->result_array()[0]['ID_GASTO']);

        $this->db->trans_complete();
        return $resp;
    }
                
            
    public function traeGastos($param)
    {   $this->db->trans_start();
        $resp  = array();
 					
        $this->db->select('*');
        $this->db->where('TORRE'      ,  $param['torre']);
        $this->db->where('ID_DEPTO'   ,  $param['id_depto']);
        $this->db->where('ID_EDIFICIO',  $param['id_edificio']);

        $query = $this->db->get('GASTOS');        
        $resp  = (empty($query->result()) ?  NULL : $query->result_array());        

        $this->db->trans_complete();
        return $resp;
    }
    
    public function traeEdoCta($id_edificio, $ano, $mes )
    { 
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();

        $lan    = ($convSql->PREFIX_DB_CVR === "")?"SET lc_time_names = 'es_MX';":"SET LANGUAGE Spanish; ";
        $qryLan = $this->db->query( $lan);
        $qry = $this->db->query( "SELECT ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_PAGO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatddMyyyy().") AS ".$convSql->getINT_FIELD_BD()."DIAS".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_PAGO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatdddd_dd_MMM_yyyy()." ) AS FECHA            FROM ".$convSql->getPREFIX_DB_CVR().$convSql->getINT_FIELD_BD()."CUOTAS".$convSql->getEND_FIELD_BD()."          WHERE ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_PAGO".$convSql->getEND_FIELD_BD()."      ,".$convSql->sqlDateFormatMyyyy().") = ".$mes.$ano." AND ".$convSql->getINT_FIELD_BD()."ID_EDIFICIO".$convSql->getEND_FIELD_BD()." = ".$id_edificio." AND ".$convSql->getINT_FIELD_BD()."STATUS".$convSql->getEND_FIELD_BD()." = '".STATUS_PAG."' GROUP BY ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_PAGO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatddMyyyy()."), ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_PAGO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatdddd_dd_MMM_yyyy().")
                                    UNION
                                    SELECT ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_GASTO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatddMyyyy().") AS ".$convSql->getINT_FIELD_BD()."DIAS".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_GASTO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatdddd_dd_MMM_yyyy()." ) AS FECHA          FROM ".$convSql->getPREFIX_DB_CVR().$convSql->getINT_FIELD_BD()."GASTOS".$convSql->getEND_FIELD_BD()."          WHERE ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_GASTO".$convSql->getEND_FIELD_BD()."     ,".$convSql->sqlDateFormatMyyyy().") = ".$mes.$ano." AND ".$convSql->getINT_FIELD_BD()."ID_EDIFICIO".$convSql->getEND_FIELD_BD()." = ".$id_edificio." AND ".$convSql->getINT_FIELD_BD()."STATUS".$convSql->getEND_FIELD_BD()." = 1                GROUP BY ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_GASTO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatddMyyyy()."), ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_GASTO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatdddd_dd_MMM_yyyy().")
                                    UNION
                                    SELECT ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_INICIO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatddMyyyy().") AS ".$convSql->getINT_FIELD_BD()."DIAS".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_INICIO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatdddd_dd_MMM_yyyy()." ) AS FECHA        FROM ".$convSql->getPREFIX_DB_CVR().$convSql->getINT_FIELD_BD()."TRABAJOS_MANTO".$convSql->getEND_FIELD_BD()."  WHERE ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_INICIO".$convSql->getEND_FIELD_BD()."    ,".$convSql->sqlDateFormatMyyyy().") = ".$mes.$ano." AND ".$convSql->getINT_FIELD_BD()."ID_EDIFICIO".$convSql->getEND_FIELD_BD()." = ".$id_edificio." AND ".$convSql->getINT_FIELD_BD()."STATUS".$convSql->getEND_FIELD_BD()." IN (9,10,12)       GROUP BY ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_INICIO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatddMyyyy()."), ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_INICIO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatdddd_dd_MMM_yyyy()." )
                                    UNION
                                    SELECT ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_COMPROMISO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatddMyyyy().") AS ".$convSql->getINT_FIELD_BD()."DIAS".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_COMPROMISO".$convSql->getEND_FIELD_BD().",".$convSql->sqlDateFormatdddd_dd_MMM_yyyy().") AS FECHA  FROM ".$convSql->getPREFIX_DB_CVR().$convSql->getINT_FIELD_BD()."TRABAJOS_MANTO".$convSql->getEND_FIELD_BD()."  WHERE ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_COMPROMISO".$convSql->getEND_FIELD_BD().",".$convSql->sqlDateFormatMyyyy().") = ".$mes.$ano." AND ".$convSql->getINT_FIELD_BD()."ID_EDIFICIO".$convSql->getEND_FIELD_BD()." = ".$id_edificio." AND ".$convSql->getINT_FIELD_BD()."STATUS".$convSql->getEND_FIELD_BD()." IN (9,10,12)       GROUP BY ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_COMPROMISO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatddMyyyy()."), ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_COMPROMISO".$convSql->getEND_FIELD_BD().",".$convSql->sqlDateFormatdddd_dd_MMM_yyyy().")
                                    UNION
                                    SELECT ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."PD".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."MES_PTO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatddMyyyy().") AS ".$convSql->getINT_FIELD_BD()."DIAS".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."PD".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."MES_PTO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatdddd_dd_MMM_yyyy().") AS FECHA  FROM ".$convSql->getPREFIX_DB_CVR().$convSql->getINT_FIELD_BD()."PRESUPUESTO".$convSql->getEND_FIELD_BD()." ".$convSql->getINT_FIELD_BD()."P".$convSql->getEND_FIELD_BD()." INNER JOIN ".$convSql->getPREFIX_DB_CVR().$convSql->getINT_FIELD_BD()."PRESUPUESTO_DETALLE".$convSql->getEND_FIELD_BD()." ".$convSql->getINT_FIELD_BD()."PD".$convSql->getEND_FIELD_BD()." ON ".$convSql->getINT_FIELD_BD()."P".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."PRESUPUESTO_ID".$convSql->getEND_FIELD_BD()." = ".$convSql->getINT_FIELD_BD()."PD".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."PRESUPUESTO_ID".$convSql->getEND_FIELD_BD()." WHERE ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."PD".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."MES_PTO".$convSql->getEND_FIELD_BD()." , ".$convSql->sqlDateFormatMyyyy().") = ".$mes.$ano." AND ".$convSql->getINT_FIELD_BD()."P".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."ID_EDIFICIO".$convSql->getEND_FIELD_BD()." = ".$id_edificio." AND ".$convSql->getINT_FIELD_BD()."P".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."STATUS".$convSql->getEND_FIELD_BD()." =".PTO_ACTIVO." GROUP BY ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."PD".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."MES_PTO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatddMyyyy()."), ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."PD".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."MES_PTO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatdddd_dd_MMM_yyyy().")
                                    ORDER BY 1",FALSE);       
        $dias      = $qry->result_array();
        $cuotas    = array();
        $gastos    = array();
        $trabajosA = array();
        $trabajosD = array();
        $ptos      = array();
        $edoCta    = array();
        $isPto     = FALSE;
       
       foreach ($dias as $d) { 
           $this->db->select("C.IMPORTE AS IMPORTE, CONCAT('DEPTO ', COALESCE(DTO.TORRE,''), ' ', COALESCE(DTO.NUMERACION,''), ' ', C.CUOTA_DEL_MES) AS CONCEPTO", FALSE);
           $this->db->join  ($convSql->getPREFIX_DB_CVR()."DEPARTAMENTOS DTO", "C.ID_DEPTO = DTO.ID_DEPTO",'inner outer', FALSE); 
           $this->db->where ($convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."C".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."FECHA_PAGO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatddMyyyy().") = ".$d['DIAS']." AND C.ID_EDIFICIO = ".$id_edificio." AND C.STATUS = '".STATUS_PAG."' and 0=", FALSE);
           $queryC = $this->db->get('CUOTAS C', FALSE);
           $cuotas = $queryC->result_array();

           $this->db->select("TOTAL AS IMPORTE, CONCEPTO AS CONCEPTO");           
           $this->db->where ($convSql->sqlDateFunction()."(FECHA_GASTO, ".$convSql->sqlDateFormatddMyyyy().") = ".$d['DIAS']." AND ID_EDIFICIO = ".$id_edificio." AND STATUS = 1  and 0=", FALSE);
           $queryG = $this->db->get('GASTOS');
           $gastos = $queryG->result_array();

           $convSql->agregaAnticipoImporteEdoCta($this->db);
           $this->db->where ($convSql->sqlDateFunction()."(FECHA_INICIO, ".$convSql->sqlDateFormatddMyyyy().") = ".$d['DIAS']." AND ID_EDIFICIO = ".$id_edificio." AND STATUS IN (9,10,12)  and 0=", FALSE);
           $queryTA   = $this->db->get('TRABAJOS_MANTO');
           $trabajosA = $queryTA->result_array();

           $convSql->agregaLiquidacionImporteEdoCta($this->db);
           $this->db->where ($convSql->sqlDateFunction()."(FECHA_COMPROMISO, ".$convSql->sqlDateFormatddMyyyy().") = ".$d['DIAS']." AND ID_EDIFICIO = ".$id_edificio." AND STATUS IN (9,10,12)  and 0=", FALSE);
           $queryTD   = $this->db->get('TRABAJOS_MANTO');
           $trabajosD = $queryTD->result_array();

           $this->db->select("PD.IMPORTE AS IMPORTE, PD.CONCEPTO AS CONCEPTO");
           $this->db->join  ($convSql->getPREFIX_DB_CVR()."PRESUPUESTO_DETALLE PD", "P.PRESUPUESTO_ID = PD.PRESUPUESTO_ID ",'inner outer', FALSE); 
           $this->db->where ($convSql->sqlDateFunction()."(PD.MES_PTO, ".$convSql->sqlDateFormatddMyyyy().") = ".$d['DIAS']." AND P.ID_EDIFICIO = ".$id_edificio." AND P.STATUS =".PTO_ACTIVO." and 0=", FALSE);
           $queryP = $this->db->get('PRESUPUESTO P');
           $ptos   = $queryP->result_array();
           if(sizeof($ptos) !==0 )
             { $isPto  = TRUE;   }

           if( sizeof($cuotas)!==0 | sizeof($gastos)!==0 | sizeof($trabajosA)!==0 | sizeof($trabajosD)!==0 | sizeof($ptos)!==0 )
             { $edoCta[] = array("fecha"=>$d['FECHA'],"cuotas"=>$cuotas,"gastos"=>$gastos,"trabajosA"=>$trabajosA,"trabajosD"=>$trabajosD,"ptos"=>$ptos); }
       }              
       $resp  =  array("edoCta"=>$edoCta, "isPto"=>$isPto );

       $this->db->trans_complete();
       return $resp;

    }catch (Exception $e) { log_message('error', "Excepción traeEdoCta: $e"); }

    }
    
    public function traeEdoCtaAcumulado($id_edificio, $ano, $mes )
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();

        $lan    = ($convSql->PREFIX_DB_CVR === "")?"SET lc_time_names = 'es_MX';":"SET LANGUAGE Spanish; ";
        $qryLan = $this->db->query( $lan);        
        $query  = $this->db->query( "SELECT ".$convSql->sqlDateFunction()."(FECHA_PAGO,".$convSql->sqlDateFormatMyyyy().")      AS MES_INT, ".$convSql->sqlDateFunction()."(FECHA_PAGO, ".$convSql->sqlDateFormatMMM().")       AS FECHA  FROM ".$convSql->getPREFIX_DB_CVR()."CUOTAS         WHERE (".$convSql->sqlDateFunction()."(FECHA_PAGO, ".$convSql->sqlDateFormatYYYY_MM_dd().") BETWEEN '".$ano."-01"."-01'       AND ".$convSql->sqlLastDayOfMonthFunction()."('".$ano."-".$mes."-01') )  AND ID_EDIFICIO = ".$id_edificio."  AND STATUS = '".STATUS_PAG."' GROUP BY ".$convSql->sqlDateFunction()."(FECHA_PAGO,".$convSql->sqlDateFormatMyyyy()."), ".$convSql->sqlDateFunction()."(FECHA_PAGO, ".$convSql->sqlDateFormatMMM().")
                                    UNION
                                    SELECT ".$convSql->sqlDateFunction()."(FECHA_GASTO,".$convSql->sqlDateFormatMyyyy().")      AS MES_INT, ".$convSql->sqlDateFunction()."(FECHA_GASTO, ".$convSql->sqlDateFormatMMM().")      AS FECHA  FROM ".$convSql->getPREFIX_DB_CVR()."GASTOS         WHERE (".$convSql->sqlDateFunction()."(FECHA_GASTO, ".$convSql->sqlDateFormatYYYY_MM_dd().") BETWEEN '".$ano."-01"."-01'      AND ".$convSql->sqlLastDayOfMonthFunction()."('".$ano."-".$mes."-01') )  AND ID_EDIFICIO  = ".$id_edificio." AND STATUS = 1                GROUP BY ".$convSql->sqlDateFunction()."(FECHA_GASTO,".$convSql->sqlDateFormatMyyyy()."), ".$convSql->sqlDateFunction()."(FECHA_GASTO, ".$convSql->sqlDateFormatMMM().") 
                                    UNION
                                    SELECT ".$convSql->sqlDateFunction()."(FECHA_INICIO,".$convSql->sqlDateFormatMyyyy().")     AS MES_INT, ".$convSql->sqlDateFunction()."(FECHA_INICIO, ".$convSql->sqlDateFormatMMM().")     AS FECHA  FROM ".$convSql->getPREFIX_DB_CVR()."TRABAJOS_MANTO WHERE (".$convSql->sqlDateFunction()."(FECHA_INICIO, ".$convSql->sqlDateFormatYYYY_MM_dd().") BETWEEN '".$ano."-01"."-01'     AND ".$convSql->sqlLastDayOfMonthFunction()."('".$ano."-".$mes."-01') )  AND ID_EDIFICIO = ".$id_edificio."  AND STATUS IN (9,10,12)       GROUP BY ".$convSql->sqlDateFunction()."(FECHA_INICIO,".$convSql->sqlDateFormatMyyyy()."), ".$convSql->sqlDateFunction()."(FECHA_INICIO, ".$convSql->sqlDateFormatMMM().")
                                    UNION
                                    SELECT ".$convSql->sqlDateFunction()."(FECHA_COMPROMISO,".$convSql->sqlDateFormatMyyyy().") AS MES_INT, ".$convSql->sqlDateFunction()."(FECHA_COMPROMISO,".$convSql->sqlDateFormatMMM().")  AS FECHA  FROM ".$convSql->getPREFIX_DB_CVR()."TRABAJOS_MANTO WHERE (".$convSql->sqlDateFunction()."(FECHA_COMPROMISO, ".$convSql->sqlDateFormatYYYY_MM_dd().") BETWEEN '".$ano."-01"."-01' AND ".$convSql->sqlLastDayOfMonthFunction()."('".$ano."-".$mes."-01') )  AND ID_EDIFICIO = ".$id_edificio."  AND STATUS IN (9,10,12)       GROUP BY ".$convSql->sqlDateFunction()."(FECHA_COMPROMISO,".$convSql->sqlDateFormatMyyyy()."), ".$convSql->sqlDateFunction()."(FECHA_COMPROMISO,".$convSql->sqlDateFormatMMM().")
                                    ORDER BY 1",FALSE);       
        $meses  = $query->result_array();       
        
        foreach ($meses as $m) { 
            $qCu = $this->db->query("SELECT SUM(IMPORTE) AS TOTAL, ".$convSql->sqlDateFunction()."(FECHA_PAGO, ".$convSql->sqlDateFormatMMM().") AS FECHA FROM ".$convSql->getPREFIX_DB_CVR()."CUOTAS  WHERE ".$convSql->sqlDateFunction()."(FECHA_PAGO , ".$convSql->sqlDateFormatMMYYYY().") = ".$m['MES_INT']." AND ID_EDIFICIO = ".$id_edificio." AND STATUS = '".STATUS_PAG."' GROUP BY ".$convSql->sqlDateFunction()."(FECHA_PAGO, ".$convSql->sqlDateFormatMMM().") ORDER BY 1",FALSE);
            $cuo = (!empty($qCu->result_array())?$qCu->result_array()[0]['TOTAL']:0);
        
            $qGa = $this->db->query("SELECT SUM(TOTAL) AS TOTAL, ".$convSql->sqlDateFunction()."(FECHA_GASTO, ".$convSql->sqlDateFormatMMM().") AS FECHA FROM ".$convSql->getPREFIX_DB_CVR()."GASTOS  WHERE ".$convSql->sqlDateFunction()."(FECHA_GASTO , ".$convSql->sqlDateFormatMMYYYY().") = ".$m['MES_INT']." AND ID_EDIFICIO = ".$id_edificio." AND STATUS = 1  GROUP BY ".$convSql->sqlDateFunction()."(FECHA_GASTO, ".$convSql->sqlDateFormatMMM().") ORDER BY 1",FALSE);        
            $gas = (!empty($qGa->result_array())?$qGa->result_array()[0]['TOTAL']:0);
        
            $calculoTotTD = ($convSql->PREFIX_DB_CVR === "") ? "SUM(IF( TIPO_ANT='1', ANTICIPO, (COSTO*(ANTICIPO/100)) )) AS TOTAL," :" SUM( CASE WHEN TIPO_ANT='1' THEN ANTICIPO ELSE (COSTO*(ANTICIPO/100)) END) AS TOTAL, ";
            $qTA = $this->db->query("SELECT  ".$calculoTotTD." ".$convSql->sqlDateFunction()."(FECHA_INICIO, ".$convSql->sqlDateFormatMMM().") AS FECHA FROM ".$convSql->getPREFIX_DB_CVR()."TRABAJOS_MANTO WHERE ".$convSql->sqlDateFunction()."(FECHA_INICIO , ".$convSql->sqlDateFormatMMYYYY().") = ".$m['MES_INT']." AND ID_EDIFICIO = ".$id_edificio." AND STATUS IN (9,10,12)  GROUP BY ".$convSql->sqlDateFunction()."(FECHA_INICIO, ".$convSql->sqlDateFormatMMM().") ORDER BY 1",FALSE);               
            $ta  = (!empty($qTA->result_array())?$qTA->result_array()[0]['TOTAL']:0);
        
            $calculoTotTD = ($convSql->PREFIX_DB_CVR === "") ? "SUM(IF( TIPO_ANT='1', (COSTO-ANTICIPO), COSTO-(COSTO*(ANTICIPO/100)) )) AS TOTAL," : " SUM( CASE WHEN TIPO_ANT='1' THEN (COSTO-ANTICIPO) ELSE COSTO-(COSTO*(ANTICIPO/100)) END ) AS TOTAL,";
            $qTD = $this->db->query("SELECT ".$calculoTotTD." ".$convSql->sqlDateFunction()."(FECHA_COMPROMISO, ".$convSql->sqlDateFormatMMM().") AS FECHA FROM ".$convSql->getPREFIX_DB_CVR()."TRABAJOS_MANTO  WHERE ".$convSql->sqlDateFunction()."(FECHA_COMPROMISO, ".$convSql->sqlDateFormatMMYYYY().") = ".$m['MES_INT']." AND ID_EDIFICIO = ".$id_edificio." AND STATUS IN (9,10,12)  GROUP BY ".$convSql->sqlDateFunction()."(FECHA_COMPROMISO, ".$convSql->sqlDateFormatMMM().") ORDER BY 1",FALSE);       
            $td  = (!empty($qTD->result_array())?$qTD->result_array()[0]['TOTAL']:0);        
            
            $leyendas[] = $m['FECHA'];
            $cuotasAc[] = $cuo;
            $gastosAc[] = $gas + $ta + $td;
        }
        $resp  =  array("leyendas"=>$leyendas, "cuotas"=>$cuotasAc, "gastos"=>$gastosAc);

        $this->db->trans_complete();
        return $resp;

    }catch (Exception $e) { log_message('error', "Excepción traeEdoCtaAcumulado: $e"); }

    }
    
    public function traeTotMesEdoCta($id_edificio, $ano, $mes, $cuotaFija )
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();

        $qCu = $this->db->query("SELECT SUM(".$convSql->getINT_FIELD_BD()."C".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."IMPORTE".$convSql->getEND_FIELD_BD().") AS TOTAL, COUNT(".$convSql->getINT_FIELD_BD()."C".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."ID_CUOTA".$convSql->getEND_FIELD_BD().") AS NUM, SUM(".$convSql->getINT_FIELD_BD()."C".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."IMPORTE".$convSql->getEND_FIELD_BD()."-".$cuotaFija.") AS PENA FROM ".$convSql->getPREFIX_DB_CVR().$convSql->getINT_FIELD_BD()."CUOTAS".$convSql->getEND_FIELD_BD()." ".$convSql->getINT_FIELD_BD()."C".$convSql->getEND_FIELD_BD()." WHERE ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."C".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."FECHA_PAGO".$convSql->getEND_FIELD_BD()." ,".$convSql->sqlDateFormatMMYYYY().") = ".$mes.$ano." AND ".$convSql->getINT_FIELD_BD()."C".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."ID_EDIFICIO".$convSql->getEND_FIELD_BD()." = ".$id_edificio." AND ".$convSql->getINT_FIELD_BD()."C".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."STATUS".$convSql->getEND_FIELD_BD()." = '".STATUS_PAG."' ", FALSE);
        $cuo     = (is_array($qCu->result_array())?$qCu->result_array()[0]['TOTAL']:0);
        $cuoNum  = (is_array($qCu->result_array())?$qCu->result_array()[0]['NUM']  :0);        
        $cuoPena = (is_array($qCu->result_array())?$qCu->result_array()[0]['PENA'] :0);
      
        $qGa = $this->db->query("SELECT SUM(".$convSql->getINT_FIELD_BD()."G".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."TOTAL".$convSql->getEND_FIELD_BD().") AS TOTAL, COUNT(".$convSql->getINT_FIELD_BD()."G".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."ID_GASTO".$convSql->getEND_FIELD_BD().") AS NUM FROM ".$convSql->getPREFIX_DB_CVR().$convSql->getINT_FIELD_BD()."GASTOS".$convSql->getEND_FIELD_BD()." ".$convSql->getINT_FIELD_BD()."G".$convSql->getEND_FIELD_BD()." WHERE ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."G".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."FECHA_GASTO".$convSql->getEND_FIELD_BD()." ,".$convSql->sqlDateFormatMMYYYY().") = ".$mes.$ano." AND ".$convSql->getINT_FIELD_BD()."G".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."ID_EDIFICIO".$convSql->getEND_FIELD_BD()." = ".$id_edificio." AND ".$convSql->getINT_FIELD_BD()."G".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."STATUS".$convSql->getEND_FIELD_BD()." = 1", FALSE);        
        $gas    = (is_array($qGa->result_array())?$qGa->result_array()[0]['TOTAL']:0);
        $gasNum = (is_array($qGa->result_array())?$qGa->result_array()[0]['NUM']  :0);
      
        $qTM = $this->db->query("SELECT SUM(".$convSql->getINT_FIELD_BD()."TA".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."COSTO".$convSql->getEND_FIELD_BD().") AS TOTAL, COUNT(".$convSql->getINT_FIELD_BD()."TA".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."ID_TRABAJOS".$convSql->getEND_FIELD_BD().") AS NUM FROM ".$convSql->getPREFIX_DB_CVR().$convSql->getINT_FIELD_BD()."TRABAJOS_MANTO".$convSql->getEND_FIELD_BD()." ".$convSql->getINT_FIELD_BD()."TA".$convSql->getEND_FIELD_BD()." WHERE ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."TA".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."FECHA_INICIO".$convSql->getEND_FIELD_BD()." ,".$convSql->sqlDateFormatMMYYYY().") = ".$mes.$ano." AND ".$convSql->getINT_FIELD_BD()."TA".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."ID_EDIFICIO".$convSql->getEND_FIELD_BD()." = ".$id_edificio." AND ".$convSql->getINT_FIELD_BD()."TA".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."STATUS".$convSql->getEND_FIELD_BD()." IN (9,10,12) ", FALSE);               
        $tm    = (is_array($qTM->result_array())?$qTM->result_array()[0]['TOTAL']:0);
        $tmNum = (is_array($qTM->result_array())?$qTM->result_array()[0]['NUM']  :0);
                        
        $qPT = $this->db->query("SELECT SUM(".$convSql->getINT_FIELD_BD()."PD".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."IMPORTE".$convSql->getEND_FIELD_BD().") AS TOTAL  FROM ".$convSql->getPREFIX_DB_CVR().$convSql->getINT_FIELD_BD()."PRESUPUESTO".$convSql->getEND_FIELD_BD()." ".$convSql->getINT_FIELD_BD()."P".$convSql->getEND_FIELD_BD()." INNER JOIN ".$convSql->getPREFIX_DB_CVR().$convSql->getINT_FIELD_BD()."PRESUPUESTO_DETALLE".$convSql->getEND_FIELD_BD()." ".$convSql->getINT_FIELD_BD()."PD".$convSql->getEND_FIELD_BD()." ON ".$convSql->getINT_FIELD_BD()."P".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."PRESUPUESTO_ID".$convSql->getEND_FIELD_BD()." = ".$convSql->getINT_FIELD_BD()."PD".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."PRESUPUESTO_ID".$convSql->getEND_FIELD_BD()." WHERE ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."PD".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."MES_PTO".$convSql->getEND_FIELD_BD()." ,".$convSql->sqlDateFormatMMYYYY().") = ".$mes.$ano." AND ".$convSql->getINT_FIELD_BD()."P".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."ID_EDIFICIO".$convSql->getEND_FIELD_BD()." = ".$id_edificio." AND ".$convSql->getINT_FIELD_BD()."P".$convSql->getEND_FIELD_BD().".".$convSql->getINT_FIELD_BD()."STATUS".$convSql->getEND_FIELD_BD()." =".PTO_ACTIVO, FALSE);
        $pt  = (is_array($qPT->result_array())?$qPT->result_array()[0]['TOTAL']:0);       

        $resp  = array("cuotas" => $cuo, "numCuotas" => $cuoNum, "penaCuotas" => $cuoPena, "gastos" => $gas, "numGastos" => $gasNum, "tm" => $tm, "numTM" => $tmNum, "pto" => $pt);

        $this->db->trans_complete();
        return $resp;

     }catch (Exception $e) { log_message('error', "Excepción traeTotMesEdoCta: $e"); }      

    }
    
    public function traeDetalleChartMesEdoCta($id_edificio, $ano, $mes)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();

        $cuo = array(); $gas = array();
        $qCu = $this->db->query("SELECT C.IMPORTE, CONCAT('DEPTO ', COALESCE(D.TORRE,''), ' ', COALESCE(D.NUMERACION,'') ) AS CUOTA FROM ".$convSql->getPREFIX_DB_CVR()."CUOTAS C INNER JOIN ".$convSql->getPREFIX_DB_CVR()."DEPARTAMENTOS D ON C.ID_DEPTO = D.ID_DEPTO WHERE ".$convSql->sqlDateFunction()."(C.FECHA_PAGO , ".$convSql->sqlDateFormatMMYYYY().") = ".$mes.$ano." AND C.ID_EDIFICIO = ".$id_edificio." AND C.STATUS = '".STATUS_PAG."' GROUP BY C.IMPORTE, CONCAT('DEPTO ', COALESCE(D.TORRE,''), ' ', COALESCE(D.NUMERACION,'') ) ORDER BY 2", FALSE);
        foreach ($qCu->result_array() as $c) { $cuo[] = array($c['CUOTA'], floatval($c['IMPORTE']));  }        
      
        $qGa = $this->db->query("SELECT (TOTAL) AS IMPORTE, (CONCEPTO) AS GASTO FROM ".$convSql->getPREFIX_DB_CVR()."GASTOS WHERE ".$convSql->sqlDateFunction()."(FECHA_GASTO, ".$convSql->sqlDateFormatMMYYYY().") = ".$mes.$ano." AND ID_EDIFICIO = ".$id_edificio." AND STATUS = 1", FALSE);        
        foreach ($qGa->result_array() as $g) { $gas[] = array($g['GASTO'], floatval($g['IMPORTE']));  }        
      
        $qTM = $this->db->query("SELECT (COSTO) AS IMPORTE, (TRABAJO) AS GASTO FROM ".$convSql->getPREFIX_DB_CVR()."TRABAJOS_MANTO WHERE ".$convSql->sqlDateFunction()."(FECHA_INICIO, ".$convSql->sqlDateFormatMMYYYY().") = ".$mes.$ano." AND ID_EDIFICIO = ".$id_edificio." AND STATUS IN (9,10,12) ", FALSE);
        foreach ($qTM->result_array() as $g) { $gas[] = array($g['GASTO'], floatval($g['IMPORTE']));  }        

        $resp  = array("cuotas" => $cuo, "gastos" => $gas);

        $this->db->trans_complete();
        return $resp;

     }catch (Exception $e) { log_message('error', "Excepción traeDetalleChartMesEdoCta: $e"); }      
     
    }
    
    public function traeGastosbyID($edificio, $gasto)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();

        $query = $this->db->query("SELECT  G.ID_GASTO, G.CONCEPTO, G.MONTO, G.IVA, G.TOTAL, G.COMPROBANTE, G.FACTURA, G.GASTO_FIJO, G.ID_EDIFICIO, GF.ID_GASTOS_FIJOS, GF.GASTO_CADA_DIAS, GF.GASTO_DURANTE_MESES, G.STATUS
                                   ,".$convSql->sqlDateFunction()."(FECHA_GASTO, ".$convSql->sqlDateFormatdd__MM__yyyy().") as FECHA_GASTO
                                  FROM ".$convSql->getPREFIX_DB_CVR()."GASTOS G LEFT OUTER JOIN ".$convSql->getPREFIX_DB_CVR()."GASTOS_FIJOS GF ON G.ID_GASTO = GF.ID_GASTOS 
                                  WHERE G.ID_GASTO = ".$gasto." AND G.ID_EDIFICIO = ".$edificio."
                                 ");
        
        $resp  = ( empty($query->result()) ?  array("ID_GASTO"=>NULL, "CONCEPTO"=>NULL, "MONTO"=>NULL, "IVA"=>NULL, "TOTAL"=>NULL, "COMPROBANTE"=>"", "FACTURA"=>"", "FECHA_GASTO"=>NULL, "GASTO_FIJO"=>NULL, "FECHA_ALTA"=>NULL, "ID_EDIFICIO"=>$edificio, "ID_GASTOS_FIJOS"=>NULL, "GASTO_CADA_DIAS"=>NULL, "GASTO_DURANTE_MESES"=>NULL, "STATUS"=>1)  : $query->result_array()[0]);

        $this->db->trans_complete();
        return $resp;

    }catch (Exception $e) { log_message('error', "Excepción traeGastosbyID: $e"); }      
          
    }
    
    public function traeTbjoMtobyID($edificio, $tbjoMto)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();
  
        $this->db->select('ID_TRABAJOS, TRABAJO, DESCRIPCION, COSTO,TIPO_ANT, ANTICIPO, OBSERVACIONES, EVIDENCIA_ANTES, EVIDENCIA_DESPUES,DURACION, ID_EDIFICIO, PROVEEDOR, STATUS');
        $this->db->select($convSql->sqlDateFunction()."(FECHA_INICIO, ".$convSql->sqlDateFormatdd_MM_yyyy().") as FECHA_INICIO, ".$convSql->sqlDateFunction()."(FECHA_COMPROMISO, ".$convSql->sqlDateFormatdd_MM_yyyy().") as FECHA_COMPROMISO, ".$convSql->sqlDateFunction()."(FECHA_ALTA,".$convSql->sqlDateFormatdd_MM_yyyy().") as FECHA_ALTA", FALSE);
        $this->db->select("(NULL) AS NUEVO", FALSE);
        $this->db->where ('ID_TRABAJOS',  $tbjoMto);
        $this->db->where ('ID_EDIFICIO',  $edificio);
        $query = $this -> db -> get('TRABAJOS_MANTO');
        
        $resp  = ( empty($query->result()) ? array("ID_TRABAJOS"=>intval(substr(now(), -7)), "TRABAJO"=>NULL, "DESCRIPCION"=>NULL, "COSTO"=>NULL, "TIPO_ANT"=>1, "EVIDENCIA_ANTES"=>NULL, "EVIDENCIA_DESPUES"=>NULL, "ANTICIPO"=>NULL, "OBSERVACIONES"=>NULL, "DURACION"=>NULL, "ID_EDIFICIO"=>$edificio, "PROVEEDOR"=>NULL, "STATUS"=>9, "NUEVO"=>"SI" ) : $query->result_array()[0]);

        $this->db->trans_complete();
        return $resp;

      }catch (Exception $e) { log_message('error', "Excepción traeTbjoMtobyID: $e"); }      
        
    }
    
    public function poblarSelect()
    {   $this->db->trans_start();
        $resp  = array();
         
        $this->db->select  ('ID_EDIFICIO, NOMBRE, CALLE, NUMERO, COLONIA, LOGOTIPO');
        $this->db->order_by("FECHA_ALTA","ASC");					
        $query = $this->db->get('EDIFICIOS');

        $options    = array();
        $options[0] = '::Seleccione una opción::';
        if (empty($query->result()) )
            {   $resp  = $options;      }
        else
            {   foreach ($query->result() as $row)							 
                    { $options[$row->ID_EDIFICIO] = $row->NOMBRE." ".$row->CALLE." ".$row->NUMERO." ".$row->COLONIA; }
                $resp  = $options;  
            }

        $this->db->trans_complete();
        return $resp;
    }        
   
    public function traeGastosFiltros($param)
    {   
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();
         
        $off  = (($param['pagina']-1) * $param['registrosPagina']);
        /****** CONTEO INI*************/
    	$this -> db -> select('count(ID_GASTO) as conteo');
        if (!empty($param['f1']) & !empty($param['f2']))
            { $this->db->where($convSql->sqlDateFunction()."(FECHA_GASTO,".$convSql->sqlDateFormatyyyy__MM__dd().") BETWEEN '".$param['f1']."' AND '".$param['f2']."'",NULL, FALSE ); }
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
        
        $query = $this->db->get('GASTOS')->result();
	    $conteo = $query[0]->conteo;
        /****** CONTEO FIN*************/

        /****** REGISTROS INI*************/
        $this -> db -> select('ID_GASTO, CONCEPTO, MONTO, IVA, TOTAL, COMPROBANTE, FACTURA, ID_EDIFICIO, STATUS');
        $this -> db -> select($convSql->sqlDateFunction()."(FECHA_GASTO, ".$convSql->sqlDateFormatdd_MMM_yyyy().") as FECHA_GASTO, ".$convSql->sqlDateFunction()."(FECHA_ALTA, ".$convSql->sqlDateFormatdd_MMM_yyyy().") as FECHA_ALTA", FALSE);        
        if (!empty($param['f1']) & !empty($param['f2']))
           { $this->db->where($convSql->sqlDateFunction()."(FECHA_GASTO,".$convSql->sqlDateFormatyyyy__MM__dd().") BETWEEN '".$param['f1']."' AND '".$param['f2']."'",NULL, FALSE ); }
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
        
        $this->db->order_by("ID_GASTO","DESC"); 
        $queryReg = $this->db->get('GASTOS',$param['registrosPagina'],$off);			
        /****** REGISTROS FIN*************/
        $resp  = ( empty($queryReg->result()) ? FALSE : array("conteo"=>$conteo, "registros"=>$queryReg->result_array(), "offset"=>$off,"totales"=>$this->calculaTotales($param)) );   

        $this->db->trans_complete();
        return $resp;
    }catch (Exception $e) { log_message('error', "Excepción traeGastosFiltros: $e"); }      
        
    }
    
    private function calculaTotales($param)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();
        
        /****** TOTAL GAS INI*************/
    	$this -> db -> select('SUM(TOTAL) as TOTAL');
        if (!empty($param['f1']) & !empty($param['f2']))
           { $this->db->where($convSql->sqlDateFunction()."(FECHA_GASTO,".$convSql->sqlDateFormatyyyy__MM__dd().") BETWEEN '".$param['f1']."' AND '".$param['f2']."'",NULL, FALSE ); }
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
        
        $this->db->where('STATUS', 1);
        $queryGas = $this->db->get('GASTOS ');        
        $gas      = empty($queryGas->result()) ? 0 : $queryGas->result_array()[0]['TOTAL'];
        /****** TOTAL GAS FIN*************/
        
        /****** TOTAL TM INI*************/
    	$this -> db -> select('SUM(COSTO) as TOTAL');
        if (!empty($param['f1']) & !empty($param['f2']))
           { $this->db->where($convSql->sqlDateFunction()."(FECHA_COMPROMISO,".$convSql->sqlDateFormatyyyy__MM__dd().") BETWEEN '".$param['f1']."' AND '".$param['f2']."'",NULL, FALSE ); }
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
        
        $this->db->where("STATUS IN (9,10,12) ",NULL, FALSE ); 
        $queryTM = $this->db->get('TRABAJOS_MANTO ');        
        $tm      = empty($queryTM->result()) ? 0 : $queryTM->result_array()[0]['TOTAL'];
        /****** TOTAL TM FIN*************/
        
        /****** TOTAL CUOTA INI*************/
    	$this -> db -> select('SUM(IMPORTE) as TOTAL');
        if (!empty($param['f1']) & !empty($param['f2']))
           { $this->db->where($convSql->sqlDateFunction()."(FECHA_PAGO,".$convSql->sqlDateFormatyyyy__MM__dd().") BETWEEN '".$param['f1']."' AND '".$param['f2']."'",NULL, FALSE ); }
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
        
        $this->db->where('STATUS', STATUS_PAG);                 
        $queryC = $this->db->get('CUOTAS ');        
        $cuota  = empty($queryC->result()) ? 0 : $queryC->result_array()[0]['TOTAL'];
        /****** TOTAL CUOTA FIN*************/
        
        /****** TOTAL PTO INI*************/
        $where1 = "";
        $where2 = "";
        $where3 = "";
        
        $this->db->select('SUM(PD.IMPORTE) as TOTAL');        
        if (!empty($param['f1']) & !empty($param['f2']))
           {  $where1 = " AND ".$convSql->sqlDateFunction()."(PD.MES_PTO,".$convSql->sqlDateFormatyyyy__MM__dd().") BETWEEN '".$param['f1']."' AND '".$param['f2']."'"; }
        if (!empty($param['f3']))
            { $where2 = 'AND shipper = '.$param['f3']; }
 
        if (is_array($param['id_edificio']))
        { $util   = new Utils();
          $edis   = $util->edificiosString($param['id_edificio']);
          $where3 = " AND P.ID_EDIFICIO IN $edis"; 
          unset($util);
        }
        else
        { $where3 = " AND P.ID_EDIFICIO = ".$param['id_edificio']; }

        $queryPTO = $this->db->query("SELECT SUM(PD.IMPORTE) as TOTAL FROM ".$convSql->getPREFIX_DB_CVR()."PRESUPUESTO P INNER JOIN ".$convSql->getPREFIX_DB_CVR()."PRESUPUESTO_DETALLE PD ON P.PRESUPUESTO_ID = PD.PRESUPUESTO_ID WHERE P.STATUS =".PTO_ACTIVO . $where1 . $where2 . $where3, FALSE);        
        $pto      = empty($queryPTO->result()) ? 0 : $queryPTO->result_array()[0]['TOTAL'];        
        /****** TOTAL PTO FIN*************/
        
        $resp  = array("totGas"=>$gas, "totalGasStr"=>"$ ".number_format($gas, 2, '.', ','),"totTM"=>$tm, "totalTMStr"=>"$ ".number_format($tm, 2, '.', ','),"totCuota"=>$cuota, "totalCuotaStr"=>"$ ".number_format($cuota, 2, '.', ','),"totPto"=>$pto, "totalPtoStr"=>"$ ".number_format($pto, 2, '.', ',') );

        $this->db->trans_complete();
        return $resp;

    }catch (Exception $e) { log_message('error', "Excepción calculaTotales: $e"); }      
               
    }
    
    public function traeTbjoMtoFiltros($param)
    {
    try{$convSql = new ConverterSQL();
        $this->db->trans_start();
        $resp  = array();
        
        $off  = (($param['pagina']-1) * $param['registrosPagina']);
        /****** CONTEO INI*************/
    	$this -> db -> select('count(ID_TRABAJOS) as conteo');
        if (!empty($param['f1']) & !empty($param['f2']))        
           { $this->db->where($convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_INICIO".$convSql->getEND_FIELD_BD().",".$convSql->sqlDateFormatyyyy__MM__dd().") BETWEEN '".$param['f1']."' AND '".$param['f2']."'",NULL, FALSE ); }
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
        
        $query  = $this->db->get('TRABAJOS_MANTO')->result();
	    $conteo = $query[0]->conteo;
        /****** CONTEO FIN*************/        

        /****** REGISTROS INI*************/
        $this->db->select('ID_TRABAJOS, TRABAJO, DESCRIPCION, COSTO, TIPO_ANT, ANTICIPO, OBSERVACIONES, EVIDENCIA_ANTES, EVIDENCIA_DESPUES, DURACION, ID_EDIFICIO, PROVEEDOR, ORDEN_TRABAJO, STATUS');
        $this->db->select($convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_INICIO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatdd_MMM_yyyy().") as FECHA_INICIO, ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_COMPROMISO".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatdd_MMM_yyyy().") as FECHA_COMPROMISO, ".$convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_ALTA".$convSql->getEND_FIELD_BD().", ".$convSql->sqlDateFormatdd_MMM_yyyy().") as FECHA_ALTA", FALSE);        

        if (!empty($param['f1']) & !empty($param['f2']))
           { $this->db->where($convSql->sqlDateFunction()."(".$convSql->getINT_FIELD_BD()."FECHA_INICIO".$convSql->getEND_FIELD_BD().",".$convSql->sqlDateFormatyyyy__MM__dd().") BETWEEN '".$param['f1']."' AND '".$param['f2']."'",NULL, FALSE ); }           
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
        
        $this->db->order_by("ID_TRABAJOS","DESC"); 
        $queryReg = $this->db->get('TRABAJOS_MANTO', $param['registrosPagina'],$off);			
        /****** REGISTROS FIN*************/
        
        $resp  = ( empty($queryReg->result()) ? FALSE : array("conteo"=>$conteo, "registros"=>$queryReg->result_array(), "offset"=>$off));   

        $this->db->trans_complete();
        return $resp;        

    }catch (Exception $e) { log_message('error', "Excepción traeTbjoMtoFiltros: $e"); }   

    }
    
    public function traeCuotasAX($param)
    {   $this->db->trans_start();
        $resp  = array();
        
        $this->db->select('D.NUMERACION');        
        $this->db->where ('D.TORRE'      , $param['torre']);
        $this->db->where ('D.ID_EDIFICIO', $param['id_edificio']);
        $this->db->where ('D.ID_DEPTO'   , $param['id_depto']);
        $queryReg = $this->db->get('DEPARTAMENTOS D');

        $resp  = ( empty($queryReg->result()) ? array() : $queryReg->result_array());   

        $this->db->trans_complete();
        return $resp;
    }
    
    public function traePtoActivo($id_edificio)
    {   $this->db->trans_start();
        $resp  = array();
        
        $this->db->select('PRESUPUESTO_ID');
        $this->db->where ('ID_EDIFICIO', $id_edificio);
        $this->db->where ('STATUS'     , PTO_ACTIVO);
        $queryReg = $this->db->get('PRESUPUESTO');
        $resp  = ( empty($queryReg->result()) ? 0 : $queryReg->result_array()[0]);   

        $this->db->trans_complete();
        return $resp;
    }
    
}//MDL_gastos
