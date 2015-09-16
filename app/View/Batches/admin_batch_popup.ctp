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
	#DetailsBox{
		color:#333;	
	}
</style> 

<div class="block-border" id="printcoupon">
    <div class="block-content dark-bg">
        <h1>Batch Listing</h1> 
			<span class="closepopup">&nbsp;</span>   
			
			<table class="table" cellpadding="0" cellspacing="0" id="DetailsBox" width="600px"> 
				<thead>
						<tr>				
								<th scope="col">Coupon Id </th>
								<th scope="col">Product Code </th>
								<th scope="col">Activation Code</th>
								<th scope="col">Expire Date </th>
						</tr>							
				</thead>
				<tbody>
					<?php 
						if(isset($coupon_data) && !empty($coupon_data)){
							foreach($coupon_data as $Record){
					?>
									<tr>
											<td>
													<?php   echo  $Record['Coupon']['coupon_id'];    ?>
											</td>
											<td>
													<?php   echo  $Record['Coupon']['product_code'];    ?>
											</td>
											<td>
													<?php   echo  $Record['Coupon']['activation_code'];    ?>	
											</td>
											<td>
													<?php   echo  $Record['Coupon']['expire_date'];    ?>
											</td>
									</tr>
									
					<?php  } ?>
					<tr>
							<td colspan="4" style="background-color:#;">
									<ul class="controls-buttons">
									        <li> 
									            <?php echo $this->Paginator->prev($this->Html->image("/images/icons/fugue/navigation-180.png").'Prev', array('escape'=>false,'class'=>"next_prev_but"));    ?>
									        </li>
												<?php echo $this->Paginator->numbers(array('before' => '','after' => '','separator' => '','tag'=>'li','class' => 'numbers_tab'));?>
												<li>
											            <?php  echo $this->Paginator->next('Next'.$this->Html->image("/images/icons/fugue/navigation.png"), array('escape'=>false,'class'=>"next_prev_but")); ?>
											   </li>  
									</ul>
							</td>
					</tr>
    
	    <?php }else{
	    echo '<tr><td colspan=6>'.NO_RECORD_FOUND.'</td><tr>';
	    }
	    ?>
				</tbody>
			</table> 
		<style>
					.numbers_tab{font-weight: bold;}
					.current{line-height: 1.333em;margin: -0.333em -0.25em;background: -moz-linear-gradient(center top , white, #2BCEF3 5%, #057FDB) repeat scroll 0 0 transparent;border-color: #1EAFDC #1193D5 #035592;border-radius: 0.5em 0.5em 0.5em 0.5em;box-shadow: 0 0 0.25em rgba(0, 0, 0, 0.5);color: #fff;display: block;min-width: 1.083em;padding: 0.442em 0.6em !important;text-align: center;text-transform: uppercase;}
					
					.next_prev_but{line-height: 1.333em;margin: -0.333em -0.25em;background: -moz-linear-gradient(center top , #F8F8F8, #E7E7E7) repeat scroll 0 0 transparent;border: 1px solid white;border-radius: 0.5em 0.5em 0.5em 0.5em;box-shadow: 0 0 0.25em rgba(0, 0, 0, 0.5);color: #333333;display: block;min-width: 1.083em;padding: 0.333em 0.5em;text-align: center;text-transform: uppercase;}
					
		</style> 
 
		<script type="text/javascript" language="javascript">
		    $(document).ready(function(){
					if($(".next_prev_but").find("a")){
					    $("a").parent("span.next_prev_but").removeClass("next_prev_but");
					} 
		    });
		</script> 
			
		<script>
		    $(document).ready(function(){
		        $(".numbers_tab a").click(function(){
		            $("#popup_to_showbatch").load(this.href);
		            return false;
		        })
		        $("span a[rel=prev]").click(function(){
		            $("#popup_to_showbatch").load(this.href);
		            return false;
		        })
		        $("span a[rel=next]").click(function(){
		            $("#popup_to_showbatch").load(this.href);
		            return false;
		        }) 
		    });
		</script>
		 
    </div>
</div> 