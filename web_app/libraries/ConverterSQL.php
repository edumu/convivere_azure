<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cartero
 *
 * @author edumu
 */
class ConverterSQL {
    
    var $PREFIX_DB_CVR;
    var $INT_FIELD_BD;
    var $END_FIELD_BD;    
    
function __construct()
{
    switch (ENVIRONMENT)
    {
    case 'development':
        $this->PREFIX_DB_CVR = 'data_convivere.convivere.';
        $this->INT_FIELD_BD  = '[';
        $this->END_FIELD_BD  = ']';		
	break;

    case 'testing':
        $this->PREFIX_DB_CVR = '';
        $this->INT_FIELD_BD  = '`';
        $this->END_FIELD_BD  = '`';
    case 'production':	    
        $this->PREFIX_DB_CVR = 'data_convivere.convivere.';
        $this->INT_FIELD_BD  = '[';
        $this->END_FIELD_BD  = ']';		
	break;
    }// switch
}//CONSTUCTOR

public function setPREFIX_DB_CVR($param)
{ $this->PREFIX_DB_CVR = $param;       }

public function getPREFIX_DB_CVR()
{ return $this->PREFIX_DB_CVR;   }

public function setINT_FIELD_BD($param)
{ $this->INT_FIELD_BD = $param;       }

public function getINT_FIELD_BD()
{ return $this->INT_FIELD_BD;   }

public function setEND_FIELD_BD($param)
{ $this->END_FIELD_BD = $param;       }

public function getEND_FIELD_BD()
{ return $this->END_FIELD_BD;   }



public function agregaFechaAlta($db)
{
    if($this->PREFIX_DB_CVR === "")
        { $db->select("DATE_FORMAT(".$this->INT_FIELD_BD."E".$this->END_FIELD_BD.".".$this->INT_FIELD_BD."FECHA_ALTA".$this->END_FIELD_BD.",'%Y-%m') as FECHA_ALTA", FALSE); }
    else          
        { $db->select("FORMAT(E.FECHA_ALTA,'yyyy-MM') as FECHA_ALTA", FALSE);  }        
}

public function agregaFechaAltaSQL()
{
    if($this->PREFIX_DB_CVR === "")
        { return ", DATE_FORMAT(".$this->INT_FIELD_BD."E".$this->END_FIELD_BD.".".$this->INT_FIELD_BD."FECHA_ALTA".$this->END_FIELD_BD.",'%Y-%m') as FECHA_ALTA"; }
    else          
        { return ", FORMAT(E.FECHA_ALTA,'yyyy-MM') as FECHA_ALTA";  }
}

public function agregaPrimeraCuotaEdificio($db)
{
    if($this->PREFIX_DB_CVR === "")
        { $db->select("CONCAT(DATE_FORMAT(DATE_ADD(".INT_FIELD_BD."E".END_FIELD_BD.".".INT_FIELD_BD."FECHA_ALTA".END_FIELD_BD.", INTERVAL 1 MONTH),'%Y-%m'),'-',".INT_FIELD_BD."E".END_FIELD_BD.".".INT_FIELD_BD."DIA_CORTE".END_FIELD_BD.") AS PRIMERA_CUOTA", FALSE); }
    else        
        { $db->select("CONCAT(FORMAT(DATEADD(month, 1, ".INT_FIELD_BD."E".END_FIELD_BD.".".INT_FIELD_BD."FECHA_ALTA".END_FIELD_BD."), 'yyyy-MM'),'-',".INT_FIELD_BD."E".END_FIELD_BD.".".INT_FIELD_BD."DIA_CORTE".END_FIELD_BD.") AS PRIMERA_CUOTA", FALSE);            }        
}

public function agregaAnticipoImporteEdoCta($db)
{
    if($this->PREFIX_DB_CVR === "")
        { $db->select("(IF(TIPO_ANT='1', ANTICIPO, (COSTO * (ANTICIPO / 100)) )) AS IMPORTE, CONCAT('ANTICIPO ', TRABAJO,' ', DESCRIPCION) AS CONCEPTO"); }
    else          
        { $db->select("(CASE WHEN TIPO_ANT='1' THEN ANTICIPO ELSE (COSTO * (ANTICIPO / 100)) END) AS IMPORTE, CONCAT('ANTICIPO ', TRABAJO,' ', DESCRIPCION) AS CONCEPTO");  }        
}


public function agregaLiquidacionImporteEdoCta($db)
{
    if($this->PREFIX_DB_CVR === "")
        { $db->select("(IF(TIPO_ANT='1', (COSTO - ANTICIPO), COSTO - (COSTO * (ANTICIPO / 100)) )) AS IMPORTE, CONCAT('LIQUIDACIÓN ',TRABAJO) AS CONCEPTO"); }
    else          
        { $db->select("( CASE WHEN TIPO_ANT='1' THEN (COSTO - ANTICIPO) ELSE COSTO - (COSTO * (ANTICIPO / 100)) END) AS IMPORTE, CONCAT('LIQUIDACIÓN ',TRABAJO) AS CONCEPTO"); }        
}

public function queryCuotasPendientes($db, $id_edificio, $agruparPorDepto, $torre, $id_depto)
{
    if($this->PREFIX_DB_CVR === "")
        {   $whereTorre      = ($torre    === NULL?"":" AND `D`.`TORRE`    = '".$torre."'");
            $whereDepto      = ($id_depto === NULL?"":" AND `D`.`ID_DEPTO` = ".$id_depto  );
            $groupByDepto    = $agruparPorDepto?"":", `FC`.`CUOTAS`";
            $selGroupByDepto = $agruparPorDepto?"":", `FC`.`CUOTAS` AS CUOTA_PENDIENTE";
            
            $db->query("SET @idEdificio   = ".$id_edificio);
            $db->query("SET @primeraCuota = (SELECT CONCAT(DATE_FORMAT(DATE_ADD(`ED`.`FECHA_ALTA`, INTERVAL 1 MONTH),'%Y-%m'),'-',`ED`.`DIA_CORTE`) AS PRIMERA_CUOTA 
                                                    FROM EDIFICIOS `ED` WHERE `ED`.`ID_EDIFICIO` = @idEdificio)");
            $db->query("SET @ultimaCuota  = (SELECT IF(`ED`.`DIA_CORTE` > CAST(DATE_FORMAT(NOW(),'%d') AS INT)
                                                                , DATE_FORMAT(LAST_DAY(DATE_ADD(NOW(), INTERVAL -1 MONTH)),'%Y-%m-%d')
                                                                , DATE_FORMAT(NOW(),'%Y-%m-%d')
                                                            ) AS ULTIMA_CUOTA
                                                    FROM EDIFICIOS `ED` WHERE `ED`.`ID_EDIFICIO` = @idEdificio)");
            $db->query("SET @months       = -1                       ");
            $cuotasQRY = $db->query("SELECT `D`.`ID_DEPTO`, `U`.`CUENTA`, CONCAT('', COALESCE(`U`.`NOMBRE`,''), ' ', COALESCE(`U`.`APELLIDOS`,'')), `D`.`TORRE`, `D`.`NUMERACION` AS DEPTO$selGroupByDepto
                                     FROM `DEPARTAMENTOS` `D` 
                                     INNER JOIN `DETALLE_DEPTO` `DD` ON `D`.`ID_DEPTO`  = `DD`.`ID_DEPTO` AND `D`.`TORRE` = `DD`.`TORRE` 
                                     INNER JOIN `USUARIOS` `U` ON `DD`.`CUENTA`  = `U`.`CUENTA`
                                     INNER JOIN `EDIFICIOS` `E` ON `E`.`ID_EDIFICIO` = `D`.`ID_EDIFICIO`
                                     INNER JOIN  (SELECT FC.CUOTAS, FC.ID_EDIFICIO
                                                  FROM
                                                    ( SELECT CAST( DATE_FORMAT(date_range,'%Y-%m') AS CHAR) AS CUOTAS, @idEdificio AS ID_EDIFICIO
                                                        FROM (SELECT (DATE_ADD(@primeraCuota, INTERVAL(@months := @months +1) month)) AS date_range
                                                            FROM mysql.help_topic a limit 0,1000
                                                            ) a
                                                        WHERE a.date_range BETWEEN @primeraCuota and LAST_DAY(@ultimaCuota)
                                                    ) FC
                                                ) `FC` ON `E`.`ID_EDIFICIO` = `FC`.`ID_EDIFICIO` AND `FC`.`CUOTAS` IS NOT NULL
                                     LEFT OUTER JOIN `CUOTAS` `C` ON  `C`.`ID_DEPTO` = `D`.`ID_DEPTO` AND `C`.`ID_EDIFICIO` = `D`.`ID_EDIFICIO` 
                                                                AND `C`.`STATUS` = 'PAGADA' AND `C`.`CUOTA_DEL_MES` = `FC`.`CUOTAS`
                                     WHERE `E`.`ID_EDIFICIO` = @idEdificio AND `C`.`CUOTA_DEL_MES` IS NULL $whereTorre $whereDepto
                                     GROUP BY `D`.`ID_DEPTO`, `U`.`CUENTA`, CONCAT('', COALESCE(`U`.`NOMBRE`,''), ' ', COALESCE(`U`.`APELLIDOS`,'')), `D`.`TORRE`, `D`.`NUMERACION`$groupByDepto
                                     ORDER BY `D`.`TORRE`, `D`.`NUMERACION`$groupByDepto"
                                    , FALSE);

        }
    else        
        {   $whereTorre      = ($torre    === NULL?"":" AND [D].[TORRE]    = '".$torre."'");
            $whereDepto      = ($id_depto === NULL?"":" AND [D].[ID_DEPTO] = ".$id_depto  );
            $groupByDepto    = $agruparPorDepto?"":", [FC].[CUOTAS]";
            $selGroupByDepto = $agruparPorDepto?"":", [FC].[CUOTAS] AS CUOTA_PENDIENTE";
                        
            $cuotasQRY = $db->query("DECLARE @idEdificio   int      = ".$id_edificio."; 
                                     DECLARE @primeraCuota Datetime = (SELECT CAST(CONCAT(FORMAT(DATEADD(month, 1, [ED].[FECHA_ALTA]), 'yyyy-MM'),'-',[ED].[DIA_CORTE]) AS Datetime) AS PRIMERA_CUOTA FROM ".$this->PREFIX_DB_CVR."EDIFICIOS [ED] WHERE [ED].[ID_EDIFICIO] = @idEdificio); 
                                     DECLARE @ultimaCuota  Datetime = (SELECT CAST((CASE WHEN [DIA_CORTE] > CAST(FORMAT(GETDATE(),'dd') AS INT) THEN FORMAT(EOMONTH(DATEADD(month, 1, GETDATE())),'yyyy-MM-dd') ELSE FORMAT(GETDATE(),'yyyy-MM-dd') END ) AS Datetime) AS ULTIMA_CUOTA FROM ".$this->PREFIX_DB_CVR."EDIFICIOS [ED] WHERE [ED].[ID_EDIFICIO] = @idEdificio);
                                     With dt As
                                     (
                                        Select @primeraCuota As [TheDate]
                                        Union All
                                        Select DateAdd(m, 1, TheDate) From dt Where [TheDate] < @ultimaCuota
                                     )
                                    SELECT [D].[ID_DEPTO], U.CUENTA, CONCAT('', COALESCE(U.NOMBRE,''), ' ', COALESCE(U.APELLIDOS,'')) AS CONDOMINO , [D].[TORRE], [D].[NUMERACION] AS DEPTO$selGroupByDepto
                                    FROM ".$this->PREFIX_DB_CVR."DEPARTAMENTOS [D] 
                                    INNER JOIN ".$this->PREFIX_DB_CVR."DETALLE_DEPTO DD ON D.ID_DEPTO  = DD.ID_DEPTO AND D.TORRE = DD.TORRE 
                                    INNER JOIN ".$this->PREFIX_DB_CVR."USUARIOS U ON DD.CUENTA  = U.CUENTA
                                    INNER JOIN  ".$this->PREFIX_DB_CVR."EDIFICIOS [E] ON [E].[ID_EDIFICIO] = [D].[ID_EDIFICIO]
                                    INNER JOIN  (
                                                    SELECT FORMAT([TheDate],'yyyy-MM') as CUOTAS, @idEdificio AS ID_EDIFICIO
                                                    FROM dt 
                                                ) [FC] ON [E].[ID_EDIFICIO] = [FC].[ID_EDIFICIO] AND [FC].[CUOTAS] IS NOT NULL
                                    LEFT OUTER JOIN ".$this->PREFIX_DB_CVR."CUOTAS [C] ON  [C].[ID_DEPTO] = [D].[ID_DEPTO] AND [C].[ID_EDIFICIO] = [D].[ID_EDIFICIO] AND [C].[STATUS] = 'PAGADA' AND [C].[CUOTA_DEL_MES] = [FC].[CUOTAS]
                                    WHERE    [E].[ID_EDIFICIO] = @idEdificio AND [C].[CUOTA_DEL_MES] IS NULL $whereTorre $whereDepto
                                    GROUP BY [D].[ID_DEPTO], U.CUENTA, CONCAT('', COALESCE(U.NOMBRE,''), ' ', COALESCE(U.APELLIDOS,'')), [D].[TORRE], [D].[NUMERACION]$groupByDepto
                                    ORDER BY [D].[TORRE]   , [D].[NUMERACION]$groupByDepto"
                                    , FALSE);

        }
    return $cuotasQRY;
}


