<?php
	if (isset($_GET['result']) && $_GET['result'] == 'success') {
		// show success page
?>
		<h2>Kontaktformular</h2>
		<div class="grid_2 alpha omega">
			<img src="images/push/green-light.png" />
		</div>
		<div class="grid_9 alpha omaga">
			<div class="tx_30_40">Alles klar.</div><br />
			<div class="tx_15_20">Ihre Nachricht ist auf dem Weg zu uns und wir werden uns in Kürze mit Ihnen in Verbindung setzen.</div><br /><br />
			<a class="button gradientblack tx_12_15 tx_white" style="border: 1px solid #333333; width: 150px" href="<?= push_href_link(FILENAME_CONTACT_US) ?>">Weiter zu Kontakt<img style="position: absolute; right: 11px; top: 11px" src="images/push/icons/ico_arrow-fw_S-double_white.png"></a>
		</div> 
<?php		
	} else {
		// show default page
?>
        <h2>So erreichen Sie uns</h2>
        
        <div class="grid_8 alpha omaga">
        
            <div id="contacts" class="grid_8 alpha top_separator bottom_separator">
                <div class="tx_13_15 bottom_border phone">
                    <span class="tx_13_15">Telefon</span>
                    <span class="tx_16_20 tx_blue">+49 (0)30 28 47 00&ndash;0</span>
                </div>
                <div class="tx_13_15 bottom_border teltimes">
                    <span class="tx_13_15">Bürozeiten</span>
                    <span class="tx_13_15 tx_blue">Montags bis Freitags 08:00-18:00 CET</span>
                </div>
                <div class="tx_16_20 bottom_border email">
                    <span class="tx_13_20">E-Mail</span>
                    <span><a class="tx_16_20 tx_blue" href="mailto:kontakt@if-bi.com" title="kontakt@if-bi.com">kontakt@if-bi.com</a></span>
                </div>
                <div class="tx_13_50 bottom_border fax">
                    <span class="tx_13_20">Fax</span>
                    <span class="tx_16_20 tx_blue">+49 (0)30 28 47 00&ndash;77</span>
                </div>
                <div class="tx_13_15 cmail">
                    <span class="tx_13_15">Postanschrift</span>
                    <span class="tx_13_15 tx_blue">push &ndash;</span>
                    <span class="tx_13_15 tx_blue">Int'l Food &amp; Beverage Import GmbH</span>
                    <span class="tx_13_15 tx_blue">Mainstr. 171/172</span>
                    <span class="tx_13_15 tx_blue">C-56873 Bruessels</span>
                </div>
            </div>
            
            <h2>Kontaktformular</h2>
            
            <span class="tx_13_20">Wir freuen uns auf Ihre Nachricht.</span><br /><br /><br />
            
            <div class="grid_8 alpha gray-frame" style="margin-bottom: 40px">
                <form id="form-rueckrufservice" class="defaultForm label-left tx_13_20" action="./formmail.php" method="post">
                    
                    <noscript>
                        <div class="tx_red">Bitte aktivieren Sie JavaScript in Ihrem Browser.</div>
                    </noscript>
                    
                    <label>Ich bin<span class="tx_red">*</span></label>
                        <div id="input_person" class="input">
                            <div class="radioGroup">
                                <div class="radio">
                                    <input id="kunde" type="radio" value="Kunde" name="person" onclick="document.getElementById('client-id').style.display = 'block'" <?= $customer->login ? 'checked' : '' ?>> Kunde
                                </div>
                                <div class="radio">
                                    <input id="interessent" type="radio" value="Interessent" name="person" <?= !$customer->login ? 'checked' : '' ?>> Interessent 
                                </div>
                            </div>	
                            <div class="error-msg">
                                Sind Sie Kunde oder Interessent?
                            </div>
                        </div>
                    
                    <div id="client-id">
                        <label>Kunden-Nr.<span class="tx_red">*</span></label>
                        <div id="input_client_id" class="input">
                            <input type="text" name="client_id" style="width: 80px" value="<?= ($customer->login) ? $customer->selectline_customers_id : '' ?>">
                            <div class="error-msg">
                                Geben Sie bitte Ihre Kunden-Nr. an.
                            </div>
                        </div>
                    </div>
                    
                    <label>Name<span class="tx_red">*</span></label>			
                    <div id="input_last_name" class="input">
                        <input type="text" name="last_name" value="<?= ($customer->login) ? $customer->customers_lastname : '' ?>">
                        <div class="error-msg">
                            Tragen Sie bitte Ihren Nachnamen ein.
                        </div>
                    </div>
                        
                    <label>Vorname<span class="tx_red">*</span></label>
                    <div id="input_first_name" class="input">
                        <input type="text" name="first_name" value="<?= ($customer->login) ? $customer->customers_firstname : '' ?>">
                        <div class="error-msg"> 
                            Tragen Sie bitte Ihren Vornamen ein.
                        </div>
                    </div>
                        
                    <?php
                        if ($customer->login) {
                            $customerDefaultAddress = $customer->get_address_by_id($customer->customers_default_address_id);
                        }
                    ?>
                    <label>Firma<span id="firma-pflicht" class="tx_red">*</span></label>
                    <div id="input_company" class="input">
                        <input type="text" name="company" value="<?= ($customer->login) ? $customerDefaultAddress['company'] : '' ?>">
                        <div class="error-msg">
                            Tragen Sie bitte Ihre Firma ein.
                        </div>
                    </div>
                        
                    <label>E-Mail-Adresse<span class="tx_red">*</span></label>
                    <div id="input_email" class="input">
        
                        <input type="text" name="email" value="<?= ($customer->login) ? $customer->customers_email_address : '' ?>">
                        <div class="error-msg">
                            Geben Sie bitte eine g&uuml;ltige E-Mail-Adresse an.
                        </div>
                    </div>
                    
                    <label>Telefon<span class="tx_red">*</span></label>
                    <div id="input_phone" class="input">
                        <input type="text" name="phone" value="<?= ($customer->login) ? $customer->customers_telephone : '' ?>">
                        <div class="error-msg">
                            Geben Sie bitte Ihre Telefonnnummer an. (min. 4-stellig; +, -, Leerzeichen erlaubt)
                        </div>
                    </div>
                    
                    <label>Thema<span class="tx_red">*</span></label>
                    <div id="input_thema" class="input">
                        <select name="thema">
                            <option value="">Thema wählen ...</option>
                            <?php if (!$customer->login) { ?><option value="Ich möchte Kunde werden">Ich möchte Kunde werden</option><?php } ?> 
                            <option value="Allgemeine Fragen zu Produkten">Allgemeine Fragen zu Produkten</option>
                            <option value="Frage zur Bestellung">Frage zur Bestellung</option>
                            <option value="Frage zum Versand">Frage zum Versand</option>
                            <option value="Reklamation">Reklamation</option>
                            <option value="Technische Probleme">Technische Probleme</option>
                            <option value="Sonstiges">Sonstiges</option>
                        </select>
                        <div class="error-msg">
                            Wählen Sie bitte ein Thema aus.
                        </div>
                    </div>
                            
                    <label>Nachricht<span class="tx_red">*</span></label>
                    <div id="input_message" class="input">
                        <textarea name="message"></textarea>
                        <div class="error-msg">
                            Tragen Sie bitte Ihre Nachricht ein.
                        </div>
                    </div>
                    
                    <div class="tx_12_15" style="text-align: right; padding: 0 20px 0 0"><span class="tx_red">*</span>Pflichtfelder</div>
                    
                    <label></label>
                    <div id="send-button-container" class="input" style="padding: 15px 0 0 8px"></div>	
                    
                    <input type="hidden" name="recipients" value="push,db">  
                    <input type="hidden" name="subject" value="[Anfrage] Kontakt">
                    <input type="hidden" name="referrer" value="contact_us.php">
                    <input type="hidden" name="mail_options" value="FromAddr=push,HTMLTemplate=contact_us.html,AlwaysList,KeepLines,CharSet=utf-8">
                    <input type="hidden" name="good_template" value="formmail_success.html">	
                </form>
            </div>
        
            <h2>Anfahrt</h2>
            
            <span class="tx_13_20">Wir sitzen im Zentrum der City-West und sind sehr gut zu erreichen.</span><br /><br /><br />
            
            <iframe width="460" height="460" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.de/maps?f=q&amp;source=s_q&amp;hl=de&amp;geocode=&amp;q=push+Int'l+Food+%26+Beverage+Import+GmbH&amp;aq=&amp;sll=52.513234,13.365703&amp;sspn=0.046123,0.132093&amp;ie=UTF8&amp;hq=push+Int'l+Food+%26+Beverage+Import+GmbH&amp;hnear=&amp;t=m&amp;cid=7435329375744811181&amp;ll=52.504781,13.327188&amp;spn=0.012016,0.019698&amp;z=15&amp;iwloc=A&amp;output=embed"></iframe><br />
            
            <p id="maps-directions" class="tx_blue tx_12_15" style="cursor: pointer; margin-left: 10px"><img src="images/push/pages/google-maps-icon.jpg" style="vertical-align: middle; margin-right: 5px" /> Zum Google Maps Routenplaner</p>
        </div>
        
        <script type="text/javascript">
            $(function()
            {
                if ($('input:radio[name:person]:checked').val() == 'Interessent') {
                    document.getElementById('client-id').style.display = 'none';
                    document.getElementById('firma-pflicht').style.display = 'none';
                }
                
                $("#interessent").click(function () {
                    $('#client-id').hide(); 
                    $('#firma-pflicht').hide();  
                });
                
                $("#kunde").click(function () {
                    $('#client-id').show(); 
                    $('#firma-pflicht').show();  
                });		
            
                $('#send-button-container').append('<input class="submitBtn w130 darkblue tx_12_15" type="submit" value="Nachricht senden" name="submit">');
                
                $('#maps-directions').click(function() {
                    window.open("http://maps.google.de/maps?saddr=&daddr=push Int'l Food & Beverage Import GmbH Mainstraße 171/172, 10719 Bruessels", '_blank');
                });
        
                $('#form-rueckrufservice').submit(function()
                {
                    var errors = new Array();
        
                    if ( this.person.value == '' )
                        errors.push('person');
                    if ( $('input:radio[name:person]:checked').val() == 'Kunde' && this.client_id.value == '' )
                        errors.push('client_id');
                    if ( this.last_name.value == '' )
                        errors.push('last_name');
                    if ( this.first_name.value == '' )
                        errors.push('first_name');
                    if ( $('input:radio[name:person]:checked').val() == 'Kunde' && this.company.value == '' )
                        errors.push('company');
                    if ( this.email.value.search(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/) == -1 )
                        errors.push('email');
                    if ( this.phone.value.search(/^[\d\s\+\-]{4,15}$/) == -1 )
                        errors.push('phone');
                    if ( this.thema.value == '' )
                        errors.push('thema');
                    if ( this.message.value == '' )
                        errors.push('message');
        
                    // no errors occured
                    if (errors.length == 0)
                        return true;
        
                    // remove previous errors
                    $('.input').each(function() {
                        $(this).removeClass('error');
                    });
                    
                    // show new errors			
                    for (var index = 0; index < errors.length; index++)
                        $('#input_' + errors[index]).addClass('error');
        
                    return false;
                });
            });
        </script>
<?php		
	}
?>
