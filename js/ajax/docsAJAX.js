



$("document").ready(function (){
  
  $("document").ready(function ()
{ 
    habilitaJS_Webpart("HM");
    $("#f_webpart_t1").click(function(){  habilitaJS_Webpart("HM");  });
    $("#f_webpart_t2").click(function(){  habilitaJS_Webpart("AS");  });
    $("#f_webpart_t3").click(function(){  habilitaJS_Webpart("A1");  });
    $("#f_webpart_t4").click(function(){  habilitaJS_Webpart("GL");  });    
}); //$("document").ready

});


function habilitaJS_Webpart(tipo)
{

switch(tipo){ 
 
 case "HM": /*********     Formulario Docs  INI       ***********/
        paginarAX('docs', 'docsTable', 1, 10);            
        $("#savButtDocs" ).click (function(){ submitFormFin('Docsform', 'docs', 'docsTable', 'Activo', 'Inv'); });        
        if( Number($("#firstTimeDoc").val()) === 0 )
        {
          habilitaMODAL("docs");        
          var param = {"tipo"        : "Docs"
                      ,"allowedTypes": "jpg,png,gif,pdf,doc,docx"
                      ,"url"         : $('#baseURL').val()+"portal/agregaArchivoAX"             
                      ,"urlBorrar"   : $('#baseURL').val()+"portal/borrarArchivoAX"
                      ,"urlRename"   : $('#baseURL').val()+"portal/renombraArchivoAX"             
                      ,"campo"       : "docAdjunto"
                      ,"divFile"     : "divDoc"
                      };        
          subirArchivoCNVR(param);
          $("#firstTimeDoc").val(1);
        }
 break;     /*********     Formulario Docs  FIN       ***********/
 
 case "AS": /*********     AS  INI       ***********/        
 break;     /*********     AS  FIN      ***********/    

 case "A1": /*********     A1  INI       ***********/        
 break;     /*********     A1  FIN      ***********/    

 case "GL": /*********     GLOSARIO  INI       ***********/
          $('.treeGrid').treegrid();
 break;     /*********     GLOSARIO  FIN      ***********/     
}//switch
}//habilitaJS_Webpart

