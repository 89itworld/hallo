<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">
	// Load the Visualization API and the piechart package.
	google.load('visualization', '1', {'packages':['corechart']});
</script>
<?php $a = $this->Session->read('Auth.User.role_id');
echo $this->element("backend/datepicker");
?>
<!--<article class="container_12">
    <section class="grid_8" style="width:100%;">-->
        <div class="block-border">
            <?php echo $this->element('backend/server_error');
                echo $this->Form->create('null',array('url'=>array(),'inputDefaults'=>array('div'=>false,'label'=>false),"type"=>"get",'id'=>'advance', 'name' => 'advance','class' => 'block-content form'));
		?>
                <fieldset class="">
		    <div class="columns">
			<p class="colx2-left">
			      
			    <div class="float-left gutter-right">
				<label for="stats-period">Dealer</label>
				<?php
				    
				    $selected_dealer = isset($condition['dealer'])?$condition['dealer']:""; 
				    echo $this->Form->input('dealer',array('type'=>'select','options'=>$dealer_data,'selected'=>$selected_dealer,"empty"=>"All","label"=>false,"div"=>false));
				    ?>
			    </div>
			    <div class="float-left gutter-right">
				<label for="stats-period">Product</label>
				<?php
				    
				    $selected_product = isset($condition['product_code'])?$condition['product_code']:""; 
				    echo $this->Form->input('product_code',array('type'=>'select','options'=>$product_data,'selected'=>$selected_product,"empty"=>"All products","label"=>false,"div"=>false,"escape"=>false,"class"=>"select_product"));
				    ?>
			    </div>
			    
			    
			    
			    <div class="float-left gutter-right">
				<label for="stats-period">From</label>
				<span class="input-type-text">
				    <?php echo $this->Form->input("from",array("type"=>"text","class"=>"datepicker_from",'readonly' => true));?>
				</span>
			    </div>
			    <div class="float-left gutter-right">
				<label for="stats-period">TO</label>
				<span class="input-type-text">
				    <?php echo $this->Form->input("to",array("class"=>"datepicker_to",'readonly' => true));?>
				</span>
			    </div>
			    
			    <div class="float-left gutter-right">
				<label for="stats-period">&nbsp;</label>
				<span class="">
				<?php
				echo $this->Form->button('Search',array("type"=>"button","id"=>"gen","div"=>false,"label"=>false));
			        ?>
				</span>
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
    <div class="block-border block-content form">
	<h1><?php echo DEALER_REPORT; ?></h1>
	<div class="no-margin" style="min-height: 500px;">
	    <div class="content-columns right30" style="margin: 30px 0px 30px 200px;">
		<div class="content-left" id="ovfer">
		    <div id="overall_chart" class="with-padding" >
			Left column content
		    </div>
		</div>
		
	    </div>
	</div>
	<div class="block-footer"></div>
    </div>		 
		
</section>


<script type="text/javascript">
	     
$(document).ready(function(){
    $(".select_product option").each(function(){
	if ($(this).val().indexOf("_") !=-1) {
	    $(this).attr("style","color:#00F;");
	}
    });     
    
    advancegraph(null,7);
        
    $("#ovfer div:first").html('<div style="margin:auto;"><img src="'+ajax_url+'img/bar_loader.gif" class="temp_loading" alt="Please wait..." style="margin:auto;display:block;"></div>');//loader_graph.gif
    $("#gen").click(function (){
       var ndata=$("#advance").serialize();
       var c_value = $("#advance").find("#chart_type").val();
       $("#ovfer div:first").html('<div style="margin:auto;"><img src="'+ajax_url+'img/bar_loader.gif" class="temp_loading" alt="Please wait..." style="margin:auto;display:block;"></div>');
       advancegraph(ndata,c_value);
    });
});

function advancegraph(a,chart_value){
	    
    $.ajax({
	url:ajax_url+'admin/graphs/index/4?'+a,
	success:function(response){
		     
	    var resp = jQuery.parseJSON(response);
	    var data = new google.visualization.DataTable();
	    var j = 0;
	    if (resp == "") {
		       $("#overall_chart").html("<span style='font-weight:bold;font-size:16px;color:#F00;'>No record found.</span>");  
	    }else{
		$.each(resp, function(key,val) {
			     
		    if(j == 0){
			for(var i=0;i<val.Key.length;i++){
			    if(i!=0){
				data.addColumn('number',val.Key[i]);
			    }else{
				data.addColumn('string',val.Key[i]);
			    }
			}
		    }else{
			var itemsarray=[];
			for(var k=0;k<val.Key.length;k++){
			    if(k==0){
				    itemsarray.push("'"+val.Key[k]+"'");
			    }else{
				    itemsarray.push(val.Key[k]);
			    }
			    
			}
			data.addRows([itemsarray]);
		    }
		    
		    j++;
			     
		});
		replace_id = 'ovfer';
		
		switch(chart_value){
			     
		    case '3':{
			var chart = new google.visualization.ColumnChart(document.getElementById(replace_id));
			break;
		    }
		    default:{
			var chart = new google.visualization.ColumnChart(document.getElementById(replace_id));
			break;
		    }
		}
		if(chart_value == 1 || (chart_value == 2)){
		    chart.draw(data, {width: '100%', height: 300,
			chartArea: {left:80,top:30, width:"70%",height:"70%"},
			legendTextStyle: {color:'#666666'},
			hAxis: {title: 'Total coupon',
				     titleTextStyle: {color: '#5c5c5c'},
				     titlePosition: 'out',
				     animation:{
						  duration: 20000,
						  easing: 'out',
				     }
			},
			vAxis: {title: (chart_value == 1)?'Year/Month':'',titleTextStyle: {color: '#5c5c5c'},titlePosition: 'out'}
				 
		    });
		}else{
		    chart.draw(data, {width: '100%', height: 300,
			chartArea: {left:60,top:30, width:"70%",height:"70%"},
			legendTextStyle: {color:'#666666'},
			hAxis: {title: 'Year/Month',
				     titleTextStyle: {color: '#5c5c5c'},
				     titlePosition: 'out',
				     animation:{
						  duration: 2000,
						  easing: 'in',
				     }
			},
			vAxis: {title: 'Total coupon',titleTextStyle: {color: '#5c5c5c'},titlePosition: 'out'}
				 
		    });
		}
	    }
	}
    });
    //chart = ["Area","Bar","Bubble","Column","Combo","Line","Pie","Column"];
    //notify("Dealer "+chart[chart_value]+" Charts Updated");
    //notify("Dealer Report");
}                
        
</script>