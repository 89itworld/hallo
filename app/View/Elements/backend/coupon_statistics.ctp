<style>
	     
	     .table, .with-head {border: 1px solid #000;}
	     .table tbody th{ background: #006699 !important;color:#FFF;padding: 0.25em !important;font-weight:bold !important;border:0px;font-size: 14px;}
	     .table tbody tr,.table tbody tr td{padding: 0.25em !important;background: #FFF !important;border:1px #eee solid;}
	     
	     .table tbody th, .table tbody tr td {line-height: .80em !important;}
	     
</style>

<div class="" style="margin-left:20px;min-height: 110px;">
	     
	     <?php
			  
			  if(!empty($coupon_stock)){  ?>
			  <table width="30%" cellspacing="0" class="table">
				       <tr>
						    <th scope="col"><b>Hallo.dk Stock Report</b></th>
						    <th scope="col"><b>Available</b></th>
				       </tr>
				       <tbody>
				       
				       <?php
				       
				       foreach($coupon_stock as $k1=>$v1){ ?>

				       <tr>
						    <td><?php echo $v1['name']; ?> </td>
						    <td> <?php echo $v1['remain']; ?></td>
				       </tr>
				       <?php
						    
				       } ?>
				       </tbody>
			  
			  <?php
			  }else{
				 echo "<span style='font-weight:bold;font-size:16px;color:#F00;'>No record found.</span>";     
			  }
	     
?>       
			  
	     </table>	  
			 
</div>
<script type="application/x-javascript">
$(document).ready(function(){
	     $("#tab-global").attr("style","");
});
</script>