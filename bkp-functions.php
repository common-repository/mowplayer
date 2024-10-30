<?php
/**
 * @param string $method
 * @param array $headers
 * @param array $query_params
 * @return array
 */

 global $wpdb;

 /**
  * action on publish post
  */
function replace_media_url($content){

    //https://www.youtube.com/watch?v=doe3kUqHIcM
    $regex_1 = "/http(?:s?):\/\/(?:www\.)?(?:youtu(?:be\.com\/watch\?v=|\.be\/))\w*/";

    //https://www.youtube.com/watch?v=doe3kUqHIcM&amp;ab_channel=PlayStation

    //<iframe width="560" height="315" src="https://www.youtube.com/embed/7qFfFVSerQo" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

    //<iframe title="Estos CUBREBOCAS luchan contra el COVID-19" width="640" height="360" src="https://www.youtube.com/embed/f_SRd6OKmTc?feature=oembed" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen=""></iframe>
    $iframeSrc = preg_replace('/<iframe[^>]src\s=\s*"?https:?:W[^\s"\/]youtube.com(?:\[^>])/');
    $regex_2 = "/ /";

    //$fb_url_1 = "https://www.facebook.com/9gag/videos/797836024082952";
    $regex_3 = "/http(?:s?):\/\/(?:www\.)?facebook.com\/(?:.*\/videos\/\d*)/";

    /*
    $fb_url_2 = "https://www.facebook.com/watch/live/?v=554340498667393&ref=watch_permalink";
    $fb_url_3 = "https://www.facebook.com/watch/?v=554340498667393";
    $fb_url_4 = "https://www.facebook.com/watch/?ref=watch_permalink&v=395282871030822";
    */



    //test youtube
    //https://www.youtube.com/watch?v=f_SRd6OKmTc
    //https://www.youtube.com/watch?v=7qFfFVSerQo

    //test FACEBOOK
    //https://www.facebook.com/mow/videos/554340498667393
    //https://www.facebook.com/mow/videos/10153231379946729/

    $regex_gral = "/(http(?:s?):\/\/(?:www\.)?(?:youtu(?:be\.com\/watch\?v=|\.be\/))\w*)|(http(?:s?):\/\/(?:www\.)?facebook.com\/(?:.*\/videos\/\d*))/";
    $found = preg_match_all( $regex_gral , $content , $matches);

    $qty_found = $found;

    if ($found) {

        foreach ($matches[0] as $match) {


            //si es youtube
            $pattern_yt = "/http(?:s?):\/\/(?:www\.)?(?:youtu(?:be\.com\/watch\?v=|\.be\/))/";
            $url_type = preg_match($pattern_yt, $match, $coincidencia);

            //si es facebook https://www.facebook.com/mow/videos/10153231379946729/
            $pattern_fb_1 = "/http(?:s?):\/\/(?:www\.)?facebook.com\/(?:.*\/videos\/)/";
            $url_type_1 = preg_match($pattern_fb_1, $match, $coincidencia);


            //reemplaza youtube
            if ($url_type) {
                $id_yt =  preg_replace($pattern_yt, '', $match);
                $id = array(
                        'id' => $id_yt,
                        'type'=>''
                        );
                $video_insert = mow_yt_shortcode( $id );

                $yt_url_regex = "/http(?:s?):\/\/(?:www\.)?(?:youtu(?:be\.com\/watch\?v=|\.be\/))\w*/";
                $content = preg_replace($yt_url_regex, $video_insert, $content);

            }

/*
            //reemplaza facebook https://www.facebook.com/mow/videos/10153231379946729/
            if ($url_type_1) {
                $id_fb_1 = preg_replace($pattern_fb_1, '', $match);

                $id = array(
                        'id' => $id_fb_1,
                        'type'=>'fb'
                        );
                $video_insert = mow_yt_shortcode( $id );

                $fb_url_regex = "/http(?:s?):\/\/(?:www\.)?facebook.com\/(?:.*\/videos\/\d*)/";

                //$fb_url_sdk = '/data-href="http(?:s?):\/\/(?:www\.)?facebook.com\/(?:.*\/videos\/\w*")/';
                //$url_fb_escape = preg_match($fb_url_sdk, $match, $coincidencia);

                //if ($url_fb_escape) {
                    $content = preg_replace($fb_url_regex, $video_insert, $content);

                //}

            }
*/
        }



    }

    return $content;
}

$table_name = $wpdb->prefix . "mowplayer_settings";
$query = "SELECT * FROM ".$table_name;
$options = $wpdb->get_row($query);

$option_auto_replace = $options->autoreplace;

if ($option_auto_replace == '1') {
    add_filter('content_save_pre','replace_media_url');
}
