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
* Shipment admin controller
*
* @category    Sendbox
* @package     Sendbox_Shipments
* @author      Ultimate Module Creator
*/
class Sendbox_Shipments_Adminhtml_Shipments_ShipmentController extends Sendbox_Shipments_Controller_Adminhtml_Shipments
{
/**
* init the shipment
*
* @access protected
* @return Sendbox_Shipments_Model_Shipment
*/
protected function _initShipment()
{
$shipmentId  = (int) $this->getRequest()->getParam('id');
$shipment    = Mage::getModel('sendbox_shipments/shipment');
if ($shipmentId) {
$shipment->load($shipmentId);
}
Mage::register('current_shipment', $shipment);
return $shipment;
}
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
$this->_title(Mage::helper('sendbox_shipments')->__('Sendbox Shipments'))
->_title(Mage::helper('sendbox_shipments')->__('Shipments'));
$this->renderLayout();
}
public function getquoteAction() {
$auth=Mage::helper('sendbox_shipments')->getAuthHeader();

// "Fetch" display
$this->loadLayout();
//get order object details and populate payload
$order_id=$this->getRequest()->getParam('order_id');
$order=null;
$block==null;
$response=null;
if(isset($order_id))
{


$order=Mage::getModel('sales/order')->load($order_id);
$post= mage::helper('sendbox_shipments')->build_quote_payload($order);

}else{

$this->_getSession()->addError($this->__('No Order Id'));
$block=$this->getLayout()->createBlock('core/text','sendbox-block')->setText("<h2>No Order Id</h2>");
$this->_addContent($block);
$this->_setActiveMenu("sales");
$this->renderLayout();

}



$url= Mage::helper("sendbox_shipments")->getBaseUrl().'/v1/merchant/shipments/delivery_quote';
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Content-Type: application/json',
'Authorization: '.$auth)
);
$response= json_decode(curl_exec($ch));

$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// close the connection, release resources used
curl_close($ch);
// do anything you want with your response
$post_url=Mage::helper('adminhtml')->getUrl('/shipments_shipment/post');
$form_key=Mage::getSingleton('core/session')->getFormKey();
$output='<center><h2>Choose Carrier</h2><form method="post" action="'.$post_url.'">
	<div class="origin_name">
		<label for="email">Carrier Rates:</label>
		<select name="selected_courier_id" >
			';
			$rates=$response->{'rates'};
			foreach ($rates as $rate) {
			
			$output.= '<option value="'.$rate->id.'" >'.$rate->courier->name.'- ₦'.$rate->fee.'</option>';
			}
			$output.='
		</select>
	</div>
	
	<div><input name="form_key" type="hidden" value="'.$form_key.'" /></div>
	<div><input name="fee" type="hidden" value="'.$rate->fee.'" /></div>
	<div> <input type="hidden" value="'.$order->getId().'" name="order_id"/></div>
	<div >
	<br/>
		<button name="submit" class="scalable" type="submit">Post Shipment</button>
		
	</div>
</form></center>';

  if($httpcode >= 400  & $httpcode<500){
 	$output2="";
           foreach($response as $key => $value){
           $output2.= $key . ":" . $value;
          }
$output=$output2;
$this->_getSession()->addError($this->__($output));

 }else  if($httpcode>=500 ){
       $message=$response->{'message'};
$output=$message;

$this->_getSession()->addError($this->__($output));
}else if($httpcode>200 && $httpcode<209){


}else{
	$this->_getSession()->addError($this->__('Sorry,Carrier rates could not be fetched at this time,retry later.'));

}
$block=$this->getLayout()->createBlock('core/text','sendbox-block')->setText($output);
$this->_addContent($block);
$this->_setActiveMenu("sales");
$this->renderLayout();


}


