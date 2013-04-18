<?php

require_once("Mage/Adminhtml/controllers/Catalog/Product/AttributeController.php");
class RMG_ColorSwatch_Catalog_Product_AttributeController extends Mage_Adminhtml_Catalog_Product_AttributeController
{
	public function uploadAction()
	{
        if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '') {
            try {
                $fileName       = $_FILES['file']['name'];
                $fileExt        = strtolower(substr(strrchr($fileName, "."), 1));
                $fileNamewoe    = rtrim($fileName, $fileExt);
                $fileName       = str_replace(' ', '', $fileNamewoe) . '.' . $fileExt;

                $uploader = new Varien_File_Uploader('file');
                $uploader->setAllowedExtensions(array('png', 'jpg', 'jpeg', 'gif')); //allowed extensions
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $path = Mage::getBaseDir('media') . DS . 'attribute_swatches';

                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }

                $uploader->save($path . DS, $fileName);
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
	}
}