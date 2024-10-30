<?php
    include('../../../../wp-load.php');
    $value_settings = $_POST['setting-video'];
    $value_passback = $_POST['setting-passback'];

    $value_auto_replace = $_POST['setting-auto-replace'];


    if ($value_passback != null) {
        $value_passback = true;
    } else {
        $value_passback = false;
    }

    if ($value_auto_replace != null) {
        $value_auto_replace = true;
    } else {
        $value_auto_replace = false;
    }

    $table_name = $wpdb->prefix . "mowplayer_settings";
    $data = array(
            'settings' => $value_settings,
            'passback' => $value_passback,
            'autoreplace' =>$value_auto_replace
    );
    $where = array(
            'id' => "1"
    );
    $wpdb->update( $table_name,  $data, $where  );


 ?>
