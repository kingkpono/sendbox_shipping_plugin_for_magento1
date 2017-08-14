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
 * Shipment abstract REST API handler model
 *
 * @category    Sendbox
 * @package     Sendbox_Shipments
 * @author      Ultimate Module Creator
 */
abstract class Sendbox_Shipments_Model_Api2_Shipment_Rest extends Sendbox_Shipments_Model_Api2_Shipment
{
    /**
     * current shipment
     */
    protected $_shipment;

    /**
     * retrieve entity
     *
     * @access protected
     * @return array|mixed
     * @author Ultimate Module Creator
     */
    protected function _retrieve() {
        $shipment = $this->_getShipment();
        $this->_prepareShipmentForResponse($shipment);
        return $shipment->getData();
    }

    /**
     * get collection
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _retrieveCollection() {
        $collection = Mage::getResourceModel('sendbox_shipments/shipment_collection');
        $entityOnlyAttributes = $this->getEntityOnlyAttributes(
            $this->getUserType(),
            Mage_Api2_Model_Resource::OPERATION_ATTRIBUTE_READ
        );
        $availableAttributes = array_keys($this->getAvailableAttributes(
            $this->getUserType(),
            Mage_Api2_Model_Resource::OPERATION_ATTRIBUTE_READ)
        );
        $collection->addFieldToFilter('status', array('eq' => 1));
        $store = $this->_getStore();
        $collection->addStoreFilter($store->getId());
        $this->_applyCollectionModifiers($collection);
        $shipments = $collection->load();
        $shipments->walk('afterLoad');
        foreach ($shipments as $shipment) {
            $this->_setShipment($shipment);
            $this->_prepareShipmentForResponse($shipment);
        }
        $shipmentsArray = $shipments->toArray();
        $shipmentsArray = $shipmentsArray['items'];

        return $shipmentsArray;
    }

    /**
     * prepare shipment for response
     *
     * @access protected
     * @param Sendbox_Shipments_Model_Shipment $shipment
     * @author Ultimate Module Creator
     */
    protected function _prepareShipmentForResponse(Sendbox_Shipments_Model_Shipment $shipment) {
        $shipmentData = $shipment->getData();
        if ($this->getActionType() == self::ACTION_TYPE_ENTITY) {
            $shipmentData['url'] = $shipment->getShipmentUrl();
        }
    }

    /**
     * create shipment
     *
     * @access protected
     * @param array $data
     * @return string|void
     * @author Ultimate Module Creator
     */
    protected function _create(array $data) {
        $this->_critical(self::RESOURCE_METHOD_NOT_ALLOWED);
    }

    /**
     * update shipment
     *
     * @access protected
     * @param array $data
     * @author Ultimate Module Creator
     */
    protected function _update(array $data) {
        $this->_critical(self::RESOURCE_METHOD_NOT_ALLOWED);
    }

    /**
     * delete shipment
     *
     * @access protected
     * @author Ultimate Module Creator
     */
    protected function _delete() {
        $this->_critical(self::RESOURCE_METHOD_NOT_ALLOWED);
    }

    /**
     * delete current shipment
     *
     * @access protected
     * @param Sendbox_Shipments_Model_Shipment $shipment
     * @author Ultimate Module Creator
     */
    protected function _setShipment(Sendbox_Shipments_Model_Shipment $shipment) {
        $this->_shipment = $shipment;
    }

    /**
     * get current shipment
     *
     * @access protected
     * @return Sendbox_Shipments_Model_Shipment
     * @author Ultimate Module Creator
     */
    protected function _getShipment() {
        if (is_null($this->_shipment)) {
            $shipmentId = $this->getRequest()->getParam('id');
            $shipment = Mage::getModel('sendbox_shipments/shipment');
            $shipment->load($shipmentId);
            if (!($shipment->getId())) {
                $this->_critical(self::RESOURCE_NOT_FOUND);
            }
            if ($this->_getStore()->getId()) {
                $isValidStore = count(array_intersect(array(0, $this->_getStore()->getId()), $shipment->getStoreId()));
                if (!$isValidStore) {
                    $this->_critical(self::RESOURCE_NOT_FOUND);
                }
            }
            $this->_shipment = $shipment;
        }
        return $this->_shipment;
    }
}
