<?php
/**
 * Sendbox_Shipments extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Sendbox
 * @package        Sendbox_Shipments
 * @copyright      Copyright (c) 2017
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Shipment widget block
 *
 * @category    Sendbox
 * @package     Sendbox_Shipments
 * @author      Ultimate Module Creator
 */
class Sendbox_Shipments_Block_Shipment_Widget_View extends Mage_Core_Block_Template implements
    Mage_Widget_Block_Interface
{
    protected $_htmlTemplate = 'sendbox_shipments/shipment/widget/view.phtml';

    /**
     * Prepare a for widget
     *
     * @access protected
     * @return Sendbox_Shipments_Block_Shipment_Widget_View
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $shipmentId = $this->getData('shipment_id');
        if ($shipmentId) {
            $shipment = Mage::getModel('sendbox_shipments/shipment')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($shipmentId);
            if ($shipment->getStatus()) {
                $this->setCurrentShipment($shipment);
                $this->setTemplate($this->_htmlTemplate);
            }
        }
        return $this;
    }
}
