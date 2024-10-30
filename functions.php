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

  /*
  //test youtube
  https://www.youtube.com/watch?v=f_SRd6OKmTc
  https://www.youtube.com/watch?v=7qFfFVSerQo
  test FACEBOOK
  https://www.facebook.com/mow/videos/554340498667393
  https://www.facebook.com/watch/live/?v=554340498667393&ref=watch_permalink //NO ANDA EN GUTENBERG
  https://www.facebook.com/watch/?v=554340498667393 - ERROR FIXED
  https://www.facebook.com/watch/?ref=watch_permalink&v=554340498667393 - ERROR FIXED
  https://www.facebook.com/watch/?ref=watch_permalink&amp;v=395282871030822

  */

function replace_media_url($content){
  //$regex_gral = "/(http(?:s?):\/\/(?:www\.)?(?:youtu(?:be\.com\/watch\?v=|\.be\/))\S*)|(http(?:s?):\/\/(?:www\.)?facebook.com(?:.*\/videos\/\d*)|(?:.*\/watch\/\?v=\d*)|(?:.*\/watch\/live\/\?v=\d*\&amp;ref=watch_permalink)|(?:.*\/watch\/\?ref=watch_permalink\&v=\d*))|(http(?:s?):\/\/(?:www\.)?facebook.com(?:.*\/watch\/\?ref=watch_permalink\&amp;v=\d*))/";
  $regex_gral = "/(http(?:s?):\/\/(?:www\.)?(?:youtu(?:be\.com\/watch\?v=|\.be\/))\S*)|(http(?:s?):\/\/(?:www\.)?facebook.com(?:.*\/videos\/\d*)|(?:.*\/watch\/live\/\?v=\d*\&amp;ref=watch_permalink)|(?:.*\/watch\/\?ref=watch_permalink\&v=\d*))|(http(?:s?):\/\/(?:www\.)?facebook.com(?:.*\/watch\/\?ref=watch_permalink\&amp;v=\d*))|(https?:\/\/www\.facebook\.com\/(?:watch\/\?v=\d+))/";
  //$regex_gral = "/(https?:\/\/www\.facebook\.com\/(?:watch\/\?v=\d+))/";

  $found = preg_match_all( $regex_gral , $content , $matches);

  if ($found) {
      $yt_regex = "/http(?:s?):\/\/(?:www\.)?(?:youtu(?:be\.com\/watch\?v=|\.be\/))/";
      //$fb_regex = "/http(?:s?):\/\/(?:www\.)?facebook.com\/(?:.*\/videos\/)/";
      $fb_regex = "/http(?:s?):\/\/(?:www\.)?facebook.com(?:.*\/videos\/)|(?:.*\/watch\/\?v=)|(?:.*\/watch\/live\/\?v=)|(?:.*\/watch\/\?ref=watch_permalink\&v=)|(http(?:s?):\/\/(?:www\.)?facebook.com(?:.*\/watch\/\?ref=watch_permalink\&amp;v=))/";
      $all_regex = array(0 => $yt_regex, 1 => $fb_regex);
      $all_ids = preg_replace($all_regex, "", $matches[0]);
      $ids_total = count($all_ids);

      for ($i=0; $i <= $ids_total ; $i++) {
          $search = "&amp;ref=watch_permalink";
          $all_ids[$i] = str_replace ( $search , "" , $all_ids[$i]  );

      }

      $valid_ids = function($item) use ($matches){
          return array_search($item, $matches[0]) == FALSE;
      };
      $all_ids = array_filter($all_ids, $valid_ids);
      foreach ($all_ids as $index => $cur_id) {
            $has_type = preg_match($fb_regex, $matches[0][$index]);
            $id = array(
              'id' => $cur_id,
              'type'=> $has_type ? 'fb': ''
            );
            /*
            if ($id['type']=="") {
                $content = '<style>
                                .wp-embed-responsive .wp-block-embed.wp-embed-aspect-16-9 .wp-block-embed__wrapper:before {
                                    padding-top: 0;
                                }
                            </style>';
            }
            */
            //HTTP REQUEST a MOWplayer
            $url = 'mowplayer.com/watch/js/v-'.$id['id'].'.js';
            $handle = curl_init($url);
            curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
            $response = curl_exec($handle);
            $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
            if($httpCode != 404) {
                $video_insert = mow_yt_shortcode( $id );
                $content = str_replace($matches[0][$index], $video_insert, $content);
            } else {
                $content = $content;
            }
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


/**
 * Custom ads.txt
 */
/*
 function get_ads() {
    $ads_file = get_home_path() . 'ads.txt'; //The ads.txt file.
    if(file_exists($ads_file)){
        $prev_content = file_get_contents($ads_file);
        $default_content = "adstxtcustommow \ncustomads \nmowads";
        $adstxtfile = file_put_contents($ads_file, $prev_content.$default_content);
        return $adstxtfile;
    } else {
        $default_content = "adstxtcustommow \ncustomads \nmowads";
        $adstxtfile = file_put_contents($ads_file, $default_content);
        return $adstxtfile;
    }
}
add_filter('get_ads_filter', 'get_ads');
*/

function get_ads(){
    $path = get_home_path();
    $ads_file = $path . 'ads.txt';
	$path = explode('wp-content',dirname(__FILE__));
	$path = $path[0];

	$ads_file = $path.'ads.txt';

    if(file_exists($ads_file)){
        $prev_content = file_get_contents($ads_file);

        //$default_content = "\n#Mowplayer\ngoogle.com, pub-6864402317197092, RESELLER, f08c47fec0942fa0\nadvertising.com,12010, RESELLER\ntritondigital.com, 38633, DIRECT, 19b4454d0b87b58b\nbeachfront.com, 1626, RESELLER, e2541279e8e2ca4d\nsmartadserver.com, 3261, RESELLER\ncontextweb.com, 560288, RESELLER, 89ff185a4c4e857c\npubmatic.com, 156439, RESELLER, 5d62403b186f2ace\npubmatic.com, 154037, RESELLER, 5d62403b186f2ace\nrubiconproject.com, 16114, RESELLER, 0bfd66d529a5\nappnexus.com, 3703, RESELLER, f5ab79cb980f11d1\ndistrictm.io, 101760, RESELLER, 3fd707be9c4527c3\nloopme.com, 5679, RESELLER, 6c8d5f95897a5a3b\nxad.com, 958, RESELLER, 81cbf0a75a5e0e9a\nrhythmone.com, 2564526802, RESELLER, a670c89d4a324e47\nsmaato.com, 1100044045, RESELLER, 07bcf65f187117b4\npubnative.net, 1006576, RESELLER, d641df8625486a7b\nadyoulike.com, b4bf4fdd9b0b915f746f6747ff432bde, RESELLER\naxonix.com, 57264, RESELLER\nadmanmedia.com, 43, RESELLER\nappnexus.com,1408,DIRECT,f5ab79cb980f11d1\nkonektimedia.com, 304-b4437, RESELLER\ngoogle.com, pub-7612738114777168, RESELLER, f08c47fec0942fa0\ngoogle.com, pub-1290995901905588, RESELLER, f08c47fec0942fa0\nprodooh.com, pdh-1534, DIRECT";

        $default_content = "\n#Mowplayer\ngoogle.com, pub-6864402317197092, RESELLER, f08c47fec0942fa0\nadvertising.com,12010, RESELLER\ntritondigital.com, 38633, DIRECT, 19b4454d0b87b58b\nsmartadserver.com, 3261, RESELLER\ncontextweb.com, 560288, RESELLER, 89ff185a4c4e857c\npubmatic.com, 156439, RESELLER, 5d62403b186f2ace\npubmatic.com, 154037, RESELLER, 5d62403b186f2ace\nrubiconproject.com, 16114, RESELLER, 0bfd66d529a55807\nopenx.com, 537149888, RESELLER, 6a698e2ec38604c6\nappnexus.com, 3703, RESELLER, f5ab79cb980f11d1\ndistrictm.io, 101760, RESELLER, 3fd707be9c4527c3\nloopme.com, 5679, RESELLER, 6c8d5f95897a5a3b\nxad.com, 958, RESELLER, 81cbf0a75a5e0e9a\nrhythmone.com, 2564526802, RESELLER, a670c89d4a324e47\nsmaato.com, 1100044045, RESELLER, 07bcf65f187117b4\npubnative.net, 1006576, RESELLER, d641df8625486a7b\nadyoulike.com, b4bf4fdd9b0b915f746f6747ff432bde, RESELLER\naxonix.com, 57264, RESELLER\nadmanmedia.com, 43, RESELLER\nappnexus.com,1408,DIRECT,f5ab79cb980f11d1\ntaboola.com,1373247,DIRECT,c228e6794e811952\nspotx.tv,71451,RESELLER\nspotxchange.com, 71451, RESELLER\nadvertising.com, 8603, RESELLER\npubmatic.com, 156307, RESELLER, 5d62403b186f2ace\nappnexus.com, 3364, RESELLER\nindexexchange.com, 183756, RESELLER\ncontextweb.com, 560382, RESELLER\nopenx.com, 539154393, RESELLER\ntremorhub.com, z87wm, RESELLER, 1a4e959a1b50034a\nrubiconproject.com, 16698, RESELLER, 0bfd66d529a55807\nfreewheel.tv, 799921, RESELLER\nrhythmone.com, 1166984029, RESELLER, a670c89d4a324e47\nsmartadserver.com, 3563, RESELLER\nbeachfront.com, 13749, RESELLER, e2541279e8e2ca4d\nadvertising.com, 28458, RESELLER\nemxdgt.com, 1643, RESELLER, 1e1d41537f7cad7f\nimprovedigital.com, 1577, RESELLER\nvideo.unrulymedia.com, 1166984029, RESELLER\n" ;

        $deleted = unlink($ads_file);

        $myfile = fopen($path."ads.txt", "w");
        $txt = $prev_content;
        fwrite($myfile, $txt);
        $txt = $default_content;
        fwrite($myfile, $txt);
        fclose($myfile);
        //chmod($myfile, 0777);
    } else {
        $myfile = fopen($path."ads.txt", "w");

        //$txt = "\n#Mowplayer\ngoogle.com, pub-6864402317197092, RESELLER, f08c47fec0942fa0\nadvertising.com,12010, RESELLER\ntritondigital.com, 38633, DIRECT, 19b4454d0b87b58b\nbeachfront.com, 1626, RESELLER, e2541279e8e2ca4d\nsmartadserver.com, 3261, RESELLER\ncontextweb.com, 560288, RESELLER, 89ff185a4c4e857c\npubmatic.com, 156439, RESELLER, 5d62403b186f2ace\npubmatic.com, 154037, RESELLER, 5d62403b186f2ace\nrubiconproject.com, 16114, RESELLER, 0bfd66d529a5\nappnexus.com, 3703, RESELLER, f5ab79cb980f11d1\ndistrictm.io, 101760, RESELLER, 3fd707be9c4527c3\nloopme.com, 5679, RESELLER, 6c8d5f95897a5a3b\nxad.com, 958, RESELLER, 81cbf0a75a5e0e9a\nrhythmone.com, 2564526802, RESELLER, a670c89d4a324e47\nsmaato.com, 1100044045, RESELLER, 07bcf65f187117b4\npubnative.net, 1006576, RESELLER, d641df8625486a7b\nadyoulike.com, b4bf4fdd9b0b915f746f6747ff432bde, RESELLER\naxonix.com, 57264, RESELLER\nadmanmedia.com, 43, RESELLER\nappnexus.com,1408,DIRECT,f5ab79cb980f11d1\nkonektimedia.com, 304-b4437, RESELLER\ngoogle.com, pub-7612738114777168, RESELLER, f08c47fec0942fa0\ngoogle.com, pub-1290995901905588, RESELLER, f08c47fec0942fa0\nprodooh.com, pdh-1534, DIRECT";
        
        $txt = "\n#Mowplayer\ngoogle.com, pub-6864402317197092, RESELLER, f08c47fec0942fa0\nadvertising.com,12010, RESELLER\ntritondigital.com, 38633, DIRECT, 19b4454d0b87b58b\nsmartadserver.com, 3261, RESELLER\ncontextweb.com, 560288, RESELLER, 89ff185a4c4e857c\npubmatic.com, 156439, RESELLER, 5d62403b186f2ace\npubmatic.com, 154037, RESELLER, 5d62403b186f2ace\nrubiconproject.com, 16114, RESELLER, 0bfd66d529a55807\nopenx.com, 537149888, RESELLER, 6a698e2ec38604c6\nappnexus.com, 3703, RESELLER, f5ab79cb980f11d1\ndistrictm.io, 101760, RESELLER, 3fd707be9c4527c3\nloopme.com, 5679, RESELLER, 6c8d5f95897a5a3b\nxad.com, 958, RESELLER, 81cbf0a75a5e0e9a\nrhythmone.com, 2564526802, RESELLER, a670c89d4a324e47\nsmaato.com, 1100044045, RESELLER, 07bcf65f187117b4\npubnative.net, 1006576, RESELLER, d641df8625486a7b\nadyoulike.com, b4bf4fdd9b0b915f746f6747ff432bde, RESELLER\naxonix.com, 57264, RESELLER\nadmanmedia.com, 43, RESELLER\nappnexus.com,1408,DIRECT,f5ab79cb980f11d1\ntaboola.com,1373247,DIRECT,c228e6794e811952\nspotx.tv,71451,RESELLER\nspotxchange.com, 71451, RESELLER\nadvertising.com, 8603, RESELLER\npubmatic.com, 156307, RESELLER, 5d62403b186f2ace\nappnexus.com, 3364, RESELLER\nindexexchange.com, 183756, RESELLER\ncontextweb.com, 560382, RESELLER\nopenx.com, 539154393, RESELLER\ntremorhub.com, z87wm, RESELLER, 1a4e959a1b50034a\nrubiconproject.com, 16698, RESELLER, 0bfd66d529a55807\nfreewheel.tv, 799921, RESELLER\nrhythmone.com, 1166984029, RESELLER, a670c89d4a324e47\nsmartadserver.com, 3563, RESELLER\nbeachfront.com, 13749, RESELLER, e2541279e8e2ca4d\nadvertising.com, 28458, RESELLER\nemxdgt.com, 1643, RESELLER, 1e1d41537f7cad7f\nimprovedigital.com, 1577, RESELLER\nvideo.unrulymedia.com, 1166984029, RESELLER\n" ;

        fwrite($myfile, $txt);
        fclose($myfile);
        //chmod($myfile, 0777);
    }

}

add_action('get_ads_filter', 'get_ads');
