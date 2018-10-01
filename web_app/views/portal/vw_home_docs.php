<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include(__DIR__."/../webpart_header.php"); 


include(__DIR__."/../webpart_menu.php");

echo link_tag(array('href' => 'style/css/ems/cvr_style.css','rel' => 'stylesheet', 'type' => 'text/css'));

echo script_tag(array('src' => 'js/jquery.treegrid.min.js','charset' => 'utf-8','type' => 'text/javascript'));
echo script_tag(array('src' => 'js/jquery.uploadfile.js'  ,'charset' => 'utf-8','type' => 'text/javascript'));
echo script_tag(array('src' => 'js/ajax/docsAJAX.js'      ,'charset' => 'utf-8','type' => 'text/javascript'));

include(__DIR__."/../webpart_barra_path.php");

?>

<!-- Start Tabs Style -->
<section id="Tabs-Style" class="light-wrapper">
<div class="container inner">        	  
<div class="row">
    <div class="col-md-12">        
        <div class="Tabs-Style9 side-tabs" role="tabpanel">
           <!-- Nav tabs -->
                <ul class="nav nav-tabs tab-nav ui-tabs-nav" role="tablist">
                    <li role="presentation" class="active"><a href="#tabs-1" aria-controls="tabs-1" role="tab" data-toggle="tab" id="f_webpart_t1"><i class="fa fa-folder-open"></i> Inicio </a></li>
                    <li role="presentation"><a href="#tabs-2" aria-controls="tabs-2" role="tab" data-toggle="tab" id="f_webpart_t2"><i class="fa fa-users">     </i> Asambleas</a></li>
                    <li role="presentation"><a href="#tabs-3" aria-controls="tabs-3" role="tab" data-toggle="tab" id="f_webpart_t3"><i class="fa fa-users">     </i> Asambleas</a></li>
                    <li role="presentation"><a href="#tabs-4" aria-controls="tabs-4" role="tab" data-toggle="tab" id="f_webpart_t4"><i class="fa fa-book">     </i> Glosario</a></li>
                </ul>
                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="tabs-1">
                        <?php include("webpart_doc_inicio.php");  ?> 
                    </div>                                                 
                    <div role="tabpanel" class="tab-pane fade" id="tabs-2">
                        <div class="Block-Case">
                            <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Duis cursus. Maecenas ligula eros, blandit nec, pharetra at, semper at, magna. Nullam ac lacus. Nulla facilisi. Praesent viverra justo vitae neque. Praesent blandit adipiscing velit. Suspendisse potenti. Donec mattis, pede vel pharetra blandit, magna ligula faucibus eros, id euismod lacus dolor eget odio. Nam scelerisque. Donec non libero sed nulla mattis commodo. Ut sagittis. Donec nisi lectus, feugiat porttitor, tempor ac, tempor vitae, pede. Aenean vehicula velit eu tellus interdum rutrum. Maecenas commodo. Pellentesque nec elit. Fusce in lacus. Vivamus a libero vitae lectus hendrerit hendrerit.</p>
                        </div>
                    </div>
                    
                    <div role="tabpanel" class="tab-pane fade" id="tabs-3">
                        <div class="Block-Case">
                            <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
                            <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
                            <p>Proin elit arcu, rutrum commodo, vehicula tempus, commodo a, risus. Curabitur nec arcu. Donec sollicitudin mi sit amet mauris. Nam elementum quam ullamcorper ante. Etiam aliquet massa et lorem. Mauris dapibus lacus auctor risus. Aenean tempor ullamcorper leo. Vivamus sed magna quis ligula eleifend adipiscing. Duis orci. Aliquam sodales tortor vitae ipsum. Aliquam nulla. Duis aliquam molestie erat. Ut et mauris vel pede varius sollicitudin. Sed ut dolor nec orci tincidunt interdum. Phasellus ipsum. Nunc tristique tempus lectus.</p>
                        </div>
                    </div>
                    
                    <div role="tabpanel" class="tab-pane fade" id="tabs-4">
                        <?php include("webpart_glosario.php");  ?> 
                    </div>
                    
                    
                </div>
                <!--End Tab panes -->
        </div>	
    </div>
</div>
</div>
</section>
<!-- End Tabs Style -->

<?php

include(__DIR__."/../webpart_footer_portal.php"); 
