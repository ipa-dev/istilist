<?php if (!is_page(array('login', 'forgot-password', 'register', 'reset-password', 'add-member', 'thank-you', 'activation'))) { ?>
<div class="footer">
<?php global $options; ?>
    <div class="copy"><?= $options['general-copyright']; ?></div>
</div>
<?php } wp_footer(); ?>
</body>
</html>