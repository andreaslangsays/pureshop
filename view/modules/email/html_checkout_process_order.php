<?php
ob_start();
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<META content="text/html; charset=utf-8" http-equiv=Content-Type>
</HEAD>
<BODY>
<Div style="width:620px">
	<DIV style="margin-bottom:20px; margin-top:20px">
        <IMG src="http://if-bi.com/shop/images/push/push-logo.png"><span>&nbsp;&nbsp;&nbsp;</span>
        <FONT color=#1179cb face="Myriad Pro, Arial, Helvetica, sans-serif">TASTE IT. LOVE IT. SHOP IT.</FONT>
    </DIV>
<!-- Start MSG-->
    <DIV style="border:solid #ccc 1px; overflow:hidden; padding:20px; font-size:13px; line-height:1.5">
      <DIV><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">Lieber push Kunde,</FONT></DIV>
      <DIV><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
      <DIV>
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">vielen Dank für Ihre Bestellung!</FONT></P>
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">&nbsp;</FONT></P>

<!-- Start Cart-->
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif"><b>Übersicht über Ihre Online-Bestellung Nr. <?php echo $insert_id;?> vom <?php echo date('j. n. Y'); ?></b></FONT></P>
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">&nbsp;</FONT></P>
        <div style="position:relative; overflow:hidden">
            <table border="0" cellpadding="0" cellspacing="0" width="560" style="font-family:Myriad Pro, Arial, Helvetica, sans-serif; font-size:13px; color:#333; line-height:1.5; vertical-align:top">
                <tr>
                    <td style="font-size:11px; color:#666; border-bottom:solid #ccc 1px; padding:0 20px 10px 0; vertical-align:top; width:160px">Artikel</td>
                    <td style="font-size:11px; color:#666; border-bottom:solid #ccc 1px; padding:0 20px 10px 0; vertical-align:top; width:160px">Preis/ Einheit</td>
                    <td style="font-size:11px; color:#666; border-bottom:solid #ccc 1px; padding:0 20px 10px 0; vertical-align:top; width:40px">Anzahl</td>
                    <td style="font-size:11px; color:#666; border-bottom:solid #ccc 1px; padding:0 0 10px 20px; vertical-align:top; width:120px; text-align:right">Summe</td>
                </tr>
<?php
//here starts the products-looooop
$pq=push_db_query("SELECT * FROM orders_products WHERE orders_id = '" . $insert_id . "'");
while($m_product=push_db_fetch_array($pq))
{
		$p->load_product($m_product['products_id']);
		if(substr($m_product['products_name'],-2) == 'VE')
		{
			$VE = true;
		}
		else
		{
			$VE = false;	
		}
?>
		        <tr>
                    <td style="border-bottom:dotted #ccc 1px; padding:10px 20px 10px 0; vertical-align:top; width:160px">
                        <span><?php  echo $p->products_name ?></span><br>
                        <?php 						if($p->manufacturers_name <>'')
						{
						?>
						<span style="font-size:12px; color:#666"><?php echo $p->manufacturers_name; ?></span><br>
                        <?php
						}
						?>
						<span style="font-size:12px; color:#666"><?php $p->products_model;?></span>
                    </td>
                    <td style="border-bottom:dotted #ccc 1px; padding:10px 20px 10px 0; vertical-align:top; width:160px">
                        <?php 
						if($VE)
						{
						?>
						<?php echo $currencies->format($m_product['final_price'])?>/ VE<br>
                        <?php
						}
						else
						{
						?>
						<?php echo $currencies->format($m_product['final_price'])?>/ Stück
						<?php
						}
						?>
                    </td>
                    <td style="border-bottom:dotted #ccc 1px; padding:10px 20px 10px 0; vertical-align:top; width:40px">
                        <?php 
						if($VE)
						{
						?>
						<?php echo $m_product['products_quantity'] ?><br>
                        <?php

						}
						else
						{
						?>
						<?php echo $m_product['products_quantity'] ?>
						<?php
						}
						?>
                    </td>
                    <td style="border-bottom:dotted #ccc 1px; padding:10px 0 10px 20px; vertical-align:top; width:120px; text-align:right;font-size:15px; color:#1179CB"><?php echo $currencies->format($m_product['final_price'] * $m_product['products_quantity']); ?></td>
                </tr>
<?php
	}
