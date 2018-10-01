<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Edificio extends CI_Controller {
    

public function test()
{  
try{
    $fecha_gasto    = "2018/06/21";//"2018/05/23";
    $gasto_cada_dia = "1";
    $util = new Utils();
    
    unset($util);

    
    $this->load->view('portal/TEST_1',$data);  
    
    }
     catch (Exception $e                 ){ echo 'ERROR Excepción test EDIFICIO: ' . $e->getMessage();}
}//test
    
    
public function index()
{  
try{    $ds                = $this->validaSesion(ADMIN,NULL);        
        $menu              = new GeneradorMenu($this->session->userdata('datos_sesion'),"Edificio");                
        $data              = $menu->getmenu();
        $data['edif']      = $this->MDL_edificio->poblarSelect();// ojo restringir por nivel acceso y cuenta
        $data['torres']    = array("0"=>'');
        $data['penal']     = $this->MDL_edificio->poblarSelectCat("tipo_penal", TRUE);
        $data['tipoDueno'] = $this->MDL_edificio->poblarSelectCat("tipo_dueno", TRUE);
        $data['usuarios']  = $this->MDL_usuario->poblarSelectUsuario();
        $data['fieldsRadioAme'] = "<label class='checkbox'><input type='checkbox' id='tiene_ame' name='tiene_ame' value='0'><i></i><span id='lblAme'>No tiene amenidades</span></label>";
        $data['referencia']   = NULL;
        $data['imp']          = array("importe"=>NULL,"importeStr"=>NULL,"penalizacion"=>NULL,"penaStr"=>NULL);
        $data['cuotas']       = array();
        $data['tiposPagoRadioButton']= NULL;
        $data['fullCalendar'] = TRUE;
        $data['edificio']   = $ds['edificio'];

        $this->load->view('portal/vw_home_edificio',$data);
        
    }catch (Exception $e) {echo 'Excepción Index Portal: '.$e;}
}//index



public function traeEdificioAX()
{
 try{   $vs = $this->validaSesion(ADMIN,TRUE);
        if( isset($vs['session']))
        {   echo json_encode ($vs); 
            exit(0);
        }
        $torresField[] = array();        
        
        $id_edificio = $this -> input -> post('id_edificio');
        $edificio    = $this -> MDL_edificio -> traeEdificio($id_edificio);
        $x           = 0;
        foreach($this -> MDL_edificio -> traeTorres($id_edificio) as $t)        
            { $x++; 
              $torresField[] = inputText_crv(array('col'=>'col-2','label'=>'Torre:','icon'=>'fa-building','inputName'=>'torre'.$x,'class'=>'validate[required,custom[onlyLetterNumber]] text-input','value'=>$t['TORRE'],'placeholder'=>'Ej. A') );
              $torres[]      = array("val"=>$t['TORRE'],"text"=>$t['TORRE']);
            }

        echo json_encode (array("e"=>$edificio,"t"=>$torresField,"torres"=>$torres,"dir"=>DIR_LOGOS));

    } catch (Exception $e) {echo ' traeEdificioAX Excepción: ',  $e, "\n";}	
}//traeEdificioAX




public function guardarAX()
{
 try{   $vs = $this->validaSesion(ADMIN,TRUE);
        if( isset($vs['session']))
        {   echo json_encode ($vs); 
            exit(0);
        }
        $this->load->helper('date');
        $ds           = $this -> session -> userdata('datos_sesion');
        $id_edificio  = $this -> input   -> post('id_edificio');
        $num_viviendas= $this -> input   -> post('num_viviendas');
        $num_torres   = $this -> input   -> post('num_torres');
        $guardoBD     = 0;

        if($id_edificio === "0")                
        {
         $camposForm = array("NOMBRE"=>$this->input->post('nombre'),"FECHA_ALTA"=>standard_date('DATE_W3C', time()),"CREADO_MOD"=>$ds['cuenta'],"CALLE"=>$this->input->post('calle'),"NUMERO"=>$this->input->post('numero'),"COLONIA"=>$this->input->post('colonia') ,"ALCALDIA"=>$this->input->post('alcaldia'),"ESTADO"=>$this->input->post('estado'),"CP"=>$this->input->post('cp'),"LATITUD"=>$this->input->post('latitud'),"LONGITUD"=>$this->input->post('longitud'),"CUOTA_MANTO"=>$this->input->post('cuota_manto'),"DIA_CORTE"=>$this->input->post('dia_corte'),"TIPO_PENALIZACION"=>$this->input->post('tipo_penalizacion'),"PENALIZACION"=>$this->input->post('penalizacion'),"NUM_TORRES"=>$num_torres,"NUM_VIVIENDAS"=>$num_viviendas,"LOGOTIPO"=>$this->input->post('logoEdificio'),"TIENE_AMENIDADES"=>$this->input->post('tiene_ame')); 
         $guardoBD   = $this -> MDL_edificio -> insert_edificio($camposForm);
         $id_edificio= $this -> MDL_edificio -> traeIdEdificio(array("NOMBRE"=>$this->input->post('nombre'),"CREADO_MOD"=>$ds['cuenta'],"CALLE"=>$this->input->post('calle'),"NUMERO"=>$this->input->post('numero'),"COLONIA"=>$this->input->post('colonia')));
         for ($x = 1; $x <= $num_torres; $x++) 
            {   for ($y = 1; $y <= $num_viviendas; $y++) 
                {  $this ->  MDL_edificio -> insert_deptos(array("ID_DEPTO"=>$id_edificio.$x.$y,"TORRE"=>$this->input->post('torre'.$x),"NUMERACION"=>$x.$y,"ID_EDIFICIO"=>$id_edificio)); }
            }
        }
        else
        {
         $camposForm = array("NOMBRE"=>$this->input->post('nombre'),"CREADO_MOD"=>$ds['cuenta'],"CALLE"=>$this->input->post('calle'),"NUMERO"=>$this->input->post('numero'),"COLONIA"=>$this->input->post('colonia') ,"ALCALDIA"=>$this->input->post('alcaldia'),"ESTADO"=>$this->input->post('estado'),"CP"=>$this->input->post('cp'),"LATITUD"=>$this->input->post('latitud'),"LONGITUD"=>$this->input->post('longitud'),"CUOTA_MANTO"=>$this->input->post('cuota_manto'),"DIA_CORTE"=>$this->input->post('dia_corte'),"TIPO_PENALIZACION"=>$this->input->post('tipo_penalizacion'),"PENALIZACION"=>$this->input->post('penalizacion'),"NUM_TORRES"=>$num_torres,"NUM_VIVIENDAS"=>$num_viviendas,"LOGOTIPO"=>$this->input->post('logoEdificio'),"TIENE_AMENIDADES"=>$this->input->post('tiene_ame')); 
         $guardoBD   = $this -> MDL_edificio -> update_edificio($id_edificio, $camposForm);
         for ($x = 1; $x <= $num_torres; $x++) 
            {   for ($y = 1; $y <= $num_viviendas; $y++) 
                {  $this ->  MDL_edificio -> update_deptos($id_edificio.$x.$y,array("TORRE"=>$this->input->post('torre'.$x),"NUMERACION"=>$x.$y,"ID_EDIFICIO"=>$id_edificio)); }
            }
        }                			        

        echo json_encode ( array("registros"=>$guardoBD,"test"=>$this->input->post('tiene_ame')) );

    } catch (Exception $e) {echo ' guardarAX Excepción: ',  $e, "\n";}	
}//guardarAX


public function creaTorresAX()
{
 try{   $vs = $this->validaSesion(ADMIN,TRUE);
        if( isset($vs['session']))
        {   echo json_encode ($vs); 
            exit(0);
        }
        $letras   = array("","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");
        $torres[] = array();
        $num_torres = $this -> input -> post('num_torres');
        if ($num_torres === "1")
            { $torres[] = inputText_crv(array('col'=>'col-2','label'=>'Torre:','icon'=>'fa-building','inputName'=>'torre1','class'=>'validate[required,custom[onlyLetterNumber]] text-input','value'=>"Te. Única",'placeholder'=>'Ej. A') );}        
        else{
            for ($x = 1; $x <= $num_torres; $x++) 
                { $torres[] = inputText_crv(array('col'=>'col-2','label'=>'Torre:','icon'=>'fa-building','inputName'=>'torre'.$x,'class'=>'validate[required,custom[onlyLetterNumber]] text-input','value'=>$letras[$x],'placeholder'=>'Ej. A') );}}

        echo json_encode ($torres);                          

    } catch (Exception $e) {echo ' creaTorresAX Excepción: ',  $e, "\n";}	
}//creaTorresAX



public function detalleDeptoAX()
{
try{$vs = $this->validaSesion(ADMIN,TRUE);
    if( isset($vs['session']))
    {   echo json_encode ($vs); 
        exit(0);
    }

    $this->load->helper('date');    
    $ds     = $this -> session -> userdata('datos_sesion');
    $cuenta = $this -> input -> post('usuario');
            
    $param = array("CUENTA"      => $cuenta
                  ,"CONTACTO"    => $this -> input -> post('tipoDueno')
                  ,"ID_EDIFICIO" => $this -> input -> post('id_edificio')
                  ,"TORRE"       => $this -> input -> post('torre')
                  ,"ID_DEPTO"    => $this -> input -> post('id_depto')
                  ,"FECHA_ALTA"  => standard_date('DATE_W3C', time())
            	  ,"CREADO_MOD"  => $ds['cuenta']
                 );
    
    $this->MDL_edificio->insert_detalle_deptos($param);
    $name= $this->MDL_usuario->traeUsuario($cuenta);

    echo json_encode ($name);

    } catch (Exception $e) {echo ' detalleDeptoAX Excepción: ',  $e, "\n";}	
}//detalleDeptoAX


public function traeAsambleasAX($id_edificio=NULL)
{
try{$this->load->helper('date');
            
    $eventos = array(array("id"    => "999"
                        ,"title" => "Conference"
                        ,"start" => "2018-06-20"
                        ,"end"   => "2018-06-20"
                        )
                      ,array("id"    => "888"
                        ,"title" => "MOMU"
                        ,"start" => "2018-07-13 08:00:00"
                        ,"end"   => "2018-06-13 10:00:00"
                        )
                    );
    
    echo json_encode ($eventos);

    } catch (Exception $e) {echo ' traeAsambleasAX Excepción: ',  $e, "\n";}	
}//traeAsambleasAX


public function saveInvAX($id_edificio=NULL)
{
 try{$util = new Utils();
     
     $id_inv      = $this -> input -> post('id_inv');
     $nuevoTM     = $this -> input -> post('nuevoIA');
     $activo      = $this -> input -> post('activo');
     $desc_activo = $this -> input -> post('desc_activo');
     $cantidad    = $this -> input -> post('cantidad');
     $invAdjunto  = $this -> input -> post('invAdjunto');     
     $datos       = array("ID_INVENTARIO"=>$id_inv, "ACTIVO"=>$activo, "DESCRIPCION"=>$desc_activo, "CANTIDAD"=>$cantidad, "FOTO"=>$invAdjunto, "FECHA_ALTA"=>standard_date('DATE_W3C', time()), "ID_EDIFICIO"=>$id_edificio );     
     
     if($nuevoTM == NULL || $nuevoTM == "")
          { unset($datos["FECHA_ALTA"]);
            $this->MDL_edificio->update_inventario(array("ID_INVENTARIO"=>$id_inv, "ID_EDIFICIO"=>$id_edificio), $datos);     
          }
     else {  $this->MDL_edificio->insert_inventario($datos);      }

     unset($util);

     echo json_encode ( array("inv" => $id_inv) );

    }catch (Exception $e ){ log_message('error', 'ERROR saveInvAX  Excepción : ' . $e->getMessage());}
}//saveInvAX 


public function avisoInventarioAX()
{
 try{$vs = $this->validaSesion(USER,TRUE);        
     if( isset($vs['session']))
     {   echo json_encode ($vs); 
        exit(0);
     }
     $this->load->library('PDF');
     $util = new Utils();
          
     $id_edificio = $vs['edificio'];//$this->input->post('id_edificio');     
     $path        = DIR_DOCUMENTS . $id_edificio."/";
     
     if (file_exists($path) == FALSE)
        {   mkdir($path, 0777);     }
       
     $fileNameIA = $path."Inventario_Activos_".$util->dateFormatHoyDD_MMM_Y().".pdf";
     
     if (file_exists($fileNameIA))
        {  unlink($fileNameIA);  }
        
     $edificio = $this->MDL_edificio->traeEdificio  ($id_edificio);
     $activos  = $this->MDL_edificio->traeInventario($id_edificio);     

     $htmlPDF  = $this->load->view('portal/vw_formato_inventario', $util->getAvisoInventarioData($activos, $edificio, TRUE), TRUE);     
     $this->pdf->generate($htmlPDF, $fileNameIA, FALSE);
     
     unset($util);

     echo json_encode ($fileNameIA);
     
    }catch (Exception $e ){ log_message('error', 'ERROR avisoInventarioAX  Excepción : ' . $e->getMessage());  }
    
}//avisoInventarioAX


}//Edificio
