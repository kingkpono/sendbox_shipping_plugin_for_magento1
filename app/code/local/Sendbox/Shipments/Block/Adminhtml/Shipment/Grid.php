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
 * Shipment admin grid block
 *
 * @category    Sendbox
 * @package     Sendbox_Shipments
 * @author      Ultimate Module Creator
 */
class Sendbox_Shipments_Block_Adminhtml_Shipment_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('shipmentGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Sendbox_Shipments_Block_Adminhtml_Shipment_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('sendbox_shipments/shipment')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Sendbox_Shipments_Block_Adminhtml_Shipment_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'channel_code',
            array(
                'header'    => Mage::helper('sendbox_shipments')->__('Channel Code'),
                'align'     => 'left',
                'index'     => 'channel_code',
            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('sendbox_shipments')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('sendbox_shipments')->__('Enabled'),
                    '0' => Mage::helper('sendbox_shipments')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'origin_name',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Origin Name'),
                'index'  => 'origin_name',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'origin_phone',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Origin Phone'),
                'index'  => 'origin_phone',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'origin_city',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Origin City'),
                'index'  => 'origin_city',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'origin_state',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Origin State'),
                'index'  => 'origin_state',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'origin_country',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Origin Country'),
                'index'  => 'origin_country',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'destination_address',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Destination Address'),
                'index'  => 'destination_address',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'destination_phone',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Destination Phone'),
                'index'  => 'destination_phone',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'destination_street',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Destination Street'),
                'index'  => 'destination_street',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'destination_state',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Destination State'),
                'index'  => 'destination_state',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'delivery_priority_code',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Delivery Priority Code'),
                'index'  => 'delivery_priority_code',
                'type'  => 'options',
                'options' => Mage::helper('sendbox_shipments')->convertOptions(
                    Mage::getModel('sendbox_shipments/shipment_attribute_source_deliveryprioritycode')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'incoming_option_code',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Pickup'),
                'index'  => 'incoming_option_code',
                'type'  => 'options',
                'options' => Mage::helper('sendbox_shipments')->convertOptions(
                    Mage::getModel('sendbox_shipments/shipment_attribute_source_incomingoptioncode')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'pickup_date',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Pickup Date'),
                'index'  => 'pickup_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'delivery_type_code',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Delivery Type Code'),
                'index'  => 'delivery_type_code',
                'type'  => 'options',
                'options' => Mage::helper('sendbox_shipments')->convertOptions(
                    Mage::getModel('sendbox_shipments/shipment_attribute_source_deliverytypecode')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'accept_value_on_delivery',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Accept Value On Delivery'),
                'index'  => 'accept_value_on_delivery',
                'type'  => 'options',
                'options' => Mage::helper('sendbox_shipments')->convertOptions(
                    Mage::getModel('sendbox_shipments/shipment_attribute_source_acceptvalueondelivery')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'amount_to_receive',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Amount to Receive'),
                'index'  => 'amount_to_receive',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'fee_payment_channel_code',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Fee Payment Channel Code'),
                'index'  => 'fee_payment_channel_code',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'url_key',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('URL key'),
                'index'  => 'url_key',
            )
        );
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                'store_id',
                array(
                    'header'     => Mage::helper('sendbox_shipments')->__('Store Views'),
                    'index'      => 'store_id',
                    'type'       => 'store',
                    'store_all'  => true,
                    'store_view' => true,
                    'sortable'   => false,
                    'filter_condition_callback'=> array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('sendbox_shipments')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('sendbox_shipments')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('sendbox_shipments')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('sendbox_shipments')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('sendbox_shipments')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sendbox_shipments')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('sendbox_shipments')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Sendbox_Shipments_Block_Adminhtml_Shipment_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('shipment');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('sendbox_shipments')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('sendbox_shipments')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('sendbox_shipments')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sendbox_shipments')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('sendbox_shipments')->__('Enabled'),
                            '0' => Mage::helper('sendbox_shipments')->__('Disabled'),
                        )
                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'delivery_priority_code',
            array(
                'label'      => Mage::helper('sendbox_shipments')->__('Change Delivery Priority Code'),
                'url'        => $this->getUrl('*/*/massDeliveryPriorityCode', array('_current'=>true)),
                'additional' => array(
                    'flag_delivery_priority_code' => array(
                        'name'   => 'flag_delivery_priority_code',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sendbox_shipments')->__('Delivery Priority Code'),
                        'values' => Mage::getModel('sendbox_shipments/shipment_attribute_source_deliveryprioritycode')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'incoming_option_code',
            array(
                'label'      => Mage::helper('sendbox_shipments')->__('Change Pickup'),
                'url'        => $this->getUrl('*/*/massIncomingOptionCode', array('_current'=>true)),
                'additional' => array(
                    'flag_incoming_option_code' => array(
                        'name'   => 'flag_incoming_option_code',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sendbox_shipments')->__('Pickup'),
                        'values' => Mage::getModel('sendbox_shipments/shipment_attribute_source_incomingoptioncode')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'delivery_type_code',
            array(
                'label'      => Mage::helper('sendbox_shipments')->__('Change Delivery Type Code'),
                'url'        => $this->getUrl('*/*/massDeliveryTypeCode', array('_current'=>true)),
                'additional' => array(
                    'flag_delivery_type_code' => array(
                        'name'   => 'flag_delivery_type_code',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sendbox_shipments')->__('Delivery Type Code'),
                        'values' => Mage::getModel('sendbox_shipments/shipment_attribute_source_deliverytypecode')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'accept_value_on_delivery',
            array(
                'label'      => Mage::helper('sendbox_shipments')->__('Change Accept Value On Delivery'),
                'url'        => $this->getUrl('*/*/massAcceptValueOnDelivery', array('_current'=>true)),
                'additional' => array(
                    'flag_accept_value_on_delivery' => array(
                        'name'   => 'flag_accept_value_on_delivery',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('sendbox_shipments')->__('Accept Value On Delivery'),
                        'values' => Mage::getModel('sendbox_shipments/shipment_attribute_source_acceptvalueondelivery')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Sendbox_Shipments_Model_Shipment
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Sendbox_Shipments_Block_Adminhtml_Shipment_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * filter store column
     *
     * @access protected
     * @param Sendbox_Shipments_Model_Resource_Shipment_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Sendbox_Shipments_Block_Adminhtml_Shipment_Grid
     * @author Ultimate Module Creator
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
