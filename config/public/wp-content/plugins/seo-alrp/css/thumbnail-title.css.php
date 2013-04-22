<?php
    $options = get_option('alrp_rp_options');
    $height = ( 4 < $options['limit'] ) ? 'min-height: 180px; height: 180px;' : '';
?>
<style type="text/css">
/**
* thumbnail-title related posts style  
**/
#alrp-related-posts h3, #alrp-related-posts h2{font-size:1.2em;font-style:normal;margin-bottom: 10px;}
.alrp-content-caption{background:none repeat scroll 0 0 #FFF;border:1px solid rgba(255,255,255,0.5);box-shadow:1px 1px 3px rgba(0,0,0,0.5);float:left;line-height:18px;margin:0 10px 15px 0;max-width:<?php echo ($options['imgw']+10); ?>px;padding:5px;text-align:center;width:<?php echo ($options['imgw']+10); ?>px; <?php echo $height; ?>}
.alrp-content-caption:hover{border:1px solid rgba(255,255,255,0.5);box-shadow:0px 0px 1px rgba(0,0,0,0.5);}
.alrp-content-caption img{margin:5px 5px 0;width:<?php echo $options['imgw']; ?>px;height: <?php echo $options['imgh']; ?>px;}
.alrp-content-caption p{font-size: 11px;margin:5px}
.alrp-content-caption a{color:#252525;text-decoration: none}
.alrp-content-caption a:hover{color:#252525;text-decoration: none}
</style>