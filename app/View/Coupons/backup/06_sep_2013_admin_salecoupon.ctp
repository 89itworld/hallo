<?php echo $this->Html->css(array('admin/common','admin/standard') ); ?>  
<script type="text/javascript"> 
	var host = window.location.host;
	var proto = window.location.protocol;
	var ajax_url = proto+"//"+host+"/hallo/";
	$(document).ready(function(){  
		var ShowCouponId=$("#popup_cid").html();
		$("#ShowCouponId").html(ShowCouponId);
		
		var ShowActivationCode=$("#popup_code").html();
		$("#ShowActivationCode").html(ShowActivationCode);
		
		var ShowDescription=$("#popup_desc").html();
		$("#ShowDescription").html(ShowDescription);
	});	 
	function printDiv(divName) {
		 var  printdata;
		 printdata = '<div style="margin:auto;width:80%;height:80%;">'; 
		 printdata += $('#'+divName).html();
		 printdata += '<div style="width:80%;"><h1> Description</h1>';
		 
		 printdata += $('#ShowDescription').html();
		 printdata += '</div></div>';
	     Popup(printdata); 
	}
	function Popup(data) 
    {
        var mywindow = window.open('', 'Coupon', 'height=330,width=700');
        mywindow.document.write('<html><head><title>Coupon</title>');
        /*optional stylesheet*/ //
        mywindow.document.write('<link rel="stylesheet" type="text/css" href="/hallo/css/admin/printcoupon.css" media="all"  />');
        mywindow.document.write('</head><body style="width:100%;margin:auto;">');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.print();
        mywindow.close();

        return true;
    }
    function SendCouponEmail(){
    	var ShowCouponId=$("#popup_cid").html();
		$("#ShowCouponId").html(ShowCouponId);
		
		var ShowActivationCode=$("#popup_code").html();
		$("#ShowActivationCode").html(ShowActivationCode); 
		 
		var email=prompt("Please enter email.",""); 
		if(email !=null) {
		 	var emReg = /^[a-z0-9_\+-]+(\.[a-z0-9_\+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*\.([a-z]{2,4})$/;
			if(!emReg.test(email)) {
			    // Validation Failed
			    alert('Please enter a valid email address.');
			}else{
				$('#c_loading').css('display','block'); 
		  		$.ajax({
		        	type: "POST",
		            url:ajax_url+'admin/coupons/couponemail/'+ ShowCouponId +'/' + ShowActivationCode + '/'+email,
		            success:function(response){  
		            	if(response){    
			            	$('#c_loading').css('display','none'); 
					 	   	alert('email sent successfully!'); 
					 	 }else{
					 	 	alert('Sorry, email was not sent !'); 
					 	 }
		            }       	 
				}); 
			}	 
		} 
    	
    }
    function SendCouponSms(){
    	var ShowCouponId=$("#popup_cid").html();
		$("#ShowCouponId").html(ShowCouponId);
		
		var ShowActivationCode=$("#popup_code").html();
		$("#ShowActivationCode").html(ShowActivationCode);
		
		
		var mobile=prompt("Please enter your mobile number.\nExp: 41234567 (8 digit)",""); 
		if(mobile !=null) {
		
				var mbReg = /^([1-9]{1}[0-9]{7})$/;
	  	 		if(!mbReg.test(mobile)) {
				    // Validation Failed
				    alert('Please enter a valid 8 digit mobile number.');
				}else{ 
				 	$('#c_loading').css('display','block'); 
			  		$.ajax({
			        	type: "POST",
			            url:ajax_url+'admin/coupons/couponsms/'+ mobile +'/'+ ShowCouponId +'/' + ShowActivationCode ,
			            success:function(response){   
			            	$('#c_loading').css('display','none'); 
					 	   	alert(response); 
			            }       	 
					}); 
				}
		
		} 
    }
</script>
<style type="text/css"> 
	#DetailsBox td{
		padding:6px;
	}  
	#ShowCouponId{
		width:188px;
	}
	.popheading{
		font-size:16px;
		font-weight:bold;
		padding: 0.278em 0.444em 0.389em;
		color:#cccccc;
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
</style> 

<div class="block-border" id="printcoupon">
    <div class="block-content dark-bg">
        <h1>Coupon Details</h1> 
			<span class="closepopup">&nbsp;</span>  
			<table border="0" cellpadding="0" cellspacing="0" id="DetailsBox" width="600px">
				<tr> 
					<td class="popheading">Coupon Id</td> 
					<td class="popheading">Activation Code</td>	
				</tr>
				<tr> 
					<td id="ShowCouponId" class="boldtext">&nbsp;</td> 
					<td id="ShowActivationCode" class="boldtext">&nbsp;</td>	
				</tr> 
			</table> 
		</p>
    </div>
</div>
<div class="block-border" style="margin-top:5px;">
	<div class="block-content dark-bg small-div">  
			<span id="c_loading"></span> 
			<?php echo $this->Form->button('Send Sms',array('type'=>'button','id'=>'sendsms','div'=>FALSE,'class'=>'submit_button btn','onclick'=>"return SendCouponSms();")); ?>
         	<?php echo $this->Form->button('Send Mail',array('type'=>'button','id'=>'sendmail','div'=>FALSE,'class'=>'submit_button btn','onclick'=>"return SendCouponEmail();")); ?>
        	<?php echo $this->Form->button('Print',array('type'=>'button','id'=>'eprint','div'=>FALSE,'class'=>'submit_button btn','onclick'=>"return printDiv('printcoupon');")); ?>
    	  
	</div>
</div>
<div class="block-border" style="margin-top:5px;" >
	<div class="block-content dark-bg small-div">
		<p><span id="ShowDescription" class="popheading" style="color:#ffffff;">&nbsp;</span></p>
	</div>
</div>
