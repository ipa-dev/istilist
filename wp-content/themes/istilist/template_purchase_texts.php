<?php /* Template Name: Purchase Texts */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) { 
      global $user_ID; 
?>
<div id="dashboard">
	<div class="maincontent noPadding">
        <div class="section group">
            <?php get_sidebar('menu'); ?>
            <div class="col span_9_of_12 matchheight">
                <div class="dash_content">
                    <h1><?php the_title(); ?></h1>
                    <div class="box">
                        <div id="form-container">
                            <div id="sq-ccbox" style="position:relative; top:130px">
                                <form id="nonce-form" novalidate action="<?php bloginfo('url'); ?>/process-card" method="post">
                                <fieldset>
                                    <span class="label">Card Number</span>
                                    <div id="sq-card-number"></div>

                                    <div class="third">
                                    <span class="label">Expiration</span>
                                    <div id="sq-expiration-date"></div>
                                    </div>

                                    <div class="third">
                                    <span class="label">CVV</span>
                                    <div id="sq-cvv"></div>
                                    </div>

                                    <div class="third">
                                    <span class="label">Postal</span>
                                    <div id="sq-postal-code"></div>
                                    </div>
                                </fieldset>

                                <button id="sq-creditcard" class="button-credit-card" onclick="requestCardNonce(event)">Pay $50.00</button>

                                <div id="error"></div>

                                <!--
                                    After a nonce is generated it will be assigned to this hidden input field.
                                -->
                                <input type="hidden" id="card-nonce" name="nonce">
                                </form>
                            </div> <!-- end #sq-ccbox -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
  document.addEventListener("DOMContentLoaded", function(event) {
    if (SqPaymentForm.isSupportedBrowser()) {
      paymentForm.build();
      paymentForm.recalculateSize();
    }
  });
</script>
<?php } ?>