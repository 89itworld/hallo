<?php //echo $this->Html->script('tiny_mce/tiny_mce.js');?>
<script type="text/javascript">
/*    
    tinyMCE.init({
	mode	: "textareas",	
	theme	: "advanced",
	elements: "textarea",
	plugins : "safari,ibrowser,jbimages,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

	// Theme options
	theme_advanced_buttons1 :"save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect",
	theme_advanced_buttons2 :"fontsizeselect,cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink",
	theme_advanced_buttons3 :"ibrowser,jbimages,tablecontrols,|,hr,image,cleanup,help,code,preview,|,forecolor,backcolor",
	theme_advanced_buttons4 :"removeformat,visualaid,media,insertdate,inserttime,sub,sup,|,charmap,emotions,iespell,advhr,|,print,|,ltr,rtl,|,fullscreen,anchor",
  
  theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	theme_advanced_resizing : true,
	
	height	: "300px",
	width	: "120px",
	editor_selector:'text_editor'
});
*/
</script>


<?php echo $this->Html->script('tinymce/tinymce.min.js');?>
<script type="text/javascript">
tinymce.init({
    selector: "textarea",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste moxiemanager"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>

