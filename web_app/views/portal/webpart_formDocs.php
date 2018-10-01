<?php 

echo '<div style="display:none;">
      <div id="modalCB_contentDoc" >
     ';

echo form_open(base_url().'docs/saveDocAX/'.$edificio, array('id'=>'Docsform','name'=>'Docsform','class'=>'sky-form'));
echo form_input(array('name'=> 'docAdjunto'  , 'id' => 'docAdjunto'  , 'type' => 'hidden', 'value' => NULL));
echo form_input(array('name'=> 'id_doc'      , 'id' => 'id_doc'      , 'type' => 'hidden', 'value' => NULL));
echo form_input(array('name'=> 'nuevoDoc'    , 'id' => 'nuevoDoc'     , 'type' => 'hidden', 'value' => NULL));
echo form_input(array('name'=> 'firstTimeDoc', 'id' => 'firstTimeDoc', 'type' => 'hidden', 'value' => 0));

echo form_fieldset();    
echo '<div class="row">
       '.inputText_crv(array('col'=>'col-4','label'=>'Activo:','icon'=>'fa-archive','inputName'=>'activo','class'=>'validate[required,custom[onlyLetterNumber]] text-input','value'=>NULL,'placeholder'=>'Ej. Lamparas', 'maxlength'=>80) ).'
       <label class="select">'.form_dropdown('tipoDoc', $type_ant, 0, 'id=\'type_ant\' ').'<i></i></label> 
       <section class="col col-2"></section>
       '.inputText_crv(array('col'=>'col-4','label'=>'Descripción:'   ,'icon'=>'fa-archive','inputName'=>'desc_activo','class'=>'validate[required,custom[onlyLetterNumber]] text-input','value'=>NULL,'placeholder'=>'Ej. Lampara de techo del lobby', 'maxlength'=>150) ).'
       <section class="col col-3"></section>
      </div><!--row -->';
      echo '<div class="row">      
      <section class="col col-4"></section>
      <section class="col col-2"></section>
      <section class="col col-4"><label class="label"><i class="fa fa-image"></i> Foto:</label> 
       <div id="divInventario">'.img( array('src' => base_url().'logo_edificios/LogoConvivere.jpg','class'=>"img-circle centerDiv", "width"=>"200", "heigth"=>"350")).'</div>
       <div id="subirArchivoCNVRDocs">Click para cargar foto</div>
       <span></span><div id="statusDocs"></div>       
       </section>
       <section class="col col-2"></section>      
     </div><!--row -->';      
echo form_fieldset_close();

echo "<footer class='col col-11' id='divFooter'>        
        <span class='button button-secondary canButtonModal' id='cancelarButtonDocs'><i class='fa fa-close'>".nbs()."       </i>Cancelar</span>
        <span class='button'             id='savButtDocs'><i class='fa fa-check-circle'>".nbs()."</i>Cargar</span>
        <span id='confirmDocsform'></span>
      </footer>
     ";
echo form_close();
echo '</div><!--modalColorbox_content -->             
      </div><!--display:none -->';