public function postAction() {
$auth=Mage::helper('sendbox_shipments')->getAuthHeader();

$this->loadLayout();
// "Fetch" display

//get order object details and populate payload
$order_id=$this->getRequest()->getParam('order_id');
$selected_courier_id=$this->getRequest()->getParam('selected_courier_id');
$fee=$this->getRequest()->getParam('fee');

$order=null;
$block==null;
$response=null;
if(isset($order_id))
{

$order=Mage::getModel('sales/order')->load($order_id);
$post= Mage::helper('sendbox_shipments')->build_payload($order,$selected_courier_id,$fee);


$url=Mage::helper("sendbox_shipments")->getBaseUrl().'/v1/merchant/shipments';



$ch = curl_init($url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Content-Type: application/json',
'Authorization: '.$auth)
);
$resp= curl_exec($ch);

$response= json_decode($resp);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
// close the connection, release resources used
curl_close($ch);

$tracking_code;
$status_code;
$output;

 if($httpcode >= 400  & $httpcode<500){
 	
$output=$resp;

$this->_getSession()->addError($this->__($output));

 }else  if($httpcode>=500 ){
     
$output=$resp;

$this->_getSession()->addError($this->__($output));
}
else if($httpcode>=200 && $httpcode<209){

$tracking_code=$response->{'code'};
$status_code=$response->{'status_code'};
$carrier=$response->{'carrier'};
$carrier_name=$carrier->{'name'};
$output= "Tracking Number:".$tracking_code.",<br/> Status:". $status_code;
Mage::helper('sendbox_shipments')->make_shipment($order,$tracking_code,$carrier_name);
$this->_getSession()->addSuccess($this->__('Shipment Created <br/>'.$output));


}else{
$output='Sorry shipment could not be created.Try again later';
$this->_getSession()->addError($this->__('Sorry shipment could not be created.Try again later'));
}


$this->_redirect('/sales_order/view',array('order_id' =>$order_id));
return;


}
}

