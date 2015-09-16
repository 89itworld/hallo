<?php
    echo $this->Html->css(array('admin/jquery-ui-1.8.22.custom.css'));
    echo $this->Html->script(array('admin/jquery-ui-1.8.22.custom.min'));
?>
<script type="text/javascript">
$(function() {
    var startDate;
    var endDate;
    
    var selectCurrentWeek = function() {
        window.setTimeout(function () {
            $('.week-picker').find('.ui-datepicker-current-day a').addClass('ui-state-active')
        }, 1);
    }
    
    $('.week-picker').datepicker( {
        maxDate: new Date,
        dateFormat: "mm-dd-yy",
        showOtherMonths: true,
        selectOtherMonths: true,
        firstDay: 1,
        onSelect: function(dateText, inst) { 
            var date = $(this).datepicker('getDate');
            if(date.getDay() == 0){
                startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() -6 );
                endDate = new Date(date.getFullYear(), date.getMonth(), date.getDate());
            }else{
                startDate = new Date(date.getFullYear(), date.getMonth(), (date.getDate()+1) - date.getDay());
                endDate = new Date(date.getFullYear(), date.getMonth(), (date.getDate()+1) - date.getDay() + 6);
            }
            
            
           
            var dateFormat = inst.settings.dateFormat || $.datepicker._defaults.dateFormat;
            $('#startDate').val($.datepicker.formatDate( dateFormat, startDate, inst.settings ));
            $('#endDate').val($.datepicker.formatDate( dateFormat, endDate, inst.settings ));
            
            selectCurrentWeek();
        },
        beforeShowDay: function(date) {
            var cssClass = '';
            if(date >= startDate && date <= endDate)
                cssClass = 'ui-datepicker-current-day';
            return [true, cssClass];
        },
        onChangeMonthYear: function(year, month, inst) {
            selectCurrentWeek();
        }
    });
    
});
</script>

<?php
$fields = array("from","to");
$condition = isset($this->params['named']['sort'])?$this->params['named']:$this->params['url'];;
$search_url = $this->Common->make_search_url($fields);
$this->Paginator->options(array('url'=>$search_url['urlArray']));
?>
<div class="block-border">
    <?php echo $this->element('backend/server_error');
        echo $this->Form->create('CouponSale',array('url'=>array('controller'=>'vendors','action' => 'weekly_csv', 'admin' =>true),'inputDefaults'=>array('div'=>false,'label'=>false),"type"=>"get",'id'=>'weekly_csv', 'name' => 'weekly_csv','class' => 'block-content form'));
    ?>
    <fieldset class="">
        <div class="columns">
            <p class="colx2-left">
                <div class="float-left gutter-right">
                    <label for="stats-period">Date</label>
                    <span class="input-type-text">
                        <div class="week-picker"></div>
                    </span>
                </div>
                <div class="float-left gutter-right">
                    <label for="stats-period">From</label>
                    <span class="input-type-text">
                        <?php echo $this->Form->input("from",array("type"=>"text","id"=>"startDate",'value'=>isset($condition['from'])?$condition['from']:"",'readonly' => true));
                    
                        ?>
                    </span>
                </div>
                <div class="float-left gutter-right">
                    <label for="stats-period">To</label>
                    <span class="input-type-text">
                        <?php echo $this->Form->input("to",array("type"=>"text","id"=>"endDate",'value'=>isset($condition['from'])?$condition['from']:"",'readonly' => true));
                    
                        ?>
                    </span>
                </div>
                
                <div class="float-left gutter-right">
                    <label for="stats-period">&nbsp;</label>
                    <span class="">
                    <?php
                    //echo $this->Form->button("Export To CSV",array("type"=>"submit","class"=>"red","style"=>'margin:0 4px;width:auto;','name'=>'export_to_csv','value'=>'csv'));
                    echo $this->Form->button("Export To txt",array("type"=>"submit","class"=>"red","style"=>'margin:0 4px;width:auto;','name'=>'export_to_txt','value'=>'txt'));
                    ?>
                    </span>
                </div>
                
            </p>
        </div>
    </fieldset>
    <?php echo $this->Form->end();?>
    
</div>
<div class="clear"></div>




