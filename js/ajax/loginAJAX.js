$("document").ready(function (){

  $("#login-button").click(function(){ submitFormLogin("login"); });
  
  habilitaMODAL("signUp");  
  $("#savButtonModalSignUp").click(function(){ submitFormFAmail('SignUp'); });
  
  habilitaMODAL("pwdForgot"); 
  $("#savButtonModalPwdForgot").click(function(){ submitFormFAmail('PwdForgot'); });
  
  });


  function submitFormFAmail(forma) 
  {   $("#"+forma).validationEngine();	  
      if($("#"+forma).validationEngine('validate'))
        { submitFAAX($('#baseURL').val(),forma,$('#tituloAlert').val()); }
  }
  
  function submitFAAX(baseURL,forma,tituloAlert)
 {
    $("#confirm"+forma).removeClass("msjConfirmMail").removeClass("msjOkMail").removeClass("msjErrMail").html("").show(); 
    $.ajax({data      : $("#"+forma).serialize(),
            url       : $("#"+forma).attr("action"),
            type      : 'post',
            dataType  : 'json',
            beforeSend: function () { $("#confirm"+forma).removeClass("msjOkMail").removeClass("msjErrMail").addClass("msjConfirmMail");
                                      $("#confirm"+forma).html("<img src=\""+$('#baseURL').val()+"style/images/loading.gif\" width='50' height='50'> Validando acceso, espere por favor...");
                                    },
            success   : function (response) { $("#confirm"+forma).removeClass("msjConfirmMail").addClass(response['class']);
                                              $("#confirm"+forma).html(  response['confirm']);
                                            }, 
            error     : function(xhr, textStatus, error)
                            { jAlert("Ha ocurrido un error al ingresar al sistema: " + xhr.statusText + " " + textStatus+" "+error,tituloAlert); 
                             // $("#confirm"+forma).removeClass("msjconfirm").removeClass("msjOk").addClass("msjErr").html("<img src=\""+baseURL+"images/close.png\">Ocurrio un problema al guardar la seccion, intentelo mas tarde");
                            }
           });    
 }


function submitFormLogin(forma) 
{   $("#"+forma).validationEngine();	  
    if($("#"+forma).validationEngine('validate'))
      { submitLoginAX($('#baseURL').val(),forma,$('#tituloAlert').val()); }
}
 

function submitLoginAX(baseURL,forma,tituloAlert)
{
    $("#confirm"+forma).removeClass("msjErrCentro").removeClass("check-box").removeClass("msjOk2").removeClass("msjOk").removeClass("msjErr").addClass("msjconfirm").html("").show(); 
    $.ajax({data      : $("#"+forma).serialize(),
            url       : $("#"+forma).attr("action"),
            type      : 'post',
            dataType  : 'json',
            beforeSend: function () { $("#confirm"+forma).removeClass("msjOk").addClass("msjconfirm");
                                      $("#confirm"+forma).html("<img src=\""+$('#baseURL').val()+"style/images/loading.gif\" width='50' height='50'> Validando acceso, espere por favor...");},
            success   : function (response) {                                
                                      if(response['acceso'] === true)
                                      {
                                        $("#confirm"+forma).removeClass("msjconfirm").addClass("msjOk2");
                                        $("#confirm"+forma).html("<img src=\""+$('#baseURL').val()+"style/images/loading.gif\" width='50' height='50'><img src=\""+baseURL+"style/images/check.png\">Prepranado acceso, espere por favor...");                                       
                                        window.location.href = baseURL+"portal/";
                                      }
                                      else
                                      {
                                        $("#confirm"+forma).removeClass("msjconfirm").addClass("msjErrCentro");
                                        $("#confirm"+forma).html("<img src=\""+baseURL+"style/images/important.gif\"><br>"+(isEmpty(response['mensaje']['usuario'])?"":response['mensaje']['usuario'])+(isEmpty(response['mensaje']['pwd'])?"":response['mensaje']['pwd']));
                                      }
                                 }, 
            error     : function(xhr, textStatus, error)
                            { jAlert("Ha ocurrido un error al ingresar al sistema: " + xhr.statusText + " " + textStatus+" "+error,tituloAlert); 
                             // $("#confirm"+forma).removeClass("msjconfirm").removeClass("msjOk").addClass("msjErr").html("<img src=\""+baseURL+"images/close.png\">Ocurrio un problema al guardar la seccion, intentelo mas tarde");
                            }
           });    
}

