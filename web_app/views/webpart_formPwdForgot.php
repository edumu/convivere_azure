<?php 
echo '<div style="display:none;">
      <div id="modalCB_pwdForgot" >
     ';

echo form_open(base_url().'portal/pwdForgotAX/', array('id'=>'PwdForgot','name'=>'PwdForgot','class'=>'sky-form'));
echo form_fieldset();    
echo '<div class="row divRecuadroGris">      
      <section class="col col-12">
        <i class="fa fa-key"> <strong>Para recuperar su contraseña, por favor ingrese su correo electrónico </strong></i>
      </section>
      </div>'.br(2);
echo '<div class="row"><section class="col col-2"></section>'.inputText_crv( array('col'=>'col-8','label'=>'Correo Electrónico','icon'=>'fa-envelope','inputName'=>'userPwdForgot','inputType'=>'text','class'=>'validate[required,custom[email]] text-input', 'value'=>NULL,'maxLength'=>40,'placeholder'=>'Cuenta Ej: usr@convivere.mx') ).'<section class="col col-2"></section>
      </div>
      <div class="row"> </div>';      
echo form_fieldset_close(); 

echo "<footer class='col col-11' id='divFooter'>        
        <span class='button button-secondary canButtonModal' id='cancelarButtonPwdForgot'><i class='fa fa-close'>".nbs()."       </i>Cancelar</span>
        <span class='button'                                 id='savButtonModalPwdForgot'><i class='fa fa-check-circle'>".nbs()."</i>Enviar</span>
        <span id='confirmPwdForgot'></span>
      </footer>
     ";
echo form_close();
echo '</div>             
      </div>';