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
 * Admin search model
 *
 * @category    Sendbox
 * @package     Sendbox_Shipments
 * @author      Ultimate Module Creator
 */
class Sendbox_Shipments_Model_Adminhtml_Search_Shipment extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Sendbox_Shipments_Model_Adminhtml_Search_Shipment
     * @author Ultimate Module Creator
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('sendbox_shipments/shipment_collection')
            ->addFieldToFilter('channel_code', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $shipment) {
            $arr[] = array(
                'id'          => 'shipment/1/'.$shipment->getId(),
                'type'        => Mage::helper('sendbox_shipments')->__('Shipment'),
                'name'        => $shipment->getChannelCode(),
                'description' => $shipment->getChannelCode(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/shipments_shipment/edit',
                    array('id'=>$shipment->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