$ot=push_db_query("SELECT * FROM orders_total WHERE orders_id='" . $insert_id . "'");
while($temp=push_db_fetch_array($ot)){
	switch($temp['class']){
		case 'push_pre_netto':
			$gesamtnetto = $temp['value'];
		break;
		case 'push_shipping':
			$versand = $temp['value'];
		break;
		case 'push_onlinerabatt':
			$onlinerabatt = $temp['value'];
		break;
		case 'push_discount_5':
			$m_discount_text="5% Rabatt";// auf Gesamtbestellwert";
			$m_discount = $temp['value'];
		break;
		case 'push_discount_10':
			$m_discount_text="10% Rabatt";// auf Gesamtbestellwert";
			$m_discount = $temp['value'];
		break;
		case 'push_discount_S':
			$m_discount_s_text = $temp['title'] . " Gutschein";
			$m_discount_s = $temp['value'];
		break;
		case 'push_discount_C':
			$m_discount_s = $temp['value'];
		break;
		case 'push_summe':
			$summe = $temp['value'];
		break;
		case 'push_tax_7':
			$tax7 = $temp['value'];
		break;
		case 'push_tax_19':
			$tax19 = $temp['value'];
		break;
		default;	
	}
}
?>
<!--                <tr>
                    <td style="border-bottom:dotted #ccc 1px; padding:10 20 10 0; vertical-align:top; width:160">
                        <span>[[Artikel 2]]</span><br>
                        <span style="font-size:12px; color:#666">[[Hersteller Art. 2]]</span><br>
                        <span style="font-size:12px; color:#666">[[Art.Nr. Art.2]]</span>
                    </td>
                    <td style="border-bottom:dotted #ccc 1px; padding:10 20 10 0; vertical-align:top; width:160">[[0,00]]/ VE</td>
                    <td style="border-bottom:dotted #ccc 1px; padding:10 20 10 0; vertical-align:top; width:40">[[1]]</td>
                    <td style="border-bottom:dotted #ccc 1px; padding:10 0 10 20; vertical-align:top; width:120; text-align:right;font-size:15; color:#1179CB">[[11.000,00]]</td>
                </tr>
