<?php
/**
 * @version sofortueberweisung.de 2.3.0 - $Date: 2010-05-20 12:42:54 +0200 (Do, 20 Mai 2010) $
 * @author Payment Network AG (integration@payment-network.com)
 * @link http://www.payment-network.com/

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 - 2007 Henri Schmidhuber (http://www.in-solution.de)
  Copyright (c) 2007 osCommerce

  Released under the GNU General Public License
  
  $Id: pn_sofortueberweisung.php 169 2010-05-20 10:42:54Z thoma $
  
*/

define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_TITLE', 'DIRECTebanking.com');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_PUBLIC_TITLE', 'DIRECTebanking.com');

define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ALLOWED_TITLE' , 'Allowable zones');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ALLOWED_DESC' , 'Please enter <b>separately</b> the zones, which should be allowed for this module. (z.B. AT,DE (if empty, all zones are allowed))');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS_TITLE' , 'Activate DIRECTebanking.com direct module');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS_DESC' , 'Accept payment via prepayment with integrated DIRECTebanking.com?');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS_DESC', 'M&ouml;chten Sie Zahlungen per sofort&uuml;berweisung.de akzeptieren?');

define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_USER_ID_TITLE' , 'Customer ID');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_USER_ID_DESC' , 'Your Customer ID at DIRECTebanking.com');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_ID_TITLE' , 'Project ID');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_ID_DESC' , 'The responsible project ID at DIRECTebanking.com, to which the payment is affiliate');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_PASSWORD_TITLE' , 'Project password:');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_PASSWORD_DESC' , 'The project password (at extended settings / project password and input check)');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_NOTIF_PASSWORD_TITLE', 'Notification password:');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_PROJECT_NOTIF_PASSWORD_DESC', 'The notification password (extended settings / passwords and hash algorithms)');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM_TITLE', 'Hashing algorithm:');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM_DESC', 'The hashing algorithm (extended settings / passwords and hash algorithms)');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_SORT_ORDER_TITLE' , 'Sequence of display');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_SORT_ORDER_DESC' , 'Sequence of display. Lowest number is shown first.');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ZONE_TITLE', 'Hashing algorithm: MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_HASH_ALGORITHM<br /><br />Payment zone');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ZONE_DESC' , 'If a zone is selected, the payment method is only valid for this zone.');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ORDER_STATUS_ID_TITLE' , 'Confirmed order status');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_ORDER_STATUS_ID_DESC' , 'Order status after entry of an order, for which DIRECTebanking.com forwarded a successful payment affirmation');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TMP_STATUS_ID_TITLE','Temporary order status');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TMP_STATUS_ID_DESC','Order status for transactions that are not completed yet');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_UNC_STATUS_ID_TITLE','Unconfirmed order status');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_UNC_STATUS_ID_DESC','Order status after entry of an order, for which no or a faulty payment affirmation has been transfered');

define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_REASON_1_TITLE','Reason line 1');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_REASON_1_DESC', 'In the reason line 1 the following options are available');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_REASON_2_TITLE','Reason line 2');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_REASON_2_DESC', 'In the reason (max 27 characters) the following placeholders will be replaced:<br /> {{order_id}}<br />{{order_date}}<br />{{customer_id}}<br />{{customer_name}}<br />{{customer_company}}<br />{{customer_email}}');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_IMAGE_TITLE','Payment selection graphic / text');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_IMAGE_DESC','Shown graphic / text in the selection of the payment options');

define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT_TEXT', 'DIRECTebanking.com is the free, ISO certified online payment system of the Payment Network AG. Your advantages: no additional registration, automatic debiting from your online bank account, highest safety standards and immediate shipping of stock goods. In order to pay with DIRECTebanking.com you need your eBanking login data, that is bank connection, account number, PIN and TAN.');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT_IMAGEALT', 'DIRECTebanking.com is the free, ISO certified online payment system of the Payment Network AG. Your advantages: no additional registration, automatic debiting from your online bank account, highest safety standards and immediate shipping of stock goods. In order to pay with DIRECTebanking.com you need your eBanking login data, that is bank connection, account number, PIN and TAN.');

define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_ERROR_HEADING', 'The following error has been announced by DIRECTebanking.com during the process:');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_ERROR_MESSAGE', 'Payment via DIRECTebanking.com is unfortunately not possible or has been cancelled by the customer. Please select another payment method.');
  
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION', (MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_STATUS != 'True' ? 
	'<form action="'.push_href_link(FILENAME_MODULES, '', 'SSL').'" method="get"><input type="hidden" name="set" value="payment">
	<input type="hidden" name="module" value="pn_sofortueberweisung"><input type="hidden" name="action" value="install">
	<input type="hidden" name="autoinstall" value="1"><input type="submit" value="Autoinstaller (recommended)" /></form><br />' : '').'<br />
	<b>DIRECTebanking.com</b><br>During the payment process the customer is informed about the payment system via customizable texts and pictures and is directly forwarded to DIRECTebanking.com after the order transaction is finished. The order is always written into the database even if a client aborts the payment process. He then is alternatively able to pay using a common bank transfer and can still be contacted respectively.');

