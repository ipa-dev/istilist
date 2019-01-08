<?php /* Template Name: Process Card */ ?>
<?php get_header(); ?>
<?php if (is_user_logged_in()) { 
        global $user_ID; 
        $nonce = $_POST['nonce'];

        //fail if nothing sent to server
        if (is_null($nonce)) {
            error_log("No nonce was passed to processcard.php.");
            echo "Invalid card data";
            http_response_code(422);
            return;
        }

        //Charge the card for the order using the nonce.
        $transactions_api = new \SquareConnect\Api\TransactionsApi();
        $note = sprintf("{'fn':'%s', 'em':'%s' }", $fullName, $email); //Add a note to capture fullname and email.
        $charge = new \SquareConnect\Model\ChargeRequest(); 
        $charge->setCardNonce($nonce);
        $charge->setIdempotencyKey(com_create_guid());
        $charge->setAmountMoney("5000");
        $charge->setNote($note);

        //If this call fails, let the top level catch block handle the exception.
        $chargeResult = $transactions_api->charge($locationId, $charge);

        //Credit the users account proper amount of text messages
        $num_texts_left = get_user_meta($user_ID, 'text_credit', true);
        if (!empty($num_texts_left)) {
            update_user_meta($user_ID, 'text_credit', $num_texts_left + 1250);
        }
        header('Location: ' . get_bloginfo( 'url' ) . '/store-profile');
} ?>