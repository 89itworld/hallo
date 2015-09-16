<style>
.collapsible-list.with-icon a, .collapsible-list.with-icon span, .collapsible-list .with-icon a, .collapsible-list .with-icon span {
    background-image: url("../../images/icons/fugue/control-000-small.png") !important;
    padding-left: 2.25em;
}
.voucher_vendor_coupon{cursor:pointer;}
</style>

<script type="text/javascript">
$(document).ready(function(){
	     //it is used to find data through json while clicking on voucher
    
	     $("span.voucher_vendor_coupon").live("click",function(){
			  
			  $(".coupon_detail").html('<div style="margin:auto;"><img src="'+ajax_url+'img/bar_loader.gif" class="temp_loading" alt="Please wait..." style="margin:auto;display:block;"></div>');
			  $(".voucher_vendor_coupon").css("background-color","");
			  $(this).css("background-color","#CCC");
			  $.ajax({
				       url:ajax_url+"admin/dashboards/coupon_statistics/"+$(this).attr("id"),
				       success:function(result){
						    
						    $(".detail_coupon").find('img.temp_loading').remove();
						   /* var data = JSON.parse(result);
						    new_data = "<h3>Total activated coupon = <strong><span style = 'color:#000;'>"+data.total+"</span></strong><br>";
						    new_data += "Total activated remaning coupon = <strong><span style = 'color:#0f0;'>"+data.remain+"</span></strong><br>";
						    new_data += "Total sold coupon = <strong><span style = 'color:#00f;'>"+data.sold+"</span></strong><br>";
						    new_data += "Total expired coupon = <span style = 'color:#f00;'>"+data.expire+"</span><strong></h3>"; 
						   */ 
						    $(".coupon_detail").html(result);
				       }
			  });
			  return false;
	     });
});
</script>
<div style="width:30%;float:left;">
	     <!--<h2>Vendor Voucher Listing</h2>-->
	     <ul class="collapsible-list with-bg">
			  <?php if(!empty($vendors_data)){
			  
			  foreach($vendors_data as $k=>$v){?>
			  <li class="closed">
				       <b class=""></b> 
				       <span class="voucher_vendor_coupon" id="<?php echo ucfirst($v['Vendor']['id']);?>"><b><?php echo ucfirst($v['Vendor']['name']);?></b></span>
				      <?php /* <ul class="with-icon icon-user">
				       <?php
				       if(!empty($v['Product'])){ asort($v['Product']);
				       foreach($v['Product'] as $k1=>$v1){?>
					       <li><b><?php echo $this->Html->link(ucfirst($categories_list[$v1['category_id']]),"javascript:void(0);",array("alt"=>$v1['category_id']."/".$v1['vendor_id'],"escape"=>false,"class"=>"voucher_vendor_coupon"));?></a></b></li>
				       <?php }
				       }else{
					       echo "<li style='color:#F00'><b>".NO_VOUCHER_FOUND."</b></li>";
				       }
				       ?>
				       </ul>
				       */?>
			  </li>
			  <?php }} ?>
	     </ul>
</div>
<div style="width:60%;float:right;" class="detail_coupon">
	     <!--<p>
			<button onclick="$('#fold-fade').fadeAndRemove();" type="button">Fade and remove</button>-->
	     </p>
	     <p class="box with-padding coupon_detail" id="fold-fade">
			  <b>Please select vendor first.</b>
	     </p>
	     
	     
	    
</div>