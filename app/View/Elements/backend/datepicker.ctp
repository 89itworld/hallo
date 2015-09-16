<?php
    echo $this->Html->css(array('admin/jquery-ui-1.8.22.custom.css'));
    echo $this->Html->script(array('admin/jquery-ui-1.8.22.custom.min'));
?>

<script type="text/javascript" language="javascrpt">
$(document).ready(function(){
    $(".datepicker_from").datepicker({
        maxDate: new Date,
        dateFormat: "mm-dd-yy",
        showOn: "button",
        buttonImage: "../../images/icons/fugue/calendar-month.png",
        buttonImageOnly: true,
    });
    $(".datepicker_to").datepicker({
        maxDate: new Date,
        dateFormat: "mm-dd-yy",
        showOn: "button",
        buttonImage: "../../images/icons/fugue/calendar-month.png",
        buttonImageOnly: true,
    });
    $(".datepicker_expire").datepicker({
        minDate: new Date,
        dateFormat: "yy-mm-dd",
        showOn: "button",
        buttonImage: "../../images/icons/fugue/calendar-month.png",
        buttonImageOnly: true,
    });
    $(".datepicker_expire_report").datepicker({
        
        dateFormat: "yy-mm-dd",
        showOn: "button",
        buttonImage: "../../images/icons/fugue/calendar-month.png",
        buttonImageOnly: true,
    });
});
</script>