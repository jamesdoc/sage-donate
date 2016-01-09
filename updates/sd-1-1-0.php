<?php
    /*
     * Update to SD v1.1.0
     * Adds editable GiftAid form text
     * Prevents form being submitted with £0.00
    */

    $giftaid_heading = "Are you a UK tax payer?";
    $giftaid_content = "<p>If you are a UK taxpayer, the value of your gift can be increased by 25% under the Gift Aid scheme at no extra cost to you.</p><p>I understand that I must pay an amount of UK income Tax and/or Capital Gains Tax, excluding VAT and Council tax, at least equal to the tax that charities reclaim on my donations (currently 25p for every £1 donated).</p>";
    $giftaid_yes = "I am a UK taxpayer and I would like this and all future donations, until I notify you otherwise, to be Gift Aid donations.";
    $giftaid_no = "I am not a UK taxpayer.";

    add_option( 'sd_giftaid_heading', $giftaid_heading );
    add_option( 'sd_giftaid_content', $giftaid_content );
    add_option( 'sd_giftaid_yes_label', $giftaid_yes );
    add_option( 'sd_giftaid_no_label', $giftaid_no );

    // Return with the current version being set
    $current_version = '1.1.0';
?>
