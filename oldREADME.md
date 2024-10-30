# Mowplayer plugin documentation


## Plugin content.

    .
    |-- Mow.php
    |-- Install.php
    |-- Settings.php
    |-- Functions.php
    |-- Assets
    |   |-- Icon-128x128.png
    |   |-- Icono-gradient-small.svg
    |   |-- Icono-gradient.svg
    |   |-- Isologo-gradient-svg
    |   |-- Logo.png
    |   |-- Mow-logo-128x128.png
    |   |-- Mow-logo-new.png
    |   |-- Mow-logo.png
    |-- Content
    |   |-- Ajax.php
    |-- Css
    |   |-- Custom-block-editor.css
    |   |-- Mowplayer.css
    |-- Js
        |-- Custom-block-editor.js
        |-- Custom-tinymce.js

# Functions

## Mow.php
	

### Plugin index, required data

    Plugin Name: Mowplayer
    Plugin URI: http://wordpress.org/plugins/mowplayer/
    Description: Insert mowplayer video for classic and block editor
    Author: Mowplayer.com
    Version: 3.1
    Author URI: https://mowplayer.com/
 

### Required files 

    define('MOWPLAYER_PLUGIN_FILE', __FILE__);
    register_deactivation_hook(__FILE__, 'mow_uninstall');
    register_activation_hook(__FILE__, 'mow_install');
    require( dirname(__FILE__) . '/install.php' );
    require( dirname(__FILE__) . '/functions.php' );




### Uninstall and truncate functions
delete mysql table from database on plugin uninstall

    function mow_uninstall() 

    function mow_truncateall()


### Wordpress hooks 

#### Function to set mowplayer menu admin
    
    mow_menu() 

#### Function to set mowplayer settings page
    
    mow_page() 

#### Function to enqueue Styles and jQuery for mowplayer
    
    mow_add_css()

#### Mowplayer css and admin menu hooks
    
    add_action('admin_enqueue_scripts', 'mow_add_css');
    add_action('admin_menu', 'mow_menu');

### TinyMce hooks

    add_action( 'admin_enqueue_scripts', 'mow_admin_enqueue_scripts' );
    add_action( 'admin_head', 'mow_add_mce_button' );


#### Add hooks for mowplayer button and set only for admin

    mow_add_mce_button()

### Create shortcode
Get settings options from mysql database and create video markup with the options settings. Verifies if video exist on mowplayer with CURL
    
    mow_yt_shortcode( $id )

## Install.php

### Create required table for settings on mysql database

    mow_install() 

## Settings.php

### Wordpress admin page for settings options

    Custom-block-editor.js

### Function to create custom block on wordpress editor and paste shortcode on post editor
Generate SVG icon for wordpress custom block

    wp.blocks.registerBlockType

Setup new custom block for wordpress editor 5.0+

Add regex to filter invalid urls.

Generate custom shortcode with youtube url ID

    [Mowplayer-Video ID='1234abc’]

Insert shortcode on post


## Custom-tinymce.js

Function to create custom button on wordpress editor and paste shortcode on post editor


    tinymce.PluginManager.add()

Setup new custom button for wordpress classic editor

Add regex to filter invalid urls.

Generate custom shortcode with youtube url ID

    [Mowplayer-Video ID='1234abc’]

Insert shortcode on post



## Ajax.php

Updates mowplayer settings on mysql database
