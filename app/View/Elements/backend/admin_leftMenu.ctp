<section class="grid_4">
			<!--<div class="block-border"><div class="block-content">-->
				<h1>Favourites</h1>
				
				<ul class="favorites no-margin with-tip" title="Context menu available!">
					
					<li>
						<?php
							echo  $this->Html->image('/images/icons/web-app/48/Info.png',array('width' =>'48','height' => '48'));												    
						?> 
						<a href="#">Settings<br>
						<small>System &gt; Settings</small></a>
						<ul class="mini-menu">
							<li>
									<?php 
									  echo $this->Html->link(
										    $this->Html->image('/images/icons/fugue/arrow-270.png',array('title' =>'Move down','width' => '16','height' => '16')),
										    '#',
										    array('escape' => false)
									  );
									?> 
							</li>
							<li>
									<?php 
									  echo $this->Html->link(
										    $this->Html->image('/images/icons/fugue/cross-circle.png',array('title' =>'Delete','width' => '16','height' => '16')).' Delete',
										    '#',
										    array('escape' => false)
									  );
									?>  
							</li> 
						</ul>
					</li> 
					<li>
						<?php
							echo  $this->Html->image('/images/icons/web-app/48/Line-Chart.png',array('width' =>'48','height' => '48'));												    
						?>  
						<a href="#">Bandwidth usage<br>
						<small>Stats &gt; Server &gt; Bandwidth usage</small></a>
						<ul class="mini-menu">
							<li><a href="#" title="Move up"><img src="images/icons/fugue/arrow-090.png" width="16" height="16"></a></li>
							<li><a href="#" title="Move down"><img src="images/icons/fugue/arrow-270.png" width="16" height="16"></a></li>
							<li><a href="#" title="Delete"><img src="images/icons/fugue/cross-circle.png" width="16" height="16"> Delete</a></li>
						</ul>
					</li>
					
					<li>
						<?php
							echo  $this->Html->image('/images/icons/web-app/48/Modify.png',array('width' =>'48','height' => '48'));												    
						?> 
						<a href="#">New post<br>
						<small>Write &gt; New post</small></a>
						<ul class="mini-menu">
							<li><a href="#" title="Move up"><img src="images/icons/fugue/arrow-090.png" width="16" height="16"></a></li>
							<li><a href="#" title="Move down"><img src="images/icons/fugue/arrow-270.png" width="16" height="16"></a></li>
							<li><a href="#" title="Delete"><img src="images/icons/fugue/cross-circle.png" width="16" height="16"> Delete</a></li>
						</ul>
					</li>
					
					<li>
						<?php
							echo  $this->Html->image('/images/icons/web-app/48/Pie-Chart.png',array('width' =>'48','height' => '48'));												    
						?>  
						<a href="#">Browsers stats<br>
						<small>Stats &gt; Sites &gt; Browsers stats</small></a>
						<ul class="mini-menu">
							<li><a href="#" title="Move up"><img src="images/icons/fugue/arrow-090.png" width="16" height="16"></a></li>
							<li><a href="#" title="Move down"><img src="images/icons/fugue/arrow-270.png" width="16" height="16"></a></li>
							<li><a href="#" title="Delete"><img src="images/icons/fugue/cross-circle.png" width="16" height="16"> Delete</a></li>
						</ul>
					</li>
					
					<li>
						<?php
							echo  $this->Html->image('/images/icons/web-app/48/Comment.png',array('width' =>'48','height' => '48'));												    
						?>   
						<a href="#">Manage comments<br>
						<small>Comments &gt; Manage comments</small></a>
						<ul class="mini-menu">
							<li><a href="#" title="Move up"><img src="images/icons/fugue/arrow-090.png" width="16" height="16"></a></li>
							<li><a href="#" title="Delete"><img src="images/icons/fugue/cross-circle.png" width="16" height="16"> Delete</a></li>
						</ul>
					</li>
					
				</ul>
				
				<form class="form" name="stats_options" id="stats_options" method="post" action="">
					<fieldset class="grey-bg no-margin">
						<legend>Add favourite</legend>
						<p class="input-with-button">
							<label for="simple-action">Select page</label>
							<select name="simple-action" id="simple-action">
								<option value=""></option>
								<option value="1">Page 1</option>
								<option value="2">Page 2</option>
							</select>
							<button type="button">Add</button>
						</p>
					</fieldset>
				</form>
				
			<!--</div></div>-->
		</section>