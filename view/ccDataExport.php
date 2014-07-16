<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include ('includes/application_top.php');
include ('includes/ccCron.php');

$pageAction = $_GET['action']; 
$ccCronHandler = new ccCron(); 

switch($pageAction){
    case "sendCategory": 
        $ccCronHandler->authenticate(); 
        $response = $ccCronHandler->sendCategories();
        break;
    case "sendProducts":
        $response = $ccCronHandler->sendProducts();
        break;
    case "sendStock":
        $response = $ccCronHandler->sendStock();
        break;
    case "getOrders":
        $response = $ccCronHandler->getOrders();
        break;
    case "sendClients": 
        $response = $ccCronHandler->sendClients();
        break;
}
echo "Response: <br />";
print_r($response);

?>
