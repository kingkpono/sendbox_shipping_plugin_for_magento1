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
 * Shipment front contrller
 *
 * @category    Sendbox
 * @package     Sendbox_Shipments
 * @author      Ultimate Module Creator
 */
class Sendbox_Shipments_ShipmentController extends Mage_Core_Controller_Front_Action
{

    /**
      * default action
      *
      * @access public
      * @return void
      * @author Ultimate Module Creator
      */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('sendbox_shipments/shipment')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('sendbox_shipments')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'shipments',
                    array(
                        'label' => Mage::helper('sendbox_shipments')->__('Shipments'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('sendbox_shipments/shipment')->getShipmentsUrl());
        }
        $this->renderLayout();
    }

    /**
     * init Shipment
     *
     * @access protected
     * @return Sendbox_Shipments_Model_Shipment
     * @author Ultimate Module Creator
     */
    protected function _initShipment()
    {
        $shipmentId   = $this->getRequest()->getParam('id', 0);
        $shipment     = Mage::getModel('sendbox_shipments/shipment')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($shipmentId);
        if (!$shipment->getId()) {
            return false;
        } elseif (!$shipment->getStatus()) {
            return false;
        }
        return $shipment;
    }

    /**
     * view shipment action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function viewAction()
    {
        $shipment = $this->_initShipment();
        if (!$shipment) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_shipment', $shipment);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('shipments-shipment shipments-shipment' . $shipment->getId());
        }
        if (Mage::helper('sendbox_shipments/shipment')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('sendbox_shipments')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'shipments',
                    array(
                        'label' => Mage::helper('sendbox_shipments')->__('Shipments'),
                        'link'  => Mage::helper('sendbox_shipments/shipment')->getShipmentsUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'shipment',
                    array(
                        'label' => $shipment->getChannelCode(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $shipment->getShipmentUrl());
        }
        $this->renderLayout();
    }

    /**
     * shipments rss list action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function rssAction()
    {
        if (Mage::helper('sendbox_shipments/shipment')->isRssEnabled()) {
            $this->getResponse()->setHeader('Content-type', 'text/xml; charset=UTF-8');
            $this->loadLayout(false);
            $this->renderLayout();
        } else {
            $this->getResponse()->setHeader('HTTP/1.1', '404 Not Found');
            $this->getResponse()->setHeader('Status', '404 File not found');
            $this->_forward('nofeed', 'index', 'rss');
        }
    }

     public function financeupdateAction()
    {
        
    }
    public function deliveryupdateAction()
    {
            
        
           $data = json_decode(file_get_contents('php://input'), true);
             
            $response=array("status"=>300);
            Mage::log("id ".$data["reference_code"], false, 'mylog8.log', true);
           if(isset($data["reference_code"]))
            {
            $order = Mage::getModel("sales/order")->loadByIncrementId($data["reference_code"]);
             Mage::log("status b4 ".$order->getStatus(), false, 'mylog8.log', true);
             $status_code=$data['status_code'];
             $status_from_sendbox=$data['status']['name'];
            $status = Mage::getModel('sales/order_status')->load($status_code);
           if($status->status==null){//status deos not  exist
             
             $status->setStatus($status_code);
            $status->setLabel($status_from_sendbox);
            $status->save();
            
           }-
           
           
            $order->setStatus($status_code);
           $order->save();
           Mage::log("status after ".$order->getStatus(), false, 'mylog8.log', true);
              $response=array("status"=>200);
             }
            
       

    header('Content-Type: application/json');
    echo json_encode($response);

    }

    /**
     * Submit new comment action
     * @access public
     * @author Ultimate Module Creator
     */
    public function commentpostAction()
    {
        $data   = $this->getRequest()->getPost();
        $shipment = $this->_initShipment();
        $session    = Mage::getSingleton('core/session');
        if ($shipment) {
            if ($shipment->getAllowComments()) {
                if ((Mage::getSingleton('customer/session')->isLoggedIn() ||
                    Mage::getStoreConfigFlag('sendbox_shipments/shipment/allow_guest_comment'))) {
                    $comment  = Mage::getModel('sendbox_shipments/shipment_comment')->setData($data);
                    $validate = $comment->validate();
                    if ($validate === true) {
                        try {
                            $comment->setShipmentId($shipment->getId())
                                ->setStatus(Sendbox_Shipments_Model_Shipment_Comment::STATUS_PENDING)
                                ->setCustomerId(Mage::getSingleton('customer/session')->getCustomerId())
                                ->setStores(array(Mage::app()->getStore()->getId()))
                                ->save();
                            $session->addSuccess($this->__('Your comment has been accepted for moderation.'));
                        } catch (Exception $e) {
                            $session->setShipmentCommentData($data);
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    } else {
                        $session->setShipmentCommentData($data);
                        if (is_array($validate)) {
                            foreach ($validate as $errorMessage) {
                                $session->addError($errorMessage);
                            }
                        } else {
                            $session->addError($this->__('Unable to post the comment.'));
                        }
                    }
                } else {
                    $session->addError($this->__('Guest comments are not allowed'));
                }
            } else {
                $session->addError($this->__('This shipment does not allow comments'));
            }
        }
        $this->_redirectReferer();
    }
}
