 <script type="text/javascript">
$(document).ready(function(){
	       $("#tab-global div:first").html('<div style="margin:auto;"><img src="'+ajax_url+'img/loader_graph.gif" class="temp_loading" alt="Please wait..." style="margin:auto;display:block;"></div>');
	       dealer();
}); 
function dealer(){
	       $.ajax({
			      url:ajax_url+'admin/graphs/index/1',
			    
			      success:function(response){
					     var resp = jQuery.parseJSON(response);
					     var data = new google.visualization.DataTable();
					     var j=0;
					     $.each(resp, function(key, val) {
							    if(j==0){
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
								    //alert(itemsarray);
								      data.addRows([itemsarray]);
	       
							    }
							    j++;		
					     });
			 
					     column_width = $("#tab-global").width(); 
					     var chart = new google.visualization.ColumnChart(document.getElementById('tab-global'));
					     
					     chart.draw(data, {width:column_width,height: 300,
							    chartArea: {left:60,top:30, width:"70%",height:"70%"},
							    legendTextStyle: {color:'#666666'},
							    hAxis: {
									   title: 'Month',
									   titleTextStyle: {color: '#5c5c5c'},
									   titlePosition: 'out',
									   animation:{
											  duration: 20000,
											  easing: 'out',
									   }
												  
							    },
							    vAxis: {title: 'DKK',titleTextStyle: {color: '#5c5c5c'},titlePosition: 'out'}
					     });
					     
			      }
			 
		 
	       });
	       notify("Dealer Charts Updated");
}                

</script>
