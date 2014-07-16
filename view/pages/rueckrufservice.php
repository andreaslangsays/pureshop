<?php
	if (isset($_GET['result']) && $_GET['result'] == 'success') {
		// show success page
?>
		<h2>Wir rufen Sie zurück</h2>
		<div class="grid_2 alpha omega">
			<img src="images/push/green-light.png" />
		</div>
		<div class="grid_10 alpha omaga">
			<div class="tx_30_40">Alles klar.</div><br />
			<div class="tx_15_20">Ihre Nachricht ist auf dem Weg zu uns. Wir freuen uns auf das Telefonat mit Ihnen.</div><br /><br />
			<a class="button gradientblack tx_12_15 tx_white" style="border: 1px solid #333333; width: 150px" href="<?= push_href_link(FILENAME_RUECKRUFSERVICE) ?>">Weiter zu Rückrufservice<img style="position: absolute; right: 6px; top: 11px" src="images/push/icons/ico_arrow-fw_S-double_white.png"></a>
		</div> 
<?php		
	} else {
		// show default page
?> 
        <h1>Wir rufen Sie zur&uuml;ck</h1>
        
        <span class="tx_13_20">Wann immer Sie wollen. Fast jedenfalls.</span><br /><br /><br />
        
        <div class="grid_8 alpha gray-frame">
            <form id="form-rueckrufservice" class="defaultForm label-left tx_13_20" action="./formmail.php" method="post">
                
                <noscript>
                    <div class="tx_red">Bitte aktivieren Sie JavaScript in Ihrem Browser.</div>
                </noscript>
                
                <label>Ich bin<span class="tx_red">*</span></label>
                    <div id="input_person" class="input">
                        <div class="radioGroup">
                            <div class="radio">
                                <input type="radio" value="Kunde" name="person" onclick="document.getElementById('client-id').style.display = 'block'" <?= $customer->login ? 'checked' : '' ?>> Kunde
                            </div>
                            <div class="radio">
                                <input type="radio" value="Interessent" name="person" onclick="document.getElementById('client-id').style.display = 'none'" <?= !$customer->login ? 'checked' : '' ?>> Interessent
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
                <label>Firma</label>
                <div class="input">
                    <input type="text" name="company" value="<?= ($customer->login) ? $customerDefaultAddress['company'] : '' ?>">
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
                
                <label>R&uuml;ckruftermin:</label>
                <div id="input_date" class="input">
                    <div id="rueckruf-datepicker" style="display: inline-block; position: relative; width: 150px">
                        <input id="datepicker" type="text" name="date" style="width: 75px" value="TT.MM.JJJJ" onclick="this.value=''" onblur="if(this.value=='') this.value='TT.MM.JJJJ';">
                    </div>			
                    <select name="timeslot" id="timeslot" style="width: 107px">
                        <option id="defaultTimeslot" value="">Zeitraum</option>
                        <?php
                            
                        ?>
                        <option value="8:00-9:00">8:00 - 9:00</option>
                        <option value="9:00-10:00">9:00 - 10:00</option>
                        <option value="10:00-11:00">10:00 - 11:00</option>
                        <option value="11:00-12:00">11:00 - 12:00</option>
                        <option value="12:00-13:00">12:00 - 13:00</option>
                        <option value="13:00-14:00">13:00 - 14:00</option>
                        <option value="14:00-15:00">14:00 - 15:00</option>
                        <option value="15:00-16:00">15:00 - 16:00</option>
                        <option value="16:00-17:00">16:00 - 17:00</option>
                        <option value="17:00-18:00">17:00 - 18:00</option>
                    </select>
                    <div class="error-msg">
                        Geben Sie bitte ein gültiges Datum und Zeit ein.
                    </div>
                </div>
                        
                <label>R&uuml;ckruf zu folgendem Thema<span class="tx_red">*</span></label>
                <div id="input_message" class="input">
                    <textarea name="message"></textarea>
                    <div class="error-msg">
                        Geben Sie bitte das Thema an.
                    </div>
                </div>
                
                <div class="tx_12_15" style="text-align: right; padding: 0 20px 0 0"><span class="tx_red">*</span>Pflichtfelder</div>
                
                <label></label>
                <div id="send-button-container" class="input" style="padding: 15px 0 0 8px"></div>	
                
                <input type="hidden" name="recipients" value="push,db">
                <input type="hidden" name="subject" value="[Anfrage] R&uuml;ckrufservice">
                <input type="hidden" name="referrer" value="rueckrufservice.php">
                <input type="hidden" name="mail_options" value="FromAddr=push,HTMLTemplate=rueckrufservice.html,AlwaysList,KeepLines,CharSet=utf-8">
                <input type="hidden" name="good_template" value="formmail_success.html">	
            </form>
        </div>
        
        <script type="text/javascript">
            $(function()
            {
                if ($('input:radio[name:person]:checked').val() == 'Interessent') {
                    document.getElementById('client-id').style.display = 'none';
                }
            
                $('#send-button-container').append('<input class="submitBtn w130 darkblue tx_12_15" type="submit" value="Nachricht senden" name="submit">');
                
                 $( "#datepicker" ).datepicker({
                    showOn: "button",
                    buttonImage: "images/push/calendar-icon.png"
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
                    if ( this.email.value.search(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/) == -1 )
                        errors.push('email');
                    if ( this.phone.value.search(/^[\d\s\+\-]{4,15}$/) == -1 )
                        errors.push('phone');
                    if ( this.message.value == '' )
                        errors.push('message');
                    if (this.date.value != "TT.MM.JJJJ") {
                        if (!checkDate(this.date)) {
                            errors.push('date');
                        }
                    }
        
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
                
                $('#datepicker').val("TT.MM.JJJJ");
                
                $('#datepicker').change(function() {
                
                    // enable all timeslots
                    $('#timeslot option:disabled').each(function() {
                        $(this).removeAttr('disabled');
                    });
                    
                    // reset timeslot
                    $('#defaultTimeslot').prop("selected", true);			
                            
                    if (!($(this).val() == '' || $(this).val() == "TT.MM.JJJJ")) {
                        if (checkDate(document.getElementById("datepicker"))) {
                            if (isToday($(this).val())) {
                            
                                var now = new Date();						
                                var timeslot = new Date();
                                timeslot.setHours(6, 30, 0, 0);
                                
                                $('#timeslot option').each(function() {
                                    if ($(this).attr("id") != "defaultTimeslot" && timeslot < now) {
                                        $(this).prop("disabled", true);
                                    }
                                    timeslot.setHours(timeslot.getHours() + 1);
                                });
                            }	
                        }
                    }
                });
                
                function isToday(dateString) {			// date: TT.MM.JJJJ
                
                    re = /^(\d{1,2})\.(\d{1,2})\.(\d{4})$/; 
                    
                    if(regs = dateString.match(re)) { 
                    
                        var today = new Date();
                        var date = new Date(regs[3], regs[2] - 1, regs[1]);
                        
                        if (date.setHours(0,0,0,0) == today.setHours(0,0,0,0)) {				
                            return true;
                        }
                    }
                    
                    return false;
                }
                
                function checkDate(field) { 
                    var date = new Date();
                    var minYear = date.getUTCFullYear();
                    var maxYear = 2099;
                    var minMonth = date.getUTCMonth() + 1;
                    var minDay = date.getUTCDate();
                    
                    re = /^(\d{1,2})\.(\d{1,2})\.(\d{4})$/; 
                    
                    if(field.value != '') { 
                        if(regs = field.value.match(re)) { 
                            if(regs[1] < 1 || regs[1] > 31) { 
                                return false;
                            } else if(regs[2] < 1 || regs[2] > 12) { 
                                return false;
                            } else if(regs[3] < minYear || regs[3] > maxYear) { 
                                return false;
                            } else {
                                if (regs[3] == minYear) { 
                                    if (regs[2] == minMonth) {
                                        if (regs[1] < minDay) {
                                            return false;
                                        }
                                    } else if (regs[2] < minMonth) {
                                        return false;
                                    }
                                }
                            }
                        } else { 
                            return false;
                        } 
                    } else { 
                        return false;
                    } 
                                    
                    if ((date.getHours() > 17) || (date.getHours() == 17 && date.getMinutes() > 30)) {
                        return false;
                    }
                                        
                    return true; 
                }
            });
        </script>
<?php		
	}
?>