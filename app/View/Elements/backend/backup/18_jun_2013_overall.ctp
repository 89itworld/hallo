<?php $a = $this->Session->read('Auth.User.role_id');
echo $this->element("backend/datepicker");

?>

<div class="content-columns right30">
	     <!-- Vertical separator -->
	     <div class="content-columns-sep"></div>
	     <!-- Left column -->
	     <div class="content-left" id="ovfer">
			  <div id="overall_chart" class="with-padding" ><!-- dark-grey-gradient-->
				       Left column content
			  </div>
	     </div>
	     <?php echo $this->Form->create(null,array('url'=>array(),'inputDefaults'=>array('div'=>false,'label'=>false),'id'=>'advance', 'name' => 'advance','class' => 'form',"type"=>"get")); ?>
			  <fieldset class="dark-bg">
				       <legend>
						    <a href="#">
								 Click here to
								 <span class="show-expanded">collapse</span>
								 <span class="show-collapsed">expand</span>
						    </a>
				       </legend>
				       <ul>
						    <li>
								 <span class="label">From</span>
								 <span class="input-type-text">
									      <?php echo $this->Form->input("from",array("type"=>"text","class"=>"datepicker_from",'readOnly'=>true));?>
								 </span>
								 <span class="label">To</span>
								 <span class="input-type-text">
									      <?php echo $this->Form->input("to",array("type"=>"text","class"=>"datepicker_to",'readOnly'=>true));?>
								 </span>
								 <p class="colx2-left">
									      <label for="simple-select">Select Vendor</label>
									      <?php
									      echo $this->Form->input('vendor',array('type'=>'select','options'=>$vlist,"empty"=>"All","label"=>false,"div"=>false,"class"=>"half-width"));?>
									      <label for="simple-select">Select Dealer</label>
									      <?php
									      echo $this->Form->input('dealer',array('type'=>'select','options'=>$dlist,"empty"=>"All","label"=>false,"div"=>false,"class"=>"half-width"));?>
									      <label for="simple-select">Select Voucher</label>
									      <?php
									      asort($voucher_list);
									      echo $this->Form->input('voucher',array('type'=>'select','options'=>$voucher_list,"empty"=>"All","label"=>false,"div"=>false,"class"=>"half-width"));?>
									      <label for="simple-select">Chart Type</label>
									      <?php
									      $chart = array("Area","Bar","Bubble","Column","Combo","Line");//"0"=>"Pie"
									      echo $this->Form->input('chart_type',array('type'=>'select','options'=>$chart,"label"=>false,"div"=>false,"id"=>"chart_type","empty"=>"Select chart type"));
									      ?>
								 </p>
								 <div class="float-right" style="clear:both;">
								 <?php echo $this->Form->button("Generate",array("type"=>"button","id"=>"gen","div"=>false,"label"=>false));?>
								 </div>
						    </li>
				       </ul>
			  </fieldset>
	     <?php echo  $this->Form->end();?>
</div>



<script type="text/javascript">
	     
$(document).ready(function(){
	     
	     $(".graph_over_all").click(function(){
			  
			  advancegraph(null,7);
	     });
	     
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
			  url:ajax_url+'admin/graphs/index/3?'+a,
			  success:function(response){
				       
				       var resp = jQuery.parseJSON(response);
				       var data = new google.visualization.DataTable();
				       var j = 0;
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
						    
						    case '0':{
								 var chart = new google.visualization.AreaChart(document.getElementById(replace_id));
								 break;
						    }
						    case '1':{
								 
								 var chart = new google.visualization.BarChart(document.getElementById(replace_id));
								 break;
						    }
						    case '2':{
								  
								 var chart = new google.visualization.BubbleChart(document.getElementById(replace_id));
								 break;
						    }
						    case '3':{
								 var chart = new google.visualization.ColumnChart(document.getElementById(replace_id));
								 break;
						    }
						    case '4':{
								 var chart = new google.visualization.ComboChart(document.getElementById(replace_id));
								 break;
						    }
						    case '5':{
								 var chart = new google.visualization.LineChart(document.getElementById(replace_id));
								 break;
						    }
						    case '6':{
								 var chart = new google.visualization.PieChart(document.getElementById(replace_id)); 
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
								 hAxis: {title: 'DKK',
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
								 vAxis: {title: 'DKK',titleTextStyle: {color: '#5c5c5c'},titlePosition: 'out'}
								 
						    });
				       }
			  }
	     });
	     chart = ["Area","Bar","Bubble","Column","Combo","Line","Pie","Column"];
	     notify("Dealer "+chart[chart_value]+" Charts Updated");
}                
        
</script>

