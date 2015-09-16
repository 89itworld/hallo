<?php //echo $this->element("backend/product_left_list");?>
<section class="grid_8" style="min-width:63%;">
	<div class="block-border">
		
		<div class="columns">
			<div>
				
				<div style="display: block;" id="tab-global" class="tabs-content">
					<?php echo $this->Session->flash(); ?>
					<ul class="tabs js-tabs same-height">
						<li class=""><a href="#tab-options" title="Options">Upload Coupon CSV</a></li>
						<!--<li class="current"><a href="#tab-locales" title="Locales">Product Status</a></li>
						
						<li class=""><a href="#tab-advance" title="Advance">Options</a></li>-->
						
					</ul>
					
					<div class="tabs-content">
						<div style="display: none;" id="tab-options">
						<!--<div style="display: block;" id="tab-locales">-->
						<?php //echo $this->element("backend/product_status"); ?>
						<!--</div>-->
						
						
						<?php echo $this->element("backend/coupon_csv_upload"); ?>	
						</div>
						<!--<div style="display: none;" id="tab-advance">
							Advance
						</div>-->
						
					</div>
				</div>
				
				
			</div>
			
			
		</div>
		
	</div>
</section>

<div class="black_overlay fullpage">
		  <div id="black_overlay_loading" style="display:block;top: 22%;">
		  		<div class="txtcont">
					<b class="loadertxt">Please don't refresh or leave this page ....... </b>
				</div>
				<?php echo $this->Html->image('../images/ajax-loader.gif',array('alt' => 'loader image', 'title' => 'Please don\'t refresh or leave this page ....... ')); ?> 
		  		 
		  </div>
</div>
<style type="text/css">
		.txtcont{
			margin-left:-110px;
		}
		.fullpage{
			width: 5000px !important; 
		}
		.loadertxt{
			font-size:22px;
			color:red;
		}
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
</style>