<?php echo $this->element("backend/datepicker"); ?>  
<?php echo $this->Html->script('admin/ajaxfileupload.js'); ?>	
	 
<script type="text/javascript">
	var host = window.location.host;
	var proto = window.location.protocol;
	var ajax_url = proto+"//"+host+"/hallo/";
	$(function(){ 
	$('#buttonUpsload').click(function(){
		 
		//ev.preventDefault();
		//alert(fdata);
		//var new_url = ajax_url+"admin/coupon/upload_coupon_csv?file_To_Upload="+$("#fileToUpload").val();
		//$.post(new_url);
		ajaxFileUpload();
	});
	});
	function ajaxFileUpload()
	{
		//});
		//alert("fjsd");
		var file_value = $('#fileToUpload').val();
		var fileNameIndex = file_value.lastIndexOf("\\") + 1;
        var filename_one = file_value.substr(fileNameIndex);
		
		$("#loading")
		
		.ajaxStart(function(){
			 
			$(this).show();
		})
		.ajaxComplete(function(){
			$(this).hide();

		}); 
		$.ajaxFileUpload
		(
			{			 
				//url:ajax_url+'doajaxfileupload.php',		
				url:ajax_url+'admin/coupons/coupon_csv_conformation',		
				secureuri:false,
				fileElementId:'fileToUpload',
				dataType: 'json',
				data:{name:'logan', id:'id'},
				success: function (data, status)
				{	 
					if(typeof(data.error) != 'undefined')
					{
						if(data.error != '')
						{  
							alert(data.error);
						}else
						{  
						 	 //alert(data.msg + '-----' + data.picname);
					  		// $(".popup_to_show").html('<br><b>Total count </b>= '+ data.num + '<br><br><b>Filled record </b>= '+ data.data_count + '<br><br><b>Unfilled record </b>= '+ data.empty_data);
						   	$(".popup_to_show").html('<b><br>' + data.data_count +'/' + data.num +' product code '+ data.product_code +' of coupon value '+data.coupon_price +' Dkk with expiry date '+data.expiry_date +'<br><br>Total no of duplicate coupon is '+ data.duplicate_count +' with invalid coupon is ' + data.empty_data +'<br><br>Do you want to proceed?</b>');
						    
						    $(".popup_to_show").dialog({ 
						    		modal: true,
						    		width:580,
						    		height:200, 
						    		resizable: false,
						    	 	buttons: {
										"Submit": function() {
											$( this ).dialog( "close" );
											show_overlay();  
    										showPopUp("popup_div");
											
											$("#fileToUpload").remove();
											var newInput = document.createElement("input");
											 newInput.type="text";
  											 newInput.name="fileToUpload_one";
  											 newInput.id="fileToUpload_one";
  											 
  											 document.getElementById('submit_csv_form').appendChild(newInput);
  											 document.getElementById('fileToUpload_one').value = filename_one;
  											 
  											 $('#submit_csv_form').submit();
											},
										Cancel: function() {
											$( this ).dialog( "close" );
										}
									}
						    	});
					 	}
					}
				},
				error: function (data, status, e)
				{
					//var csvVal = $('#fileToUpload').val();
				    /*if(csvVal=='')
				    {
					        alert("Empty csv file!"); 
				    }else{ */
				    	//alert(data.error + status);
				    	alert(e); 
				   // }  
				}
			}
		) 
		return false;

	} 
	 
</script>	
 
 
 
<style>
#BrowserHidden {position:relative;text-align: right;-moz-opacity:0 ;filter:alpha(opacity: 0);opacity: 0;z-index: 2;}
</style>
<section class="grid_8" style="width:100%;">
    <div class="block-border">
        <?php echo $this->element('backend/server_error');
       // echo $this->Form->create('Coupon', array('enctype'=>'multipart/form-data','type'=>'POST', 'action'=>'upload_coupon_csv','name'=>'addform','id'=>'addform'));  
		  echo $this->Form->create('Coupon',array('url'=>array('controller'=>'coupons','action' => 'upload_coupon_csv', 'admin' =>true),'enctype' => 'multipart/form-data','inputDefaults'=>array('div'=>false,'label' => false),'class' => 'block-content form submit_form','id'=>'submit_csv_form')); 
	    ?>
        <h1><?php echo UPLOAD_COUPON_CSV; ?></h1>
        <span id="load_csv_loader" ></span> 
        <fieldset>
            <?php echo $this->Session->flash(); ?>
            <p>
                <label for="simple-required">Upload Coupon<?php echo REQUIRED; ?></label> 
                <?php
                 //echo $this->Form->input('vendor_id',array('type'=>'hidden',"id"=>"coupon_vendor_id"));
                 //echo $this->Form->input('category_title',array('type'=>'hidden',"id"=>"coupon_category_title"));
                 //echo $this->Form->input('category_id',array('type'=>'hidden',"id"=>"coupon_category_id"));
                 //echo $this->Form->input('product_code',array('type'=>'hidden',"id"=>"coupon_product_id"));
			 	  // echo $this->Form->input('csv_file',array('type'=>'file','class'=>'','id'=>'BrowserHidden','onchange'=>"getElementById('FileField').value = getElementById('BrowserHidden').value;"));
                
            	 //  echo $this->Form->input('csv_file_text',array('type'=>'text','class'=>'full-width',"id"=>"FileField","style"=>"width:85%;",'readonly' => true));
                
             ?> 
                <input id="fileToUpload" type="file" size="45" name="fileToUpload" class="input">
                <span id="new_fileupload_element"></span>
            </p>
            <p>
            	<?php 	
            	
            	echo $this->Html->image('loading30.gif',array('id'=>'loading','style'=>"display:none;padding-left:3px;"));  
    	 				 
				?>
            </p>
            <p>
                <span class="submit_button_p">
                <?php 
                	echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;'));
			echo $this->Form->button('Upload',array('id'=>'buttonUpsload','div'=>FALSE,'class'=>'submit_button','type'=>'button'));
        	    ?> 
                </span>
                    
            </p>
        </fieldset>
        <?php echo $this->Form->end();?>
    </div>
    <div id="temp_submit"></div>

</section> 
<div class="popup_to_show"  title="Status of uploaded CSV" style="display:none;"></div>
<div class="clear"></div>