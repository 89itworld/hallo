<style>
#BrowserHidden {position:relative;text-align: right;-moz-opacity:0 ;filter:alpha(opacity: 0);opacity: 0;z-index: 2;}
</style>
<section class="grid_8" style="width:100%;">
    <div class="block-border">
        <?php echo $this->element('backend/server_error');
        echo $this->Form->create('Coupon',array('url'=>array('controller'=>'coupons','action' => 'upload_coupon_csv', 'admin' =>true),'enctype' => 'multipart/form-data','inputDefaults'=>array('div'=>false,'label'=>false),'class' => 'block-content form')); ?>
        <h1><?php echo UPLOAD_COUPON_CSV; ?></h1>
         
        <fieldset>
            <?php echo $this->Session->flash(); ?>
            <p>
                <label for="simple-required">Upload Coupon<?php echo REQUIRED; ?></label> 
                <?php
                echo $this->Form->input('category_title',array('type'=>'hidden',"id"=>"coupon_category_title"));
                
                echo $this->Form->input('category_id',array('type'=>'hidden',"id"=>"coupon_category_id"));
                
                echo $this->Form->input('product_id',array('type'=>'hidden',"id"=>"coupon_product_id"));
                
                echo $this->Form->input('csv_file',array('type'=>'file','class'=>'','id'=>'BrowserHidden','onchange'=>"getElementById('FileField').value = getElementById('BrowserHidden').value;"));
                
                echo $this->Form->input('csv_file_text',array('type'=>'text','class'=>'full-width',"id"=>"FileField","style"=>"width:85%;",'readonly' => true));
                
                echo '<span class="submit_button open_hidden_file" style="margin-left:5px;">Browse</span>';
                
                ?>
            </p>
            
            <p>
                <span class="submit_button_p">
                <?php
                
                echo $this->Form->button("Reset",array("type"=>"reset","class"=>"grey","style"=>'margin:0 4px;'));
                echo $this->Form->submit('Submit',array('div'=>FALSE,'class'=>'submit_button'));
                ?>
                </span>
                    
            </p>
        </fieldset>
        <?php echo $this->Form->end();?>
    </div>

</section>

<div class="clear"></div>