-->
                <tr>
                    <td colspan="3" style="padding:10px 0 0 0; vertical-align:top; width:160px"><b>Gesamtbestellwert netto</b></td>
                    <td style="padding:10px 0 0 20px; vertical-align:top; width:120px; text-align:right;font-size:15px; color:#1179CB"><?php echo $currencies->format($gesamtnetto);unset($gesamtnetto);?></td>
                </tr>
                <tr>
                    <td colspan="3" style="padding:0; vertical-align:top; width:160px">Versandkosten</td>
                    <td style="padding:0 0 0 20px; vertical-align:top; width:120px; text-align:right;font-size:15px; color:#1179CB"><?php echo $currencies->format($versand);?></td>
                </tr>
                <tr>
                    <td colspan="4" style="padding:0; vertical-align:top; width:160px; color:#FF00FF">Ersparnisse:</td>

                </tr>
				<?php
					if($versand == 0)
					{
				?>
                <tr>
                    <td style="padding:0 20px 0 0; vertical-align:top; width:160px; color:#FF00FF">Versandkosten</td>
                    <td style="padding:0 20px 0 0 ; vertical-align:top; width:160px; color:#FF00FF"">9,95</td>
                    <td style="padding:0; vertical-align:top; width:40px">&nbsp;</td>
                    <td style="padding:0; vertical-align:top; width:120px; text-align:right; color:#FF00FF">&nbsp;</td>
                </tr>
             <?php
					}
					unset($versand);
					
					if($onlinerabatt > 0)
					{		 
			 ?>
			    <tr>
                    <td style="padding:0; vertical-align:top; width:160px; color:#FF00FF">1% Onlinezusatzrabatt</td>
                    <td style="padding:0; vertical-align:top; width:160px; color:#FF00FF"">&nbsp;</td>
                    <td style="padding:0; vertical-align:top; width:40px">&nbsp;</td>
                    <td style="padding:0; vertical-align:top; width:120px; text-align:right; color:#FF00FF">- <?php echo $currencies->format($onlinerabatt);?></td>
                </tr>
				<?php
					}
					unset($onlinerabatt);
					
					if(isset($m_discount) & $m_discount > 0)
					{
				?>
<!--                <tr>
                    <td colspan="4" style="padding:0; vertical-align:top; width:160; color:#FF00FF">[[Individueller Kundenbonus:]]</td>
                </tr>-->
                <tr>
                    <td colspan="3" style=" padding:0 0 10px 0; vertical-align:top; width:160px; color:#FF00FF"><?php echo $m_discount_text;?></td>
                    <td style=" padding:0 0 10px 0; vertical-align:top; width:120px; text-align:right; color:#FF00FF">- <?php echo $currencies->format($m_discount);?></td>
                </tr>
				<?php
					}
					unset($m_discount);
					
					if(isset($m_discount_s) & $m_discount_s > 0)
					{
				?>
                <tr>
                    <td colspan="3" style=" padding:0 0 10px 0; vertical-align:top; width:160px; color:#FF00FF"><?php echo $m_discount_s_text;?></td>
                    <td style="padding:0 0 10px 0; vertical-align:top; width:120px; text-align:right; color:#FF00FF">- <?php echo $currencies->format($m_discount_s);?></td>
                </tr>
				<?php
					}
					unset($m_discount_s);
				?>
				<tr>
                    <td colspan="4" style="border-bottom:dotted #ccc 1px; padding:0 0 10px 0; vertical-align:top; width:160px; color:#FF00FF">&nbsp;</td>
                </tr>
                <tr>
                    <td style="border-bottom:solid #ccc 1px; padding:10px 0 10px 0; vertical-align:top; width:160px;">
                    <b>Gesamtbetrag netto</b><br>
                    MwSt. 19%: <?php echo (isset($tax19))? $currencies->format($tax19) :'0,00&nbsp;EUR';unset($tax19);?><br>
                    MwSt. 7%: <?php echo (isset($tax7))? $currencies->format($tax7) :'0,00&nbsp;EUR';unset($tax7);?>
                    </td>
                    <td colspan="3" style="border-bottom:solid #ccc 1px; padding:10px 0 10px 20px; vertical-align:top; width:120px; text-align:right; color:#1179CB; font-size:30px"><?php echo $currencies->format($summe);unset($summe);?></td>
                </tr> 
		</table>
		</div>
<!-- End Cart-->

        <P style="MARGIN: 0cm 0cm 0pt"><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">&nbsp;</FONT></P>
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">&nbsp;</FONT></P>
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">Bitte beachten Sie: Dies ist <b>keine</b> Auftragsbestätigung. Eine solche lassen wir Ihnen nur zukommen, insofern es Änderungen an Ihrer Lieferung geben sollte (zB. Artikelverfügbarkeit). Die Versandinformationen (inklusive Track & Trace Nummer) werden Ihnen separat per E-Mail zugehen.</FONT></P>
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">&nbsp;</FONT></P>
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">Sollten Sie Fragen haben, kontaktieren Sie uns bitte unter 0800-4324835 (0800-pushTEL) oder unter <a href="mailto:service@if-bi.com" style="color:#1179bc; text-decoration:none" target="_blank">service@if-bi.com.</a></FONT></P>
        <P style="MARGIN: 0cm 0cm 0pt">&nbsp;</P>
        <P style="MARGIN: 0cm 0cm 0pt"><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">Freundliche Grüße</FONT></P>
      </DIV>
      <DIV><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
      <DIV><FONT color="#333" face="Myriad Pro, Arial, Helvetica, sans-serif">Ihr push Team</FONT></DIV>
    </DIV>  
