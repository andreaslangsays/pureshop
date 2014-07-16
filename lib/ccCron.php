<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ccCron
 *
 * @author Christoph Ebeling
 */
class ccCron {
    public function ccCron(){
        
    }
    
    public function sendCategories(){
        //TODO implement offset
        $categories_query = "select t1.categories_id,categories_image,parent_id,t2.categories_name,sort_order from ".TABLE_CATEGORIES." t1 LEFT JOIN ".TABLE_CATEGORIES_DESCRIPTION." t2 ON t1.categories_id = t2.categories_id WHERE t2.language_id = 1 LIMIT 0,100";
	echo $categories_query;
        $categories_query = push_db_query($categories_query);
	//$categories = xtc_db_fetch_array($categories_query);

        
        $cList = array();
        $i = 0;
        while($category = push_db_fetch_array($categories_query)){
            
            
            $cList[$i]["categoryId"] = $category['categories_id'];
            $cList[$i]["parentId"] = $category['parent_id'];
            $cList[$i]["categoryName"] = $category['categories_name'];
            //$cList[$i]["categoryUrl"] = HTTP_SERVER.DIR_WS_CATALOG."index.php?".xtc_category_link($category['categories_id']);
            $cList[$i]["visibility"] = 1;
            $cList[$i]["imageUrl"] = $categories['categories_image'];
            $cList[$i]["position"] = $category['sort_order'];
            $i++;
        }
         
            $method = "category";
            $data = array(
                        'action' => "load",
                        'categories' => $cList
                    );


            $data = array_merge($data, $this->getAuthentification()); 
            
            print_r($data);
                    
            //print_r($data);
            $response = $this->sendRequest($method, $data);
            return $response;
    }
    public function sendProducts(){
        $pList = $this->getProducts();
        $method = "product";
            $data = array(
                        'action' => "load",
                        'products' => $pList
                    );


            $data = array_merge($data, $this->getAuthentification()); 
            
            print_r($data);
                    //TODO get categories
            //print_r($data);
            $response = $this->sendRequest($method, $data);
            return $response;
    }
    public function sendStock(){
            $sList = $this->getStock();
            $method = "stock";
            $data = array(
                        'action' => "load",
                        'stock' => $sList
                    );


            $data = array_merge($data, $this->getAuthentification()); 
            
            print_r($data);
            //TODO get categories
            $response = $this->sendRequest($method, $data);
            return $response;
    }
    public function sendClients(){
            $method = "customer";
            $query = xtc_db_query("SELECT * FROM customers");
            while($customer = push_db_fetch_array($query)){
                    $data['customerId'] = $customer['customers_id'];
                    $data['email'] = $customer['customers_email_address'];
                    $data['phone'] = $customer['customers_telephone']; 
                    $data['action'] = "add"; 
                    $data = array_merge($data, $this->getAuthentification()); 
                    $response .= $this->sendRequest($method, $data); 
                    echo "!".$response;
            }
            return $response;
    }
    public function getOrders(){
        //$this->getOrder(1);
        //return;
        $method = "order";
        $data = array(
                    'action' => "list",
                    'filterAttribute' => "sync",
                    'filterCondition' => 0,
                );

        $data = array_merge($data, $this->getAuthentification());
        $response = $this->sendRequest($method, $data);
        foreach($response['orders'] as $order){
            $this->getOrder($order);
        }
        print_r($response);
        
    }
    
    private function getOrder($orderID){
        $method = "order";
        $data = array(
                    'action' => "get",
                    'orderId' => $orderID
                );

        $data = array_merge($data, $this->getAuthentification());
        $response = $this->sendRequest($method, $data);
        $this->saveOrder($response);
        print_r($response); 
    }
        

