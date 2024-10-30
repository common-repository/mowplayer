<?php
/**
 * This function is called when plugin is installed and create required database tables
 * @global Object $wpdb
 */
function mow_install() {
    global $wpdb;
		$table_name = $wpdb->prefix . "mowplayer_settings";
		$charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE  IF NOT EXISTS $table_name (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                settings VARCHAR(128) DEFAULT NULL,
                passback BOOLEAN DEFAULT NULL,
                autoreplace BOOLEAN DEFAULT NULL,
                PRIMARY KEY  (id)
              ) $charset_collate;";
    $wpdb->query($sql);

    /* Set default settings */
    $wpdb->insert($table_name, array(
        'id' => '1',
        'settings' => 'amp',
        'passback' => true,
        'autoreplace' => true,
    ));

    /**
     * Call ads.txt function
     */
    do_action('get_ads_filter');
}
