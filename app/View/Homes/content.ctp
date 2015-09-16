<!--page-row start-->
	<section class="page-row content-wrapper">
		<section class="lt-cr"></section>
		<section class="rt-cr"></section>
		<section class="row-mid">
			<div class="back">
				<a href="#" class="back-arrow">back</a>
			</div>
			<div class="head-block">
				<h1>TEST HEADING 1</h1>
			</div>
		</section>
	</section>
<!--page-row end-->

<!--content-widget start-->
	<section class="content-widget">
		<!--left-widget start-->
		<section class="left-widget">
			<ul class="left-nav">

				<li>
				<?php echo $this->Html->link('PRISER / PRODUKTER',array('controller'=>'homes', 'action' => 'content'), array("title"=>"PRISER / PRODUKTER","escape"=>false,"class"=>"active"));
				?>
				</li>
				<!--<li>
					<a href="#" class="active">Nectellus magna</a>
				</li>
				<li>
					<a href="#">Nulla commodo</a>
				</li>
				<li>
					<a href="#">Feugiat mollis aporta Samet sapien</a>
				</li>
				<li>
					<a href="#">Integer hendrerit Purus</a>
				</li>-->
			</ul>

		</section>
		<!--left-widget end-->

		<!--right-widget start-->
		<?php
			if(!empty($cms_data)){
			    echo $cms_data['CmsPage']['content'];
			}
		?>
		<!--right-widget end-->
		<div class="clear"></div>
	</section>
<!--content-widget end-->