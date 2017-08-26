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
 * Shipments default helper
 *
 * @category    Sendbox
 * @package     Sendbox_Shipments
 * @author      Ultimate Module Creator
 */
class Sendbox_Shipments_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Ultimate Module Creator
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }


public function getAuthHeader()
{
if(Mage::getStoreConfig('sendbox_shipments/shipment/test_mode'))
  return Mage::getStoreConfig('sendbox_shipments/shipment/sendbox_auth_header_test');
  else
    return Mage::getStoreConfig('sendbox_shipments/shipment/sendbox_auth_header');

}

public function isAuthHeaderSet()
{
if(Mage::getStoreConfig('sendbox_shipments/shipment/sendbox_auth_header_test')!=null && Mage::getStoreConfig('sendbox_shipments/shipment/sendbox_auth_header')!=null)
  return  false;
  else
  	 return  true;
  
}
public function getBaseUrl()
{
if(Mage::getStoreConfig('sendbox_shipments/shipment/test_mode'))
  return "http://api.sendbox.com.ng";
  else
     return "https://api.sendbox.ng";

}
public function getBaseTrackUrl()
{
if(Mage::getStoreConfig('sendbox_shipments/shipment/test_mode'))
  return "http://sendbox.com.ng";
  else
     return "https://sendbox.ng";

}
 public function make_shipment($order,$tracking_code,$carrier_code)
  {

    if($order->canShip())
{
	
//create shipment
	
$convertor = Mage::getModel('sales/convert_order');
$shipment = $convertor->toShipment($order);

foreach ($order->getAllItems() as $orderItem) {
    if ($orderItem->getQtyToShip() && !$orderItem->getIsVirtual()) {
        $item = $convertor->itemToShipmentItem($orderItem);
        $item->setQty($orderItem->getQtyToShip());
        $shipment->addItem($item);
    }
}
$shipment->register();

$order->setIsInProcess(true);
Mage::getModel('core/resource_transaction')
         ->addObject($shipment)
         ->addObject($order)
         ->save();
          if($shipment->getId() != ''|| $shipment->getId() != null) { 
        $track = Mage::getModel('sales/order_shipment_track')
                 ->setShipment($shipment)
                 ->setData('title', 'Ship via Sendbox')
                 ->setData('number', $tracking_code)
                 ->setData('carrier_code', $carrier_code)
                 ->setData('order_id', $order->getIncrementId())
                 ->save();
    }


         return true;
        
}else{
	return false;

}
  }
  public function build_payload($order,$selected_courier_id,$fee)
  {

      //billing street
        $street=Mage::getStoreConfig('sendbox_shipments/shipment/origin_street');
        

        //shipping street
        $shipping_street="";
        $i=0;
        $shipping_str_length=count($order->getShippingAddress()->getStreet());
        foreach ($order->getShippingAddress()->getStreet() as $str) {

          if($i != ($shipping_str_length-1))
          $shipping_street.=$str.",";
          else
           $shipping_street.=$str;

          $i++;
        }
        //items
        $items_string='[';
        $item_length=count($order->getAllItems());
        $j=0;
      

        foreach ($order->getAllItems() as $item) 
         {
           
            $output='{"name": "'.$item->getName().'",
            "weight": '.$item->getWeight().',
            "package_size_code": "medium",
            "quantity": '.$item->getQtyOrdered().',
            "value": '.$item->getPrice().',
             "reference_code": "'.$item->getSku().'",
            "amount_to_receive": '.$item->getPrice().'
           }';

             if($j != ($item_length-1))
              $output.=',';
            $j++;
           $items_string.=$output;
         }//end for each item

           $items_string=$items_string.',{"name": "delivery fee",
            "weight": 0,
            "package_size_code": "medium",
            "quantity": 1,
            "value": '.$order->getShippingAmount().',
             "reference_code": "'.$order->getIncrementId().'",
            "amount_to_receive": '.$order->getShippingAmount().'
           }';

         $items_string.=']';

  $billingCountry = Mage::getModel('directory/country')->loadByCode(Mage::getStoreConfig('sendbox_shipments/shipment/origin_country'));
