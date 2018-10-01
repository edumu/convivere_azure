<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Convivere extends CI_Controller {
    
     
public function index()
{  
try{    
        $menu = new GeneradorMenu(array("nombre"=>"","apellidos"=>"","nivelacceso"=>"P","edificio"=>NULL,"depto"=>NULL),"Área de Acceso");

        $this->load->view('home',$menu->getmenu());
        
    }catch (Exception $e) {echo 'Excepción Index Convivere: '.$e;}
}


        
/***  TEST FUNCTION ***/
     public function test()
    {  try{ 
            //$datos_sesion = $this->MDL_usuario->validaUsuario("eduardo.munoz.siller@gmail.com", "ney");
            $this->session->set_userdata(array("NOMBRE"=>"EMS", "CUENTA"=>"eduardoMS" ));
            echo br()."**** VER:".$this->session->has_userdata('NOMBRE');
            echo br()."**** VER:".isset($_SESSION['NOMBRE']);
            
            
            echo br()."***** CONVIVERE TEST I *****" .$this->session->userdata("NOMBRE");
            echo br()."***** CONVIVERE TEST II *****" . sizeof($this->session->userdata());
            
            echo "<pre>";
            print_r($this->session->all_userdata());
            echo "</pre>";
            echo br()."VER:".$_SERVER["DOCUMENT_ROOT"];
            echo br()."VER II: ".FCPATH;

            $datos       = array("ID_INVENTARIO"=>"1", "ACTIVO"=>"2", "DESCRIPCION"=>"3", "CANTIDAD"=>"4", "FOTO"=>"5", "FECHA_ALTA"=>standard_date('DATE_W3C', time()), "ID_EDIFICIO"=>"6");
            print_r($datos);
            echo br(2);
            unset($datos["FECHA_ALTA"]);
            print_r($datos);
          //redirect(base_url() . "portal/test");

/*                    
            $this->load->library('Cartero');
            $cartero = new Cartero();
            $cartero->setfromMail("soluciones@convivere.mx");
            $cartero->setfromName("Soluciones Convivere");
            $cartero->setto      ("eduardo.munoz.siller@gmail.com");
            $cartero->setsubject ("TEST MAIL CVR II");
            $cartero->setmessage ("TEST MAIL CVR II");

            $cartero->mandaCorreo();
            echo 'DESPUES DE MANDAR CoRREO';

            $T= $this->MDL_usuario->testFunction("Aeduardo.munoz.siller@gmail.com","ney");
            echo 'VER: '.$T ;

            $deptosMorosos = $this->MDL_cuotas->traeCuotasPendientes("1", TRUE, NULL, NULL);
            var_dump($deptosMorosos);

            $dm = $this->MDL_cuotas->traeTotCuotasTasa("1", "1110");
            var_dump($dm);

            $dm = $this->MDL_gastos->traeTbjoMtobyID("1", "1");
            var_dump($dm);
*/

          } catch (Exception $e) {echo 'Excepción Convivere:',  $e, "\n";}		
    }
    
     public function captcha()
    {  try{
                    
            $this->load->view('portal/webpart_captcha_form');
            
          } catch (Exception $e) {echo 'Excepción Convivere:',  $e, "\n";}		
    }
    
    
public function generarPDFAX()
{
try{
    	$this->load->library('PDF');        
        $typePDF = "";//param enviado desde AJAX

        switch ($typePDF) {
            case "factura":
            break;
            default:
                $data = NULL;
                $html = $this->load->view('export/pdf/vw_pdf_factura', $data , true );
                $myPDF = new PDF("test.pdf",$html);
            break;
        }        
        $myPDF->crearPDF();        
        echo json_encode ( array("filename"  => $myPDF->getnombrePDF(), "accion" => $myPDF->getpdfCreado()) );
        
    } catch (Exception $e) {echo 'generarPDFAX Excepción: ',  $e->getMessage(), "\n";}	
}

public function paginadorAX()
{           
    try{
        $vs = $this->validaSesion(TRUE,TRUE);
        if( isset($vs['session']))
        {   echo json_encode ($vs); 
            exit(0);
        }

        $pagina = $this -> input -> post('pagina');
        $users  = $this -> md_usuario -> traeUsers($this->registros_x_pagina,$pagina, $this->dirFoto);

        echo json_encode ($users);

    } catch (Exception $e) {echo ' paginadorAX Excepción: ',  $e, "\n";}		
}

public function addUploadedFileAX()
{
try{	
        $vs = $this->validaSesion(TRUE,TRUE);
        if( isset($vs['session']))
        {   echo json_encode ($vs); 
            exit(0);
        }

        if(isset($_FILES["myfile"]))
        {
            $ret = array();		
            $error =$_FILES["myfile"]["error"];

            if(!is_array($_FILES["myfile"]["name"])) //single file
            {                            
                $fileName =  $this->utils->generaNombre($_FILES["myfile"]["name"]);                        
                move_uploaded_file($_FILES["myfile"]["tmp_name"],$this->dirFoto.$fileName);
                $ret[]= $fileName;                                            
            }
            else  //Multiple files, file[]
            {
              $fileCount = count($_FILES["myfile"]["name"]);
              for($i=0; $i < $fileCount; $i++)
              {                            
                    $fileName =  $this->utils->generaNombre($_FILES["myfile"]["name"][$i]);
                    move_uploaded_file($_FILES["myfile"]["tmp_name"][$i],$this->$dirFoto.$fileName);
                    $ret[]= $fileName;
              }
            }                                         
            echo json_encode($ret);                
        }
  } catch (Exception $e) {echo ' addUploadedFileAX Excepción: ',  $e, "\n";}         
}

public function	renameUploadedFileAX()
{	
try{	
        $vs = $this->validaSesion(TRUE,TRUE);
        if( isset($vs['session']))
        {   echo json_encode ($vs); 
            exit(0);
        }
        $this->load->helper('date');

        $errores       = array();
        $errorTxt      = "";        
        $extension     = $this -> input -> post('extension');
        $nombreArchivo = $this -> input -> post('nombreArchivo');                
        $idImagen      = "tll_".intval(substr(now(), -5));
        $nombreArchivo = $this->dirFoto.$nombreArchivo.".".$extension;
        $nombreHermes  = $this->dirFoto.$idImagen.".".$extension;

        $cmd = "$nombreArchivo -resize 55x55! "; 
        exec("convert $cmd $nombreHermes ",$errores);
        unlink($nombreArchivo);

        if ( !empty($errores) )
            { $errorTxt = "<br />Hubo errores al trabajar conconversion:<br />".print_r($errores); }       

        echo json_encode (array("nombreHermes" => $nombreHermes,"hImg" => $idImagen.".".$extension,"erroresd" => $errorTxt));

    } catch (Exception $e) {echo 'renameUploadedFileAX Excepción: ',  $e, "\n";}	
}

public function	deleteUploadedFileAX()
{
try{	
        $vs = $this->validaSesion(TRUE,TRUE);
        if( isset($vs['session']))
        {   echo json_encode ($vs); 
            exit(0);
        }
        if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
        {
                $fileName =$_POST['name'];
                $filePath = $this->dirFoto.$fileName;
                if (file_exists($filePath)) 		
                        unlink($filePath);
                
                echo "Deleted File ".$fileName."<br>";
        }
    } catch (Exception $e) {echo 'deleteUploadedFileAX Excepción: ',  $e, "\n";}	        
}

    
}// end controller
