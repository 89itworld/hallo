
<div class="no-margin" style="margin-left:20px;">
	     <span style="color:#3399CC;font-size:20px;margin-bottom:10px;"> <b>Stock Statistics</b></span>
	     <table width="80%" cellspacing="0" class="table">
			  <tbody>
				       
			  <?php	       foreach($coupon_stock as $k=>$v){ ?>
						    <tr><td><b> <?php echo $v['name']?></b></td> 
			  <?php			    if(!empty($v['catid'])){

				       ?>
								 <td>
									      <table width="100%" cellspacing="0" class="table">
									      <tbody>
											   <tr>
											   <?php
											   $k=1;
											   foreach($v['catid'] as $k1=>$v1){
													if($k%8==0){
														     echo '</tr><tr>';
													}
											   ?>
						    
													<td><b> <?php echo $v['name']." ".$v1['id_cat']?> <b>
													<br><b> <?php echo "Remaining :<span style='color:#090'>".$v1['remain']?></span></b></td> 
											   <?php
													$k++;
											   } ?>
											   </tr> 
									      </tbody>
									      </table>
								 </td>
			  <?php
						    }else{
								 echo "<td style='color:#F00;'>No coupon found.</td>";
						    }
						    echo '</tr>';
				       }
			  ?>       
			  
			  </tbody>

	     </table>
</div>
<script type="application/x-javascript">
$(document).ready(function(){
	     $("#tab-global").attr("style","");
});
</script>