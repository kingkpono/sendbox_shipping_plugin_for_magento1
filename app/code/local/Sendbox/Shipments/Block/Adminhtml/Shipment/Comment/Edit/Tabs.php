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
 * Shipment comment admin edit tabs
 *
 * @category    Sendbox
 * @package     Sendbox_Shipments
 * @author      Ultimate Module Creator
 */
class Sendbox_Shipments_Block_Adminhtml_Shipment_Comment_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
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
        $this->setId('shipment_comment_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('sendbox_shipments')->__('Shipment Comment'));
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
            'form_shipment_comment',
            array(
                'label'   => Mage::helper('sendbox_shipments')->__('Shipment comment'),
                'title'   => Mage::helper('sendbox_shipments')->__('Shipment comment'),
                'content' => $this->getLayout()->createBlock(
                    'sendbox_shipments/adminhtml_shipment_comment_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_shipment_comment',
                array(
                    'label'   => Mage::helper('sendbox_shipments')->__('Store views'),
                    'title'   => Mage::helper('sendbox_shipments')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'sendbox_shipments/adminhtml_shipment_comment_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve comment
     *
     * @access public
     * @return Sendbox_Shipments_Model_Shipment_Comment
     * @author Ultimate Module Creator
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
    }
}
