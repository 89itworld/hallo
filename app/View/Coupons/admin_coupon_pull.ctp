<?php
     echo $this->Html->css(array('admin/jquery-ui-1.8.22.custom.css'));
     echo $this->Html->script(array('admin/jquery-ui-1.8.22.custom.min'));
     echo $this->Html->script(array('admin/jquery.bpopup-0.8.0.min'));
?>   
<section class="grid_13">
     <div class="block-border">
	  <div class="block-content" style="background:#F2F2F2;">
	       <h1><?php echo LBL_COUPON_SALE; ?></h1>
	       <?php if($this->Session->Flash()){ ?>
	       <div style="margin-bottom: 25px;"> <?php echo $this->Session->Flash();?> </div>	
	       <?php }  ?>
				
	       <fieldset class="grey-bg no-margin">	
		    <?php echo $this->Form->create("Coupon",array("url"=>array("controller"=>"coupons","action"=>"coupon_pull","admin"=>true),"id"=>"coupon_sell",'class' => 'block-content form')); ?> 
		    <table width="45%" cellspacing="0" cellpadding="0" style="margin: 20px auto; padding-top: 0px;">
			 <tbody>
					       
			      <tr>
				   <td>
					<label for="vendor_id">Vælg Produkt</label>
				   </td>
				   <td colspan="2">
					<label for="vendor_id">Vælg Mængde</label> 
				   </td> 
			      </tr>
			      <tr>
				   <td width="200px">  
					<?php  
					echo $this->Form->input('Product', array('options' => $product_data, 'empty' => 'Vælg Produkt...','label' => FALSE,'div' => FALSE,'style'=>'padding:10px !important;'));
					?> 
				   </td>
				   <td width="200px">   
					<?php 
					echo $this->Form->input('Count', array('type'=>'text','maxlength'=> '3','label' => FALSE,'div' => FALSE,'style'=>'padding:10px !important;'));
					?>
				   </td>  
				   <td>  
					<?php  
					echo $this->Form->button('Træk',array('type'=>'button','id'=>'buycoupon','div'=>FALSE,'class'=>'submit_button','onclick'=>"return BuyBulkCouponConfirm();"));   
					echo $this->Html->link( 'Annullere' , array( 'controller' => 'coupons' , 'action' => 'list' , 'admin' => true ),array('class' => 'cancle_button','style' => 'line-height: 1.3em;margin-left:30px;') );
					?>
				   </td> 
			      </tr> 
			 </tbody>
		    </table>
	       </fieldset>
	       <?php echo $this->Form->end();?>	 
	  </div>		
     </div>		 
</section>
<div class="black_overlay">
     <div id="black_overlay_loading" style="display:block;"><?php echo $this->Html->image('../images/ajax-loader.gif',array('alt' => 'loading','title' =>'Please wait ..'));?>  </div>
</div>
<div class="popup_to_show"  title="Please Confirm..." style="display:none;"></div>
<div class="clear"></div> 
<div id="popup_to_showcoupon" style="display:none;">   </div>
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

 