<!--EOM-->
<!-- Start Contact-->  
    <DIV>
      <DIV style="border:solid 1px #88bde5; background-color:#e7f2fa; padding:10px; margin-top:20px; margin-bottom:20px; font-size:11px; line-height:1.5">
        <DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">push Int’l Food &amp; Beverage Import GmbH<BR>
          Mainstraße 171/172, C-56873 Bruessels<BR>
          <a href="http://www.if-bi.com" style="color:#1179bc; text-decoration:none" target="_blank">www.if-bi.com</a></FONT></DIV>
        <DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
        <DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Telefon: +49 (0)30 28 47 00-0, 
          Telefax: +49 (0)30 28 47 00-77, E-Mail: kontakt@if-bi.com</FONT></DIV>
        <DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
        <DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Handelsregister: Amtsgericht 
          Charlottenburg, Handelsreg.-Nr.: HRB 94602B<BR>
          VAT-ID: 
          DE239223851<BR>
          Geschäftsführer: Matthias Gladiatory, Andreas Heroe, Stefan 
          Richter</FONT></DIV>
        <DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
        <DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Bankverbindung: 
          Commerzbank<BR>
          Bankleitzahl: 100 400 00, Kontonummer: 51 63 05 000 <BR>
          BIC: 
          COBADEFF, IBAN: DE87 1004 0000 0516 3050 00</FONT></DIV>
        <DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
        <DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Öko-Kontrollstelle: 
          DE-ÖKO-044</FONT></DIV>
      </DIV>
    </DIV>
<!-- End Contact-->

<!-- Start Disclaimer-->    
    <div style="font-size:11px; line-height:1.5; padding-left:10px; padding-right:10px; margin-bottom:20px">
        <DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Achtung!</FONT></DIV>
        <DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif">Diese email kann Betriebs- und 
          Geschäftsgeheimnisse, dem Anwaltsgeheimnis unterliegende oder sonstige 
          vertrauliche Informationen enthalten. Sollten Sie diese email irrtümlich 
          erhalten haben, ist Ihnen eine Kenntnisnahme des Inhalts, eine Vervielfältigung 
          oder Weitergabe der email ausdrücklich untersagt. Bitte benachrichtigen Sie uns 
          und vernichten Sie die email. <BR>
          Der Absender hat alle erdenklichen 
          Vorsichtsmaßnahmen getroffen, dass die Anlagen dieser eMail frei von 
          Computerviren o.ä. sind. Gleichwohl schließen wir die Haftung für jeden Schaden 
          aus, der durch Computerviren o.ä. verursacht wurde, soweit wir nicht vorsätzlich 
          oder grob fahrlässig gehandelt haben. Wir raten Ihnen, dass Sie in jedem Fall 
          Ihre eigene Virenprüfung vornehmen, bevor Sie die Anlagen öffnen. Vielen Dank </FONT></DIV>
        <DIV><FONT color="#666" face="Myriad Pro, Arial, Helvetica, sans-serif"></FONT>&nbsp;</DIV>
        <DIV><FONT face="Myriad Pro, Arial, Helvetica, sans-serif"><FONT color="#666">Important!! <BR>
          The information contained in this email message may be confidential 
          information, and may also be the subject of legal professional privilege. If you 
          are not the intended recipient, any use, interference with, disclosure or 
          copying of this material is unauthorised and prohibited. Please inform us 
          immediately and destroy the email. <BR>
          We have taken every reasonable precaution 
          to ensure that any attachment to this eMail has been swept for viruses. However, 
          we cannot accept liability for any damage sustained as a result of software 
          viruses and would advice that you carry out your own virus checks before opening 
          any attachment. Thank you for your cooperation</FONT> </FONT></DIV>
    </div>
    <!-- End Contact-->
    
</DIV>
</BODY>
</HTML>
<?php
$html_email_order=ob_get_clean();
?>