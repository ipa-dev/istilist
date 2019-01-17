<div class="col span_6_of_12">
    <label>Type in a promo message that you want to be sent approximately 20 minutes after a shopper is registered. Type 'NA' if you do not want a daily text promo.</label>
    <textarea form="forms" name="daily_promo_text" id="daily_promo_text" maxlength="160" ><?php $daily_promo_text = get_user_meta($user_ID, 'daily_promo_text', true);
        if (!empty($daily_promo_text)) {
            echo $daily_promo_text;
        } else {
            echo 'NA';
        } ?></textarea>
    <div id="textarea_feedback"></div>
    <span id="testPromoText" class="custom_button">Send Test</a>
</div>
