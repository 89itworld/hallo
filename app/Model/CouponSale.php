<?php
class CouponSale extends AppModel{
    
    var $name = 'CouponSale';
	var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'dealer_id',
        )
    );
    /*
    * Function to overwrite  paginateCount due to the resion of using group by in reporting section
    */
    
    function paginateCount($conditions = null, $recursive = 0, $extra = array()) {
	$parameters = compact('conditions');
		    //pr($recursive); die("fslkh");
	$this->recursive = $recursive;
	$count = $this->find('count', array_merge($parameters, $extra));
	if (isset($extra['group'])) {
	    $count = $this->getAffectedRows();
	}
	return $count;
    }
	
}

?>