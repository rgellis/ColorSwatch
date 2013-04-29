<?php

class RMG_ColorSwatch_Model_Mysql4_ColorSwatch extends Mage_Core_Model_Mysql4_Abstract
{
	public function _construct()
	{
		$this->_init('colorswatch/colorSwatch', 'swatch_id');
	}
}