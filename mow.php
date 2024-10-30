<?php
/*
  Plugin Name: Mowplayer
  Plugin URI: http://wordpress.org/plugins/mowplayer/
  Description: Add Mowplayer videos automatically to your site!
  Author: Mowplayer.com
  Version: 5.1.6
  Author URI: https://mowplayer.com/
 */

/**
 * Required files
 */
define('MOWPLAYER_PLUGIN_FILE', __FILE__);
register_deactivation_hook(__FILE__, 'mow_uninstall');
register_activation_hook(__FILE__, 'mow_install');
//
require( dirname(__FILE__) . '/functions.php' );
require( dirname(__FILE__) . '/install.php' );


$plugin_directory = plugin_dir_path( __FILE__ );


/**
 * Delete mowplayer tables from database
 */
function mow_uninstall() {
    global $wpdb;
    $table_name = $wpdb->prefix . "mowplayer_settings";
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
}

/**
 * Truncate mowplayer tables from database
 */
function mow_truncateall() {
    global $wpdb;
    $table_name = $wpdb->prefix . "mowplayer_settings";
    $sql = "TRUNCATE TABLE $table_name;";
    $wpdb->query($sql);
}

/**
 * Set mowplayer menu admin
 */
function mow_menu() {
    add_menu_page('Mowplayer', 'Mowplayer', 'manage_options', 'mow-videos', 'mow_page', plugins_url('assets/logo.png', MOWPLAYER_PLUGIN_FILE));
}

/**
 * Set mowplayer settings page
 */
function mow_page() {
    require( dirname(__FILE__) . '/settings.php' );
}

/**
 * Enqueue Styles and jQuery for mowplayer
 */
function mow_add_css() {
    wp_enqueue_script('jquery');
    wp_register_style('mow_style', plugins_url('css/mowplayer.css', MOWPLAYER_PLUGIN_FILE));
    wp_enqueue_style('mow_style');
}

/**
 * Mowplayer css and admin menu hooks
 */
add_action('admin_enqueue_scripts', 'mow_add_css');
add_action('admin_menu', 'mow_menu');

/**
 * TinyMce hooks
 */
add_action( 'admin_enqueue_scripts', 'mow_admin_enqueue_scripts' );
add_action( 'admin_head', 'mow_add_mce_button' );

/**
 * Register and enqueue custom-tinymce.js file
 */
function mow_admin_enqueue_scripts() {
    $plugin_directory = get_site_url().'/wp-content/plugins/mowplayer/';
    wp_register_script('mow-tinymce-script',$plugin_directory.'js/custom-tinymce.js', $deps ='', $ver = '3.8' );
    wp_localize_script('mow-tinymce-script', 'WPURLS', array('siteurl' => get_option('siteurl')));
    wp_enqueue_script( 'mow-tinymce-script',  $deps ='', $ver = '3.8');
}

/**
 * Add hooks for mowplayer button and set only for admin
 */
function mow_add_mce_button() {
    if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
        return;
    }
    add_filter( 'mce_external_plugins', 'mow_add_tinymce_plugin' );
    add_filter( 'mce_buttons', 'wow_register_mce_button' );
}

/**
 * Add button on wordpress editor
 */
function mow_add_tinymce_plugin( $plugin_array ) {
    $plugin_directory = get_site_url().'/wp-content/plugins/mowplayer/';
    $plugin_array['mow_tinymce_button'] = $plugin_directory.'js/custom-tinymce.js?ver=3.8';
    return $plugin_array;
}

function wow_register_mce_button( $buttons ) {
    array_push( $buttons, 'mow_tinymce_button' );
    return $buttons;
}

/**
 * Rich text Gutemberg editor wordpress 5.0+
 */
add_action('enqueue_block_editor_assets', 'block_editor_mowplayer_button');

function block_editor_mowplayer_button() {
	// Load the compiled blocks into the editor.
    $plugin_directory = get_site_url().'/wp-content/plugins/mowplayer/';

    wp_register_script(
                    'custom-block-editor',
                    $plugin_directory.'js/custom-block-editor.js',
                    $deps ='', $ver = '2.3'
                );
    wp_localize_script(
                    'custom-block-editor',
                    'WPURLS',
                    array('siteurl' => get_option('siteurl'))
                );


	wp_enqueue_script(
		'custom-block-editor',
	    $plugin_directory.'/js/custom-block-editor.js?ver=2.3',
		array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor' ),
		'2.3',
		true
	);
    // Load the compiled styles into the editor.
	wp_enqueue_style(
		'custom-block-editor-css',
		$plugin_directory.'/css/custom-block-editor.css?ver=1.2',
		array( 'wp-edit-blocks' ),
        '1.2'
	);
}

/**
 * Add facebook SDK on header
 */
 function fb_sdk(){
     echo ' <script async defer src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.2"></script>';
 }
 add_action('wp_head', 'fb_sdk');


 /**
  * Add mowscipt on header
  */
  function mow_head_script(){
      ?>
      <script async src="https://mowplayer.com/dist/player.js"></script>
      <?php
      //echo '<script src="https://mowplayer.com/dist/player.js"></script>';
  }
  add_action('wp_head', 'mow_head_script');



