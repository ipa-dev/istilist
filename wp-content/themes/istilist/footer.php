<?php if (!is_page(array('login', 'forgot-password', 'register', 'reset-password', 'add-member', 'thank-you', 'activation'))) {
    ?>
<div class="footer">
<?php global $options; ?>
<?php if (is_user_logged_in()) {
        ?>
    <div class="copy"><?php echo $options['general-copyright']; ?></div>
<?php
    } else {
        ?>
    <div class="copy" style="text-align: center;"><?php echo $options['general-copyright']; ?></div>
<?php
    } ?>
</div>
<?php
} ?>
<script>
jQuery(document).ready(function(){
    jQuery('#emailthisreport').click(function(){
        var chart1 = jQuery('#chart_to_png_trafficflow').html();
        var chart2 = jQuery('#chart_to_png_trafficflowweekly').html();
        var chart3 = jQuery('#chart_to_png_trafficflowmonthly').html();
        var chart4 = jQuery('#chart_to_png_trafficflowweekly1').html();
        var chart5 = jQuery('#chart_to_png_trafficflowmonthly1').html();
        
        jQuery.ajax({
            url: "<?php get_bloginfo('url'); ?>/send-report",
            type: "post",
            data: {"chart1": chart1, "chart2": chart2, "chart3": chart3, "chart4": chart4, "chart5": chart5},
            success: function(response){
                alert("Email successfully");
            },
            error:function(response){
            	alert("there is error while submit");
            }   
        });
    }) 
});
</script>
<?php wp_footer(); ?>
</body>
</html>