<!-- Content -->
	<article class="container_12">
		<?php //echo $this->element('backend/admin_leftMenu');?>
		<section class="grid_12">
			 
			<div class="block-border">
					<?php echo $this->element('backend/server_error');
						echo $this->Form->create('CmsPage',array('url'=>array('controller'=>'settings','action' => 'edit_cms_page', 'admin' =>true),'enctype' => 'multipart/form-data','inputDefaults'=>array('div'=>false,'label'=>false),'id'=>'edit_cms_page', 'name' => 'edit_cms_page','class' => 'block-content form'));
						echo $this->Form->input('id',array('type'=>'hidden'));
					?>
						<h1><?php echo EDIT_CMS_PAGE; ?></h1>
						<fieldset>
							<?php echo $this->Session->flash(); ?>
							<p>
								<label for="simple-required">Title<?php echo REQUIRED; ?></label> 
								<?php echo $this->Form->input('title',array("type"=>"text",'class'=>'full-width'));?>
							</p>
							<p>
								<label for="simple-required">Page Order<?php echo REQUIRED; ?></label> 
								<?php echo $this->Form->input('page_order',array("type"=>"text",'class'=>'full-width'));?>
							</p>
							<p>
								<label for="simple-required">Meta title<?php echo REQUIRED; ?></label> 
								<?php echo $this->Form->input('meta_title',array("type"=>"text",'class'=>'full-width'));?>
							</p>
							<p>
								<label for="simple-required">Meta description<?php echo REQUIRED; ?></label> 
								<?php echo $this->Form->input('meta_description',array("type"=>"text",'class'=>'full-width'));?>
							</p>
							<p>
								<label for="simple-required">Content<?php echo REQUIRED; ?></label> 
								<?php
								//echo $this->data['CmsPage']['content'];
								
								echo $this->Form->input('content',array('type'=>'textarea','row'=>'50','class'=>'full-width text_editor','id'=>'text_editor','style'=>'height:400px;'));?>
							</p>
							<p>
								<label for="simple-action">Select action</label>
						 
								<?php				
									$options=array('1'=>'Save and publish','0'=> 'Save only');					
									echo $this->Form->input('is_active',array('type'=>'select','legend'=>false,'label'=>FALSE,'options'=>$options,'class'=>'full-width'));
								?>
							</p>
							<p>
								<span class="submit_button_p">
								<?php
								echo $this->Html->link( 'Cancel' , array( 'controller' => 'settings' , 'action' => 'cms_list' , 'admin' => true ),array('class' => 'cancle_button'));
								echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;'));
								echo $this->Form->submit('Submit',array('div'=>FALSE,'onclick' => 'return ajax_form("edit_cms_page","admin/settings/validate_edit_cms_ajax","receiver")','class'=>'submit_button'));
								
								?>
								</span>
								
							</p>
						</fieldset>
					<?php echo $this->Form->end();?>
			</div>
		
		</section>
		 
		<div class="clear"></div>
</article>
<?php echo $this->element("backend/tiny_mce");?>