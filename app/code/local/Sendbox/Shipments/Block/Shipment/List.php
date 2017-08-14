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
 * Shipment list block
 *
 * @category    Sendbox
 * @package     Sendbox_Shipments
 * @author Ultimate Module Creator
 */
class Sendbox_Shipments_Block_Shipment_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $shipments = Mage::getResourceModel('sendbox_shipments/shipment_collection')
                         ->addStoreFilter(Mage::app()->getStore())
                         ->addFieldToFilter('status', 1);
        $shipments->setOrder('channel_code', 'asc');
        $this->setShipments($shipments);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Sendbox_Shipments_Block_Shipment_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'sendbox_shipments.shipment.html.pager'
        )
        ->setCollection($this->getShipments());
        $this->setChild('pager', $pager);
        $this->getShipments()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
