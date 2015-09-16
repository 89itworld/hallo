
<?php echo $this->element('backend/dealerjs');?>
<?php echo $this->element('backend/vendorjs');?>


<div class="lite-grey-gradient with-padding">
    <div class="col200pxL-left">
        <h3>Trends</h3>
        <ul class="side-tabs js-tabs same-height">
                <li><a title="Dealer Stats" href="#tab-global" onclick="dealer();">Dealer Stats</a></li>
                <li><a title="Vender Stats" href="#" onclick="vendor();">voucher Stats</a></li>
                <li><a title="Coupon Statistics" href="#tab-relations">Coupon Statistics</a></li>
                
                
        </ul>
            
    </div>
    <div class="col200pxL-right">
        <div style="height: 302px;" class="tabs-content" id="tab-global">
            <div></div>
        </div>
        <!--<div class="tabs-content" id="tab-settings" style="height: 302px; display: none;">
            <div></div>
        </div>-->
        <div class="tabs-content" id="tab-relations" style="height: 500px; display: none;">
            <?php echo $this->element('backend/coupon_statistics');?>
        </div>
    </div>
</div>

