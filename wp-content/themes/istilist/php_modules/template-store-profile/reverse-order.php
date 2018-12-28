<div class="col span_6_of_12">
    <label>Check box to show shoppers in reverse order</label>
    <input type="checkbox" name="reverse_order" 
        <?php if (!empty($user_reverse_order) && $user_reverse_order=='on') {
            echo 'checked="checked"';
        } ?>
    />
</div>