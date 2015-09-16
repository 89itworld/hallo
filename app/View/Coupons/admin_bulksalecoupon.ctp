<?php echo $this->Html->css(array('admin/common','admin/standard') ); ?>   
 
<style type="text/css">  
	.errormessage{
		color:#ff0000;
		font-weight:bold;
	}
	.greenmessage{
		color:#00ff00;
		font-weight:bold;
	}
	.boldtext{
		font-size:26px;
		font-weight:bold;
	}
	.closepopup{
		background-image: url("../../images/icons/web-app/32/Delete.png");
		background-repeat:no-repeat;
	    color: red;
	    cursor: pointer;
	    cursor: pointer;
	    display: inline-block; 
	    position: absolute;
	    padding-bottom: 17px;
	    padding-left: 26px;
	    position: absolute;
	    right: -19px;
	    top: -20px; 
	}
	.small-div{ 
		padding-top: 1.833em;
	}
	.btn{
		margin-left:77px;
	} 
	#c_loading{
		background-image:url('../../images/table-loader.gif');
		background-repeat: no-repeat; 
	    height: 22px;
	    display: none;
	}
	.export_csv-pdf{
		margin: 0px auto auto 125px;
	}
</style> 

<div class="block-border" id="printcoupon">
    <div class="block-content dark-bg">
        <h1>Coupon Details</h1> 
			<span class="closepopup">&nbsp;</span>  
			<table border="0" cellpadding="0" cellspacing="0" id="DetailsBox" width="600px">
				<tr>
					<td colspan="2">
						<?php  
							if(isset($result_message)){
								echo "<p class='greenmessage'>$result_message</p>";
							}
							if(isset($error_message)){
								echo "<p class='errormessage'>$error_message</p>";
							}
							if(isset($sold_CouponID)){ 
								//pr($sold_CouponID); 
							}
						?> 
					</td>
			        </tr> 	
			</table> 
		</p>
    </div>
</div>
<?php if(isset($error_message) && ($error_message == "")){?>
<div class="block-border" style="margin-top:5px;">
	<div class="block-content dark-bg small-div">  
		<span id="c_loading"></span>
		
		<div class="export_csv-pdf">
		<?php echo $this->Form->create('Coupon',array('url'=>array('controller'=>'coupons','action'=>'bulksalecoupon',$product_id,$count_coupon, 'admin' =>true)));
		if(!empty($sold_CouponID)){
			$i = 0;
			foreach($sold_CouponID as $k=>$v){
				echo $this->Form->input('id][',array('type'=>'hidden','value'=>$k));
				$i++;
			}
		}		
		echo $this->Form->button("Export To CSV",array("type"=>"submit","class"=>"red","style"=>'margin:0 4px;width:auto;','name'=>'export_to_csv','value'=>'csv'));
		
		echo $this->Form->button("Export To PDF",array("type"=>"submit","class"=>"grey","style"=>'margin-left:37px;','name'=>'export_to_pdf','value'=>'pdf'));
		
		echo $this->Form->end();
		
		?>
		</div>
	</div>
</div>
<?php } ?>