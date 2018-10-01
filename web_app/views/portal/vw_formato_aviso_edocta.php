<?php
echo "<html>";
echo "<head>";
$meta = array( array('name' => 'author', 'content' => 'DATARE'),
               array('name' => 'Content-type', 'content' => 'text/html; charset=UTF-8', 'type' => 'equiv')
             );
echo meta($meta);
echo '<link rel="STYLESHEET" href="'.($viewPDF===TRUE?"":base_url()).'style/css/ems/cvr_formatos_pdf.css" type="text/css">';
echo '</head>';
      echo   '<body marginwidth="0" marginheight="0" class="pdfFondo">';     
      
      include("webpart_header_pdf.php");
      
      echo   br(2);
      echo   '<div><table align="center" class="edoCta">'.
              '<tbody>'.
                '<tr> <td width="70%"></td><td width="30%" class="even_row">'.br().'<strong>'.$hoy.'</strong> </td> </tr>'.
              '</tbody>'.
              '</table></div>';
      echo   '<div>'.
                '<p><strong>Estimado Condomino '.$edocta['depto']['CONDOMINO'].' '.$edocta['depto']['DEPTO'].',</strong></p>'.
                '<p style="text-align: justify;">El siguiente correo es para hacerle llegar el estado de cuenta del edificio </p>'.
                '<br><p style="text-align: justify;"> La información se envía en un archivo adjunto en formato PDF, también si gusta puede obtener más detalle en <a href="'.base_url().'">CONVIVERE</a> </p>'.
                '<br><p style="text-align: justify;"> Sin más por el momento reciba un cordial saludos y quedo a sus ordenes</p>'.
                '<br><p style="text-align: justify;">Atte.</p>'.
                '<br><p style="text-align: justify;"><um>'.$edocta['admin']['nombre'].' '.$edocta['admin']['apellidos'].'</um></p>'.
                '</p>'.
                '<br>'.
              '</div>';
     echo   '<p><table align="center" class="edoCta">'.
                '<thead>'.
                    '<tr> <th width="50%" colspan="2"><br> </th> </tr>'.
                '</thead>'.
              '<tbody>';               
     echo   '<tr> <td width="100%" colspan="2"> <p style="text-align: justify;">Si tiene cualquier duda al respecto puede ponerse en contacto al '.CALL_CENTER.' o en la dirección de correo electrónico '.CORREO_OFICIAL.'.</p> </td> </tr>';
     echo   '<tr> <td width="100%" colspan="2"> <p>'.nbs().'</p> </td> </tr>';
     echo   '<tr> <td width="100%" colspan="2"> <p>Sin otro particular, reciba un cordial saludo.</p> </td> </tr>';
     echo   '<tr> <td width="100%" colspan="2"> <p>'.nbs().'</p> </td> </tr>';
     echo   '<tr> <td width="100%" colspan="2"><div class="recuadroGris" style="text-align: center;"> <strong>Administración CONVIVERE</strong> </div> </td> </tr>';
     echo   '</tbody>'.
            '</table></p>';

      echo    '</body>';      
      echo   '</html>'; 

