<?php
    /*
     * Adds new optional database field for gift allocation
     */

    global $sb_db_tablename;
    global $wpdb;

    $sql = "ALTER TABLE $sb_db_tablename ADD gift_allocation varchar(255)";
    $wpdb->query( $sql );

    add_option( 'sd_show_allocate', False );
    add_option( 'sd_allocate_default', 'Give where the need is greatest' );

    // Return with the current version being set
    $current_version = '1.2.0';
?>
