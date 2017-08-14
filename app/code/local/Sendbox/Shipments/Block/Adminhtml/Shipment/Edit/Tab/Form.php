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
 * Shipment edit form tab
 *
 * @category    Sendbox
 * @package     Sendbox_Shipments
 * @author      Ultimate Module Creator
 */
class Sendbox_Shipments_Block_Adminhtml_Shipment_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Sendbox_Shipments_Block_Adminhtml_Shipment_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('shipment_');
        $form->setFieldNameSuffix('shipment');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'shipment_form',
            array('legend' => Mage::helper('sendbox_shipments')->__('Shipment'))
        );

        $fieldset->addField(
            'origin_name',
            'text',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Origin Name'),
                'name'  => 'origin_name',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'origin_address',
            'textarea',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Origin Address'),
                'name'  => 'origin_address',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'origin_phone',
            'text',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Origin Phone'),
                'name'  => 'origin_phone',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'origin_city',
            'text',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Origin City'),
                'name'  => 'origin_city',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'origin_state',
            'text',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Origin State'),
                'name'  => 'origin_state',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'origin_country',
            'text',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Origin Country'),
                'name'  => 'origin_country',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'origin_street',
            'textarea',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Origin Street'),
                'name'  => 'origin_street',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'destination_address',
            'text',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Destination Address'),
                'name'  => 'destination_address',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'destination_phone',
            'text',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Destination Phone'),
                'name'  => 'destination_phone',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'destination_street',
            'text',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Destination Street'),
                'name'  => 'destination_street',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'destination_state',
            'text',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Destination State'),
                'name'  => 'destination_state',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'delivery_priority_code',
            'select',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Delivery Priority Code'),
                'name'  => 'delivery_priority_code',
                'required'  => true,
                'class' => 'required-entry',

                'values'=> Mage::getModel('sendbox_shipments/shipment_attribute_source_deliveryprioritycode')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'incoming_option_code',
            'select',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Pickup'),
                'name'  => 'incoming_option_code',
                'required'  => true,
                'class' => 'required-entry',

                'values'=> Mage::getModel('sendbox_shipments/shipment_attribute_source_incomingoptioncode')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'pickup_date',
            'date',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Pickup Date'),
                'name'  => 'pickup_date',
                'required'  => true,
                'class' => 'required-entry',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'delivery_type_code',
            'select',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Delivery Type Code'),
                'name'  => 'delivery_type_code',
                'required'  => true,
                'class' => 'required-entry',

                'values'=> Mage::getModel('sendbox_shipments/shipment_attribute_source_deliverytypecode')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'accept_value_on_delivery',
            'select',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Accept Value On Delivery'),
                'name'  => 'accept_value_on_delivery',
                'required'  => true,
                'class' => 'required-entry',

                'values'=> Mage::getModel('sendbox_shipments/shipment_attribute_source_acceptvalueondelivery')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'amount_to_receive',
            'text',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Amount to Receive'),
                'name'  => 'amount_to_receive',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'fee_payment_channel_code',
            'text',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Fee Payment Channel Code'),
                'name'  => 'fee_payment_channel_code',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'channel_code',
            'text',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Channel Code'),
                'name'  => 'channel_code',
                'required'  => true,
                'class' => 'required-entry',

           )
        );
        $fieldset->addField(
            'url_key',
            'text',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Url key'),
                'name'  => 'url_key',
                'note'  => Mage::helper('sendbox_shipments')->__('Relative to Website Base URL')
            )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('sendbox_shipments')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('sendbox_shipments')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('sendbox_shipments')->__('Disabled'),
                    ),
                ),
            )
        );
        $fieldset->addField(
            'in_rss',
            'select',
            array(
                'label'  => Mage::helper('sendbox_shipments')->__('Show in rss'),
                'name'   => 'in_rss',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('sendbox_shipments')->__('Yes'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('sendbox_shipments')->__('No'),
                    ),
                ),
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
            Mage::registry('current_shipment')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $fieldset->addField(
            'allow_comment',
            'select',
            array(
                'label' => Mage::helper('sendbox_shipments')->__('Allow Comments'),
                'name'  => 'allow_comment',
                'values'=> Mage::getModel('sendbox_shipments/adminhtml_source_yesnodefault')->toOptionArray()
            )
        );
        $formValues = Mage::registry('current_shipment')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getShipmentData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getShipmentData());
            Mage::getSingleton('adminhtml/session')->setShipmentData(null);
        } elseif (Mage::registry('current_shipment')) {
            $formValues = array_merge($formValues, Mage::registry('current_shipment')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
