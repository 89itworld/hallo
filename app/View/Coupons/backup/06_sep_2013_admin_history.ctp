<?php
echo $this->element("backend/datepicker"); 
?> 
<style> 
	.c_loading{
		background-image:url('../../images/table-loader.gif');
		background-repeat: no-repeat; 
		height: 22px;
		left: 12px;
		padding-left: 17px;
		position: relative; 
		display:none;
	}
</style>
<script type="text/javascript">

	var host = window.location.host;
	var proto = window.location.protocol;
	var ajax_url = proto+"//"+host+"/";
	function SendCouponEmail(row){
    	var ShowCouponId=$("#ShowCouponId"+row).html(); 
		var ShowActivationCode=$("#ShowActivationCode"+row).html(); 
	 	
	 	var email=prompt("Please enter email.",""); 
		if(email !=null) {
			
			var emReg = /^[a-z0-9_\+-]+(\.[a-z0-9_\+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,4})$/;
  	 		if(!emReg.test(email)) {
			    // Validation Failed
			    alert('Please enter a valid email address.');
			}else{
				$('#eloading'+row).css('display','inline'); 
		  		$.ajax({
		        	type: "POST",
				url:ajax_url+'admin/coupons/couponemail/'+ ShowCouponId +'/' + ShowActivationCode + '/'+email,
				success:function(response){  
					if(response){    
						$('#eloading'+row).css('display','none'); 
					 	   	alert('email sent successfully!'); 
					 	}else{
					 	 	alert('Sorry, email was not sent !'); 
					 	}
					}       	 
				});
			}
		} 
	}
	function SendCouponSms(row){
		var ShowCouponId=$("#ShowCouponId"+row).html(); 
		var ShowActivationCode=$("#ShowActivationCode"+row).html(); 
		
		var mobile=prompt("Please enter your mobile number.\nExp: 41234567 (8 digit)",""); 
		if(mobile !=null) {
		
				var mbReg = /^([1-9]{1}[0-9]{7})$/;
	  	 		if(!mbReg.test(mobile)) {
				    // Validation Failed
				    alert('Please enter a valid 8 digit mobile number.');
				}else{ 
					$('#smsloading'+row).css('display','inline');  
			  		$.ajax({
			        	type: "POST",
			            url:ajax_url+'admin/coupons/couponsms/'+ mobile +'/'+ ShowCouponId +'/' + ShowActivationCode ,
			            success:function(response){   
			            	$('#smsloading'+row).css('display','none');  
					 	   	alert(response); 
			            }       	 
					}); 
				} 
		} 
	}
	
</script>
<!--<article class="container_12">
    <section class="grid_8" style="width:100%;">-->
        <div class="block-border">
		<?php echo $this->element('backend/server_error');
                echo $this->Form->create('Coupon',array('url'=>array('controller'=>'coupons','action' => 'history', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),'id'=>'coupon_report', 'name' => 'coupon_report','class' => 'block-content form'));
			?>
                <fieldset class="">
		    <div class="columns">
			<p class="colx2-left">
				
			       <div class="float-left gutter-right">
				   <label for="stats-period">From</label>
				   <span class="input-type-text">
				       <?php echo $this->Form->input("from",array("type"=>"text","class"=>"datepicker_from" ,'readonly' => true));?>
				   </span>
			       </div>
			       <div class="float-left gutter-right">
				   <label for="stats-period">TO</label>
				   <span class="input-type-text">
				       <?php echo $this->Form->input("to",array("class"=>"datepicker_to" ,'readonly' => true));?>
				   </span>
			       </div> 
			       <div class="float-left gutter-right">
					<label for="stats-period">Coupon Id</label>
					<span class="input-type-text">
					    <?php echo $this->Form->input("c_id",array("type"=>"text" ));?>
					</span>
			       </div>
				<div class="float-left gutter-right">
					<label for="stats-period">Sub Dealer</label>
					<?php
					
					$selected_sub_dealer = ((isset($sub_dealer) && !empty($sub_dealer))?$sub_dealer:""); 
					echo $this->Form->input('s_d',array('type'=>'select','options'=>$sub_dealer_data,'selected'=>$selected_sub_dealer,"empty"=>"All","label"=>false,"div"=>false));
					?>
				</div>
				<div class="float-left gutter-right">
					<label for="stats-period">Product</label>
					<?php
					
					$selected_product = ((isset($product_c) && !empty($product_c))?$product_c:""); 
					echo $this->Form->input('p_c',array('type'=>'select','options'=>$product_data,'selected'=>$selected_product,"empty"=>"All","label"=>false,"div"=>false));
					?>
				</div>
			       <div class="float-left gutter-right">
				   <label for="stats-period">Maximum Records</label>
				   <span class="input-type-text">
				       <?php echo $this->Form->input("limit",array("type"=>"text" ));?>
				   </span>
			       </div>
			       
			       <div class="float-left gutter-right">
				   <label for="stats-period">&nbsp;</label>
				   <span class="">
					   <?php echo $this->Form->button('Search',array("type"=>"submit",'div'=>FALSE)); 
						     echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 20px;width:auto;'));				    
					    ?>
				   </span>
			       </div>
			</p>
		    </div>
		</fieldset>
            <?php echo $this->Form->end();?>
        </div>
    
    <!--</section>-->
     
    <div class="clear"></div>
