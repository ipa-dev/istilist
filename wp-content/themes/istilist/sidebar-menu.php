<?php global $user_ID; ?>
<?php $user_role = get_user_role($user_ID); ?>
<div class="col span_3_of_12 matchheight">
    <div class="dash_menu">
        <!-- If role == storeowner -->
        <?php if ($user_role == 'storeowner') {
    ?>
        <?php wp_nav_menu(array('theme_location'=>'mainmenu')) ?>
        <?php
} ?>
        <!-- If role == storeemployee -->
        <?php if ($user_role == 'storeemployee') {
        ?>
        <?php wp_nav_menu(array('theme_location'=>'storeemployeemenu')); ?>
        <?php
    } ?>
        <!-- If role == storesupervisor -->
        <?php if ($user_role == 'storesupervisor') {
        ?>
        <?php wp_nav_menu(array('theme_location'=>'storesupervisormenu')); ?>
        <?php
    } ?>
    </div>
</div>
