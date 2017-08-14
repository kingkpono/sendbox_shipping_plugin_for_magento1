<?php
// app/code/local/Sendbox/Customshippingmethod/Model
class Sendbox_Customshippingmethod_Model_Demo
extends Mage_Shipping_Model_Carrier_Abstract
implements Mage_Shipping_Model_Carrier_Interface
{
  protected $_code = 'sendbox_customshippingmethod';
 
  public function collectRates(Mage_Shipping_Model_Rate_Request $request)
  {
    $result = Mage::getModel('shipping/rate_result');
    $result->append($this->_getDefaultRate());
 
    return $result;
  }
 
  public function getAllowedMethods()
  {
    return array(
      'sendbox_customshippingmethod' => $this->getConfigData('name'),
    );
  }
 
  protected function _getDefaultRate()
  {
    $rate = Mage::getModel('shipping/rate_result_method');
    $quote= Mage::getModel('checkout/cart')->getQuote();
   
    $fee= mage::helper('sendbox_customshippingmethod')->getQuotation($quote);
     if($fee==0 || $fee==null|| $fee=="")
     
            return false;
        
    $rate->setCarrier($this->_code);
    $rate->setCarrierTitle($this->getConfigData('title'));
    $rate->setMethod($this->_code);
    $rate->setMethodTitle($this->getConfigData('name'));
    $rate->setPrice($fee);
    $rate->setCost($fee);
     
    return $rate;
  }
}