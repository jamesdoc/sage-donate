<?php
    $status = get_option('sd_live_staging');
    $url = self::$sage_url[$status];
?>

<section class="sd_redirect">

    <p class="sd_redirect__message"><?php echo get_option('sd_redirect_message'); ?></p>

    <form method="POST" class="sd_redirect__form" id="SagePayForm" action="<?php echo $url; ?>">
        <input type="hidden" name="VPSProtocol" value= "3.00">
        <input type="hidden" name="TxType" value= "PAYMENT">
        <input type="hidden" name="Vendor" value= "innovista">
        <input type="hidden" name="Crypt" value= "<?php echo $crypt ?>">
        <input type="submit" value="Continue to SagePay" class="sd_redirect__form__btn btn">
    </form>

</section>

<script>setTimeout(function(){document.getElementById("SagePayForm").submit();}, 3000);</script>
