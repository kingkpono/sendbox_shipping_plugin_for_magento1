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
 * Shipment view block
 *
 * @category    Sendbox
 * @package     Sendbox_Shipments
 * @author      Ultimate Module Creator
 */
class Sendbox_Shipments_Block_Shipment_View extends Mage_Core_Block_Template
{
    /**
     * get the current shipment
     *
     * @access public
     * @return mixed (Sendbox_Shipments_Model_Shipment|null)
     * @author Ultimate Module Creator
     */
    public function getCurrentShipment()
    {
        return Mage::registry('current_shipment');
    }
}
