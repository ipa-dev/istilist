<?php ob_start(); ?>
<?php session_start(); ?>
<?php
if(is_user_logged_in()){
    global $user_ID;
    $timezone = get_user_meta($user_ID, 'selecttimezone', true);
    date_default_timezone_set($timezone);
}
?>
<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo('charset'); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<!-- Responsive and mobile friendly stuff -->
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php
/*
* Print the <title> tag based on what is being viewed.
*/
global $page, $paged;

wp_title('|', true, 'right');

// Add the blog name.
bloginfo('name');

// Add the blog description for the home/front page.
$site_description = get_bloginfo('description', 'display');
if ($site_description && (is_home() || is_front_page()))
    echo " | $site_description";

// Add a page number if necessary:
if ($paged >= 2 || $page >= 2)
    echo ' | ' . sprintf(__('Page %s', 'twentyeleven'), max($paged, $page));

?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<!-- Responsive Stylesheets -->
<link rel="stylesheet" media="all" href="<?php bloginfo('template_directory'); ?>/css/commoncssloader.css" />
<link rel="stylesheet" media="only screen and (max-width: 1024px) and (min-width: 769px)" href="<?php bloginfo('template_directory'); ?>/css/1024.css">
<link rel="stylesheet" media="only screen and (max-width: 768px) and (min-width: 481px)" href="<?php bloginfo('template_directory'); ?>/css/768.css">
<link rel="stylesheet" media="only screen and (max-width: 480px)" href="<?php bloginfo('template_directory'); ?>/css/480.css">
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>?ver=<?php echo(mt_rand(10,100)); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->

<!-- Custom Responsive Stylesheets -->
<link rel="stylesheet" media="only screen and (max-width: 1024px) and (min-width: 993px)" href="<?php bloginfo('template_directory'); ?>/css/mediaquerycss/styleMax1024.css?ver=<?php echo(mt_rand(10,100)); ?>">
<link rel="stylesheet" media="only screen and (max-width: 992px) and (min-width: 769px)" href="<?php bloginfo('template_directory'); ?>/css/mediaquerycss/styleMax992.css?ver=<?php echo(mt_rand(10,100)); ?>">
<link rel="stylesheet" media="only screen and (max-width: 768px) and (min-width: 481px)" href="<?php bloginfo('template_directory'); ?>/css/mediaquerycss/styleMax768.css?ver=<?php echo(mt_rand(10,100)); ?>">
<link rel="stylesheet" media="only screen and (max-width: 480px)" href="<?php bloginfo('template_directory'); ?>/css/mediaquerycss/styleMax480.css?ver=<?php echo(mt_rand(10,100)); ?>">

<?php
/* We add some JavaScript to pages with the comment form
* to support sites with threaded comments (when in use).
*/
if (is_singular() && get_option('thread_comments'))
    wp_enqueue_script('comment-reply');

/* Always have wp_head() just before the closing </head>
* tag of your theme, or you will break many plugins, which
* generally use this hook to add elements to <head> such
* as styles, scripts, and meta tags.
*/
//wp_enqueue_script('jquery');

wp_head();
?>



<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/jquery.fancybox.css" />
<script src="<?php bloginfo('template_directory'); ?>/js/modernizr-2.8.2-min.js"></script>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/slicknav.css" />
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.slicknav.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.fancybox.pack.js"></script>
<script>
	jQuery(function(){
		jQuery('.nav').slicknav({
		  prependTo:'#rspnavigation',
          label:''
		});
	});
</script>
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery(".fancybox").fancybox({
            
		});
        
        jQuery(".assignStylist").fancybox({
            maxWidth	: 300,
       		maxHeight	: 220,
        	fitToView	: false,
        	width		: '90%',
        	height		: '90%',
        	autoSize	: false,
        	closeClick	: false,
        	openEffect	: 'none',
        	closeEffect	: 'none'
        });
	});
</script>


<script src="<?php bloginfo('template_directory'); ?>/js/jquery.matchHeight-min.js"></script>

<script type="text/javascript">
jQuery(function($){
    $('.matchheight').matchHeight();
});
</script>


<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/jquery.bxslider.css" />

<script src="<?php bloginfo('template_directory'); ?>/js/jquery.bxslider.min.js"></script>

<script>
jQuery(document).ready(function(){
  jQuery('.bxslider').bxSlider({
      auto: true,
      nextSelector: '.slider_prev',
      prevSelector: '.slider_next',
      nextText: '<img src="<?php bloginfo('template_directory'); ?>/images/prev.png" />',
      prevText: '<img src="<?php bloginfo('template_directory'); ?>/images/next.png" />'
  });
});
</script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.validate.min.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/additional-methods.js"></script>

<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/jquery.datetimepicker.css" />
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.datetimepicker.js"></script>
<script>
jQuery(document).ready(function(){
    jQuery('#purchase_date').datetimepicker({
        timepicker:false,
        format:'m-d-Y',
        onSelectDate:function(ct,$i){
            jQuery('.xdsoft_datetimepicker').hide();
        }
    });
    jQuery('#customer_wear_date').datetimepicker({
        timepicker:false,
        format:'m-d-Y',
        onSelectDate:function(ct,$i){
            jQuery('.xdsoft_datetimepicker').hide();
        }
    });
});
</script>
<?php if(is_page(array('analytics-reporting', 'analytics-htmltopdf'))){ ?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script>
google.load('visualization', '1', {packages: ['corechart', 'line', 'bar']});
</script>
<?php } ?>


</head>
<?php global $options; ?>
<?php if(is_page(array('login', 'forgot-password', 'register', 'reset-password', 'add-member', 'thank-you', 'activation'))) { ?>
<body <?php body_class(); ?> style="background: url(<?php echo $options['general-background']['url']; ?>);">
<?php } else { ?>
<body <?php body_class(); ?>>
<?php } ?>
<?php if(!is_page(array('login', 'forgot-password', 'register', 'reset-password', 'add-member', 'thank-you', 'activation'))) { ?>
<div id="header">
	<div class="maincontent noPadding">
	    <div class="section group">
	        <div class="col span_6_of_12">
                <h1><a href="<?php bloginfo('url'); ?>"><?php echo $options['general-logo']; ?></a></h1>                          
	        </div>
	        <div class="col span_6_of_12">
                <?php if(is_user_logged_in()){ ?>
                <?php global $user_ID; ?>
                <div class="unav">
                    <ul>
                        <li class="user">
                            <a href="#">
                                <?php echo get_store_img($user_ID); ?>
                                <?php echo get_the_author_meta('display_name', $user_ID); ?>
                            </a>
                        </li>
                    </ul>
                </div>
                <?php } else { ?>
                <div class="unav notunav">
                    <ul>
                        <li><a href="<?php bloginfo('url'); ?>/register">Register</a></li>
                        <li><a href="<?php bloginfo('url'); ?>/login">Login</a></li>
                    </ul>
                </div>
                <?php } ?>                          
	        </div>
	    </div>
	</div>
</div>
<?php } ?>