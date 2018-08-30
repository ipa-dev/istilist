<div class="bullkActionsForm">
    <form method="post" action="http://istilist.com/wp-content/themes/istilist/php_modules/template-dashboard/bulk-actions/module-onbulkselect.php" id="bulkActionForm">
        <div class="submit" style="width:50px;" onclick="confirmation();">Submit</div>
    </form>
    <select form="bulkActionForm" id="bulk_select" name="bulk_select"  style="">
        <option value="NULL" selected="selected">Bulk Actions...</option>
        <option value="purchased">Purchased</option>
        <option value="not-purchased">Not Purchased</option>
    </select>
</div>
<script type="text/javascript">
    function confirmation() {
        swal({
            title: "Are you sure?",
            text: "This action cannot be undone.",
            type: "warning",
            showCancelButton: true,
            showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false,
                closeOnCancel: true
        }, function (isConfirm) {
            if (isConfirm) {
                document.getElementById("bulkActionForm").submit();
            }
        });
    }
</script>