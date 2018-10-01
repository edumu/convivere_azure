<?php
      echo "<html>";
      echo "<head>";
$meta = array( array('name' => 'author'      , 'content' => 'DATARE'),
               array('name' => 'Content-type', 'content' => 'text/html; charset=UTF-8', 'type' => 'equiv')
             );
echo meta($meta);
echo '<link rel="STYLESHEET" href="style/css/ems/cvr_formatos_pdf.css" type="text/css">';
echo '</head>'; 
      echo   '<body marginwidth="0" marginheight="0" class="reciboBody">';
      echo   '<div class="footerMediaCarta">';
      echo   '<div style="text-align: center; padding: 0px; font-size: 7pt;">'.CALL_CENTER.' '.CORREO_OFICIAL.'</div>';
      echo   '<div class="finHoja"></div>';
      echo   '</div>';
      
      include("webpart_header_pdf.php"); 
      
      echo   br(2);      
      echo   '<div style="width: 100%;">';      
      echo   '<table align="center" class="edoCta" style="width: 100%;">';
      echo   '<tbody>';
      echo   '  <tr> <td width="2%" class="fondoColor"></td> <td width="98%" class="fondoGrisCentro"><strong>Folio</strong> </td> </tr>';
      echo   '  <tr> <td width="2%" class="fondoColor"></td> <td width="98%" class="even_row"  style="text-align: center;"> '.$recibo['referencia'].'   </td> </tr>                ';
      echo   '</tbody>';
      echo   '</table>';
      echo   br(4);
      echo   '<table align="center" class="edoCta" style="width: 100%;">';
      echo   '<tbody>';
      echo   '  <tr> <td width="2%" class="fondoColor"></td> <td width="98%" class="fondoGrisCentro" colspan="2"><strong>Lugar y Fecha de Expedición</strong> </td> </tr>';
      echo   '  <tr> <td width="2%" class="fondoColor"></td> <td width="48%" class="even_row" > '.$dirEdificio  . '   </td> ';
      echo   '       <td width="50%" class="even_row"> '.$recibo['hoy']['dateStr']. '   </td> ';
      echo   '  </tr>';             
      echo   '</tbody>';
      echo   '</table>';
      echo   br(4);
      echo   ' </div>';
      echo   br(4);
      echo   '<div class="recuadroGris" style="width: 100%;">';
      echo   '<table align="center" class="edoCta" style="width: 100%;">';
      echo   ' <tbody>';
      echo   '  <tr> <td width="2%" class="fondoColor"></td> <td width="98%" class="fondoGrisCentro"><p><strong>Pago de Mantenimiento '.$recibo['mescuotaEspanol'].' del departamento '.$recibo['depto'][0]['TORRE'].' '.$recibo['depto'][0]['NUMERACION'].'</strong> </p> </td> </tr>';
      echo   '  <tr> <td width="2%" class="fondoColor"></td> <td width="98%" class="even_row"><p> <strong>Importe pagado '.$recibo['imp']['importeStr'].' MXN </strong>'.br().$recibo['importeEnLetras'].'</p>  </td> </tr>';
      echo   '</tbody>';
      echo   '</table>';
      echo   '</div>';              
      echo   br(6);      
      echo   ' </body>';      
      echo   '</html>'; 

