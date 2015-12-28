<?php
    /*
     * Update to SD v1.0.1
     * Adds
     * - live SagePay key
     * - test SagePay key
    */

    // Switch the sd_vendor_passphrase to
    // sd_vendor_passphrase_live and sd_vendor_passphrase_test

    $vendor_pass = get_option('sd_vendor_passphrase');

    add_option( 'sd_vendor_passphrase_live', $vendor_pass );
    add_option( 'sd_vendor_passphrase_test', $vendor_pass );

    delete_option( 'sd_vendor_passphrase' );

    // Return with the current version being set
    $current_version = '1.0.1';
?>