    public function saveOrder($order){
        global $xtPrice;
        //get customer id
        $customer = push_db_fetch_array(push_db_query("SELECT customers_id FROM customers WHERE customers_email_address = '".$order['customer']['email']."' LIMIT 0,1"));
                print_r($customer);
       	$sql_data_array = array (   'customers_id' => $customer['customers_id'],
                                    'customers_name' => $order['customer']['name'],
                                    'customers_street_address' => $order['billingAddress']['street'], 
                                    'customers_suburb' => $order['billingAddress']['suburb'], 
                                    'customers_city' => $order['billingAddress']['city'], 
                                    'customers_postcode' => $order['billingAddress']['postcode'], 
                                    'customers_state' => $order['billingAddress']['state'], 
                                    'customers_country' => $order['billingAddress']['country']['title'], 
                                    'customers_telephone' => $order['billingAddress']['telephone'], 
                                    'customers_email_address' => $order['billingAddress']['email_address'], 
                                    'customers_address_format_id' => $order['billingAddress']['format_id'], 
                                    'delivery_name' => $order['shippingAdress']['firstName']." ".$order['shippingAdress']['lastName'],
                                    'delivery_company' => $order['shippingAdress']['company'], 
                                    'delivery_street_address' => $order['shippingAdress']['adress'], 
                                    'delivery_suburb' => $order['shippingAdress']['city'],  
                                    'delivery_city' => $order['shippingAdress']['city'], 
                                    'delivery_postcode' => $order['shippingAdress']['zip'], 
                                    'delivery_state' => $order['shippingAdress']['state'], 
                                    'delivery_country' => $order['shippingAdress']['country'], 

                                    'delivery_address_format_id' => $order['shippingAdress']['company'], 
                                    'payment_method' => $order['payment']['method'], 

                                    'date_purchased' => 'now()',
                                    'orders_status' => 1,                                    
                                    );

        //Save Order                          
        push_db_perform(TABLE_ORDERS, $sql_data_array); 
        $insert_id = push_db_insert_id();
        $_SESSION['tmp_oID'] = $insert_id;
        
        
        $order_totals = array();

        //Save orderd Products
        foreach($order['items'] as $product){	
        $sql_data_array = array (   'orders_id' => $insert_id,
                                    'products_id' => $product['id'], 
                                    'products_model' => $product['model'],
                                    'products_name' => $product['name'],
                                    'products_shipping_time'=>$order['shipping']['shippingTime'], 
                                    'products_price' => $product['price'], 
                                    'final_price' => $product['rowTotal'],
                                    'products_tax' => $product['taxAmount'], 
                                    'products_discount_made' => $product['discountAmount'], 
                                    'products_quantity' => $product['quantity'],
                                    );

	push_db_perform(TABLE_ORDERS_PRODUCTS, $sql_data_array);
        }
          
        //Save order total
        $order_totals = array();
        $order_totals[] =  array('title' => '<b>Summe</b>:',
                                'text' => '<b>' . $xtPrice->xtcFormat($order['grandTotal'],true) . '</b>',
                                'value' => $xtPrice->xtcFormat($order['grandTotal'],false),
                                'class' => 'ot_subtotal');
        
        $order_totals[] =  array('title' => 'Zwischensumme:',
                                'text' => $xtPrice->xtcFormat($order['subtotal'],true),
                                'value' => $order['subtotal'],
                                'class' => "ot_subtotal");
        
        for ($i = 0, $n = sizeof($order_totals); $i < $n; $i ++) {
                $sql_data_array = array ('orders_id' => $insert_id, 'title' => $order_totals[$i]['title'], 'text' => $order_totals[$i]['text'], 'value' => $order_totals[$i]['value'], 'class' => $order_totals[$i]['class'], 'sort_order' => $order_totals[$i]['sort_order']);
                push_db_perform(TABLE_ORDERS_TOTAL, $sql_data_array);
        }
        /*$customer_notification = (SEND_EMAILS == 'true') ? '1' : '0';
        $sql_data_array = array ('orders_id' => $insert_id, 'orders_status_id' => $order->info['order_status'], 'date_added' => 'now()', 'customer_notified' => $customer_notification, 'comments' => $order->info['comments']);
        xtc_db_perform(TABLE_ORDERS_STATUS_HISTORY, $sql_data_array);
        */
    }
    public function getProducts(){ 
        
        //$xtPrice = new xtcPrice($this->getConfig("currency"),$this->getConfig("status"));
        $productsList = array();
 
        $export_query = push_db_query("SELECT
                             p.products_id as productId,
                             
                             pd.products_name as productName,
                             pd.products_description as description, 
                             
                             p.products_model,
                             p.products_image,
                             p.products_price as basePrice,
                             p.products_status as availability,
                             p.products_quantity as quantity,
                             p.products_date_available as saleDateFrom,
                            
                            
                            
                             p.products_tax_class_id,
                             p.products_date_added,
                             m.manufacturers_name
                         FROM
                             " . TABLE_PRODUCTS . " p LEFT JOIN
                             " . TABLE_MANUFACTURERS . " m
                           ON p.manufacturers_id = m.manufacturers_id LEFT JOIN
                             " . TABLE_PRODUCTS_DESCRIPTION . " pd
                           ON p.products_id = pd.products_id AND
                            pd.language_id = '".$_SESSION['languages_id']."' LEFT JOIN
                             " . TABLE_SPECIALS . " s
                           ON p.products_id = s.products_id
                         WHERE
                           p.products_status = 1
                         ORDER BY
                            p.products_date_added DESC,
                            pd.products_name");

        
        while ($products = push_db_fetch_array($export_query)) {
            
            //Price---------
             /*   $products['salePrice'] = $xtPrice->xtcGetPrice($products['productId'],
                                            $format=false,
                                            1,
                                            $products['products_tax_class_id'],
                                            '');
                $products['taxPercent'] = push_get_tax_rate($products['products_tax_class_id']);

                $products['currency'] = $this->getConfig("currency"); 
	*/
            $products['taxPercent'] = push_get_tax_rate($products['products_tax_class_id']);
            //Category-------
                $categorie_query=push_db_query("SELECT
                                                categories_id
                                                FROM ".TABLE_PRODUCTS_TO_CATEGORIES."
                                                WHERE products_id='".$products['productId']."'");


                while ($categorie_data=push_db_fetch_array($categorie_query)) {
                        $products['categories'] = array($categorie_data['categories_id']);
                }
            //Description    
                // remove trash
                $products_description = strip_tags($products['description']);         
                $products_description = str_replace(";",", ",$products_description);
                $products_description = str_replace("'",", ",$products_description);
                $products_description = str_replace("\n"," ",$products_description);
                $products_description = str_replace("\r"," ",$products_description);
                $products_description = str_replace("\t"," ",$products_description);
                $products_description = str_replace("\v"," ",$products_description);
                $products_description = str_replace("&quot,"," \"",$products_description);
                $products_description = str_replace("&qout,"," \"",$products_description);
                $products_description = str_replace(chr(13)," ",$products_description);
                $products_description = substr($products_description, 0, 65536);
                $products['description'] = $products_description;
                $products['productsSku'] = "";
                 
	   //Image 		
                if ($products['products_image'] != ''){
                    $products['imgUrl'] = HTTP_SERVER . DIR_WS_HTTP_CATALOG . DIR_WS_IMAGES.$products['products_image'];

                }else{
                    $products['image'] = '';
                }
                
            
           //URL 
            
            
            $products['productUrl'] =  HTTP_SERVER . DIR_WS_HTTP_CATALOG . "product_info.php?products_id=" .$products['products_id'];
            

           
           //Unset unused data from sql-query 
            unset($products['products_image']);
           
           //Add Product to products List 
            $productsList[] = $products;
        }
        return $productsList;
   
    }
    public function getStock(){
               $export_query =push_db_query("SELECT
                             p.products_id as productId,
                             p.products_status as availability,
                             p.products_quantity as quantity

                         FROM
                             " . TABLE_PRODUCTS . " p 
                         WHERE
                           p.products_status = 1
                         ORDER BY
                            p.products_date_added DESC");

        $stockList = array();
        while ($products = push_db_fetch_array($export_query)) {
            $stockList[] = $products;
        }
        return $stockList;        
    }
    
    public function authenticate(){
        return true;
    }
    
    private function sendRequest($method, $data)
    {
        //Mage::log('API LINK ' . $this->getApiUrl());
        $url = $this->getApiUrl() . "/" . $method . "/";
        

        //Mage::log('Sending request to: ' . $url, Zend_Log::INFO, 'couchcommerce.log');
        //Mage::log($data, Zend_Log::INFO, 'couchcommerce.log');

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_USERAGENT, "Couchcommerce" . $this->getVersion());
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($curl);
        //Mage::log($response, Zend_Log::INFO, 'couchcommerce.log');
        $info = curl_getinfo($curl);
        curl_close($curl);
        
        echo "Response:".$response;

        // check the result
        if (!$response) {
            //Mage::log('ERROR No connection', Zend_Log::ERR, 'couchcommerce.log');
            return false;
        }

        $decodedResponse = json_decode($response, true);

        if (empty($decodedResponse)) {
            //Mage::log('ERROR INVALID RESPONSE: ' . $response, Zend_Log::ERR, 'couchcommerce.log');
            return false;
        }

        if ($decodedResponse['errorCode'] != 0) {
            //Mage::log('ERROR CODE: ' . $decodedResponse['errorCode'] . ' - ' . $decodedResponse['errorDesc'], Zend_Log::ERR, 'couchcommerce.log');
            return false;
        }

        $decodedResponse = $this->utf8DecodeArray($decodedResponse);

        return $decodedResponse;
    }    
    
    private function getApiUrl()
    {
        //$serverType = Mage::getStoreConfig("couchcommerce/debug/server");
        //TODO add xtc config call
        $serverType = "live";
        
        switch ($serverType) {
            default: // fall through to "live"
            case 'live':
                return 'https://shopapi.couchcommerce.com';
                break;
            case 'test':
                return 'https://shopapi.couchcommerce.com';
                break;
            case 'custom':
                //return Mage::getStoreConfig("couchcommerce/debug/connector_url");
        }
    }
    public function getAuthentification()
    {
        $timestamp = time();
        $auth = array(
            "user" => $this->getConfig('customer_identifier'),
            "storeId" => $this->getConfig('shop_number'),
            "key" => md5($timestamp . "-" .  $this->getConfig('shop_number') . "-" .  $this->getConfig('api_key')),
            "timestamp" => $timestamp
        );
        return $auth;
    }
    public function getConfig($key){
        $key = "MODULE_HEADER_TAGS_COUCHCOMMERCE_".strtoupper($key);
        $data = push_db_fetch_array(push_db_query("SELECT configuration_value FROM configuration WHERE configuration_key = '".$key."' LIMIT 0,1"));
        return $data['configuration_value'];
        
    }
    
    

    public function getVersion(){
        return "0.1";
    }
        
    function utf8DecodeArray($array)
    {
        $utf8DecodedArray = array();

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $utf8DecodedArray[$key] = $this->utf8DecodeArray($value);
                continue;
            }
            //$utf8DecodedArray[$key] = mb_convert_encoding($value, $this->encoding, 'UTF-8');
            $utf8DecodedArray[$key] = utf8_decode($value);
        }

        return $utf8DecodedArray;
    }
    
}

?>
