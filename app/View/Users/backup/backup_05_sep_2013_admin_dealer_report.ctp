<?php
echo $this->element("backend/datepicker");
$after_click_sort = array('asc'=>'<span class="sort-up"></span><span class="sort-down active"></span>','desc'=>'<span class="sort-up active"></span><span class="sort-down"></span>');
$before_click_sort = '<span class="sort-up"></span><span class="sort-down"></span>';
$default_sort = '<span class="sort-up active"></span><span class="sort-down"></span>';

$fields = array("is_active","from","to","sort","direction");
//$condition = isset($this->params['named']['page'])?$this->params['named']:$this->params['url'];
$condition = isset($this->params['named']['sort'])?$this->params['named']:$this->params['url'];;
$search_url = $this->Common->make_search_url($fields);
$this->Paginator->options(array('url'=>$search_url['urlArray']));

?>
<!--<article class="container_12">
    <section class="grid_8" style="width:100%;">-->
        <div class="block-border">
            <?php echo $this->element('backend/server_error');
                echo $this->Form->create('Reporter',array('url'=>array('controller'=>'users','action' => 'dealer_report','admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),"type"=>"get",'id'=>'category_list', 'name' => 'dealer_report_list','class' => 'block-content form'));
		/* ?>
		<h1>
		     echo $this->Html->image("../images/icons/fugue/plus-circle-blue.png",array("width"=>"16","height"=>"16","style"=>"margin-right:5px;cursor: pointer;","class"=>"search_image"));
		    echo SEARCH; 
		</h1>
		*/?>
                <fieldset class="">
		    <div class="columns">
			<p class="colx2-left">
			    <?php
				    echo $this->Form->input("sort",array("type"=>"hidden",'value'=>isset($condition['sort'])?$condition['sort']:"total"));
				echo $this->Form->input("direction",array("type"=>"hidden",'value'=>isset($condition['direction'])?$condition['direction']:"desc"));
			    /*
			    <div class="float-left gutter-right">
				<label for="stats-period">First Name</label>
				<span class="input-type-text">
				    
				    echo $this->Form->input("name",array("type"=>"text",'value'=>isset($condition['name'])?$condition['name']:""));
				</span>
			    </div>
			    */ ?>
			    <div class="float-left gutter-right">
				<label for="stats-period">From</label>
				<span class="input-type-text">
				    <?php echo $this->Form->input("from",array("type"=>"text","class"=>"datepicker_from",'value'=>isset($condition['from'])?$condition['from']:"",'readonly' => true));?>
				</span>
			    </div>
			    <div class="float-left gutter-right">
				<label for="stats-period">TO</label>
				<span class="input-type-text">
				    <?php echo $this->Form->input("to",array("class"=>" datepicker_to",'value'=>isset($condition['to'])?$condition['to']:"",'readonly' => true));?>
				</span>
			    </div>
			    
			    <div class="float-left gutter-right">
				<label for="stats-period">Status</label>
				<?php
				    $status = array(1=>"Active",0=>"Inactive");
				    $selected = isset($condition['is_active'])?$condition['is_active']:""; 
				    echo $this->Form->input('is_active',array('type'=>'select','options'=>$status,'selected'=>$selected,"empty"=>"All","label"=>false,"div"=>false));
				    ?>
			    </div>
			    
			    <div class="float-left">
				<label for="stats-period">&nbsp;</label>
				<div class="float-right"> 
				    <?php
				   echo $this->Form->button("Export To CSV",array("type"=>"submit","class"=>"red","style"=>'margin:0 4px;width:auto;','name'=>'export_to_csv','value'=>'csv'));
						
				    echo $this->Form->button("Export To PDF",array("type"=>"submit","class"=>"grey","style"=>'margin:0 4px;width:auto;','name'=>'export_to_pdf','value'=>'pdf'));
						
				    echo $this->Form->button('Search',array("type"=>"submit",'div'=>FALSE));
				    ?>
				</div>
			    </div>
			</p>
		    </div>
		    
                </fieldset>
                
                    
            <?php echo $this->Form->end();?>
        </div>
    
    <!--</section>-->
     
    <div class="clear"></div>
<!--</article>-->

<section class="grid_13">
    <div class="block-border">
	<?php
	echo $this->element("backend/common_paging");
	echo $this->Form->create("Model",array("url"=>array("controller"=>"users","action"=>"bulk_action","admin"=>true),"id"=>"bulk_action",'class' => 'block-content form'));
	
?>
	<h1><?php echo DEALER_LISTING; ?></h1>
	
	<div class="no-margin"><table class="table" cellspacing="0" width="100%">
	
	    <thead>
	    <tr>
		<th scope="col">
		<span class="column-sort">
				
		<?php
		if(isset($condition['sort']) && ($condition['sort'] == "first_name")){
				echo $after_click_sort[$condition['direction']];		
		}else{
			echo $before_click_sort;	
		}
		?>

		</span>
		<?php echo '<span class="sortkey">'.$this->Paginator->sort('first_name','Dealer Name').'</span>'; ?> 
		</th>
		
		<th scope="col">
		<span class="column-sort">
				
		<?php
		
		if(isset($condition['sort']) && ($condition['sort'] == "email")){
				echo $after_click_sort[$condition['direction']];		
		}else{
			echo $before_click_sort;	
		}
		?>

		</span>
		<?php echo '<span class="sortkey">'.$this->Paginator->sort('email','Email').'</span>';
		?> 
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
		<?php echo '<span class="sortkey">'.$this->Paginator->sort('is_active','Status').'</span>'; ?>
		</th>
		
		<th scope="col">
		<span class="column-sort">
				
		<?php
		
		if(isset($condition['sort']) && ($condition['sort'] == "total")){
				echo $after_click_sort[$condition['direction']];		
		}else{
			echo $before_click_sort;	
		}
		?>

		</span>
		<?php echo '<span class="sortkey">'.$this->Paginator->sort('total','Total Sale(DKK)').'</span>';
		?> 
		</th>
		<th scope="col">
		<span class="column-sort">
		<?php
		
		if(isset($condition['sort']) && ($condition['sort'] == "modified")){
				echo $after_click_sort[$condition['direction']];		
		}else{
			echo $before_click_sort;	
		}
		?>
		</span>
		<?php echo '<span class="sortkey">'. $this->Paginator->sort('modified','Modified').'</span>'; ?> 
		</th>
			    
	    </tr>
	    </thead>
		
	    <tbody>
	    <?php 
	    if(!empty($user_data)){foreach($user_data as $Record){ ?>
		<tr>
		    <td> 
			<?php echo $this->Html->link(ucfirst($Record['Reporter']['first_name'])." ".ucfirst($Record['Reporter']['last_name']),array("controller"=>"users","action"=>"sub_dealer_report?parent_id=".$Record['Reporter']['user_id'],'admin'=>true),array("escape"=>false)); ?>
		    </td>
		    <td> 
			<?php echo $Record['Reporter']['email']; ?>
		    </td>
		    <td> 
			<?php 
			    $status =array("Deactive","Active");
			    echo $status[$Record['Reporter']['is_active']];
			?>
		    </td>
		    <td> 
			<?php echo sprintf('%0.2f',$Record['Reporter']['total']); ?>
		    </td>
		    <td>  
			<?php  
			echo  $this->Common->format_date($Record['Reporter']['modified'], 'F d,Y');
			?>
		    </td>
		   
		</tr>
	<?php  } 
    
	    }else{
		echo '<tr><td colspan=5>'.NO_RECORD_FOUND.'</td><tr>';
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
	<div class="block-footer"></div>
	<?php echo $this->Form->end();?>			
    </div>		 
		
</section>