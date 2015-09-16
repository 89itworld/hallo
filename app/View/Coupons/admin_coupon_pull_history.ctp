<div class="">
    <div class="lite-grey-gradient with-padding">
        <div class="col200pxL-left">
            <h3>Pull Coupon List</h3>
            <ul class="side-tabs js-tabs same-height">
                <li><a title="Global properties" href="#tab-global">Pull Coupon CSV</a></li>
                <li><a title="Language settings" href="#tab-settings">Pull Coupon PDF</a></li>
            </ul>
        </div>
        <div class="col200pxL-right">
            <div style="min-height: 800px;" class="tabs-content" id="tab-global">
                <div style="margin-bottom: 5px;">
                    <?php echo $this->Session->Flash();?>
                </div>	
                <div class="no-margin">
                    <table width="100%" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th scope="col"><span class="sortkey">Vendor</span> </th>
                                <th scope="col"><span class="sortkey">Product</span> </th>
                                <th scope="col"><span class="sortkey">Date</span> </th>
                                <th scope="col"><span class="sortkey">Time</span> </th>
                                <th scope="col"><span class="sortkey">View</span></th>
                            </tr>
                        </thead>
                    
                        <tbody>
                            
                                <?php
                                if(isset($csv_files) && !empty($csv_files)){
                                    foreach($csv_files as $csv_file){
                                        //if($csv_file == '.' || $csv_file == '..') continue;
                                        if(file_exists(WWW_ROOT."img/pull_coupon_sale_csv/".$csv_file)){
                                ?>
                                <tr>
                                    <td><?php
                                    $date_time = substr($csv_file,strrpos($csv_file, '_', -26)+1);
                                    echo substr($csv_file,0,strpos($csv_file, '_'));
                                    
                                    ?>
                                    <td><?php
                                    $vendor_product_name = substr($csv_file,0,strrpos($csv_file, '_', -26));
                                    echo str_replace("_"," ",substr($vendor_product_name,strpos($vendor_product_name,"_")+1));
                                    ?>
                                    </td>
                                    <td>
                                    <?php echo str_replace("_"," ",substr($date_time,0,11));?>
                                    </td>
                                    <td>
                                    <?php
                                    echo str_replace(".csv","",str_replace("_",":",substr($date_time,12)));?>    
                                    </td>
                                    <td><?php
                                    echo $this->Html->link($this->Html->image("/images/icons/fugue/document-excel-csv.png",array("title"=>"Upload CSV","alt"=>$csv_file,'class' => 'with-tip')),HTTP_HOST."img/pull_coupon_sale_csv/".$csv_file,array('target'=>'_blank','escape'=>false));
                                    echo $this->Html->link($this->Html->image('/images/icons/fugue/cross-circle.png',array('title' =>'Delete','class' => 'with-tip')),array("controller"=>"coupons","action"=>"delete_csv_pdf","c",$csv_file,"admin"=>true),array('escape' => false,'onclick'=>"return  confirm('Are you sure you want to delete?')"));
                                    ?>
                                    </td>
                                </tr>
                               <?php }
                                }
                                }else{
                                    echo "<tr><td colspan='4'>No coupon Found</td></tr>";
                                }
                            ?>
                            
                        </tbody>
                    </table>
                </div>
                
            </div>
            <div class="tabs-content" id="tab-settings" style="min-height: 800px; display: none;">
                <div style="margin-bottom: 5px;">
                    <?php
                        echo $this->Session->Flash();
                    ?>
                </div>
                <div class="no-margin">
                    <table width="100%" cellspacing="0" class="table">
                        <thead>
                            <tr>
                                <th scope="col"><span class="sortkey">Vendor</span> </th>
                                <th scope="col"><span class="sortkey">Product</span> </th>
                                <th scope="col"><span class="sortkey">Date</span> </th>
                                <th scope="col"><span class="sortkey">Time</span> </th>
                                <th scope="col"><span class="sortkey">View</span></th>
                            </tr>
                        </thead>
                    
                        <tbody>
                            
                                <?php
                                if(isset($pdf_files) && !empty($pdf_files)){
                                    foreach($pdf_files as $pdf_file){
                                        if(file_exists(WWW_ROOT."img/pull_coupon_sale_pdf/".$pdf_file)){
                                ?>
                                <tr>
                                    
                                    <td><?php
                                        $date_time = substr($pdf_file,strrpos($pdf_file, '_', -26)+1);
                                        echo substr($pdf_file,0,strpos($pdf_file, '_'));
                                    
                                    ?>
                                    <td><?php
                                        $vendor_product_name = substr($pdf_file,0,strrpos($pdf_file, '_', -26));
                                        echo str_replace("_"," ",substr($vendor_product_name,strpos($vendor_product_name,"_")+1));
                                    ?>
                                    </td>
                                    <td>
                                    <?php echo str_replace("_"," ",substr($date_time,0,11));?>
                                    </td>
                                    <td>
                                    <?php
                                    echo str_replace(".pdf","",str_replace("_",":",substr($date_time,12)));?>    
                                    </td>
                                    <td><?php
                                    echo $this->Html->link($this->Html->image("/images/icons/fugue/document-pdf.png",array("title"=>"Upload PDF","alt"=>$pdf_file,'class' => 'with-tip')),HTTP_HOST."img/pull_coupon_sale_pdf/".$pdf_file,array('target'=>'_blank','escape'=>false));
                                    echo $this->Html->link($this->Html->image('/images/icons/fugue/cross-circle.png',array('title' =>'Delete','class' => 'with-tip')),array("controller"=>"coupons","action"=>"delete_csv_pdf","p",$pdf_file,"admin"=>true),array('escape' => false,'onclick'=>"return  confirm('Are you sure you want to delete?')"));
                                    ?>
                                    </td>
                                </tr>
                               <?php }
                                }
                                }else{
                                    echo "<tr><td colspan='4'>No Pdf Found</td></tr>";
                                }
                            ?>
                            </tr>
                        </tbody>
                    </table>
                </div>    
            </div>
        </div>
    </div>
</div>
<div style="clear:both;"></div>