public function queryTotCuotasTasa($db, $id_edificio, $id_depto)
{
    if($this->PREFIX_DB_CVR === "")
        {   
            $queryTotDeptos = $id_depto===NULL?"(SELECT `ED`.`NUM_TORRES` * `ED`.`NUM_VIVIENDAS` FROM EDIFICIOS `ED` WHERE `ED`.`ID_EDIFICIO` = @idEdificio)":"1";
            $whereDeptos    = $id_depto===NULL?"" :" AND `C`.`ID_DEPTO` =".$id_depto;

            $db->query("SET @idEdificio   = ".$id_edificio);
            $db->query("SET @primeraCuota = (SELECT CONCAT(DATE_FORMAT(DATE_ADD(`ED`.`FECHA_ALTA`, INTERVAL 1 MONTH),'%Y-%m'),'-',`ED`.`DIA_CORTE`) AS PRIMERA_CUOTA 
                                                   FROM EDIFICIOS `ED` WHERE `ED`.`ID_EDIFICIO` = @idEdificio)");
            $db->query("SET @ultimaCuota  = (SELECT IF(`ED`.`DIA_CORTE` > CAST(DATE_FORMAT(NOW(),'%d') AS INT)
                                                           , DATE_FORMAT(LAST_DAY(DATE_ADD(NOW(), INTERVAL -1 MONTH)),'%Y-%m-%d')
                                                           , CONCAT(DATE_FORMAT(NOW(),'%Y-%m'),'-',`ED`.`DIA_CORTE`)
                                                           ) AS ULTIMA_CUOTA
                                                   FROM EDIFICIOS `ED` WHERE `ED`.`ID_EDIFICIO` = @idEdificio)");
            $db->query("SET @totDeptos = ".$queryTotDeptos);
            $db->query("SET @totCuotas = (SELECT (TIMESTAMPDIFF(MONTH, @primeraCuota, @ultimaCuota)+1))");
            $db->query("SET @totCtaPag = (SELECT COUNT(`C`.`ID_CUOTA`) FROM CUOTAS `C` WHERE `C`.`STATUS` = 'PAGADA' AND `C`.`ID_EDIFICIO` = @idEdificio $whereDeptos)");

            $cuotasTasaQRY = $db->query("SELECT (@totDeptos) AS TOT_DEPTOS, (@totCuotas) AS TOT_CUOTAS, (@totDeptos * @totCuotas) AS TOT_TASA, (@totCtaPag) AS TOT_PAGADAS, ( IF(@totCtaPag=0, 100, 100-(@totCtaPag/(@totDeptos * @totCuotas))*100) ) AS TASA ", FALSE);       
        }
    else        
        {  
            $queryTotDeptos = $id_depto===NULL?"(SELECT [ED].[NUM_TORRES] * [ED].[NUM_VIVIENDAS] FROM ".$this->PREFIX_DB_CVR."EDIFICIOS [ED] WHERE [ED].[ID_EDIFICIO] = @idEdificio)":"1";
            $whereDeptos    = $id_depto===NULL?"" :" AND [C].[ID_DEPTO] =".$id_depto;

            $cuotasTasaQRY = $db->query("DECLARE @idEdificio   int      = ".$id_edificio."; 
                                         DECLARE @primeraCuota Datetime = (SELECT CAST(CONCAT(FORMAT(DATEADD(month, 1, [ED].[FECHA_ALTA]), 'yyyy-MM'),'-',[ED].[DIA_CORTE]) AS Datetime) AS PRIMERA_CUOTA FROM ".$this->PREFIX_DB_CVR."EDIFICIOS [ED] WHERE [ED].[ID_EDIFICIO] = @idEdificio); 
                                         DECLARE @ultimaCuota  Datetime = (SELECT CAST((CASE WHEN [DIA_CORTE] > CAST(FORMAT(GETDATE(),'dd') AS INT) THEN FORMAT(EOMONTH(DATEADD(month, 1, GETDATE())),'yyyy-MM-dd') ELSE FORMAT(GETDATE(),'yyyy-MM-dd') END ) AS Datetime) AS ULTIMA_CUOTA FROM ".$this->PREFIX_DB_CVR."EDIFICIOS [ED] WHERE [ED].[ID_EDIFICIO] = @idEdificio);
                                         DECLARE @totDeptos    float   = ".$queryTotDeptos."; 
                                         DECLARE @totCuotas    float   = (SELECT (DATEDIFF(MONTH, @primeraCuota, @ultimaCuota)+1)); 
                                         DECLARE @totCtaPag    float   = (SELECT COUNT([C].[ID_CUOTA]) FROM ".$this->PREFIX_DB_CVR."CUOTAS [C] WHERE [C].[STATUS] = 'PAGADA' AND [C].[ID_EDIFICIO] = @idEdificio ".$whereDeptos.");

                                         SELECT (@totDeptos) AS TOT_DEPTOS, (@totCuotas) AS TOT_CUOTAS, (@totDeptos * @totCuotas) AS TOT_TASA, (@totCtaPag) AS TOT_PAGADAS, ( CASE WHEN @totCtaPag=0 THEN 100 ELSE 100-(@totCtaPag/(@totDeptos * @totCuotas))*100 END) AS TASA ", FALSE);       
        }
    return $cuotasTasaQRY;
}



