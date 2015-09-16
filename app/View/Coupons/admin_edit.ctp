<!-- Content --> 
	<article class="container_12">
		<?php
			
			echo $this->element('backend/datepicker');
			//echo $this->element('backend/admin_leftMenu');
		?>
		<section class="grid_12">
			<div class="block-border">
				<?php echo $this->element('backend/server_error');
				
				echo $this->Form->create("Coupon",array('url'=>array('controller'=>'coupons','action' => 'edit', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),'id'=>'add_record', 'name' => 'add_record','class' => 'block-content form'));
				echo $this->Form->input("id",array("type"=>"hidden"));
				echo $this->Form->input("batch_id",array("type"=>"hidden"));
				?>
 
					<h1><?php echo EDIT_COUPON; ?></h1>
					<fieldset>							 
						<?php echo $this->Session->flash(); ?>
						<p>
							<label for="simple-required">Vendor<?php echo REQUIRED; ?></label> 
							<?php
							
							$vendor_selected = ((isset($this->data['Coupon']['vendor_id']) && ($this->data['Coupon']['vendor_id'] != "")))?$this->data['Coupon']['vendor_id']:"";
							
							echo $this->Form->input('vendor_id',array('type'=>'select','legend'=>false,'label'=>FALSE,'options'=>$vendor_list,'empty'=>"Select vendor",'class'=>'full-width','selected'=>$vendor_selected)); ?>	
						</p>
						<p>
							<label for="simple-required">Voucher<?php echo REQUIRED; ?></label> 
							<?php
							$category_selected = ((isset($this->data['Coupon']['category_id']) && ($this->data['Coupon']['category_id'] != "")))?$this->data['Coupon']['category_id']:"";
							echo $this->Form->input('category_id',array('type'=>'select','legend'=>false,'label'=>FALSE,'options'=>$category_list,'empty'=>"Select voucher",'class'=>'full-width','id'=>'select_category','selected'=>$category_selected)); ?>	
						</p>
						<p>
							<label for="simple-required">Product Code<?php echo REQUIRED; ?></label> 
							<?php
							$product_selected = ((isset($this->data['Coupon']['product_code']) && ($this->data['Coupon']['product_code'] != "")))?$this->data['Coupon']['product_code']:"";
							echo $this->Form->input('product_code',array('type'=>'select','legend'=>false,'label'=>FALSE,'empty'=>"Select product code",'options'=>$product_options,'class'=>'full-width','id'=>'select_product_list','selected'=>$product_selected)); ?>	
						</p>
						<p>
							
							<label for="simple-required">Coupon Expire Date<?php echo REQUIRED; ?></label>
							<span class="input-type-text">
							    <?php echo $this->Form->input("expire_date",array("type"=>"text","class"=>"datepicker_expire",'readonly' => true));?>
							</span>
								
						</p>
						 
						<p>
							<label for="simple-required">Coupon Id<?php echo REQUIRED; ?></label> 
							<?php echo $this->Form->input('Coupon.coupon_id',array('type'=>'text','class'=>'full-width'));?>
						</p>
						<p style="margin-bottom:0px;">
							<label for="simple-required">Activation Code<?php echo REQUIRED; ?></label> 
						</p>
						<?php
						$data = explode("|",$this->data['Coupon']["activation_code"]);
						if($data != ""){
							$flag =0;
							foreach($data as $k=>$v){
								if($flag == 0){
									echo "<p>".$this->Form->input('activation_code][',array('type'=>'text','class'=>'full-width','style'=>'width:97%;','value'=>$v))."<span>".$this->Html->image("../images/add_icon.png",array('title'=>"Add More","alt"=>"Add More","class"=>"add_more_txt")).'</span><p>';
									$flag = 1;
								}else{
									echo "<p>".$this->Form->input('activation_code][',array('type'=>'text','class'=>'full-width','value'=>$v,'style'=>'width:97%;',))."<span>".$this->Html->image("../images/cross-on-white.gif",array('title'=>"Add More","alt"=>"Add More","class"=>"remove_row")).'</span></p>';
								}
							}	
						}else{
							echo "<p>".$this->Form->input('activation_code][',array('type'=>'text','class'=>'full-width','style'=>'width:97%;','value'=>$v))."<span>".$this->Html->image("../images/add_icon.png",array('title'=>"Add More","alt"=>"Add More","class"=>"add_more_txt")).'</span><p>';
						}
						?>
						
					</fieldset>
					<fieldset>
						 
						<p>
							<label for="simple-action">Select action</label>
							<?php				
								$options=array('1'=>'Save and publish','0'=> 'Save only');
								$active_selected = ((isset($this->data['Coupon']['is_active']) && ($this->data['Coupon']['is_active'] != "")))?$this->data['Coupon']['is_active']:"";
								echo $this->Form->input('Coupon.is_active',array('type'=>'select','legend'=>false,'label'=>FALSE,'options'=>$options,"selected"=>$active_selected,'class'=>'full-width'));
							?>
						<p>
							
						</p>
							<span class="submit_button_p">
							<?php
							echo $this->Html->link( 'Cancel' , array( 'controller' => 'coupons' , 'action' => 'list' , 'admin' => true ),array('class' => 'cancle_button'));
							echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;'));
							echo $this->Form->submit('Submit',array('div'=>FALSE,'onclick' => 'return ajax_form("add_record","admin/coupons/validate_data_ajax","receiver")','class'=>'submit_button'));
							?>
							</span>
							 
						</p>
					</fieldset>
				<?php echo $this->Form->end();?>
			</div>
		
		</section>
		 
		<div class="clear"></div>
</article>