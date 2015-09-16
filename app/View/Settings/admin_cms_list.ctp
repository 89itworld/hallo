<?php
$after_click_sort = array('asc'=>'<span class="sort-up"></span><span class="sort-down active"></span>','desc'=>'<span class="sort-up active"></span><span class="sort-down"></span>');
$before_click_sort = '<span class="sort-up"></span><span class="sort-down"></span>';
$default_sort = '<span class="sort-up active"></span><span class="sort-down"></span>';
$condition = isset($this->params['named']['sort'])?$this->params['named']:$this->params['url'];
?>
<div class="clear"></div>
<!--</article>-->
<!--Search section end-->
<div style="opacity: 1;" id="control-bar" class="grey-bg clearfix">
    <div class="container_12">
	<div class="float-right">
	    <span class="submit_button_p">
	    <?php
	    echo $this->Html->link(ADD_CMS_PAGE,array("controller"=>"settings","action"=>"add_cms_page","admin"=>true),array("escape"=>false,"class"=>"submit_button"));
	    ?>
	    
	    </span>
	</div>
    </div>
</div>
<section class="grid_13">
		
    <div class="block-border">
	<?php
	echo $this->element("backend/common_paging");
	echo $this->Form->create("Model",array("url"=>array("controller"=>"categories","action"=>"bulk_action","admin"=>true),"id"=>"bulk_action",'class' => 'block-content form'));
	echo $this->Form->input("model_name",array('type'=>'hidden','value'=>"CmsPage"));
?>
	<h1><?php echo CMS_PAGE_LISTING; ?></h1>
	
	<div style="margin-bottom: 25px;">
	    <?php echo $this->Session->Flash();?>
	</div>	
	<div class="no-margin"><table class="table" cellspacing="0" width="100%">
	
	    <thead>
	    <tr>
		<th class="black-cell">
		<?php echo $this->Form->checkbox('select', array(  'class' => 'check_all' )); ?>
		</th>
		<th scope="col">
		<span class="column-sort">
				
		<?php
		
		if(isset($condition['sort']) && ($condition['sort'] == "title")){
				echo $after_click_sort[$condition['direction']];		
		}else{
			echo $before_click_sort;	
		}
		?>

		</span>
		<?php echo '<span class="sortkey">'.$this->Paginator->sort( 'title','Title').'</span>'; ?> 
		</th>
		<th scope="col">
		    <span class="column-sort">
				    
		    <?php
		    
		    if(isset($condition['sort']) && ($condition['sort'] == "page_order")){
				    echo $after_click_sort[$condition['direction']];		
		    }else{
			    echo $before_click_sort;	
		    }
		    ?>

		    </span>
		    <?php echo '<span class="sortkey">'.$this->Paginator->sort( 'page_order','Page Order').'</span>'; ?> 
		</th>
		<th scope="col">
		    <span class="column-sort">
		    <?php
		    
		    if(isset($condition['sort']) && ($condition['sort'] == "is_active")){
				    echo $after_click_sort[$condition['direction']];		
		    }else{
			    echo $before_click_sort;	
		    }
		    ?>
		    </span>
		    <?php echo '<span class="sortkey">'.$this->Paginator->sort('is_active','Active').'</span>'; ?>
		</th>
		
		<th scope="col">
		<span class="column-sort">
		<?php
		if(empty($condition)){
			echo $default_sort;	
		}elseif(isset($condition['sort']) && ($condition['sort'] == "created")){
				echo $after_click_sort[$condition['direction']];		
		}else{
				echo $before_click_sort;	
		}
		?>
		</span>
		<?php echo '<span class="sortkey">'. $this->Paginator->sort('created','Created').'</span>'; ?>
		</th> 
		
		<th scope="col" class="table-actions">Actions</th>
	    </tr>
	    </thead>
    
	    <tbody>
	    <?php 
	    if(!empty($cms_page_data)){
		foreach($cms_page_data as $Record){
?>
		<tr>
		    <th scope="row" class="table-check-cell">
		     <!--<input type="checkbox" name="selected[]" id="table-selected-1" value="1"> -->
		    <?php echo $this->Form->checkbox('id][', array('hiddenField' => false , 'class' => 'check_box' , 'value' =>ENCRYPT_DATA($Record['CmsPage']['id']))); ?>
		    </th>
		    <td> 
			<?php echo $Record['CmsPage']['title']; ?>
		    </td>
		    <td> 
			<?php echo $Record['CmsPage']['page_order']; ?>
		    </td>
		    <td> 
			<?php 
			$status =($Record['CmsPage']['is_active'] == '1')?1:0;
			
			$check_status = '/admin/settings/activate_cmspage/'.$status.'/'.ENCRYPT_DATA($Record['CmsPage']['id']).'/CmsPage';
			if($Record['CmsPage']['id'] == "1"){
			    $check_status = "#";
			}
			echo $this->Common->is_active($Record['CmsPage']['is_active'],$check_status); 
			?>
		    </td>
		    <td>  
			<?php  
			echo  $this->Common->format_date($Record['CmsPage']['created'], 'F d,Y');
			?>
		    </td>		    
		    <td class="table-actions">
			<?php 
			echo $this->Html->link($this->Html->image('/images/icons/fugue/pencil.png',array('title' =>'Edit','class' => 'with-tip')),'/admin/settings/edit_cms_page/'.ENCRYPT_DATA($Record['CmsPage']['id']),array('escape' => false));
			if($Record['CmsPage']['id'] != "1"){
			   echo $this->Html->link($this->Html->image('/images/icons/fugue/cross-circle.png',array('title' =>'Delete','class' => 'with-tip')),'/admin/settings/delete_cmspage/'.ENCRYPT_DATA($Record['CmsPage']['id']),array('escape' => false,'onclick'=>"return  confirm('Are you sure you want to delete?')"));
			}
			
			
			?> 
		    </td>
		</tr>
<?php  		} 
	    }else{
		echo '<tr><td colspan=4>'.NO_RECORD_FOUND.'</td><tr>';
	    }
	    ?>
	</tbody>
    </table>
    </div>
	
    <ul class="message no-margin">
	<li>  
	    <?php
	    echo $this->Paginator->counter(array(
			'format' => 'Results %page% - %pages% out of %count%'
		    ));
	    ?>
	</li>
    </ul>
    <?php echo $this->Form->end();?>			
</div>		 
		
</section>