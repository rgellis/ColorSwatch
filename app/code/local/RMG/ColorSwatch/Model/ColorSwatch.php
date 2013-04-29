<?php

class RMG_ColorSwatch_Model_ColorSwatch extends Mage_Core_Model_Abstract
{
	public function _construct()
	{
		parent::_construct();
		$this->_init('colorswatch/colorSwatch');
	}
}