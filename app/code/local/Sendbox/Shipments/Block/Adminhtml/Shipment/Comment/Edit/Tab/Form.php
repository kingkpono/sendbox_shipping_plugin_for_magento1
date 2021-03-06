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
 * Shipment comment edit form tab
 *
 * @category    Sendbox
 * @package     Sendbox_Shipments
 * @author      Ultimate Module Creator
 */
class Sendbox_Shipments_Block_Adminhtml_Shipment_Comment_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Shipments_Shipment_Block_Adminhtml_Shipment_Comment_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $shipment = Mage::registry('current_shipment');
        $comment    = Mage::registry('current_comment');
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('comment_');
        $form->setFieldNameSuffix('comment');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'comment_form',
            array('legend'=>Mage::helper('sendbox_shipments')->__('Comment'))
        );
        $fieldset->addField(
            'shipment_id',
            'hidden',
            array(
                'name'  => 'shipment_id',
                'after_element_html' => '<a href="'.
                    Mage::helper('adminhtml')->getUrl(
                        'adminhtml/shipments_shipment/edit',
                        array(
                            'id'=>$shipment->getId()
                        )
                    ).
                    '" target="_blank">'.
                    Mage::helper('sendbox_shipments')->__('Shipment').
                    ' : '.$shipment->getChannelCode().'</a>'
            )
        );
        $fieldset->addField(
            'title',
            'text',
            array(
                'label'    => Mage::helper('sendbox_shipments')->__('Title'),
                'name'     => 'title',
                'required' => true,
                'class'    => 'required-entry',
            )
        );
        $fieldset->addField(
            'comment',
            'textarea',
            array(
                'label'    => Mage::helper('sendbox_shipments')->__('Comment'),
                'name'     => 'comment',
                'required' => true,
                'class'    => 'required-entry',
            )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'    => Mage::helper('sendbox_shipments')->__('Status'),
                'name'     => 'status',
                'required' => true,
                'class'    => 'required-entry',
                'values'   => array(
                    array(
                        'value' => Sendbox_Shipments_Model_Shipment_Comment::STATUS_PENDING,
                        'label' => Mage::helper('sendbox_shipments')->__('Pending'),
                    ),
                    array(
                        'value' => Sendbox_Shipments_Model_Shipment_Comment::STATUS_APPROVED,
                        'label' => Mage::helper('sendbox_shipments')->__('Approved'),
                    ),
                    array(
                        'value' => Sendbox_Shipments_Model_Shipment_Comment::STATUS_REJECTED,
                        'label' => Mage::helper('sendbox_shipments')->__('Rejected'),
                    ),
                ),
            )
        );
        $configuration = array(
             'label' => Mage::helper('sendbox_shipments')->__('Poster name'),
             'name'  => 'name',
             'required'  => true,
             'class' => 'required-entry',
        );
        if ($comment->getCustomerId()) {
            $configuration['after_element_html'] = '<a href="'.
                Mage::helper('adminhtml')->getUrl(
                    'adminhtml/customer/edit',
                    array(
                        'id'=>$comment->getCustomerId()
                    )
                ).
                '" target="_blank">'.
                Mage::helper('sendbox_shipments')->__('Customer profile').'</a>';
        }
        $fieldset->addField('name', 'text', $configuration);
        $fieldset->addField(
            'email',
            'text',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Poster e-mail'),
                'name'  => 'email',
                'required'  => true,
                'class' => 'required-entry',
            )
        );
        $fieldset->addField(
            'customer_id',
            'hidden',
            array(
                'name'  => 'customer_id',
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_comment')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $form->addValues($this->getComment()->getData());
        return parent::_prepareForm();
    }

    /**
     * get the current comment
     *
     * @access public
     * @return Sendbox_Shipments_Model_Shipment_Comment
     */
    public function getComment()
    {
        return Mage::registry('current_comment');
    }
}
