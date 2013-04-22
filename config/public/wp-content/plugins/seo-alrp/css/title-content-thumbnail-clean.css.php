<?php
    $options = get_option('alrp_rp_options');
    $imgw = $options['imgh'] + 5;
?>
<style type="text/css">
#alrp-related-posts h3, #alrp-related-posts h2{font-size:1.2em;font-style:normal;margin-bottom: 10px;}
.alrp-thumbnail img { background-color:#fff; float:left; margin:0 10px 0 0; padding:7px; }
.alrp-content { min-height:<?php echo $imgw; ?>px; margin-bottom:5px; }
.alrp-content a { text-decoration:none; }
alrp-content p { padding-bottom:5px; }
</style>