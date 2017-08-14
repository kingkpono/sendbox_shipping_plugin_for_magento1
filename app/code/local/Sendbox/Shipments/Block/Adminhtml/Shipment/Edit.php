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
 * Shipment admin edit form
 *
 * @category    Sendbox
 * @package     Sendbox_Shipments
 * @author      Ultimate Module Creator
 */
class Sendbox_Shipments_Block_Adminhtml_Shipment_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'sendbox_shipments';
        $this->_controller = 'adminhtml_shipment';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('sendbox_shipments')->__('Post Shipment')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('sendbox_shipments')->__('Delete Shipment')
        );
      
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_shipment') && Mage::registry('current_shipment')->getId()) {
            return Mage::helper('sendbox_shipments')->__(
                "Edit Shipment '%s'",
                $this->escapeHtml(Mage::registry('current_shipment')->getChannelCode())
            );
        } else {
            return Mage::helper('sendbox_shipments')->__('Add Shipment');
        }
    }
}
