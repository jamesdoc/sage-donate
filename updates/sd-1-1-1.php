<?php
    /*
     * Update to SD v1.1.1
     * Be sensible- make GiftAid selection a checkbox, not a radiobox
     * Also adds a couple more CSS hooks
     */

    delete_option( 'sd_giftaid_no_label' );

    // Return with the current version being set
    $current_version = '1.1.1';
?>
