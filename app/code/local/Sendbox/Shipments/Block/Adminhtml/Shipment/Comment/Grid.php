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
 * Shipment comments admin grid block
 *
 * @category    Sendbox
 * @package     Sendbox_Shipments
 * @author      Ultimate Module Creator
 */
class Sendbox_Shipments_Block_Adminhtml_Shipment_Comment_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('shipmentCommentGrid');
        $this->setDefaultSort('ct_comment_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Sendbox_Shipments_Block_Adminhtml_Shipment_Comment_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('sendbox_shipments/shipment_comment_shipment_collection');
        $collection->addStoreData();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Sendbox_Shipments_Block_Adminhtml_Shipment_Comment_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'ct_comment_id',
            array(
                'header'        => Mage::helper('sendbox_shipments')->__('Id'),
                'index'         => 'ct_comment_id',
                'type'          => 'number',
                'filter_index'  => 'ct.comment_id',
            )
        );
        $this->addColumn(
            'channel_code',
            array(
                'header'        => Mage::helper('sendbox_shipments')->__('Channel Code'),
                'index'         => 'channel_code',
                'filter_index'  => 'main_table.channel_code',
            )
        );
        $this->addColumn(
            'ct_title',
            array(
                'header'        => Mage::helper('sendbox_shipments')->__('Comment Title'),
                'index'         => 'ct_title',
                'filter_index'  => 'ct.title',
            )
        );
        $this->addColumn(
            'ct_name',
            array(
                'header'        => Mage::helper('sendbox_shipments')->__('Poster name'),
                'index'         => 'ct_name',
                'filter_index'  => 'ct.name',
            )
        );
        $this->addColumn(
            'ct_email',
            array(
                'header'        => Mage::helper('sendbox_shipments')->__('Poster email'),
                'index'         => 'ct_email',
                'filter_index'  => 'ct.email',
            )
        );
        $this->addColumn(
            'ct_status',
            array(
                'header'        => Mage::helper('sendbox_shipments')->__('Status'),
                'index'         => 'ct_status',
                'filter_index'  => 'ct.status',
                'type'          => 'options',
                'options'       => array(
                    Sendbox_Shipments_Model_Shipment_Comment::STATUS_PENDING  =>
                        Mage::helper('sendbox_shipments')->__('Pending'),
                    Sendbox_Shipments_Model_Shipment_Comment::STATUS_APPROVED =>
                        Mage::helper('sendbox_shipments')->__('Approved'),
                    Sendbox_Shipments_Model_Shipment_Comment::STATUS_REJECTED =>
                        Mage::helper('sendbox_shipments')->__('Rejected'),
                )
            )
        );
        $this->addColumn(
            'ct_created_at',
            array(
                'header'        => Mage::helper('sendbox_shipments')->__('Created at'),
                'index'         => 'ct_created_at',
                'width'         => '120px',
                'type'          => 'datetime',
                'filter_index'  => 'ct.created_at',
            )
        );
        $this->addColumn(
            'ct_updated_at',
            array(
                'header'        => Mage::helper('sendbox_shipments')->__('Updated at'),
                'index'         => 'ct_updated_at',
                'width'         => '120px',
                'type'          => 'datetime',
                'filter_index'  => 'ct.updated_at',
            )
        );
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                'stores',
                array(
                    'header'     => Mage::helper('sendbox_shipments')->__('Store Views'),
                    'index'      => 'stores',
                    'type'       => 'store',
                    'store_all'  => true,
                    'store_view' => true,
                    'sortable'   => false,
                    'filter_condition_callback' => array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'action',
            array(
                'header'  => Mage::helper('sendbox_shipments')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getCtCommentId',
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
        $this->setMassactionIdField('ct_comment_id');
        $this->setMassactionIdFilter('ct.comment_id');
        $this->setMassactionIdFieldOnlyIndexValue(true);
        $this->getMassactionBlock()->setFormFieldName('comment');
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
                'label' => Mage::helper('sendbox_shipments')->__('Change status'),
                'url'   => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                            'name' => 'status',
                            'type' => 'select',
                            'class' => 'required-entry',
                            'label' => Mage::helper('sendbox_shipments')->__('Status'),
                            'values' => array(
                                Sendbox_Shipments_Model_Shipment_Comment::STATUS_PENDING  =>
                                    Mage::helper('sendbox_shipments')->__('Pending'),
                                Sendbox_Shipments_Model_Shipment_Comment::STATUS_APPROVED =>
                                    Mage::helper('sendbox_shipments')->__('Approved'),
                                Sendbox_Shipments_Model_Shipment_Comment::STATUS_REJECTED =>
                                    Mage::helper('sendbox_shipments')->__('Rejected'),
                            )
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
     * @param Sendbox_Shipments_Model_Shipment_Comment
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getCtCommentId()));
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
     * filter store column
     *
     * @access protected
     * @param Sendbox_Shipments_Model_Resource_Shipment_Comment_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Sendbox_Shipments_Block_Adminhtml_Shipment_Comment_Grid
     * @author Ultimate Module Creator
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->setStoreFilter($value);
        return $this;
    }
}
