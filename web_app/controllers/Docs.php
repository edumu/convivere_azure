<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Docs extends CI_Controller {
    
    
public function index()
{  
try{    $ds                = $this->validaSesion(USER,NULL);
        $menu              = new GeneradorMenu($this->session->userdata('datos_sesion'),"Docs");                
        $data              = $menu->getmenu();         
        $data['edificio']   = $ds['edificio'];

        if ($ds['nivelacceso'] == USER )
        {   $data['tiposDoc']  = $this->MDL_usuario->poblarSelectTiposDoc('tipo_doc_u');        }
        else
        {   $data['tiposDoc']  = $this->MDL_usuario->poblarSelectTiposDoc('tipo_doc');          }

        $this->load->view('portal/vw_home_docs',$data);
        
    }catch (Exception $e) {echo 'Excepción Index Portal: '.$e;}
}//index

public function saveDocAX($id_edificio=NULL)
{
 try{$util = new Utils();
     
     $id_doc      = $this -> input -> post('id_doc');
     $nuevoDoc    = $this -> input -> post('nuevoDoc');
     $docAdjunto  = $this -> input -> post('docAdjunto');     

     $activo      = $this -> input -> post('activo');
     $desc_activo = $this -> input -> post('desc_activo');
     $cantidad    = $this -> input -> post('cantidad');     

     $datos       = array("ID_INVENTARIO"=>$id_doc, "ACTIVO"=>$activo, "DESCRIPCION"=>$desc_activo, "CANTIDAD"=>$cantidad, "FOTO"=>$invAdjunto, "FECHA_ALTA"=>standard_date('DATE_W3C', time()), "ID_EDIFICIO"=>$id_edificio );     
     
     if($nuevoDoc == NULL || $nuevoDoc == "")
          { unset($datos["FECHA_ALTA"]);
            $this->MDL_docs->update_doc(array("ID_DOC"=>$id_doc, "ID_EDIFICIO"=>$id_edificio), $datos);     
          }
     else {  $this->MDL_docs->insert_doc($datos);      }

     unset($util);

     echo json_encode ( array("doc" => $id_doc) );

    }catch (Exception $e ){ log_message('error', 'ERROR saveDocAX  Excepción : ' . $e->getMessage());}
}//saveDocAX 

}//DOCS
