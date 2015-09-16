<?php echo $this->Html->script(array("ckeditor/ckeditor.js","ckfinder/ckfinder.js")); ?>
<script type="text/javascript">
    
    start_url = '';
    if (window.location.host == "localhost") {
	start_url = '/hallo';
    }
    window.onload = function(){
    CKEDITOR.replace( 'text_editor', {
    toolbar: 'Page',
    width: '1000',
    height: '400',
    filebrowserBrowseUrl : start_url+'/app/webroot/js/ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl : start_url+'/app/webroot/js/ckfinder/ckfinder.html?type=Images',
    filebrowserFlashBrowseUrl : start_url+'/app/webroot/js/ckfinder/ckfinder.html?type=Flash',
    filebrowserUploadUrl : start_url+'/app/webroot/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl :start_url+ '/app/webroot/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
    filebrowserFlashUploadUrl : start_url+'/app/webroot/js/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
});
  
}
    
</script>
