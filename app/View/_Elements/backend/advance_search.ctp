<?php
    echo $this->element("backend/datepicker");
    $fields = array("title","is_active","from","to","sort","direction");
    $condition = isset($this->params['named']['page'])?$this->params['named']:$this->params['url'];
    $search_url = $this->Common->make_search_url($fields);
    $this->Paginator->options(array('url'=>$search_url['urlArray']));
    $query_string = $search_url['query_string'];
    if(strlen($query_string)>1){
        $query_string = substr($query_string,0,-1);
    }
?>
<article class="container_12">
    <section class="grid_8" style="width:100%;">
        <div class="block-border">
            <?php echo $this->element('backend/server_error');
                echo $this->Form->create('Category',array('url'=>array('controller'=>'categories','action' => 'listing', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),"type"=>"get",'id'=>'category_list', 'name' => 'category_list','class' => 'block-content form')); ?>

                <h1><?php echo SEARCH; ?></h1>
                <fieldset>
                    <div class="columns">
                        <p class="colx2-left">
                            <label for="complex-fr-url">Title</label>
                            <span class="relative">
                                <?php echo $this->Form->input("title",array("class"=>"full-width",'value'=>isset($condition['title'])?$condition['title']:""));?>
                            </span>
                        </p>
                        <p class="colx2-right">
                            <label for="complex-fr-style">Status</label>
                            <?php
                                $status = array(1=>"Active",0=>"Inactive");
                                echo $this->Form->input("is_active",array('type'=>'select','options'=>$status,'selected'=>isset($condition['is_active'])?$condition['is_active']:"","empty"=>"All","class"=>"full-width"));
                            ?>
                            
                        </p>
                            
                    </div>
                    <div class="columns">
                        <p class="colx2-left">
                            <label for="complex-fr-url">From</label>
                            <span class="relative">
                                <?php echo $this->Form->input("from",array("class"=>"full-width datepicker_from",'value'=>isset($condition['from'])?$condition['from']:"",'readonly' => true));?>
                            </span>
                        </p>
                        <p class="colx2-right">
                            <label for="complex-fr-subtitle">To</label>
                                <?php echo $this->Form->input("to",array("class"=>"full-width datepicker_to",'value'=>isset($condition['to'])?$condition['to']:"",'readonly' => true));?>
                        </p>
                            
                    </div>
                    <div class="columns">
                        <p class="input-with-button">
                        <span class="submit_button_p">
                        <?php
                        echo $this->Form->submit('Search',array('div'=>FALSE,'class'=>'submit_button'));
                        //echo $this->Html->link('Cancel',array( 'controller' => 'categories' , 'action' => 'listing' , 'admin' => true ),array('class' => 'submit_button paddin'));
                        ?>
                        </span>
                            
                    </p>
                    </div>
                </fieldset>
                
                    
            <?php echo $this->Form->end();?>
        </div>
    
    </section>
     
    <div class="clear"></div>
</article>