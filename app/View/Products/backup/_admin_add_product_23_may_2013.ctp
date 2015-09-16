
<?php echo $this->Html->script('admin/tiny_mce/tiny_mce_src.js');?>
<script language="javascript" type="text/javascript">
	tinyMCE.triggerSave();
	tinyMCE.init({
		//General options
		mode : "exact",
		elements : "text_description",
		//mode : "textareas",
		// Theme options
		theme_advanced_buttons1 : "bold,italic,underline",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
	});
	$(document).ready(function(){
		
		var content;
		var default_value;
		
		var total_word = parseInt("<?php echo WORD_COUNT;?>");
		var message_count = parseInt("<?php echo MESSAGE_COUNT;?>");
		
		//$('#myWordCount').text((total_word)-parseInt($('.sms_description').val().length)+" character(s) left.")
		default_counter_value = parseInt($('.sms_description').val().length);
		sms_count = (default_counter_value <= 160)?1:(default_counter_value <=320)?2:3;
		$('#myWordCount').text(sms_count+" SMS -"+default_counter_value+"/"+total_word);
		
		$('.sms_description').live('keyup', function(){
			var words = $(this).val().length;
			sms_new_count = (words <= 160)?1:(words <=320)?2:3;
			$('#myWordCount').text(sms_new_count+" SMS -"+words+"/"+total_word);
			if(words >400){
				$('.sms_description').attr("value",($(this).val()).substring(0,(total_word)));
				$('#myWordCount').text(sms_new_count+" SMS -"+total_word+"/"+total_word);
			}
			
			if(words>=(total_word)){
			    $(this).val(content);
			    alert('no more than '+total_word+' words, please!');
			} else {   
			    content = $(this).val();
			}
		});
	});
</script>
<!-- Content -->
	<?php //echo $this->element('backend/admin_leftMenu');?>
	<article class="container_12">
		
		<section class="grid_12">
			<div class="block-border">
				<?php echo $this->element('backend/server_error');
					echo $this->Form->create('Product',array('url'=>array('controller'=>'products','action' => 'add_product', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),'id'=>'add_product', 'name' => 'add_product','class' => 'block-content form')); ?>
 
					<h1><?php echo ADD_PRODUCT; ?></h1>
					<fieldset>
						<?php echo $this->Session->flash(); ?>
						<p>
							<label for="simple-required">Vendor<?php echo REQUIRED; ?></label> 
							<?php echo $this->Form->input('vendor_id',array('type'=>'select','legend'=>false,'label'=>FALSE,'options'=>$vendor_list,'empty'=>"Select vendor",'class'=>'full-width')); ?>
							
						</p>
						<ul class="colx2-left" style="margin-bottom: 1.667em;">
							
							<div class="float-left gutter-right manual_voucher">
								<label for="stats-period">Voucher<?php echo REQUIRED; ?></label>
								<span class="">
								    <?php
								    echo $this->Form->input("voucher_value",array("type"=>"text","maxlength"=>"4"));
								    ?>
								</span>
							</div>
						</ul>
						<p style="clear:both;">
							<label for="simple-required">ID<?php echo REQUIRED; ?></label> 
							<?php echo $this->Form->input('p_id',array('type'=>'text','class'=>'full-width'));?>
						</p>
						<p style="clear:both;">
							<label for="simple-required">Product Title<?php echo REQUIRED; ?></label> 
							<?php echo $this->Form->input('title',array('class'=>'full-width'));?>
						</p>
						<p>
							<label for="simple-required">Product Code<?php echo REQUIRED; ?></label> 
							<?php echo $this->Form->input('product_code',array('type'=>'text','class'=>'full-width'));?>
						</p>
						<p>
							<label for="simple-required">Description[({VENDOR_NAME}, {VOUCHER}, {COUPON_CODE}, {ACTIVATION_CODE})<?php echo REQUIRED; ?>]<?php echo REQUIRED; ?></label> 
							<?php
							$description = PRODUCT_DESCRIPTION_DEFAULT_VALUE;
							echo $this->Form->input('description',array('type'=>'textarea','rows'=>'10','class'=>'full-width','value'=>$description,'id'=>'text_description'));
							
							?>
						</p>
						<p>
							<label for="simple-required">Sms Description[({VENDOR_NAME}, {VOUCHER}, {COUPON_CODE}, {ACTIVATION_CODE})<?php echo REQUIRED; ?>]<?php echo REQUIRED; ?></label> 
							<?php
							$sms_description = PRODUCT_SMS_DESCRIPTION_DEFAULT_VALUE;
							echo $this->Form->input('sms_description',array('type'=>'textarea','rows'=>'3','class'=>'full-width sms_description','value'=>$sms_description));
							
							?>
							<span id="myWordCount" style="font-weight:bold;"><?php echo WORD_COUNT;?> character(s) left</span>
						</p>
						<p>
							<label for="simple-action">Select action</label>
					 
							<?php				
								$options=array('1'=>'Save and publish','0'=> 'Save only');					
								echo $this->Form->input('is_active',array('type'=>'select','legend'=>false,'label'=>FALSE,'options'=>$options,'class'=>'full-width'));
							?>
						</p>
						<p>
							<span class="submit_button_p">
							<?php
							echo $this->Html->link( 'Cancel' , array( 'controller' => 'products' , 'action' => 'listing' , 'admin' => true ),array('class' => 'cancle_button'));
							echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;'));
							echo $this->Form->submit('Submit',array('div'=>FALSE,'onclick' => 'return ajax_form("add_product","admin/products/validate_add_product_ajax","receiver")','class'=>'submit_button'));
							?>
							</span>
							
						</p>
					</fieldset>
				<?php echo $this->Form->end();?>
			</div>
		</section>
		<div class="clear"></div>
</article>