<?php /* Template Name: Register */ ?>
<?php get_header(); ?>
<?php global $options; ?>
<div class="loginBlock">
    <div class="maincontent">
        <div class="section group">
            <div class="col span_4_of_12"></div>
            <div class="col span_4_of_12">
                <div class="logo"><h1><?php echo $options['general-logo']; ?></h1></div>
                <div class="loginSection">
                    <div class="box">
                        <div class="commonForm">
                            <div>
                                <label>Store</label>
                                <input type="text" id="store_name" />
                            </div>
                            <div>
                                <label>Contact</label>
                                <input type="text" id="contact_name" />
                            </div>
                            <div>
                                <label>Address</label>
                                <input type="text" id="address" />
                            </div>
                            <div>
                                <label>City</label>
                                <input type="text" id="city" />
                            </div>
                            <div>
                                <label>State</label>
                                <input type="text" id="state" />
                            </div>
                            <div>
                                <label>ZIP Code</label>
                                <input type="text" id="zipcode" />
                            </div>
                            <div>
                                <label>Phone</label>
                                <input type="text" id="phone_number" />
                            </div>
                            <div>
                                <label>Mobile</label>
                                <input type="text" id="mobile_number" />
                            </div>
                            <div>
                                <input type="checkbox" id="mobile_number_optin" value="1" /> Yes, I want istilist texts! <a href="#inlinemsg" class="fancybox"><i class="fa fa-info-circle"></i></a>
                            </div>
                            <div>
                                <label>Email addresses to be used for reporting</label>
                                <input type="text" id="email_address" />
                                <div class="divnote"><em>Separate multiple email addresses with a comma</em></div>
                            </div>
                            <div>
                                <label>What email address should shopper emails be sent from?</label>
                                <input type="text" id="email_to_shopper" />
                            <div>
                                <label>Website</label>
                                <input type="text" id="website" />
                            </div>
                            <div>
                                <label>Password</label>
                                <input type="password" id="pwd" />
                            </div>
                            <div>
                                <label>Security question for password reset</label>
                                <select id="security_questions">
                                    <option value="What was your childhood nickname?">What was your childhood nickname?</option>
                                    <option value="What is the name of your favorite childhood friend?">What is the name of your favorite childhood friend?</option>
                                    <option value="What street did you live on in third grade?">What street did you live on in third grade?</option>
                                    <option value="What school did you attend for sixth grade?">What school did you attend for sixth grade?</option>
                                    <option value="In what city or town was your first job?">In what city or town was your first job?</option>
                                </select><br />
                                <input type="text" name="security_answer" placeholder="Your answer" />
                            </div>
                            <div style="text-align: center;">
                                <span class="custom_button" id="register">REGISTER</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col span_4_of_12"></div>
        </div>
    </div>
</div>
<script>
jQuery(document).ready(function(){
    jQuery('#forms').validate({
        rules: {
            store_name: {
                required: true
            },
            contact_name: {
                required: true
            },
            phone_number: {
                required: true
            },
            email_address: {
                required: true,
                multiemail: true
            },
            pwd: {
                required: true
            },
            security_answer: {
                required: true
            }
        },
    })
    jQuery( '#register' ).click( function() {
        jQuery.ajax({
            url: window.location.origin + '/wp-json/istilist/v2/stores',
            method: 'POST',
            data: {
                store_name: document.getElementById( 'store_name' ).value,
                contact_name: document.getElementById( 'contact_name' ).value,
                address: document.getElementById( 'address' ).value,
                city: document.getElementById( 'city' ).value,
                state: document.getElementById( 'state' ).value,
                zipcode: document.getElementById( 'zipcode' ).value,
                phone_number: document.getElementById( 'phone_number' ).value,
                mobile_number: document.getElementById( 'mobile_number' ).value,
                mobile_number_optin: document.getElementById( 'mobile_number_optin' ).value,
                email_address: document.getElementById( 'email_address' ).value,
                email_to_shopper: document.getElementById( 'email_to_shopper' ).value,
                website: document.getElementById( 'website' ).value,
                pwd: document.getElementById( 'pwd' ).value,
                security_questions: document.getElementById( 'security_questions' ).value
            },
            success: function( response ) {
                window.location = window.location.origin + '/thank-you?action=registration';
            }
        })
    });

});
</script>
<?php get_footer(); ?>