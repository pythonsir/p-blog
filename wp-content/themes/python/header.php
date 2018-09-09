<?php

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="app">
    <v-app id="inspire">
        <?php get_template_part("template-parts/toolbars") ?>
        <v-content>


