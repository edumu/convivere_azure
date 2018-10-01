<?php 
echo '<div style="display:none;">
      <div id="modalCB_SignUp" >
     ';

echo form_open(base_url().'portal/signUpAX/', array('id'=>'SignUp','name'=>'SignUp','class'=>'sky-form'));
echo form_fieldset();    
echo '<div class="row col col-12 divRecuadroGris">             
       <section class="col col-6"><i class="fa fa-phone">   </i> '.CALL_CENTER.   ' </section>          
       <section class="col col-5"><i class="fa fa-envelope"></i> '.CORREO_OFICIAL.' </section>
       <section class="col col-11"> <strong>Nos ponemos a su disposición, háganos saber la siguiente información: </strong> </section>
      </div>';
echo '<div class="row">'
        .inputText_crv( array('col'=>'col-6','label'=>'Dirección:'   ,'icon'=>'fa-building','inputName'=>'dir'  ,'inputType'=>'text','class'=>'validate[required,custom[onlyLetterNumber]] text-input', 'value'=>NULL,'maxLength'=>80,'placeholder'=>'Ej: Av. Periferico Sur 1420, CDMX') )
        .inputText_crv( array('col'=>'col-5','label'=>'Departamento:','icon'=>'fa-home'    ,'inputName'=>'depto','inputType'=>'text','class'=>'validate[required,custom[onlyLetterNumber]] text-input', 'value'=>NULL,'maxLength'=>40,'placeholder'=>'Ej: Depto 101') )
     .'</div>';      
echo '<div class="row">
     '.inputText_crv( array('col'=>'col-11','label'=>'Comentarios'          ,'icon'=>'fa-text-height','inputName'=>'coment','inputType'=>'text','class'=>'validate[required,custom[onlyLetterNumber]] text-input', 'value'=>NULL,'maxLength'=>120,'placeholder'=>'Ej: Solicito acceso al portal') )
      .inputText_crv( array('col'=>'col-4' ,'label'=>'Nombre:'              ,'icon'=>'fa-user'       ,'inputName'=>'nombre_contacto'  ,'inputType'=>'text','class'=>'validate[required,custom[onlyLetterNumber]] text-input', 'value'=>NULL,'maxLength'=>80,'placeholder'=>'Ej: Juan Sanchez') )
      .inputText_crv( array('col'=>'col-3' ,'label'=>'Teléfono de Contacto:','icon'=>'fa-phone'      ,'inputName'=>'tel_contacto'  ,'inputType'=>'text','class'=>'validate[required,custom[phone]] text-input', 'value'=>NULL,'maxLength'=>80,'placeholder'=>'Ej: 55-12-34-56-78') )
      .inputText_crv( array('col'=>'col-4' ,'label'=>'Correo de Contacto:'  ,'icon'=>'fa-envelope'   ,'inputName'=>'correo_contacto','inputType'=>'text','class'=>'validate[required,custom[email]] text-input', 'value'=>NULL,'maxLength'=>40,'placeholder'=>'Ej: hola@micorreo.com') ).
      '</div>';
echo form_fieldset_close(); 

echo "<footer class='col col-11' id='divFooter'>        
        <span class='button button-secondary canButtonModal' id='cancelarButtonSignUp'><i class='fa fa-close'>".nbs()."       </i>Cancelar</span>
        <span class='button'                                 id='savButtonModalSignUp'><i class='fa fa-check-circle'>".nbs()."</i>Solicitar</span>
        <span id='confirmSignUp'></span>
      </footer>
     ";
echo form_close();
echo '</div>             
      </div>';