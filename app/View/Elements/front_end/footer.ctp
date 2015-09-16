<footer id="footer">
    <section class="footer-inner">
        <ul class="footer-links">
            <li> <strong>Menu</strong>
                <?php
                    echo $this->Html->link("HOME","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("ABOUT","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("FAQ","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("PRICELISTS","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("INSTALATION","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("CONTACT","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("LOGIN","javascript:void(0);",array("escape"=>false));
                ?>
                
            </li>
            <li> <strong>BRANDS</strong>
                <?php
                    echo $this->Html->link("TDC","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("TELIA","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("TELENOR","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("CBB MOBIL","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("GMOBILE","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("COLOUR MOBILE","javascript:void(0);",array("escape"=>false));
                ?>
              
            </li>
            <li> <strong>&nbsp;</strong>
                <?php
                    echo $this->Html->link("LYCA MOBILE","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("GLOBAL ONE","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("LEBARA","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("ONE MOBILE","javascript:void(0);",array("escape"=>false));
                    echo $this->Html->link("HEY","javascript:void(0);",array("escape"=>false));
                ?>
            </li>
        </ul>
        <section class="left-section">
            <p>
                <?php echo $this->Html->image("payment.png",array("alt"=>"payment"));?> 
            </p>
            <h4>HALLO.DK</h4>
            <p>Lyngbyvej 20, 2100 København Ø, 70 22 06 04, 70 22 09 04</p>
            <?php echo $this->Html->link("info@hallo.dk","javascript:void(0);",array("escape"=>false));?>
        </section>
    </section>
</footer>