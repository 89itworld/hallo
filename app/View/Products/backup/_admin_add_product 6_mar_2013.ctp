
<?php echo $this->Html->script('admin/tiny_mce/tiny_mce_src.js');?>
<script language="javascript" type="text/javascript">
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
		$('#myWordCount').text((total_word+1)-parseInt($('.sms_description').val().length)+" character(s) left.")
	
		$('.sms_description').live('keyup', function(){
			var words = $(this).val().length;
			$('#myWordCount').text((total_word+1)-words+" character(s) left.");
			if(words>=(total_word+1)){
			    $(this).val(content);
			    alert('no more than 300 words, please!');
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