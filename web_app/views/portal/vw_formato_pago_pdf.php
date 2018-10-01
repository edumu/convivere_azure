<?php
      echo "<html>";
      echo  "<head>";
$meta = array( array('name' => 'author'      , 'content' => 'DATARE'),
               array('name' => 'Content-type', 'content' => 'text/html; charset=UTF-8', 'type' => 'equiv')
             );
echo meta($meta);
echo '<link rel="STYLESHEET" href="'.($viewPDF===TRUE?"":base_url()).'style/css/ems/cvr_formatos_pdf.css" type="text/css">';
echo '</head>'; 
      echo   '<body marginwidth="0" marginheight="0">';
      
      include("webpart_header_pdf.php"  );
      include("webpart_formato_pago.php");
      
      echo   '<div id="colapsarBanco">';
      echo   '<table align="center" style="width: 90%;" >';
      echo   '<tbody>';
      echo   '<tr>';
      echo   ' <td width="2%" class="fondoColor"></td>';
      echo   ' <td width="48%" class="negritas"><strong>'.$section3_1.'</strong></td>';
      echo   ' <td width="2%"></td>';
      echo   ' <td width="48%" class="negritas"><strong>'.$section3_2.'</strong></td>';
      echo   ' </tr>';
      echo   ' <tr>';
      echo   ' <td width="2%"></td>';
      echo   ' <td width="48%" class="detalle" style="font-size: 9pt;">'.$section4_1.'</td>';
      echo   ' <td width="2%"></td>';
      echo   ' <td width="48%" class="detalle" style="font-size: 9pt;">'.$section4_2.'</td>';
      echo   ' </tr>';
      echo   ' </tbody>';
      echo   '</table>';
      echo   ' </div>';
      
      echo   ' <table align="center" style="width: 90%;" >';
      echo   '<tbody>';
      echo   ' <tr><td width="2%"></td><td width="100%" colspan="3" class="center"><p>'.$section5_1.'</p></td></tr>';
      echo   ' </tbody>';
      echo   '</table>  ';
      echo   ' </body>';
      echo   '</html>'; 