/**
 * Register mowplayer SHORTCODE
 * @param array $id of youtube video
 * @return string $video_insert type of mowplayer insert selected
 */
function mow_yt_shortcode( $id ){
    global $wpdb;
    $table_name = $wpdb->prefix . "mowplayer_settings";
    $query = "SELECT * FROM ".$table_name;
    $options = $wpdb->get_row($query);

    $option_settings = $options->settings;
    $option_passback = $options->passback;
    //$option_auto_replace = $options->autoreplace;

    /*Default settings */
    if ($option_settings == '') {
        //$option_settings = 'script';
        $option_settings = 'amp';
    }
    if ($option_passback == '') {
        $option_passback = '1';
    }
    /*
    if ($option_auto_replace ==''){
        $option_auto_replace ='0';
    }
    */

    switch ($option_settings) {
        case 'script':
            /* SCRIPT MOW */
            $video_insert = '<script src="//mowplayer.com/watch/js/v-'.$id['id'].'.js"></script>';
            break;

        case 'iframe':
            /* IFRAME MOW */
            /*
            $video_insert = '<iframe
                                src="//mowplayer.com/watch/v-'.$id['id'].'?script=1"
                                frameborder="0"
                                allow="autoplay; encrypted-media"
                                allowfullscreen>
                            </iframe>';
            */
            $video_insert = '<div data-mow_video="v-'.$id['id'].'"></div>';
            break;

        case 'amp':
            /* AMP MOW */
            /*
            $video_insert = '<amp-mowplayer
                                data-mediaid=v-'.$id['id'].'
                                layout="responsive"
                                width="16"
                                height="9">
                            </amp-mowplayer>';
            */
            /*
            $video_insert = '<amp-iframe
                                width="1" height="1"
                                layout="responsive"
                                sandbox="allow-scripts allow-same-origin" src="//mowplayer.com/watch/v-'.$id['id'].'" frameborder="0"
                                allow="autoplay; encrypted-media"
                                allowfullscreen>
                                <amp-img layout="fill" src="https://cdn.mowplayer.com/assets/images/mow.jpg" placeholder></amp-img>
                            </amp-iframe>
                            <div data-mow_video=v-'.$id['id'].'></div>';
            */
            $video_insert = '<amp-iframe
                                width="16"
                                height="9"
                                layout="responsive"
                                sandbox="allow-scripts allow-same-origin" src="//mowplayer.com/watch/v-'.$id['id'].'?ratio=16:9" frameborder="0"
                                allow="autoplay; encrypted-media"
                                allowfullscreen>
                                <amp-img layout="fill" src="https://cdn.mowplayer.com/assets/images/mow.jpg" placeholder></amp-img>
                            </amp-iframe>
                            <div data-mow_video=v-'.$id['id'].'></div>';
            break;
    }

    /* url request mowplayer*/
    $url = 'mowplayer.com/watch/js/v-'.$id['id'].'.js';

    $iframe_yt = 'https://www.youtube.com/embed/'.$id['id'];

    $iframe_fb = 'https://www.facebook.com/mowplayer/videos/'.$id['id'];

    //HTTP REQUEST a MOWplayer
    // Create a cURL handle
    $handle = curl_init($url);
    curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
    /* Get the HTML */
    $response = curl_exec($handle);
    /* Check for 404 (file not found). */
    $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);





    if($httpCode == 404 && $option_passback == "1") {
        //si es script o iframe inserta el bkp
        //si el video es de youtube

        if (!$id['type']) {
            if ($option_settings == 'script' || $option_settings == 'iframe') {

                $video_insert = '<iframe width="560"
                                    height="315"
                                    src="'.$iframe_yt.'"
                                    frameborder="0" allow="accelerometer;
                                    autoplay;
                                    encrypted-media;
                                    gyroscope;
                                    picture-in-picture"
                                    allowfullscreen>
                                </iframe>';


            } else {
                // passback for AMP

                $video_insert = '<amp-youtube
                                    width="480"
                                    height="270"
                                    layout="responsive"
                                    data-videoid="'.$id['id'].'">
                                </amp-youtube>';

            }
        } else {
            if ($option_settings == 'script' || $option_settings == 'iframe') {

                $video_insert = '<div class="fb-video"
                                    data-href="'.$iframe_fb.'"
                                    data-width="auto"
                                    data-show-text="false">
                                </div>';
            } else {
                // passback for AMP
                //Required script
                $iframe_fb = 'https://www.facebook.com/'.$id['type'].'/videos/'.$id['id'];

                $req_script = '<script async custom-element="amp-facebook" src="https://cdn.ampproject.org/v0/amp-facebook-0.1.js"></script>';
                $video_insert = '<amp-facebook width="552" height="310"
                                    layout="responsive"
                                    data-embed-as="video"
                                    data-href="'.$iframe_fb.'">
                                </amp-facebook>';
            }
        }
    }

    curl_close($handle);
    return $video_insert;
}

add_shortcode('Mowplayer-Video', 'mow_yt_shortcode');