public function sqlDateFunction()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "DATE_FORMAT";    }
    else        
        { return "FORMAT";         }
}

public function sqlLastDayOfMonthFunction()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "LAST_DAY";    }
    else        
        { return "EOMONTH";         }
}

public function sqlDateFormatMMYYYY()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%c%Y'";         }
    else        
        { return "'MMyyyy'";       }
}

public function sqlDateFormatYYYY_MM()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%Y-%m'";        }
    else        
        { return "'yyyy-MM'";      }
}

public function sqlDateFormatYYYY_MM_dd()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%Y-%m-%d'";        }
    else        
        { return "'yyyy-MM-dd'";      }
}

public function sqlDateFormatyyyy__MM__dd()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%Y/%m/%d'";     }
    else        
        { return "'yyyy/MM/dd'";   }
}

public function sqlDateFormatdd_MM_yyyy()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%d-%c-%Y'";     }
    else        
        { return "'dd-MM-yyyy'";   }
}

public function sqlDateFormatdd__MM__yyyy()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%d/%c/%Y'";     }
    else        
        { return "'dd/MM/yyyy'";   }
}
public function sqlDateFormatYYYY()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%Y'";           }
    else        
        { return "'yyyy'";         }
}


public function sqlDateFormatMMM()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%b'";     }
    else        
        { return "'MMM'";  }
}

public function sqlDateFormatdd_MMM_yyyy()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%d %b %Y'";     }
    else        
        { return "'dd MMM yyyy'";  }
}

public function sqlDateFormatdd_MMM_yyyy_hh_mm()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%d/%b/%Y hh:mm'";     }
    else        
        { return "'dd/MM/yyyy hh\:mm'";  }
}

public function sqlDateFormatMM_dd_yyyy()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%m/%d/%Y'";     }
    else        
        { return "'MM/dd/yyyy'";   }
}


public function sqlDateFormatMM()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%m'";     }
    else        
        { return "'MM'";   }
}

public function sqlDateFormatddMyyyy()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%d%c%Y'";     }
    else        
        { return "'ddMyyyy'";   }
}

public function sqlDateFormatMyyyy()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%c%Y'";     }
    else        
        { return "'Myyyy'";   }
}

public function sqlDateFormatdddd_dd_MMM_yyyy()
{
    if($this->PREFIX_DB_CVR === "" )
        { return "'%a %d - %b - %Y'";       }
    else        
        { return "'dddd dd - MMM -yyyy'";   }
}


}// End Class
