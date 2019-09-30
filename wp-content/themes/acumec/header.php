<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package CMSSuperHeroes
 * @subpackage CMS Theme
 * @since 1.0.0
 */
?>
<?php global $opt_meta_options, $opt_theme_options; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="initial-scale=1, width=device-width" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ): ?>
<link rel="shortcut icon" type="image/x-icon" href="<?php echo esc_url(get_template_directory_uri() . '/favicon.ico'); ?>" />
<?php endif; ?>
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php $transparent = '';
	if ( (!is_page() && !empty($opt_theme_options['menu_transparent']) && $opt_theme_options['menu_transparent'] == 1) || (is_page() && !empty($meta_options['header_layout']) && !empty($opt_meta_options['menu_transparent']) && $opt_meta_options['menu_transparent'] == 1) || (is_page() &&empty($meta_options['header_layout']) && !empty($opt_theme_options['menu_transparent']) && $opt_theme_options['menu_transparent'] == 1)  ) {
		$transparent = 'header-transparent';
	}
 ?>
<div id="page" class="hfeed site <?php acumec_general_class();?>">
	<?php acumec_revo_header(); ?>	
		<header id="masthead" class="site-header <?php acumec_header_layout_class('header-default');?> <?php echo esc_attr($transparent); ?>" >
			<?php acumec_header(); ?>
		</header><!-- #masthead -->
    <?php acumec_page_title(); ?> <!-- #page-title -->
	<div id="content" class="site-content">