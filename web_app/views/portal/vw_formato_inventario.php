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
      if($viewPDF===TRUE)
      {
      echo   '<div class="footer">'.
              '<div style="text-align: center;"></div>'.
              '<div style="text-align: center;"> <div class="page-number"></div> <img src="style/images/logo/footerCVR.jpg" width="800px" height="65px"></div>'.
             '</div>';
      }
      
      include("webpart_header_pdf.php");
      
      echo   br(2);
      echo   '<div><table align="center" class="edoCta">'.
              '<tbody>'.
                '<tr> <td width="70%"></td><td width="30%" class="even_row"><div class="recuadroGris"  style="text-align: center;"><strong>'.$hoy.'</strong> </div> </td> </tr>'.
              '</tbody>'.
              '</table></div>';
      echo   br(2);
      echo   '<p><table align="center" class="edoCta">'.
                '<thead>'.
                    '<tr> <th width="40%" class="fondoGrisCentro"><strong>Activo</strong>    </th>'.
                         '<th width="20%" class="fondoGrisCentro"><strong>Cantidad</strong>  </th>'.
                         '<th width="20%" class="fondoGrisCentro"><strong>Foto</strong>  </th>'.
                         '<th width="20%" class="fondoGrisCentro"><strong>Creado el</strong> </th> </tr>'.
                '</thead>'.
              '<tbody>';
                $x = 0;
                foreach ($activos as $a)
                       {  $x++;
                          $classFondo = ($x % 2)?"":"fondoBackGris";
                          echo '<tr class="'.$classFondo.'">'.
                                   '<td width="40%" valign="middle" class="left" style="font-size: 9pt;">'.$a['ACTIVO'].' '.$a['DESCRIPCION'].'</td>'.
                                   '<td width="20%" valign="middle" class="center">'. number_format($a['CANTIDAD'], 0, '.', ',').'</td>'.
                                   '<td width="20%" valign="middle" class="center">'.($a['FOTO']===NULL?'':$a['FOTO']).'</td>'.
                                   '<td width="20%" valign="middle" class="center">'. $a['FECHA_ALTA'].'</td>'.
                              '</tr>';
                       }     
     echo   '<tr> <td width="100%" colspan="4"> <p>'.nbs().'</p> </td> </tr>';     
     echo   '<tr> <td width="100%" colspan="4"><div class="recuadroGris" style="text-align: center;"> <strong>Administración CONVIVERE</strong> </div> </td> </tr>';
     echo   '<tr> <td width="100%" colspan="4"> <p style="text-align: justify;">Cualquier duda o comentario puede ponerse en contacto al '.CALL_CENTER.' o en la dirección de correo electrónico '.CORREO_OFICIAL.'.</p> </td> </tr>';
     echo   '</tbody>'.
            '</table></p>';

      echo    '</body>';      
      echo   '</html>'; 