<!--</article>-->

<section class="grid_13">
    <div class="block-border">
	<?php echo $this->Form->create("Model",array("url"=>array("controller"=>"vendors","action"=>"bulk_action","admin"=>true),"id"=>"bulk_action",'class' => 'block-content form')); ?>
	<h1><?php echo COUPON_LISTING; ?></h1>
	
	<div class="no-margin"><table class="table" cellspacing="0" width="100%">
	
	    <thead>
	    <tr>
	    <th scope="col">
			<span class="sortkey">No.</span> 
		</th>
		<th scope="col">
			<span class="sortkey">Dealer Name</span> 
		</th>
		<th>
			<span class="sortkey">Dealer Type</span> 
		</th>
		<th scope="col">
			<span class="sortkey">Coupon Id</span> 
		</th>
		<!--<th scope="col">
			<span class="sortkey">Activation Code</span>
		</th>-->
		<th scope="col">
			<span class="sortkey">Price (DKK)</span>
	 	</th> 
		<th scope="col">
			<span class="sortkey">Sold Date</span>
	 	</th>
		<th scope="col">
			<?php echo '<span class="sortkey">Send Sms</span>'; ?> 
		</th>
		<th scope="col">
			<?php echo '<span class="sortkey">Send Email</span>'; ?>
		</th>
			    
	    </tr>
	    </thead>
		
	    <tbody>
	     <?php 
	    if(!empty($coupon_data)){	$i=0;
	    	foreach($coupon_data as $Record){ ?>
		<tr>
		    <td> 
				<?php echo ++$i; ?>
		    </td>
		    <td> 
				<?php echo $Record['UserProfile']['first_name']; ?>
			</td>
			<td>
				<?php echo ($Record['User']['role_id']== 3)?'<b>Sub-Dealer</b>':'<b>Dealer</b>'; ?>
		    </td>
		    <td> 
				<span id="ShowCouponId<?php echo $i; ?>"><?php echo $Record['Coupon']['coupon_id']; ?></span>
		    </td>
		    <?php /*
		    <td> 
				<span id="ShowActivationCode<?php echo $i; ?>"><?php  echo $Record['Coupon']['activation_code']; ?></span>
		    </td>
		    */ ?>
		    <td> 
				<?php echo $Record['CouponSale']['price']; ?>
		    </td>
		    <td> 
				<?php  echo $Record['CouponSale']['created']; ?>
		    </td>
		    <td> 
				<?php echo $this->Form->button('Send Sms',array('type'=>'button','id'=>'sendsms','div'=>FALSE,'class'=>'submit_button','style' => 'line-height:16px;','onclick'=>"return SendCouponSms(".$i.");")); ?>
     	 		<span id="smsloading<?php echo $i; ?>" class="c_loading"></span> 
     	 	</td>
		    <td>  
				<?php echo $this->Form->button('Send Mail',array('type'=>'button','id'=>'sendmail','div'=>FALSE,'class'=>'submit_button','style' => 'line-height:16px;','onclick'=>"return SendCouponEmail(".$i.");")); ?>
    	 		<span id="eloading<?php echo $i; ?>" class="c_loading"></span> 
    	 	</td> 
		   
		</tr>
	<?php } 
	    }else{
		echo '<tr><td colspan=9>'.NO_RECORD_FOUND.'</td><tr>';
	    }
	    ?>
	     
    	</tbody>
	
	</table>
	</div> 
	<ul class="message no-margin">
	    <li>  
		 <?php echo (isset($i) > 0)?$i.' record{s} found!':'no record found!'; ?>
	    </li>
	</ul>
	<div class="block-footer"></div>
	<?php echo $this->Form->end();?>			
	</div>
