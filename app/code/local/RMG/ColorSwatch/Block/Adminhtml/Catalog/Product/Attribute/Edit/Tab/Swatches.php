<?php

class RMG_ColorSwatch_Block_Adminhtml_Catalog_Product_Attribute_Edit_Tab_Swatches extends Mage_Adminhtml_Block_Widget_Form
{

	public function __construct()
    {
        parent::__construct();
        $this->setTemplate('catalog/product/attribute/swatches.phtml');
    }

    /**
     * Retrieve stores collection with default store
     *
     * @return Mage_Core_Model_Mysql4_Store_Collection
     */
    public function getStores()
    {
        $stores = $this->getData('stores');
        if (is_null($stores)) {
            $stores = Mage::getModel('core/store')
                ->getResourceCollection()
                ->setLoadDefault(true)
                ->load();
            $this->setData('stores', $stores);
        }
        return $stores;
    }

    /**
     * Retrieve attribute option values if attribute input type select
     *
     * @return array
     */
    public function getOptionValues($storeId)
    {
        $attributeType = $this->getAttributeObject()->getFrontendInput();
        $defaultValues = $this->getAttributeObject()->getDefaultValue();
        if ($attributeType == 'select') {
            $defaultValues = explode(',', $defaultValues);
        } else {
            $defaultValues = array();
        }

        switch ($attributeType) {
            case 'select':
                $inputType = 'radio';
                break;
            default:
                $inputType = '';
                break;
        }

        $values = $this->getData('option_values');
        if (is_null($values)) {
            $values = array();
            $optionCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                ->setAttributeFilter($this->getAttributeObject()->getId());
                //->setPositionOrder('desc', true)
                //->load();
            $optionCollection->getSelect()
                ->joinLeft(array('swatch'=>'eav_attribute_option_swatch'),'swatch.option_id=main_table.option_id')
                ->reset(Zend_Db_Select::COLUMNS)
                ->columns(array(
                    'option_id' => 'main_table.option_id',
                    'swatch_id' => 'swatch.swatch_id',
                    'store_id'  => 'swatch.store_id',
                    'path'      => 'swatch.path'
                ));

            foreach ($optionCollection as $option) {

                $value = array();
                /*if (in_array($option->getId(), $defaultValues)) {
                    $value['checked'] = 'checked="checked"';
                } else {
                    $value['checked'] = '';
                }*/

                $value['swatch_id'] = $option->getSwatchId();
                $value['path'] = $option->getPath();
                //$value['intype'] = $inputType;
                $value['id'] = $option->getId();
                //$value['sort_order'] = $option->getSortOrder();
                foreach ($this->getStores() as $store) {

                    if ($store->getId() == $storeId) {

                        $storeValues = $this->getStoreOptionValues($store->getId());
                        if (isset($storeValues[$option->getId()])) {
                            $value['value'] = htmlspecialchars($storeValues[$option->getId()]);
                        }
                        else {
                            $value['value'] = '';
                        }

                    }
                }
                $values[] = new Varien_Object($value);
            }
            $this->setData('option_values', $values);
        }

        return $values;
    }

    /**
     * Retrieve frontend labels of attribute for each store
     *
     * @return array
     */
    public function getLabelValues()
    {
        $values = array();
        $values[0] = $this->getAttributeObject()->getFrontend()->getLabel();
        // it can be array and cause bug
        $frontendLabel = $this->getAttributeObject()->getFrontend()->getLabel();
        if (is_array($frontendLabel)) {
            $frontendLabel = array_shift($frontendLabel);
        }
        $storeLabels = $this->getAttributeObject()->getStoreLabels();
        foreach ($this->getStores() as $store) {
            if ($store->getId() != 0) {
                $values[$store->getId()] = isset($storeLabels[$store->getId()]) ? $storeLabels[$store->getId()] : '';
            }
        }
        return $values;
    }

    /**
     * Retrieve attribute option values for given store id
     *
     * @param integer $storeId
     * @return array
     */
    public function getStoreOptionValues($storeId)
    {
        $values = $this->getData('store_option_values_'.$storeId);
        if (is_null($values)) {
            $values = array();
            $valuesCollection = Mage::getResourceModel('eav/entity_attribute_option_collection')
                ->setAttributeFilter($this->getAttributeObject()->getId())
                ->setStoreFilter($storeId, false)
                ->load();
            foreach ($valuesCollection as $item) {
                $values[$item->getId()] = $item->getValue();
            }
            $this->setData('store_option_values_'.$storeId, $values);
        }
        return $values;
    }

    /**
     * Retrieve attribute object from registry
     *
     * @return Mage_Eav_Model_Entity_Attribute_Abstract
     */
    public function getAttributeObject()
    {
        return Mage::registry('entity_attribute');
    }

}
