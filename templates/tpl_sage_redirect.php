<section>

    <p>To complete your donation you will be redirected to SagePay. If this does not happen automatically please click the 'Continue to SagePay' button.</p>

    <form method="POST" id="SagePayForm" action="https://test.sagepay.com/gateway/service/vspform-register.vsp">
        <input type="hidden" name="VPSProtocol" value= "3.00">
        <input type="hidden" name="TxType" value= "PAYMENT">
        <input type="hidden" name="Vendor" value= "innovista">
        <input type="hidden" name="Crypt" value= "<?php echo $crypt ?>">
        <input type="submit" value="Continue to SagePay" class="btn">
    </form>

</section>

<script>//setTimeout(function(){document.getElementById("SagePayForm").submit();},3000);</script>