define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_PAYMENT_IMAGE', '
     <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td valign="top"><a href="https://www.sofortueberweisung.de/funktionsweise" target="_blank">{{image}}</a></td>
      </tr>
      <tr>
      	<td class="main"><br />%s</td>
      	</tr>	
    </table>');

  define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_DESCRIPTION_CHECKOUT_CONFIRMATION', '
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="main"><p>In order to pay with DIRECTebanking.com you need your eBanking login data, that is bank connection, account number, PIN and TAN.</p></td>
      </tr>
    </table>');
  
//see also /includes/languages/german/checkout_process.php
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_EMAIL_HTML_TEXT', '  <table style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:small" width="100%"><tr><td>
    <p></p>
    <table style="border-top:1px solid; border-bottom:1px solid; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:small" width="100%" border="0">
      <tr bgcolor="#F1F1F1">
        <td width="50%">
            <strong>{{EMAIL_TEXT_DELIVERY_ADDRESS}}:</strong>
        </td>
        <td>
            <strong>{{EMAIL_TEXT_BILLING_ADDRESS}}:</strong>
        </td>
      </tr>
      <tr>
        <td>
          {{DELIVERY_ADRESS}}
        </td>
        <td>
          {{BILLING_ADRESS}}
        </td>
      </tr>
    </table>
	<p>
		{{EMAIL_TEXT_ORDER_NUMBER}} <strong>{{ORDER_ID}}</strong>
	</p>
	<p>
	{{EMAIL_TEXT_DATE_ORDERED}} <strong>{{DATE_ORDERED}}</strong>
	</p>
	<p>
	Comment: <strong>{{CUSTOMER_COMMENT}}</strong>
	</p>
	<p>
		{{EMAIL_TEXT_INVOICE_URL}} 
	  <strong><a href="{{INVOICE_URL}}">{{INVOICE_URL}}</a></strong>
	</p>
    <table style="border-bottom:1px solid; font-family:Verdana, Arial, Helvetica, sans-serif; font-size:small" width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor="#F1F1F1">
			<td class="c3" align="left">
			  <div style="text-align: left">
				<strong>{{EMAIL_TEXT_PRODUCTS}}:</strong>
			  </div>
			</td>
		</tr>
		<tr>
			<td>
			  {{Item_List}}
			</td>
		</tr>
    </table>
    <div style="text-align: right">
      {{List_Total}}
    </div><br /><br />
    <p>{{EMAIL_TEXT_PAYMENT_METHOD}}: {{Payment_Modul_Text}}</p>
    {{Payment_Modul_Text_Footer}}
</td></tr></table>');

//see also /includes/languages/english/checkout_process.php
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_EMAIL_TEXT', '{{STORE_NAME}}
{{EMAIL_SEPARATOR}}
{{EMAIL_TEXT_ORDER_NUMBER}} {{ORDER_ID}}
{{EMAIL_TEXT_INVOICE_URL}} {{INVOICE_URL}}
{{EMAIL_TEXT_DATE_ORDERED}} {{DATE_ORDERED}}

{{EMAIL_TEXT_PRODUCTS}}:
{{EMAIL_SEPARATOR}}
{{Item_List}}
{{EMAIL_SEPARATOR}}
{{List_Total}}

{{EMAIL_TEXT_BILLING_ADDRESS}}:
{{EMAIL_SEPARATOR}}
{{BILLING_ADRESS}}

{{EMAIL_TEXT_DELIVERY_ADDRESS}}:
{{EMAIL_SEPARATOR}}
{{DELIVERY_ADRESS}}

Comment:
{{EMAIL_SEPARATOR}}
{{CUSTOMER_COMMENT}}

{{EMAIL_TEXT_PAYMENT_METHOD}}
{{EMAIL_SEPARATOR}}
{{Payment_Modul_Text}}

{{Payment_Modul_Text_Footer}}'); 
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_EMAIL_FOOTER', ' ');
define('MODULE_PAYMENT_PN_SOFORTUEBERWEISUNG_TEXT_EMAIL_SUBJECT', '{{EMAIL_TEXT_SUBJECT}}: {{ORDER_ID}}');  
?>