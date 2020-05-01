<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package colorlib-cn
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no">
	<?php    wp_head(); ?>
</head>

<body <?php body_class(); ?>>


<div id="sidr" class="sidr right">
	<!-- Your content -->
	<?php
	wp_nav_menu( array(
		'menu_id'=>'menu_slideout',
		'menu_class'=>'menu_slideout_class',
		'container_class'=>'menu-slideout-container',
		'walker' => new Colorlib_Walker_Nav_Menu()
	) );
	?>
</div>

<div id="page" class="site">

	<header id="masthead" class="site-header">
		<div class="container">
			<div class="row">
				<div class="col-10 col-lg-2 col-xl-2">
					<div class="site-branding">
						<?php
						the_custom_logo();
							?>
					</div><!-- .site-branding -->
				</div>

				<div class="d-none d-sm-block col-lg-8 col-xl-8 offset-xl-1" >
					<nav id="site-navigation" class="main-navigation">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'menu-1',
							'menu_id'        => 'primary-menu',
						) );
						?>
					</nav><!-- #site-navigation -->
				</div>
				<div class="col-2 d-block d-sm-none ">
					<a class="menu_diy" href="#sidr">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="30" height="30" focusable="false"><title>Menu</title><path stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-miterlimit="10" d="M4 7h22M4 15h22M4 23h22"></path></svg>
					</a>

<!--					<i class="fa fa-navicon fa-lg"></i>-->
				</div>

			</div>
		</div>
	</header><!-- #masthead -->

	<div id="content" class="site-content">
