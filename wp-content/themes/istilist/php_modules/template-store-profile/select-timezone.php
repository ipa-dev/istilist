<input type="hidden" id="js_timezone" name="selecttimezone"/>
<script type="text/javascript">
    var tz = jstz.determine();
    document.getElementById("js_timezone").value = tz.name();
</script>      