/**
* grid action
*
* @access public
* @return void
* @author Ultimate Module Creator
*/
public function gridAction()
{
$this->loadLayout()->renderLayout();
}
/**
* edit shipment - action
*
* @access public
* @return void
* @author Ultimate Module Creator
*/
public function editAction()
{
$shipmentId    = $this->getRequest()->getParam('id');
$shipment      = $this->_initShipment();
if ($shipmentId && !$shipment->getId()) {
$this->_getSession()->addError(
Mage::helper('sendbox_shipments')->__('This shipment no longer exists.')
);
$this->_redirect('*/*/');
return;
}
$data = Mage::getSingleton('adminhtml/session')->getShipmentData(true);
if (!empty($data)) {
$shipment->setData($data);
}
Mage::register('shipment_data', $shipment);
$this->loadLayout();
$this->_title(Mage::helper('sendbox_shipments')->__('Sendbox Shipments'))
->_title(Mage::helper('sendbox_shipments')->__('Shipments'));
if ($shipment->getId()) {
$this->_title($shipment->getChannelCode());
} else {
$this->_title(Mage::helper('sendbox_shipments')->__('Add shipment'));
}
if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
}
$this->renderLayout();
}
/**
* new shipment action
*
* @access public
* @return void
* @author Ultimate Module Creator
*/
public function newAction()
{
$this->_forward('edit');
}
/**
* save shipment - action
*
* @access public
* @return void
* @author Ultimate Module Creator
*/
public function saveAction()
{
if ($data = $this->getRequest()->getPost('shipment')) {
try {
$data = $this->_filterDates($data, array('pickup_date'));
$shipment = $this->_initShipment();
$shipment->addData($data);
$shipment->save();
Mage::getSingleton('adminhtml/session')->addSuccess(
Mage::helper('sendbox_shipments')->__('Shipment was successfully saved')
);
Mage::getSingleton('adminhtml/session')->setFormData(false);
if ($this->getRequest()->getParam('back')) {
$this->_redirect('*/*/edit', array('id' => $shipment->getId()));
return;
}
$this->_redirect('*/*/');
return;
} catch (Mage_Core_Exception $e) {
Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
Mage::getSingleton('adminhtml/session')->setShipmentData($data);
$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
return;
} catch (Exception $e) {
Mage::logException($e);
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('There was a problem saving the shipment.')
);
Mage::getSingleton('adminhtml/session')->setShipmentData($data);
$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
return;
}
}
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('Unable to find shipment to save.')
);
$this->_redirect('*/*/');
}
/**
* delete shipment - action
*
* @access public
* @return void
* @author Ultimate Module Creator
*/
public function deleteAction()
{
if ( $this->getRequest()->getParam('id') > 0) {
try {
$shipment = Mage::getModel('sendbox_shipments/shipment');
$shipment->setId($this->getRequest()->getParam('id'))->delete();
Mage::getSingleton('adminhtml/session')->addSuccess(
Mage::helper('sendbox_shipments')->__('Shipment was successfully deleted.')
);
$this->_redirect('*/*/');
return;
} catch (Mage_Core_Exception $e) {
Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
} catch (Exception $e) {
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('There was an error deleting shipment.')
);
$this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
Mage::logException($e);
return;
}
}
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('Could not find shipment to delete.')
);
$this->_redirect('*/*/');
}
/**
* mass delete shipment - action
*
* @access public
* @return void
* @author Ultimate Module Creator
*/
public function massDeleteAction()
{
$shipmentIds = $this->getRequest()->getParam('shipment');
if (!is_array($shipmentIds)) {
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('Please select shipments to delete.')
);
} else {
try {
foreach ($shipmentIds as $shipmentId) {
$shipment = Mage::getModel('sendbox_shipments/shipment');
$shipment->setId($shipmentId)->delete();
}
Mage::getSingleton('adminhtml/session')->addSuccess(
Mage::helper('sendbox_shipments')->__('Total of %d shipments were successfully deleted.', count($shipmentIds))
);
} catch (Mage_Core_Exception $e) {
Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
} catch (Exception $e) {
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('There was an error deleting shipments.')
);
Mage::logException($e);
}
}
$this->_redirect('*/*/index');
}
/**
* mass status change - action
*
* @access public
* @return void
* @author Ultimate Module Creator
*/
public function massStatusAction()
{
$shipmentIds = $this->getRequest()->getParam('shipment');
if (!is_array($shipmentIds)) {
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('Please select shipments.')
);
} else {
try {
foreach ($shipmentIds as $shipmentId) {
$shipment = Mage::getSingleton('sendbox_shipments/shipment')->load($shipmentId)
->setStatus($this->getRequest()->getParam('status'))
->setIsMassupdate(true)
->save();
}
$this->_getSession()->addSuccess(
$this->__('Total of %d shipments were successfully updated.', count($shipmentIds))
);
} catch (Mage_Core_Exception $e) {
Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
} catch (Exception $e) {
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('There was an error updating shipments.')
);
Mage::logException($e);
}
}
$this->_redirect('*/*/index');
}
/**
* mass Delivery Priority Code change - action
*
* @access public
* @return void
* @author Ultimate Module Creator
*/
public function massDeliveryPriorityCodeAction()
{
$shipmentIds = $this->getRequest()->getParam('shipment');
if (!is_array($shipmentIds)) {
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('Please select shipments.')
);
} else {
try {
foreach ($shipmentIds as $shipmentId) {
$shipment = Mage::getSingleton('sendbox_shipments/shipment')->load($shipmentId)
->setDeliveryPriorityCode($this->getRequest()->getParam('flag_delivery_priority_code'))
->setIsMassupdate(true)
->save();
}
$this->_getSession()->addSuccess(
$this->__('Total of %d shipments were successfully updated.', count($shipmentIds))
);
} catch (Mage_Core_Exception $e) {
Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
} catch (Exception $e) {
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('There was an error updating shipments.')
);
Mage::logException($e);
}
}
$this->_redirect('*/*/index');
}
/**
* mass Pickup change - action
*
* @access public
* @return void
* @author Ultimate Module Creator
*/
public function massIncomingOptionCodeAction()
{
$shipmentIds = $this->getRequest()->getParam('shipment');
if (!is_array($shipmentIds)) {
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('Please select shipments.')
);
} else {
try {
foreach ($shipmentIds as $shipmentId) {
$shipment = Mage::getSingleton('sendbox_shipments/shipment')->load($shipmentId)
->setIncomingOptionCode($this->getRequest()->getParam('flag_incoming_option_code'))
->setIsMassupdate(true)
->save();
}
$this->_getSession()->addSuccess(
$this->__('Total of %d shipments were successfully updated.', count($shipmentIds))
);
} catch (Mage_Core_Exception $e) {
Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
} catch (Exception $e) {
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('There was an error updating shipments.')
);
Mage::logException($e);
}
}
$this->_redirect('*/*/index');
}
/**
* mass Delivery Type Code change - action
*
* @access public
* @return void
* @author Ultimate Module Creator
*/
public function massDeliveryTypeCodeAction()
{
$shipmentIds = $this->getRequest()->getParam('shipment');
if (!is_array($shipmentIds)) {
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('Please select shipments.')
);
} else {
try {
foreach ($shipmentIds as $shipmentId) {
$shipment = Mage::getSingleton('sendbox_shipments/shipment')->load($shipmentId)
->setDeliveryTypeCode($this->getRequest()->getParam('flag_delivery_type_code'))
->setIsMassupdate(true)
->save();
}
$this->_getSession()->addSuccess(
$this->__('Total of %d shipments were successfully updated.', count($shipmentIds))
);
} catch (Mage_Core_Exception $e) {
Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
} catch (Exception $e) {
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('There was an error updating shipments.')
);
Mage::logException($e);
}
}
$this->_redirect('*/*/index');
}
/**
* mass Accept Value On Delivery change - action
*
* @access public
* @return void
* @author Ultimate Module Creator
*/
public function massAcceptValueOnDeliveryAction()
{
$shipmentIds = $this->getRequest()->getParam('shipment');
if (!is_array($shipmentIds)) {
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('Please select shipments.')
);
} else {
try {
foreach ($shipmentIds as $shipmentId) {
$shipment = Mage::getSingleton('sendbox_shipments/shipment')->load($shipmentId)
->setAcceptValueOnDelivery($this->getRequest()->getParam('flag_accept_value_on_delivery'))
->setIsMassupdate(true)
->save();
}
$this->_getSession()->addSuccess(
$this->__('Total of %d shipments were successfully updated.', count($shipmentIds))
);
} catch (Mage_Core_Exception $e) {
Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
} catch (Exception $e) {
Mage::getSingleton('adminhtml/session')->addError(
Mage::helper('sendbox_shipments')->__('There was an error updating shipments.')
);
Mage::logException($e);
}
}
$this->_redirect('*/*/index');
}
/**
* export as csv - action
*
* @access public
* @return void
* @author Ultimate Module Creator
*/
public function exportCsvAction()
{
$fileName   = 'shipment.csv';
$content    = $this->getLayout()->createBlock('sendbox_shipments/adminhtml_shipment_grid')
->getCsv();
$this->_prepareDownloadResponse($fileName, $content);
}
/**
* export as MsExcel - action
*
* @access public
* @return void
* @author Ultimate Module Creator
*/
public function exportExcelAction()
{
$fileName   = 'shipment.xls';
$content    = $this->getLayout()->createBlock('sendbox_shipments/adminhtml_shipment_grid')
->getExcelFile();
$this->_prepareDownloadResponse($fileName, $content);
}
/**
* export as xml - action
*
* @access public
* @return void
* @author Ultimate Module Creator
*/
public function exportXmlAction()
{
$fileName   = 'shipment.xml';
$content    = $this->getLayout()->createBlock('sendbox_shipments/adminhtml_shipment_grid')
->getXml();
$this->_prepareDownloadResponse($fileName, $content);
}
/**
* Check if admin has permissions to visit related pages
*
* @access protected
* @return boolean
* @author Ultimate Module Creator
*/
protected function _isAllowed()
{
return Mage::getSingleton('admin/session')->isAllowed('sendbox_shipments/shipment');
}
}