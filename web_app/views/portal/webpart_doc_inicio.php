<?php 
echo form_open(base_url(), array('id'=>'headDocsForm','name'=>'headDocsForm','class'=>'sky-form'));
echo form_fieldset();
echo '  <div class="row"><section class="col col-3"> </section>
                         <section class="col col-6"><strong id="lblTMMes"><i class="pointer fa fa-file-text-o"></i> Documentos importantes</strong></section>
                         <section class="col col-3"><span id="buttonRegistrarDoc" class="pointer modalCBDoc button" href="#modalCB_contentDoc" title="Formulario para registrar un Documento"> <i class="pointer fa fa-file-text-o"> Guardar Doc </i></span></section>
        </div><!--row -->
     ';
echo form_fieldset_close();
echo form_close();

echo form_fieldset(); 
echo '  <div class="row">
        <section class="col col-12">';            
echo  ' <div class="table-responsive">
            <table class="table table-striped table-hover" id="docsTable">
            <thead>
                <tr class="negritas">                  
                  <th><i class="pointer fa fa-file-text-o"></i></th>                 
                  <th><i class="pointer fa fa-cloud-download"></i></th>
                  <th class="accionesDocs"><i class="fa fa-wrench"></i> Acciones</th>
                </tr>
          </thead>
          <tbody></tbody>
          </table>
        </div><!--table responsive -->'; 
echo br(1).'<div id="linksPaginardocs"></div><span id="spinPaginardocs"></span>';
echo '</section>
      </div><!--row -->
     ';
echo form_fieldset_close(); 

include("webpart_formDocs.php");



