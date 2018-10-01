<script>
$(document).ready(function(){
        //Examples of how to assign the Colorbox event to elements
        $(".group1").colorbox({rel:'group1'});
        $(".group2").colorbox({rel:'group2', transition:"fade"});
        $(".group3").colorbox({rel:'group3', transition:"none", width:"75%", height:"75%"});
        $(".group4").colorbox({rel:'group4', slideshow:true});        
        $(".youtube").colorbox({iframe:true, innerWidth:640, innerHeight:390});
        $(".vimeo").colorbox({iframe:true, innerWidth:500, innerHeight:409});
        $(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
        $(".inline").colorbox({inline:true, width:"75%"});
        $(".callbacks").colorbox({
                onOpen:function(){ alert('onOpen: colorbox is about to open'); },
                onLoad:function(){ alert('onLoad: colorbox has started to load the targeted content'); },
                onComplete:function(){ alert('onComplete: colorbox has displayed the loaded content'); },
                onCleanup:function(){ alert('onCleanup: colorbox has begun the close process'); },
                onClosed:function(){ alert('onClosed: colorbox has completely closed'); }
        });        
        //Example of preserving a JavaScript event for inline calls.
        $("#click").click(function(){ 
                $('#click').css({"background-color":"#f00", "color":"#fff", "cursor":"inherit"}).text("Open this window again and this message will still be here.");
                return false;
        });
});
</script>

<h1>Colorbox Demonstration</h1>
<h2>Elastic Transition</h2>
<p><a class="group1" href="<?php echo base_url();?>style/images/ohoopee1.jpg" title="Me and my grandfather on the Ohoopee.">Grouped Photo 1</a></p>
<p><a class="group1" href="<?php echo base_url();?>style/images/ohoopee2.jpg" title="On the Ohoopee as a child">Grouped Photo 2</a></p>
<p><a class="group1" href="<?php echo base_url();?>style/images/ohoopee3.jpg" title="On the Ohoopee as an adult">Grouped Photo 3</a></p>

<h2>Fade Transition</h2>
<p><a class="group2" href="<?php echo base_url();?>style/images/ohoopee1.jpg" title="Me and my grandfather on the Ohoopee">Grouped Photo 1</a></p>
<p><a class="group2" href="<?php echo base_url();?>style/images/ohoopee2.jpg" title="On the Ohoopee as a child">Grouped Photo 2</a></p>
<p><a class="group2" href="<?php echo base_url();?>style/images/ohoopee3.jpg" title="On the Ohoopee as an adult">Grouped Photo 3</a></p>

<h2>No Transition + fixed width and height (75% of screen size)</h2>
<p><a class="group3" href="<?php echo base_url();?>style/images/ohoopee1.jpg" title="Me and my grandfather on the Ohoopee.">Grouped Photo 1</a></p>
<p><a class="group3" href="<?php echo base_url();?>style/images/ohoopee2.jpg" title="On the Ohoopee as a child">Grouped Photo 2</a></p>
<p><a class="group3" href="<?php echo base_url();?>style/images/ohoopee3.jpg" title="On the Ohoopee as an adult">Grouped Photo 3</a></p>

<h2>Slideshow</h2>
<p><a class="group4"  href="<?php echo base_url();?>style/images/ohoopee1.jpg" title="Me and my grandfather on the Ohoopee.">Grouped Photo 1</a></p>
<p><a class="group4"  href="<?php echo base_url();?>style/images/ohoopee2.jpg" title="On the Ohoopee as a child">Grouped Photo 2</a></p>
<p><a class="group4"  href="<?php echo base_url();?>style/images/ohoopee3.jpg" title="On the Ohoopee as an adult">Grouped Photo 3</a></p>

<h2>Other Content Types</h2>		
<p><a class='youtube' href="http://www.youtube.com/embed/VOJyrQa_WR4?rel=0&amp;wmode=transparent">Flash / Video (Iframe/Direct Link To YouTube)</a></p>
<p><a class='vimeo'   href="http://player.vimeo.com/video/2285902" title="R&ouml;yksopp: Remind Me">Flash / Video (Iframe/Direct Link To Vimeo)</a></p>
<p><a class='iframe'  href="http://wikipedia.com">Outside Webpage (Iframe)</a></p>
<p><a class='inline'  title="My Modal test" href="#inline_content">Inline HTML</a></p>

<h2>Demonstration of using callbacks</h2>
<p><a class='callbacks' href="<?php echo base_url();?>style/images/marylou.jpg" title="Marylou on Cumberland Island">Example with alerts</a>. Callbacks and event-hooks allow users to extend functionality without having to rewrite parts of the plugin.</p>

<!-- This contains the hidden content for inline calls -->
<div style='display:none'>
    <div id='inline_content' style='padding:10px; background:#fff;'>
    <p><strong>This content comes from a hidden element on this page.</strong></p>
    <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came from when it is closed.</p>
    <p><a id="click" href="#" style='padding:5px; background:#ccc;'>Click me, it will be preserved!</a></p>			
    <p><strong>If you try to open a new Colorbox while it is already open, it will update itself with the new content.</strong></p>			
    <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came from when it is closed.</p>
    <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came from when it is closed.</p>
    <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came from when it is closed.</p>
    <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came from when it is closed.</p>
    <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came from when it is closed.</p>
    <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came from when it is closed.</p>
    <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came from when it is closed.</p>
    <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came from when it is closed.</p>
    <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came from when it is closed.</p>
    <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came from when it is closed.</p>
    <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came from when it is closed.</p>
    <p>The inline option preserves bound JavaScript events and changes, and it puts the content back where it came from when it is closed.</p>
    
    </div>
</div>
    