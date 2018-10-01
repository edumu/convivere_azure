<?php 

echo '<div style="display:none;">
      <div id="modalCB_contentInv" >
     ';

echo form_open(base_url().'edificio/saveInvAX/'.$edificio, array('id'=>'Inv-form','name'=>'Inv-form','class'=>'sky-form'));
echo form_input(array('name'=> 'invAdjunto'  , 'id' => 'invAdjunto'  , 'type' => 'hidden', 'value' => NULL));
echo form_input(array('name'=> 'id_inv'      , 'id' => 'id_inv'      , 'type' => 'hidden', 'value' => NULL));
echo form_input(array('name'=> 'nuevoIA'     , 'id' => 'nuevoIA'     , 'type' => 'hidden', 'value' => NULL));
echo form_input(array('name'=> 'firstTimeInv', 'id' => 'firstTimeInv', 'type' => 'hidden', 'value' => 0));

echo form_fieldset();    
echo '<div class="row">
       '.inputText_crv(array('col'=>'col-4','label'=>'Activo:','icon'=>'fa-archive','inputName'=>'activo','class'=>'validate[required,custom[onlyLetterNumber]] text-input','value'=>NULL,'placeholder'=>'Ej. Lamparas', 'maxlength'=>80) ).'
       <section class="col col-2"></section>
       '.inputText_crv(array('col'=>'col-4','label'=>'Descripción:'   ,'icon'=>'fa-archive','inputName'=>'desc_activo','class'=>'validate[required,custom[onlyLetterNumber]] text-input','value'=>NULL,'placeholder'=>'Ej. Lampara de techo del lobby', 'maxlength'=>150) ).'
       <section class="col col-3"></section>
      </div><!--row -->';
      echo '<div class="row">
      '.inputText_crv(array('col'=>'col-4','label'=>'Cantidad:','icon'=>'fa-calculator','inputName'=>'cantidad','class'=>'validate[required,custom[number]] text-input onlyNumber','value'=>NULL,'placeholder'=>'Ej. 10', 'maxlength'=>10) ).'
      <section class="col col-2"></section>
      <section class="col col-4"><label class="label"><i class="fa fa-image"></i> Foto:</label> 
       <div id="divInventario">'.img( array('src' => base_url().'logo_edificios/LogoConvivere.jpg','class'=>"img-circle centerDiv", "width"=>"200", "heigth"=>"350")).'</div>
       <div id="subirArchivoCNVRInventario">Click para cargar foto</div>
       <span></span><div id="statusInventario"></div>       
       </section>
       <section class="col col-2"></section>      
     </div><!--row -->';      
echo form_fieldset_close();

echo "<footer class='col col-11' id='divFooter'>        
        <span class='button button-secondary canButtonModal' id='cancelarButtonInv'><i class='fa fa-close'>".nbs()."       </i>Cancelar</span>
        <span class='button'             id='savButtInv'><i class='fa fa-check-circle'>".nbs()."</i>Guardar</span>
        <span id='confirmInv-form'></span>
      </footer>
     ";
echo form_close();
echo '</div><!--modalColorbox_content -->             
      </div><!--display:none -->';