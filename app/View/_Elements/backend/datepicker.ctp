<?php
    echo $this->Html->css(array('admin/jquery-ui-1.8.22.custom.css'));
    echo $this->Html->script(array('admin/jquery-ui-1.8.22.custom.min'));
?>

<script type="text/javascript" language="javascrpt">
$(document).ready(function(){
    $(".datepicker_from").datepicker({dateFormat: "mm-dd-yy" });
    $(".datepicker_to").datepicker({dateFormat: "mm-dd-yy" });
});
</script>