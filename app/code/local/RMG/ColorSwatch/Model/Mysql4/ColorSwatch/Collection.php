<?php

class RMG_ColorSwatch_Model_Mysql4_ColorSwatch_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
	public function _construct()
	{
		//parent::_construct();
		$this->_init('colorswatch/colorSwatch');
	}
}