$shippingCountry = Mage::getModel('directory/country')->loadByCode($order->getShippingAddress()->getCountry());
$nextday=$date = new DateTime($order->getCreatedAt());
$date->modify('+1 day');
$pickup_date= $date->format(DateTime::ATOM); 
$baseUrl=Mage::getBaseUrl("web");
$origin_name=Mage::getStoreConfig('sendbox_shipments/shipment/origin_name');
$origin_phone=Mage::getStoreConfig('sendbox_shipments/shipment/origin_phone');
$origin_street=Mage::getStoreConfig('sendbox_shipments/shipment/origin_street');
$origin_city=Mage::getStoreConfig('sendbox_shipments/shipment/origin_city');
$origin_email=Mage::getStoreConfig('sendbox_shipments/shipment/origin_email');
$origin_state=Mage::getStoreConfig('sendbox_shipments/shipment/origin_state');
    $payload=' {
  "origin_name": "'.$origin_name.'",
  "origin_email": "'.$origin_email.'",
  "origin_phone": "'.$origin_phone.'",
  "origin_street":  "'.$street.'",
  "origin_city": "'.$origin_city.'",
  "origin_state":"'.$origin_state.'",
  "origin_country": "'. $billingCountry->getName().'",
  
  "destination_name": "'.$order->getShippingAddress()->getName().'",
  "destination_address": "'.$shipping_street.'",
   "destination_email": "'.$order->getShippingAddress()->getEmail().'",
  "destination_phone":  "'.$order->getShippingAddress()->getTelephone().'",
  "destination_street":  "'.$shipping_street.'",
  "destination_city": "'.$order->getShippingAddress()->getCity().'",
  "destination_state": "'.$order->getShippingAddress()->getRegion().'",
  "destination_country": "'. $shippingCountry->getName().'",

  "delivery_priority_code": "next_day",
  "delivery_callback":"'.$baseUrl.'sendbox_shipments/shipment/deliveryupdate" ,
  "finance_callback":"'.$baseUrl.'sendbox_shipments/shipment/financeupdate",
  "incoming_option_code": "pickup",
  "pickup_date":"'.$pickup_date.'",
  "delivery_type_code": "last_mile",
  "reference_code": "'.$order->getIncrementId().'",
  
  "use_selected_rate": true,
  "selected_rate_id": '.$selected_courier_id.',
  "accept_value_on_delivery": true,
  "amount_to_receive": '.($order->getSubtotal()+$fee).',
  "fee_payment_channel_code": "cash",
  "channel_code": "website",
  
  "items": '.$items_string.'
}';



     return $payload;
       
  }




public function build_quote_payload($quote)
  {

     
      //billing street
        $street=Mage::getStoreConfig('sendbox_shipments/shipment/origin_street');
        


        //shipping street
        $shipping_street="";
        $i=0;
        $shipping_str_length=count($quote->getShippingAddress()->getStreet());
        foreach ($quote->getShippingAddress()->getStreet() as $str) {

          if($i != ($shipping_str_length-1))
          $shipping_street.=$str.",";
          else
           $shipping_street.=$str;

          $i++;
        }
        //items
        $items_string='[';
        $item_length=count($quote->getAllItems());
        $j=0;
      
       
        foreach ($quote->getAllItems() as $item) 
         {
           
            $output='{"name": "'.$item->getName().'",
            "weight": '.$item->getWeight().',
            "package_size_code": "medium",
            "quantity": '.$item->getQtyOrdered().',
            "value": '.$item->getPrice().',
            "amount_to_receive": '.$item->getPrice().'
           }';

            if($j != ($item_length-1))
              $output.=',';
            $j++;
           $items_string.=$output;
         }//end for each item

         $items_string=$items_string.',{"name": "delivery fee",
            "weight": 0,
            "package_size_code": "medium",
            "quantity": 1,
            "value": '.$quote->getShippingAmount().',
             "reference_code": "'.$quote->getIncrementId().'",
            "amount_to_receive": '.$quote->getShippingAmount().'
           }';


         $items_string.=']';

  $billingCountry = Mage::getModel('directory/country')->loadByCode(Mage::getStoreConfig('sendbox_shipments/shipment/origin_country'));
$shippingCountry = Mage::getModel('directory/country')->loadByCode($quote->getShippingAddress()->getCountry());
$nextday=$date = new DateTime($quote->getCreatedAt());
$date->modify('+1 day');
$pickup_date= $date->format(DateTime::ATOM); 
  $origin_name=Mage::getStoreConfig('sendbox_shipments/shipment/origin_name');
$origin_phone=Mage::getStoreConfig('sendbox_shipments/shipment/origin_phone');
$origin_street=Mage::getStoreConfig('sendbox_shipments/shipment/origin_street');
$origin_city=Mage::getStoreConfig('sendbox_shipments/shipment/origin_city');
$origin_address=Mage::getStoreConfig('sendbox_shipments/shipment/origin_address');
$origin_state=Mage::getStoreConfig('sendbox_shipments/shipment/origin_state');
    $payload=' {
  "origin_name": "'.$origin_name.'",
  "origin_address": "'.$street.'",
  "origin_phone": "'.$origin_phone.'",
  "origin_street":  "'.$street.'",
  "origin_city": "'.$origin_city.'",
  "origin_state":"'.$origin_state.'",
  "origin_country": "'. $billingCountry->getName().'",
  
  "destination_name": "'.$quote->getShippingAddress()->getName().'",
  "destination_address": "'.$shipping_street.'",
  "destination_phone":  "'.$quote->getShippingAddress()->getTelephone().'",
  "destination_street":  "'.$shipping_street.'",
  "destination_city": "'.$quote->getShippingAddress()->getCity().'",
  "destination_state": "'.$quote->getShippingAddress()->getRegion().'",
  "destination_country": "'.$shippingCountry->getName().'",

  "delivery_priority_code": "next_day",
  
  "incoming_option_code": "pickup",
  "pickup_date":"'.$pickup_date.'",
  "delivery_type_code": "last_mile",
  
  "accept_value_on_delivery": true,
  "amount_to_receive": '.($quote->getSubtotal()+$fee).',
  "fee_payment_channel_code": "cash",
  "channel_code": "website",  
    "items": '.$items_string.'
 
}';

     return $payload;
       
  }


 



}
