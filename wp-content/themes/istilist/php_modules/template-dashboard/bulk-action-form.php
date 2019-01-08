<div class="bullkActionsForm">
    <form method="post" action="<?= get_bloginfo( 'url' ); ?>/process-bulk-actions" id="bulkActionForm" >
        <a id="bulkActionSubmit" class="custom_button">Submit</a>
        <select form="bulkActionForm" id="bulk_select" name="bulk_select">
            <option value="NULL" selected="selected">Bulk Actions...</option>
            <option value="all-shoppers">All Shoppers</option>
            <option value="purchased">Purchased Shoppers</option>
            <option value="not-purchased">Not Purchased Shoppers</option>
            <option value="stylist-employees">Stylist/Employees</option>
        </select>
    </form>
</div>