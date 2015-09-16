<div id="receiver">
<?php if(isset($errors)){?>
		<ul class="message error no-margin" style=" margin-bottom: 25px;">
				<li>
						Oops something went wrong. Here is what you can do:
				</li>
        	<?php foreach($errors as $error_list):
                            foreach($error_list as $v):?>																					
				<li><?php echo $v?></li>
		<?php endforeach;endforeach;?>
                </ul>
<?php } ?>
</div>