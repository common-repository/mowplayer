<?php
/**
 * @param global wpdb
 */
$plugin_directory = get_site_url().'/wp-content/plugins/mowplayer/';
wp_register_style('mow-settings-styles',$plugin_directory.'css/mowplayer.css' );
wp_enqueue_style( 'mow-settings-styles');

global $wpdb;
$table_name = $wpdb->prefix . "mowplayer_settings";
$query = "SELECT * FROM ".$table_name;

$options = $wpdb->get_row($query);
$option_settings = $options->settings;
$option_passback = $options->passback;
$option_auto_replace = $options->autoreplace;

 ?>

<div class="wrap">
    <h1 class="wp-heading-inline">Mowplayer Settings</h1>
	<form id="settings-options" action="#" method="post">
        <div class="col-md-12">
            <label>Video Player Type</label>
    		<select id="type-insert-video" name="setting-video">
    			<option value="script">JavaScript</option>
    			<option value="iframe">HTML</option>
    			<option value="amp">HTML/AMP</option>
    		</select>
        </div>
        <div class="col-md-12">
            <label>Social Media BackUp</label>
            <?php
                if ($option_passback == '1') {
                    $option_checked = 'checked';
                } else {
                    $option_checked = '';
                }
             ?>
            <input type="checkbox" <?php echo $option_checked ?> data-toggle="toggle" name="setting-passback">
        </div>

        <div class="col-md-12">
            <label>Auto Replace</label>
            <?php
                if ($option_auto_replace == '1') {
                    $option_auto_replace = 'checked';
                } else {
                    $option_auto_replace = '';
                }
             ?>
            <input type="checkbox" <?php echo $option_auto_replace ?> data-toggle="toggle" name="setting-auto-replace">
        </div>

        <div class="col-md-12">
            <input type="submit" name="" value="Save settings">
        </div>
	</form>

<!--
        <div class="col-md-12">
            <label>Update ads.txt</label>
            <button type="button" name="update-ads" id="update-ads">UPDATE ADS.TXT</button>
        </div>
-->

</div>

<?php $plug_path = plugin_dir_path( __FILE__ ); ?>

<script>
      jQuery(function () {
        jQuery('#settings-options').on('submit', function () {
          jQuery.ajax({
            type: 'post',
            url: '../wp-content/plugins/mowplayer/content/ajax.php',
            data: jQuery('#settings-options').serialize(),
            success: function () {
              alert('Settings saved');
            }
          });
          return false;
        });
      });

      /* Show selected settings */
      jQuery(document).ready(function(){
          var settingSelected = '<?php echo $option_settings ?>';
          switch (settingSelected) {
              case 'script':
                    jQuery('#type-insert-video option[value="script"]').attr('selected', 'selected');
                  break;
              case 'iframe':
                    jQuery('#type-insert-video option[value="iframe"]').attr('selected', 'selected');
                  break;
              case 'amp':
                    jQuery('#type-insert-video option[value="amp"]').attr('selected', 'selected');
                  break;
          }
      });


      jQuery('#update-ads').click(function(){
          jQuery.ajax({
            //type: 'post',
            url: '../wp-content/plugins/mowplayer/content/ajax-update.php',
            //data: 'update',
            success: function () {
              alert('Ads.txt updated!');
            }
          });
          return false;
      });




</script>

<?php
global $wpdb;
$default_settings = $wpdb->get_results( "SELECT * FROM $table_name");

?>
