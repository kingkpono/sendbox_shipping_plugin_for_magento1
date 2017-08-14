<?php
class Sendbox_Customshippingmethod_Helper_Data extends Mage_Core_Helper_Abstract
{


	public function getQuotation($quote)
	{

      $auth= Mage::helper("sendbox_shipments")->getAuthHeader();

		$payload=$this->build_quote_payload($quote);
		$url=Mage::helper("sendbox_shipments")->getBaseUrl().'/v1/merchant/shipments/delivery_quote';
$ch = curl_init($url);         

      

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  
curl_setopt($ch,CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
'Content-Type: application/json',
'Authorization: '.$auth)
);
$response= json_decode(curl_exec($ch));
// close the connection, release resources used
curl_close($ch);

if(Mage::getStoreConfig('sendbox_shipments/shipment/use_max_carrier'))
$shipping_fee=$response->{'max_quoted_fee'};
else
$shipping_fee=$response->{'min_quoted_fee'};



return $shipping_fee;			

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
            "quantity": '.$item->getQty().',
            "value": '.$item->getPrice().',
            "amount_to_receive": '.$item->getPrice().'
           }';

            if($j != ($item_length-1))
              $output.=',';
            $j++;
           $items_string.=$output;
         }//end for each item

         $items_string.=']';

  $billingCountry = Mage::getModel('directory/country')->loadByCode(Mage::getStoreConfig('sendbox_shipments/shipment/origin_country'));
$shippingCountry = Mage::getModel('directory/country')->loadByCode("NG");
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