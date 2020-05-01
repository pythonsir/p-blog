<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package thepaper
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="Description" content="<?php   if(is_home()){ echo wp_get_document_title(); }else { the_title(); }  ?>">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'thepaper' ); ?></a>

	<header id="masthead" class="site-header">
		<div class="container header-image">
			<div class="row">
					<?php the_header_image_tag(array('class'=>'img-fluid'));?>
			</div>
		</div>
		<div id="site-branding" class="site-branding" >
			<div class="container">

				<div class="row">
					<div class="col-md-2 offset-md-1 d-flex flex-row justify-content-center  thepaper-logo-border-color">
						<div class="custom-logo">
							<?php the_custom_logo(); ?>
						</div>
					</div>
					<div class="col">
						<nav id="site-navigation" class="main-navigation">
							<?php
							wp_nav_menu( array(
								'theme_location' => 'menu-1',
								'menu_id'        => 'primary-menu',
								'walker' => new Thepaper_Walker_Nav_Menu(),
							) );
							?>
						</nav>
					 </div>

				</div>

			</div>

	</header><!-- #masthead -->

	<div id="content" class="site-content">
