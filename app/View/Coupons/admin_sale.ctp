<?php
     echo $this->Html->css(array('admin/jquery-ui-1.8.22.custom.css'));
     echo $this->Html->script(array('admin/jquery-ui-1.8.22.custom.min'));
?> 
<?php echo $this->Html->script(array('admin/jquery.bpopup-0.8.0.min') ); ?>   
<section class="grid_13">
		<div class="block-border">
			<div class="block-content" style="background:#F2F2F2;">
				<h1><?php echo LBL_COUPON_SALE; ?></h1>
				<?php /*
				   <div class="block-controls">
					   
					   <ul class="controls-buttons"> 							 
							   <?php //orange-keyword BF3636?>						 
							   <li> 
									   <ul class="tags float-left"> 
										   <?php $balance_class=(isset($alert_balance_limit) && !empty($alert_balance_limit))?'tag-time-yellow':'tag-time';?>
										   <li class="<?php echo $balance_class; ?>">
											    <?php echo 'Balance Coupon Limit :: <b class="textmoney">'; echo $limit=($coupon_limit <=0)?0:$coupon_limit; echo ' Dkk</b>'; ?>
										   </li> 
										   <?php if(isset($coupon_balance_limit) && !empty($coupon_balance_limit)){ ?>
											   <li class="tag-coins"><?php echo $coupon_balance_limit; ?></li>
										   <?php } ?>
									   </ul>
							   </li> 
					   </ul>
				   </div>
				   */ ?>
				<?php if($this->Session->Flash()){ ?>
					<div style="margin-bottom: 25px;"> <?php echo $this->Session->Flash();?> </div>	
				<?php }  ?>
				
			 <fieldset class="grey-bg no-margin">	
			      <?php echo $this->Form->create("Coupon",array("url"=>array("controller"=>"coupons","action"=>"sale","admin"=>true),"id"=>"coupon_sell",'class' => 'block-content form')); ?>
				<table width="45%" cellspacing="0" cellpadding="0" style="margin: 20px auto; padding-top: 0px;">
	 					<tbody>
	 						  <?php if($coupon_limit !=0){ ?>
	 							<tr>
	 								<td>
										<label for="vendor_id">Vælg Teleselskab</label>
									</td>
									<td colspan="2">
										<label for="vendor_id"> Vælg Taletid</label>
									</td> 
	 							</tr>
	 							<tr>
									<td width="200px">  
	 									<?php  
	 										echo $this->Form->input('vendor', array('options' => $vendor_listing, 'empty' => 'Choose Vendor...','label' => FALSE,'div' => FALSE,'style'=>'padding:10px !important;'));
 									 	?> 
	 								</td>
	 								<td width="200px">   
	 									<?php
	 										  $product_options=array();
	 										echo $this->Form->input('Voucher', array('options' => $product_options, 'empty' => 'Choose Voucher...','label' => FALSE,'div' => FALSE,'style'=>'padding:10px !important;'));
 									 	?>
	 								</td>  
	 								<td>  
	 									<?php 
	 										//echo $this->Form->submit('Buy Coupon',array('div'=>FALSE,'class'=>'submit_button'));
											echo $this->Form->button('Køb Taletid',array('type'=>'button','id'=>'buycoupon','div'=>FALSE,'class'=>'submit_button','onclick'=>"return BuyCouponConfirm();"));
        	   
										?>
									 
	 									<?php
	 										echo $this->Html->link( 'Annullere' , array( 'controller' => 'coupons' , 'action' => 'list' , 'admin' => true ),array('class' => 'cancle_button','style' => 'line-height: 1.3em;margin-left:30px;') );
	 									?>
	 								</td> 
								</tr> 
								
							<?php }else{ ?>
									<tr>
										<td><span style="color:#BF3636;font-size:14px;font-weight: bold;"<?php echo $balance_message; ?> </td>
									</tr>
								
							<?php } ?>
						       	
	 					</tbody>
				   </table>
				   <div style="text-align: center;padding-top:100px;">
					<?php echo $this->Html->image("hallo.png",array("title"=>"Hallo.dk","alt"=>"Hallo.dk"));?>
					<br>For support, ring 70 22 06 04 eller mail os på <a>salg@hallo.dk</a>.

				   </div>
			      <?php echo $this->Form->end();?>	
			 </fieldset>
		    </div>		
	       </div>
	       
		
</section>
<?php if($coupon_limit !=0){ ?>
	<div class="black_overlay">
	  <div id="black_overlay_loading" style="display:block;"><img src="/images/ajax-loader.gif" alt="" /></div>
	</div>
	
	<div class="popup_to_show"  title="Please Confirm..." style="display:none;"></div>
	<div class="clear"></div>
	<div id="popup_cid" style="display:none;"></div>
	<div id="popup_code" style="display:none;"></div>
	<div id="popup_desc" style="display:none;"></div>
	<div id="popup_to_showcoupon" style="display:none;">
		 
	</div>
	<div class="clear"></div>
	
	<style type="text/css">
		#page-loader {
		  position: absolute;
		  top: 0;
		  bottom: 0%;
		  left: 0;
		  right: 0%;
		  background-color: white;
		  z-index: 99;
		  display: block;
		  text-align: center;
		  width: 100%;
		  padding-top: 25px;
		}
		.textmoney{
			 font-size: 13px;
		}  
	</style> 

<?php } ?>