<?php echo $this->element("backend/product_left_list");?>
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