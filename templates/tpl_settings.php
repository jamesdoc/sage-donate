<div class="wrap">
    <h2>WP Plugin Template</h2>
    <form method="post" action="options.php">
        <?php @settings_fields('sd_sage_donate'); ?>
        <?php @do_settings_fields('sd_sage_donate'); ?>

        <fieldset>
            <h3>Sagepay Settings</h3>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="sd_vendor_id">Vendor ID</label></th>
                    <td><input class="regular-text code" type="text" name="sd_vendor_id" id="sd_vendor_id" value="<?php echo get_option('sd_vendor_id'); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="sd_vendor_passphrase">Vendor Passphrase</label></th>
                    <td><input class="regular-text code" type="text" name="sd_vendor_passphrase" id="sd_vendor_passphrase" value="<?php echo get_option('sd_vendor_passphrase'); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="sd_payment_description">Payment Description<br /><small>Text sent to SagePay to describe the payment (eg: Online donation)</small></label></th>
                    <td><input class="regular-text code" type="text" name="sd_payment_description" id="sd_payment_description" value="<?php echo get_option('sd_payment_description'); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="live_staging">Plugin status</label></th>
                    <td>
                        <label><input type="radio" name="sd_live_staging" id="sd_live_staging" value="testing"<?php if (get_option('sd_live_staging')=='testing') { echo ' checked="checked"'; } ?> /> Testing</label><br />
                        <label><input type="radio" name="sd_live_staging" id="sd_live_staging" value="live"<?php if (get_option('sd_live_staging')=='live') { echo ' checked="checked"'; } ?> /> Live</label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="sd_currency">Currency</label></th>
                    <td>
                        <label><input type="radio" name="sd_currency" id="sd_currency" value="GBP"<?php if (get_option('sd_currency')=='GBP') { echo ' checked="checked"'; } ?> /> GBP</label><br />
                        <label><input type="radio" name="sd_currency" id="sd_currency" value="USD"<?php if (get_option('sd_currency')=='USD') { echo ' checked="checked"'; } ?> /> USD</label><br />
                        <label><input type="radio" name="sd_currency" id="sd_currency" value="EUR"<?php if (get_option('sd_currency')=='EUR') { echo ' checked="checked"'; } ?> /> EUR</label>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="sd_giftaid">Accept Gift Aid?</label></th>
                    <td><input type="checkbox" name="sd_giftaid" id="sd_giftaid" value="1"<?php if (get_option('sd_giftaid')==1) { echo ' checked="checked"'; } ?> /></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="sd_footnote_message">Footnote message<br /><small>This text is shown directly below your form.</small></label></th>
                    <td>
                        <textarea class="large-text code" name="sd_footnote_message" id="sd_footnote_message"><?php echo get_option('sd_footnote_message'); ?></textarea>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="sd_redirect_message">Redirect message<br /><small>This text is shown briefly before redirecting to the SagePay gateway.</small></label></th>
                    <td>
                        <textarea class="large-text code" name="sd_redirect_message" id="sd_redirect_message"><?php echo get_option('sd_redirect_message'); ?></textarea>
                    </td>
                </tr>

            </table>
        </fieldset>

        <fieldset>
            <h3>Mailing list</h3>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="sd_mailing_list_signup">Invite users to sign up to mailing list</label></th>
                    <td><input type="checkbox" name="sd_mailing_list_signup" id="sd_mailing_list_signup" value="1"<?php if (get_option('sd_mailing_list_signup')==1) { echo ' checked="checked"'; } ?> /></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="sd_mailing_list_cta">Custom mailing list label<br /><small>Eg: Sign up to receive monthly updated about the charity</small></label></th>
                    <td><input class="regular-text code" type="text" name="sd_mailing_list_cta" id="sd_mailing_list_cta" value="<?php echo get_option('sd_mailing_list_cta'); ?>" /></td>
                </tr>
            </table>

        </fieldset>

        <fieldset>
            <h3>After donation</h3>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="sd_success_url">Success URL</label></th>
                    <td>
                        <input class="regular-text code" type="text" name="sd_success_url" id="sd_success_url" value="<?php echo get_option('sd_success_url'); ?>" /><br />
                        <small>Remember to add the <code>[sage_donate_success]</code> tag to this page.</small>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="sd_failure_url">Failure URL</label></th>
                    <td>
                        <input class="regular-text code" type="text" name="sd_failure_url" id="sd_failure_url" value="<?php echo get_option('sd_failure_url'); ?>" /><br />
                        <small>Remember to add the <code>[sage_donate_failure]</code> tag to this page.</small>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="sd_notify_email">Send notification email to</label></th>
                    <td><input class="regular-text code" type="text" name="sd_notify_email" id="sd_notify_email" value="<?php echo get_option('sd_notify_email'); ?>" /></td>
                </tr>

                <tr valign="top">
                    <th scope="row"><label for="sd_confirmation">Send a confirmation email to donor</label></th>
                    <td>
                        <input type="checkbox" name="sd_confirmation" id="sd_confirmation" value="1"<?php if (get_option('sd_confirmation')==1) { echo ' checked="checked"'; } ?> /><br />
                        <p>
                            <textarea class="large-text code" name="sd_confirmation_body" id="sd_confirmation_body"><?php echo get_option('sd_confirmation_body'); ?></textarea>
                        </p>
                    </td>
                </tr>



            </table>

        </fieldset>

        <?php @submit_button(); ?>
    </form>
</div>
