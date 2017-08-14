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
 * Shipment admin edit tabs
 *
 * @category    Sendbox
 * @package     Sendbox_Shipments
 * @author      Ultimate Module Creator
 */
class Sendbox_Shipments_Block_Adminhtml_Shipment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('shipment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('sendbox_shipments')->__('Shipment'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Sendbox_Shipments_Block_Adminhtml_Shipment_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_shipment',
            array(
                'label'   => Mage::helper('sendbox_shipments')->__('Shipment'),
                'title'   => Mage::helper('sendbox_shipments')->__('Shipment'),
                'content' => $this->getLayout()->createBlock(
                    'sendbox_shipments/adminhtml_shipment_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_shipment',
                array(
                    'label'   => Mage::helper('sendbox_shipments')->__('Store views'),
                    'title'   => Mage::helper('sendbox_shipments')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'sendbox_shipments/adminhtml_shipment_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve shipment entity
     *
     * @access public
     * @return Sendbox_Shipments_Model_Shipment
     * @author Ultimate Module Creator
     */
    public function getShipment()
    {
        return Mage::registry('current_shipment');
    }
}
