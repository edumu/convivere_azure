<?php 


echo form_open(base_url(), array('id'=>'headInvForm','name'=>'headInvForm','class'=>'sky-form'));
echo form_fieldset();
echo '  <div class="row"><section class="col col-2"><i class="pointer fa fa-search-minus" id="togSearchInv"> Busqueda </i></section>
                         <section class="col col-3"><i class="pointer fa fa-download ria"> <img class="ria" src="'.base_url().'style/images/logoPDF.png" width="20" height="20"> </i> <div id="divIA"></div> </section>
                         <section class="col col-4"><strong id="lblInvMes">Inventario de Activos del Edificio</strong></section>
                         <section class="col col-3"><span id="buttonRegistrarInv" class="pointer modalCBInv button" href="#modalCB_contentInv" title="Formulario para capturar el Inventarios de activos del Edificio"> <i class="pointer fa fa-archive"> Registrar </i></span></section>
        </div><!--row -->
        <div id="inventarioParam">
            <div class="row">
               '.inputText_crv(array('col'=>'col-2','label'=>'Fecha de:'   ,'icon'=>'fa-calendar','inputName'=>'f1','class'=>'validate[required,custom[onlyLetterNumber]] text-input datecvr','value'=>NULL,'placeholder'=>'Ej. 01/01/2018') ).'
               '.inputText_crv(array('col'=>'col-2','label'=>'Fecha A:'    ,'icon'=>'fa-calendar','inputName'=>'f2','class'=>'validate[required,custom[onlyLetterNumber]] text-input datecvr','value'=>NULL,'placeholder'=>'Ej. 31/01/2018') ).'                      
               '.inputText_crv(array('col'=>'col-2','label'=>'Importe De:' ,'icon'=>'fa-usd','inputName'=>'f3','class'=>'validate[required,custom[onlyLetterNumber]] text-input ','value'=>NULL,'placeholder'=>'Ej. 1') ).'                       
               '.inputText_crv(array('col'=>'col-2','label'=>'Importe A:'  ,'icon'=>'fa-usd','inputName'=>'f4','class'=>'validate[required,custom[onlyLetterNumber]] text-input ','value'=>NULL,'placeholder'=>'Ej. 100') ).'
               '.inputText_crv(array('col'=>'col-4','label'=>'Concepto:'  ,'icon'=>'fa-list-ul','inputName'=>'f5','class'=>'validate[required,custom[onlyLetterNumber]] text-input ','value'=>NULL,'placeholder'=>'Ej. Limpieza') ).'
               <section class="col col-4"></section>
               <section class="col col-4"><i class="pointer fa fa-search"> Buscar </i></section>
            </div><!--row -->
        </div><!--inventarioParam -->
     ';
echo form_fieldset_close();
echo form_close();

echo form_fieldset(); 
echo '  <div class="row">
        <section class="col col-12">';            
echo  ' <div class="table-responsive">
            <table class="table table-striped table-hover" id="inventarioTable">
            <thead>
                <tr class="negritas">
                  <th>#</th>
                  <th>Activo</th>
                  <th>Descripción</th>
                  <th>Cantidad</th>
                  <th>Foto</th>                  
                  <th class="accionesInv"><i class="fa fa-wrench"></i> Acciones</th>
                </tr>
          </thead>
          <tbody></tbody>
          </table>
        </div><!--table responsive -->'; 
echo br(1).'<div id="linksPaginarInv"></div><span id="spinPaginarInv"></span>';
echo '</section>
      </div><!--row -->
     ';
echo form_fieldset_close(); 

include("webpart_formInv.php");



