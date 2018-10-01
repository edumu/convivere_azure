<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Portal extends CI_Controller {
    
    
public function index()
{  
try{    $this->validaSesion(USER,NULL);
    
        $menu = new GeneradorMenu($this->session->userdata('datos_sesion'),"Inicio");                

        $this->load->view('vw_home_portal',$menu->getmenu());
        
    }catch (Exception $e) {echo 'Excepción Index Portal: '.$e;}
}

/***  TEST FUNCTION ***/
public function test()
{  try{ 
    log_message('error', "***** Ver Portal *****" .$this->session->userdata('cuenta'));            
    
    $this->load->library('session');
        print_r($this->session->userdata());
        echo br()."***** CONVIVERE TEST I *****" .$this->session->userdata("NOMBRE");
        echo br()."***** CONVIVERE TEST II *****" . sizeof($this->session->userdata());
        
echo "<pre>";
print_r($this->session->all_userdata());
echo "</pre>";

      } catch (Exception $e) {echo 'Excepción Portal Test:',  $e, "\n";}		
}

public function login()
{
try{     
    $this->load->library('form_validation');
    $this->form_validation->set_rules('usuario', 
                                      'Usuario', 
                                      'required|trim|min_length[4]');
    $this->form_validation->set_rules('pwd',
                                      'Contraseña', 
                                      'required|alpha_dash|trim|min_length[3]|'.
                                      'callback_autenticar['.$this->input->post('usuario').']'); //TRIGER PARA AUTENTICAR POSTERIOR A VALIDACION BACKEND
    $this->form_validation->set_message('required', 'Campo %s requerido');
    $this->form_validation->set_message('alpha_dash', 'El campo %s tiene caractres no permitidos');
    $this->form_validation->set_message('min_length', 'El Campo %s debe tener un minimo de %d Caracteres');

    echo json_encode ( array("acceso" => $this->form_validation->run(), "mensaje" =>$this->form_validation->error_array() ) );
    
 }catch (Exception $e) {echo 'Excepción login:',  $e;}
 
}

public function autenticar($pwd, $usuario)
{
try{    
    $datos_sesion = $this->MDL_usuario->validaUsuario($usuario,$pwd);
    if ($datos_sesion == FALSE)							
        {
            $this->form_validation->set_message('autenticar', 'Usuario y/o Contraseña incorrectos');
            return FALSE;
        }	
    else
        {   $util = new Utils();        
            $ds   = $util->edificioDepto($usuario, $datos_sesion[0]['NIVEL_ACCESO'], $this->MDL_usuario);
            
            $ds['cuenta']      = $usuario;
            $ds["nombre"]      = $datos_sesion[0]['NOMBRE'];
            $ds["apellidos"]   = $datos_sesion[0]['APELLIDOS'];
            $ds["nivelacceso"] = $datos_sesion[0]['NIVEL_ACCESO'];

            $this->session->set_userdata('datos_sesion', $ds); 
            $this->session->userdata['last_activity'] = time();            

            unset($util);
            return TRUE;
        }
    }catch (Exception $e) {echo 'Excepción autenticar:',  $e;}
}

public function salir()
{
try{           
    $this->session->sess_destroy();    

    redirect(base_url());
    
    }catch (Exception $e) {echo 'Excepción salir:',  $e;}
}


public function keepAlive()
{
try{
    $this->session->sess_update();        
    $this->session->userdata['last_activity'] = time();
    $datos_sesion = $this->session->userdata('datos_sesion');
    $this->session->set_userdata('datos_sesion', $datos_sesion);
    
    }catch (Exception $e) {echo 'Excepción keepAlive: '.$e;}

echo json_encode( $this->session->sess_expiration );
}

public function retrieveTimeLeft()
{   
try{
    $sesioUpdate = $this->session->sess_expiration;
    
    echo json_encode($sesioUpdate - (time() - $this->session->userdata('last_activity')) );
    
    }catch (Exception $e) {echo 'Excepción keepAlive: '.$e;}
}

public function timeout()
{
try{    
    $data['sesion']   = "Su sesión ha expirado";
    $this->session->sess_destroy();
    $this->session->_sess_gc();

    $this->load->view('login',$data);
    
    }catch (Exception $e) {echo 'Excepción timeout: '.$e;}
}
    

/***************************
 * ARCHIVO UPLOAD AX INI
 **************************/
public function agregaArchivoAX()
{
 try{
    $vs = $this->validaSesion(USER,TRUE);
    if( isset($vs['session']))
    {   echo json_encode ($vs); 
        exit(0);
    }
    if(isset($_FILES["myfile"]))
    {
        $ret = array();		
        $util = new Utils();        
        if(!is_array($_FILES["myfile"]["name"])) //single file
        {
            $fileName =  $util->generaNombre($_FILES["myfile"]["name"]);
            move_uploaded_file($_FILES["myfile"]["tmp_name"], DIR_UPLOAD.$fileName);
            $ret[]= $fileName;
        }
        else  //Multiple files, file[]
        {
            $fileCount = count($_FILES["myfile"]["name"]);
            for($i=0; $i < $fileCount; $i++)
            {                  
                  $fileName = $util->generaNombre($_FILES["myfile"]["name"][$i]);
                  move_uploaded_file($_FILES["myfile"]["tmp_name"][$i], DIR_UPLOAD.$fileName);
                  $ret[]= $fileName;
            }
        }
        echo json_encode($ret);
     }
  } catch (Exception $e) {echo ' agregaArchivoAX Excepción: ',  $e, "\n";}         
}

public function	renombraArchivoAX()
{	
try{
    $vs = $this->validaSesion(USER,TRUE);
    if( isset($vs['session']))
    {   echo json_encode ($vs); 
        exit(0);
    }    

    $extension     = $this -> input -> post('extension');
    $nombreArchivo = $this -> input -> post('nombreArchivo');
    $tipo          = $this -> input -> post('tipo');
    $id_edificio   = $this -> input -> post('id_edificio');

    switch ($tipo) 
    {case "Logo":
         $dir       = DIR_LOGOS;
         $idUnico   = intval(substr(now(), -3));
         $idGasto   = NULL;
         $nombreCVR = $tipo."_".$id_edificio."_" . $idUnico;
     break;
     case "Gasto":
         $dir       = DIR_GASTOS;
         $idUnico   = intval(substr(now(), -6));
         $nombreCVR = $tipo."_".$id_edificio."_" . $idUnico;
         $tiene_iva = $this -> input -> post('tiene_iva');
         $id_gasto  = $this -> input -> post('id_gasto');
         if($id_gasto === NULL || $id_gasto === "")
              { $idGasto = $this->MDL_gastos->reservaIdGasto($idUnico, $id_edificio, $tiene_iva, $nombreCVR, standard_date('DATE_W3C', time())); }
         else { $idGasto = $id_gasto; }
     break;
     case "Doc":
         $dir       = DIR_DOCUMENTS;
         $idUnico   = intval(substr(now(), -5));
         $idGasto   = NULL;
         $nombreCVR = $tipo."_".$id_edificio."_".$idUnico;
     break;     
     case "tbjoMantoA";
     case "tbjoMantoD";
         $dir       = DIR_TBJO_MANTO;
         $idUnico   = intval(substr(now(), -4));
         $idGasto   = NULL;
         $nombreCVR = $tipo."_".$id_edificio."_".$idUnico;
     break;
    }    
    $nombreArchivo = DIR_UPLOAD . $nombreArchivo . "." . $extension;
    $pathNombreCVR = $dir . $id_edificio . '/' . $nombreCVR . "." . $extension;

    if (file_exists($dir.$id_edificio) == FALSE)
       { mkdir($dir.$id_edificio, 0777);       }

    $r = rename ($nombreArchivo, $pathNombreCVR);
    
    echo json_encode (array("pathFile" => $pathNombreCVR,"file" => $nombreCVR, "extension"=>$extension,"id_edificio" => $id_edificio,"error"=>$r,"idGasto"=>$idGasto,"tipo"=>$tipo));

    } catch (Exception $e) {echo 'renombraArchivoAX Excepción: ',  $e, "\n";}	
}

public function	borrarArchivoAX()
{	
try{$vs = $this->validaSesion(USER,TRUE);  
    if( isset($vs['session']))
    {   echo json_encode ($vs); 
        exit(0);
    }
    $extension 	   = $this -> input -> post('extension');
    $nombreArchivo = $this -> input -> post('nombreArchivo');
    $tipo          = $this -> input -> post('tipo');
    $id_edificio   = $this -> input -> post('id_edificio');   

    switch ($tipo) 
    {case "Logo":
         $dir         = DIR_LOGOS;
         $fileDefault = "CONVIVERE.png";
     break;
     case "Gasto":
         $dir         = DIR_GASTOS;
         $fileDefault = NULL;
         $param = array("id_gasto" => $this->input->post('id_gasto'),"id_edificio"=>$id_edificio);
         $datos = array("CONCEPTO" => '', "COMPROBANTE"=>'', "FACTURA"=>'');
         $this->MDL_gastos->update_gastoEvidencia($param,$datos);
     break;
     case "Doc":
          $dir         = DIR_DOCUMENTS;
          $fileDefault = NULL;
     break;
     case "tbjoMantoA";
     case "tbjoMantoD";
         $dir       = DIR_TBJO_MANTO;
         $fileDefault = "logo_edificios/LogoConvivere.jpg";
         $param = array("ID_TRABAJOS" => $this->input->post('id_tbjoManto'),"ID_EDIFICIO"=>$id_edificio);
         $campoFoto = $tipo==="tbjoMantoA"?"EVIDENCIA_ANTES":"EVIDENCIA_DESPUES";
         $datos = array($campoFoto => $fileDefault);
         $this->MDL_gastos->update_tbjo_manto($param,$datos);
     break;
 
    }
    $nombreArchivo = $nombreArchivo . "." . $extension;
    $filePath      = $dir . $id_edificio . '/' . $nombreArchivo;
    $result        = FALSE;    

    if (file_exists($filePath))
        { $result = unlink($filePath); }

    echo json_encode (array("result" => $result, "dir" => $dir, "fileDefault" => $fileDefault, "filePath" => $filePath));

    } catch (Exception $e) {echo 'borraFacturaCargadaAX Excepción: ',  $e, "\n";}	
}
/************************
 * ARCHIVO UPLOAD AX FIN
 ************************/

/************************
 * ARCHIVO paginar AX INI
 ************************/
public function paginarAX()
    {
    try{$vs = $this->validaSesion(USER,TRUE);  
        if( isset($vs['session']))
        {   echo json_encode ($vs); 
            exit(0);
        }
        
        $tipo   = $this->input->post('tipo');//"deptosCuotas";//
        $pagina = $this->input->post('pagina');//"1";//
        
        switch ($tipo) 
            {case "deptosCuotas":$param = array("f1"             => $this->input->post('f1')                
                                               ,"f2"             => $this->input->post('f2')
                                               ,"f3"             => $this->input->post('f3')                              
                                               ,"registrosPagina"=> 10
                                               ,"pagina"         => $pagina
                                               ,"acceso"         => $vs['nivelacceso']
                                               ,"cuenta"         => $vs['cuenta']
                                               ,"torre"          => $this->input->post('torre')
                                               ,"id_edificio"    => $this->input->post('id_edificio')
                                               );
            $registros = $this->MDL_edificio->traePagDeptosFinFiltros($param);             
            break;
            case "deptos":$param =array("f1"              => $this->input->post('f1')                
                                        ,"f2"             => $this->input->post('f2')
                                        ,"f3"             => $this->input->post('f3')                              
                                        ,"registrosPagina"=> 10
                                        ,"pagina"         => $pagina
                                        ,"acceso"         => $vs['nivelacceso']
                                        ,"cuenta"         => $vs['cuenta']
                                        ,"torre"          => $this->input->post('torre')
                                        ,"id_edificio"    => $this->input->post('id_edificio')
                                        );
             $registros = $this->MDL_edificio->traePagDeptosFiltros($param);             
             break;
             case "cuotas":$param =array("f1"             => $this->input->post('f1')                
                                        ,"f2"             => $this->input->post('f2')
                                        ,"f3"             => $this->input->post('f3')
                                        ,"registrosPagina"=> 10
                                        ,"pagina"         => $pagina
                                        ,"acceso"         => $vs['nivelacceso']
                                        ,"cuenta"         => $vs['cuenta']
                                        ,"id_depto"       => ($this->input->post('depto')   ===NULL?$vs['depto']:$this->input->post('depto'))
                                        ,"torre"          => ($this->input->post('torre')   ===NULL?$this->MDL_usuario->traeDeptosUser($vs['cuenta'],"TORRE"):$this->input->post('torre'))
                                        ,"id_edificio"    => ($this->input->post('edificio')===NULL?$vs['edificio']:$this->input->post('edificio'))
                                        );
             $registros = $this->MDL_cuotas->traePagCuotasFiltros($param);             
             break;
             case "gastos":$util  = new Utils();
                           $param = array("f1"             => $util->dateFormat($this->input->post('f1'))
                                         ,"f2"             => $util->dateFormat($this->input->post('f2'))
                                         ,"f3"             => $this->input->post('f3')                              
                                         ,"registrosPagina"=> 10
                                         ,"pagina"         => $pagina
                                         ,"acceso"         => $vs['nivelacceso']
                                         ,"cuenta"         => $vs['cuenta']
                                         ,"id_edificio"    => $vs['edificio']
                                        );
                            $registros = $this->MDL_gastos->traeGastosFiltros($param);
                            unset($util);
             break;
             case "tbjoMto":$util  = new Utils();
                            $param = array("f1"             => $util->dateFormat($this->input->post('f1'))
                                          ,"f2"             => $util->dateFormat($this->input->post('f2'))
                                          ,"f3"             => $this->input->post('f3')                              
                                          ,"registrosPagina"=> 10
                                          ,"pagina"         => $pagina
                                          ,"acceso"         => $vs['nivelacceso']
                                          ,"cuenta"         => $vs['cuenta']
                                          ,"id_edificio"    => $vs['edificio']
                                         );
                            $registros = $this->MDL_gastos->traeTbjoMtoFiltros($param);
                            unset($util);
             break; 
             case "inventario":$util  = new Utils();
                               $param = array("f1"             => $util->dateFormat($this->input->post('f1'))
                                             ,"f2"             => $util->dateFormat($this->input->post('f2'))
                                             ,"f3"             => $this->input->post('f3')                              
                                             ,"registrosPagina"=> 10
                                             ,"pagina"         => $pagina
                                             ,"acceso"         => $vs['nivelacceso']
                                             ,"cuenta"         => $vs['cuenta']
                                             ,"id_edificio"    => $vs['edificio']
                                            );
                                $registros = $this->MDL_edificio->traeInventarioFiltros($param);
                                unset($util);
            break; 
            case "docs":$util  = new Utils();
            $param = array("registrosPagina"=> 10
                          ,"pagina"         => $pagina
                          ,"acceso"         => $vs['nivelacceso']
                          ,"cuenta"         => $vs['cuenta']
                          ,"id_edificio"    => $vs['edificio']
                         );
             $registros = $this->MDL_docs->traeDocsPaginar($param);
             unset($util);
            break;         
            }
        
        echo json_encode ($registros);
        
    } catch (Exception $e) {log_message('error', "Excepción paginarAX: ".$e);}		
    }
/************************
 * ARCHIVO paginar AX FIN
 ************************/

/************************
 * ARCHIVO editarcampotable AX INI
 ************************/
public function editarcampotableAX()
{
try{$ds          = $this -> session -> userdata('datos_sesion');
    $tipo        = $this -> input -> post('tipo');        
    switch ($tipo) 
    {case "deptos": $param =array("id_depto"   => $this -> input -> post('id_depto')
                                 ,"id_edificio"=> $this -> input -> post('id_edificio')
                                 ,"torre"      => $this -> input -> post('torre')
                                 ,"num"        => $this -> input -> post('num')
                                );
                    $this->MDL_edificio->update_depto($param);
                    $campoTable = array("torre"=>$param['torre'],"edificio"=>$param['id_edificio'],"depto"=>$param['id_depto'],"num"=>$param['num']);
     break;
     case "gastos": $param =array("id_gasto"   => ( $this->input->post('id_gasto')===NULL ? "0" : $this->input->post('id_gasto') ) 
                                 ,"id_edificio"=> $this -> input -> post('id_edificio')                                
                                );
                    $util   = new Utils();
                    $gasto  = $this->MDL_gastos->traeGastosbyID($param['id_edificio'], $param['id_gasto']);
                    $cuotas = "";
                    for ($i = 1; $i <= $gasto['GASTO_DURANTE_MESES']; $i++) 
                    {        
                       $fechaC_F = $util->cuotaFija($util->dateFormat($gasto['FECHA_GASTO']), $gasto['GASTO_CADA_DIAS'], $i);
                       $cuotas  .= '<i class="fa fa-calendar" style="font-size:11px;font-style: italic;"> '.$fechaC_F.' $ '.number_format($gasto['TOTAL'], 2, '.', ',').'</i>';
                    }
                    $campoTable = array("gasto"=>$gasto, "dir"=>DIR_GASTOS, "cuotasFijas"=>$cuotas);
                    unset($util);
     break;
     case "tbjoMto": $param = array("id_trabajo" => $this -> input -> post('id_trabajo')
                                   ,"id_edificio"=> $this -> input -> post('id_edificio')                                
                                   );                    
                     $campoTable = $this->MDL_gastos->traeTbjoMtobyID($param['id_edificio'], $param['id_trabajo']);
     break;
     case "inventario": $param = array("id_edificio" => $this -> input -> post('id_edificio')
                                      ,"inv"=> $this -> input -> post('inv')                                
                                      );
                        $campoTable = $this->MDL_edificio->traeInventariobyID($param['id_edificio'], $param['inv']);
     break;
     case "docs": $param = array("id_edificio" => $this -> input -> post('id_edificio')
                                ,"doc"         => $this -> input -> post('doc')                                
                                );
                  $campoTable = $this->MDL_docs->traeDocbyID($param['id_edificio'], $param['doc']);
     break;
    }
    
    echo json_encode ($campoTable);
    
    } catch (Exception $e) {echo ' editarcampotableAX Excepción: ',  $e, "\n";}		
}
/************************
 * ARCHIVO editarcampotable AX FIN
 ************************/

public function traeformAX()
{
try{   $vs = $this->validaSesion(ADMIN,TRUE);
    if( isset($vs['session']))
    {   echo json_encode ($vs); 
        exit(0);
    }
    $campos = array();
    $tipo   = $this -> input -> post('tipo');
    $param  = array("id_depto"   => $this -> input -> post('id_depto')
                   ,"id_edificio"=> $this -> input -> post('id_edificio')
                   ,"torre"      => $this -> input -> post('torre')                                
                   );
    switch ($tipo) 
    {case "deptos":
        $depto   = $this->MDL_edificio->traeDepto($param);
        $detalle = $this->MDL_edificio->traeDeptoDetalle($param);
        $campos = array("depto"=>$depto,"detalle"=>$detalle);
     break;
     case "pagos":
         $util       = new Utils();
         $tiposPago  = "";
         $depto      = $this->MDL_edificio->traeDeptoXEdTor($param['id_edificio'],$param['torre'],$param['id_depto']);         
         $cuotasPen  = $this->MDL_cuotas->traeCuotasPendientes($param['id_edificio'], FALSE, $param['torre'], $param['id_depto']);         
         $cuotasSel  = $util->poblarSelectCuotasP($cuotasPen);            
         $referencia = $util->generaReferenciaCuota($depto, $cuotasPen );
         $imp        = $util->traeImporteCuota($depto, date("Y-m-d"), $cuotasPen);
         $tipoPagoSeleccionado = CARD;
         foreach($this->MDL_finanzas->poblarRadioButtonTiposPago("tipo_pago") as $rd) { $tiposPago = $tiposPago."<label class='radio'><input type='radio' name='tiposPago' class='radioTipoPago' ".($tipoPagoSeleccionado==$rd['value']?"checked":"")." value='".$rd['value']."'><i></i>".$rd['label']."</label>"; }
          
         $campos = array("depto"=>$depto,"detalle"=>array("referencia"=>$referencia,"imp"=>$imp,"cuotas"=>$cuotasSel,"tiposPagoRadioButton"=>$tiposPago));
         
         unset($util); 
     break;
    }    

    echo json_encode ($campos);

    } catch (Exception $e) {echo ' traeEdificioAX Excepción: ',  $e, "\n";}	
}//traeEdificioAX


/************************
 * ARCHIVO guardausrdeptoAX AX INI
 ************************/
public function guardausrdeptoAX()
{
try{$vs = $this->validaSesion(ADMIN,TRUE);
    if( isset($vs['session']))
    {   echo json_encode ($vs); 
        exit(0);
    }
    
    $util = new Utils();
    $ds = $this -> session -> userdata('datos_sesion');    
            
    $param = array("CUENTA"        => $this -> input -> post('cuenta')
                  ,"NOMBRE"        => $this -> input -> post('nombre')
                  ,"APELLIDOS"     => $this -> input -> post('apellido')
                  ,"CONTRASENA"    => $util-> generateRandomString(3).mt_rand(1,999)
                  ,"NIVEL_ACCESO"  => USER
                  ,"TELEFONO_FIJO" => $this -> input -> post('telefono') 
            	  ,"CELULAR"       => $this -> input -> post('celular')             	  
                 );
    $this->MDL_usuario->insert_user($param);

    echo json_encode ($param);
    
    } catch (Exception $e) {echo ' guardausrdeptoAX Excepción: ',  $e, "\n";}		
}
/************************
 * ARCHIVO guardadeptoAX AX FIN
 ************************/
public function download($dir1, $dir2, $dir3, $dir4, $filename)
{
    $this->load->helper('download');			

    $data = file_get_contents($dir1."/".$dir2."/".$dir3."/".$dir4."/".$filename);

   force_download($filename, $data);		
}

public function downloadExcel($dir,$filename)
{
    $this->load->helper('download');			

    $data = file_get_contents($dir."/".$filename);

    force_download($filename, $data);		
}

public function downloadGasto($edificio, $filename)
{
    $this->load->helper('download');			

    $data = file_get_contents(DIR_GASTOS.$edificio."/".$filename);

    force_download($filename, $data);		
}

public function downloadTbjoManto($dir, $edificio, $filename)
{
    $this->load->helper('download');			

    $data = file_get_contents($dir."/".$edificio."/".$filename);

    force_download($filename, $data);
}

public function dataForChartAX()
{try{$vs = $this->validaSesion(USER,TRUE);        
    if( isset($vs['session']))
    {   echo json_encode ($vs); 
        exit(0);
    }    
    $util      = new Utils();
    $data      = array();
    $title     = "";
    $subtitulo = "";

    $tipo       = $this -> input -> post('type');//"SE";//
    //$typeParam  = $this -> input -> post('typeParam');
    //$paramDe    = $this->utils->dateFormat($this -> input -> post('paramDe'));//$this->utils->dateFormat("01/08/2017");//
    //$paramHasta = $this->utils->dateFormat($this -> input -> post('paramHasta'));//$this->utils->dateFormat("31/08/2017");//
    
    switch ($tipo) 
    {
    case "AN":
         //TO DO devolver los anuncios
         $title     = "";
         $subtitulo = "";
    break;
    case "TM":
         //TO DO devolver los trabajos para GANTT
         $title     = "Trabajos de Mantenimiento programados";
         $subtitulo = "";
         $edificio  = $this->MDL_edificio->traeEdificio($vs['edificio']);
         $dataChart = $this->MDL_finanzas->chartGanttTM($vs['edificio'], date("Y"), $util);
         $data      = array("serieName"  => $edificio[0]['NOMBRE'] ." trabajos ".date("Y")
                           ,"categories" => $dataChart['categories']
                           ,"seriesData" => $dataChart['data']
                           );                  
    break;
    case "PAG":
         //TO DO devolver las cuotas y la meta
         $title     = "";
         $subtitulo = "";
    break;
    case "GAS":
    case "GAS2":
         $title     = $util->MesAñoActual();
         $subtitulo = date("Y")." - ". date("m");
         $dataChart = $this->MDL_finanzas->chartGaugeCuotas($vs['edificio'], date("Y"), date("m") );
         $data      = array("max"     => $dataChart['max']
                           ,"current" => $dataChart['current']
                           );
    break;
    case "RE":
         $meses       = $util->trae6MesesAnt(date("Y-m-d"));
         $mesesLLaves = $util->trae6MesesAntLlaves(date("Y-m-d"));
         $title       = "Balance Convivere";         
         $subtitulo   = "Últimos 6 meses ".$meses[0]."-".$meses[5]." ".date("Y");
         $dataChart   = $this->MDL_finanzas->chartResumenEjecutivo($vs['edificio'], $mesesLLaves);
         $data        = array( "series"     => $dataChart
                              ,"categories" => $meses
                             );
     break;
    }
    
    unset($util);
    
    echo json_encode(array("data"=>$data, "title"=>$title, "subtitulo"=>$subtitulo) );

 } catch (Exception $e) {echo 'dataForChartAX: ',  $e->getMessage(), "\n";}	
}//dataForChartAX


/************************
 * pwdForgotAX AX INI
 ************************/
public function pwdForgotAX()
{
try{
    $usuario = $this->input->post('userPwdForgot');
    $userPwd = $this->MDL_usuario->traeUsuarioForgotPWD($usuario);
    $confirm = "";
    $class   = "";

    if( $userPwd === NULL )
    {   $confirm = "<img src=\"".base_url()."style/images/close.png\"> No se encontraron registros con el correo ".$usuario; 
        $class   = "msjErrMail";
    }
    else
    {   $this->load->library('Cartero');
        $cartero = new Cartero();
        $cartero->clearEmail();  
        $cartero->setfromName("Soluciones Convivere"   );
        $cartero->setto      ($usuario                 );
        $cartero->setsubject ("Acceso al Portal Convivere");
        $cartero->setmessage ("<img src=\"".base_url()."style/images/headerCVR.jpg\">
                               <br><br>Estimado Condomino, <br><br> El equipo de Convivere le hace llegar sus credeneciales de acceso al portal.
                               <br><br> Usuario: <strong>".$usuario."</strong>
                               <br><br> Contraseña: <strong>".$userPwd['CONTRASENA']."</strong>
                               <br><br> Saludos 
                               <br><br>
                               <img src=\"".base_url()."style/images/footerCVR.jpg\">
                              ");
        $cartero->mandaCorreo();    
        unset($cartero);
        $confirm = "<img src=\"".base_url()."style/images/check.png\"> La contraseña ha sido enviada al correo.";
        $class   = "msjOkMail";
    }
        
    echo json_encode ( array("confirm" => $confirm, "class" => $class) );
    
    } catch (Exception $e) {echo ' pwdForgotAX Excepción: ',  $e, "\n";}		
}
/************************
 * pwdForgotAX AX FIN
 ************************/

 /************************
 * pwdForgotAX AX INI
 ************************/
public function signUpAX()
{
try{
    $dir             = $this->input->post('dir');
    $depto           = $this->input->post('depto');
    $coment          = $this->input->post('coment');
    $tel_contacto    = $this->input->post('tel_contacto');
    $correo_contacto = $this->input->post('correo_contacto');
    $nombre_contacto = $this->input->post('nombre_contacto');
    
    $this->load->library('Cartero');
    $cartero = new Cartero();
    $cartero->clearEmail();  
    $cartero->setfromName("Soluciones Convivere"   );
    $cartero->setto      ("soluciones@convivere.mx");
    $cartero->setsubject ("Solicitud de acceso al Portal Convivere");
    $cartero->setmessage ("<img src=\"".base_url()."style/images/headerCVR.jpg\">
                            <br><br>Estimado equipo Conivevere, <br><br> El usuario <strong>".$nombre_contacto."</strong> con teléfono <strong>".$tel_contacto."</strong> y correo <strong>".$correo_contacto."</strong>. Proporciona los siguientes datos:
                            <br><br> Edificio: <strong>".$dir."</strong>
                            <br><br> Depto: <strong>".$depto."</strong>
                            <br><br> Comentarios: <strong>".$tel_contacto."</strong>
                            <br><br> Para contar con un acceso al Portal favor de revisar la solicitud.
                            <br><br> Saludos 
                            <br><br>
                            <img src=\"".base_url()."style/images/footerCVR.jpg\">
                            ");
    $cartero->mandaCorreo();

    $cartero = new Cartero();
    $cartero->clearEmail();  
    $cartero->setfromName("Soluciones Convivere"   );
    $cartero->setto      ($correo_contacto         );
    $cartero->setsubject ("Solicitud de acceso al Portal Convivere");
    $cartero->setmessage ("Estimado Condomino, <br><br><br> Hemos recibido su información a la brevedad nos comunicaremos con usted para darle respuesta a su solicitud.                            
                            <br><br><br> Saludos 
                            ");
    $cartero->mandaCorreo();
    unset($cartero);
    $confirm = "<img src=\"".base_url()."style/images/check.png\"> Hemos recibido la información, en breve nos comunicaremos.";
    $class   = "msjOkMail";    
        
    echo json_encode ( array("confirm" => $confirm, "class" => $class) );
    
    } catch (Exception $e) {echo ' pwdForgotAX Excepción: ',  $e, "\n";}		
}
/************************
 * pwdForgotAX AX FIN
 ************************/

/************************
 * sendEdoCtaAX AX INI
 ************************/
public function sendEdoCtaAX()
{
try{$vs = $this->validaSesion(ADMIN,TRUE);        
    if( isset($vs['session']))
    {   echo json_encode ($vs); 
        exit(0);
    }
    $this->load->library('Cartero');

    $util     = new Utils();
    $pathPDF  = $this->input->post('pathPDF');
    $mesEC    = $this->input->post('mesEC');
    $edificio = $this->MDL_edificio->traeEdificio($vs['edificio']);

    ini_set('max_execution_time', 300);
    foreach($this->MDL_edificio->traeCondominosAX($vs['edificio']) as $c)
    {   $data = array("depto"=>$c, "admin"=>$vs,"titulo"=>"Estado de Cuenta ".$mesEC." Convivere" );
        $html = $this->load->view('portal/vw_formato_aviso_edocta', $util->getAvisoEdoCtaData($data, $edificio, FALSE), TRUE);

        $cartero = new Cartero();
        $cartero->clearEmail();        
        $cartero->setfromName("Soluciones Convivere");
        $cartero->setto      ($c['CUENTA']          );
        $cartero->setsubject ($data['titulo']       );        
        $cartero->setmessage ($html                 );
        $cartero->setadjunto (FCPATH . $pathPDF     );
        $cartero->mandaCorreo();
        $cartero->clearEmail();
        unset($cartero);
    }
    ini_set('max_execution_time', 30);
    unset($util);
    $confirm = "";

    echo json_encode ( array("confirm" => $confirm) );

    } catch (Exception $e) { log_message('error', "Excepción pwdForgotAX: ".$e); }
}
/************************
 * sendEdoCtaAX AX FIN
 ************************/


}//CONTROLLER
