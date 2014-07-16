<?php
/*
 * Tabs for productdetail
 */

function make_download_link($id)
{
	global $customer;
	if($customer->login)
	{
		$link= md5(time() . $customer->customers_id . $t['id']);
		$dabalink='downloads/'.$link;
		if($lq=push_db_fetch_array(push_db_query("SELECT * FROM download_links WHERE link='" . $dabalink . "'")))
		{
			push_db_query("UPDATE download_links SET id='$id' WHERE link='$dabalink'");
		}
		else
		{
			push_db_query("INSERT INTO download_links SET id='$id', link='$dabalink'");
		}
	}
	else
	{
		$link=false;
	}
	return $link;
}
function product_datasheet($pid=false){
	global $customer;
	if(!$pid)
	{
		return false;
	}
	$product = new product;
	$product->load_product($pid);
	$q=push_db_query("SELECT * FROM downloads WHERE ref_id='" . $product->products_model . "' AND ref_type='product' AND filetype = 'PDF' AND active='1'");
	if(push_db_num_rows($q)>0)
	{
?>
	<div id="pdownloads" class="tx_13_20" style="border-bottom:0px dotted #ccc;padding-bottom:10px;margin-bottom:10px;position:relative;">
	<h4 class="tx_15_30 tx_bold"style="margin-bottom:20px;">Produktinformationen</h4>
	
	<div>
		<?php
		if(!$customer->login)
		{
			
			?>
			<div style="background-color: #FFEAEA; padding: 15px; margin-bottom: 30px;display:block;width:340px;" class="red-border tx_red">
			Bitte loggen Sie sich ein um den Downloadbereich zu aktivieren.		<br>
			<a href="<?= push_href_link(FILENAME_LOGIN,'','SSL') ?>" class="button w110 gradientblack tx_12_15 tx_white" style="margin-top: 15px; border: 1px solid #333333; width: 132px">Zur Anmeldung <img src="images/push/icons/ico_arrow-fw_S-double_white.png" style="position: absolute; right: 11px; top: 11px"></a>	
			</div>
			<?php	
		}
		else
		{
			if($customer->customers_id == 36767)
			{
				$timeout=6000;
			}
			else
			{
				$timeout=1199000;
			}
			?>
			<script type="text/javascript">
				$(document).ready(function(){
				setTimeout(function(){
						$('#pdownloads').append('<div id="timeout" class="tx_12_15" style="display:none;position:absolute;top:-10px;left:-10px;width:440px;height:100%;border: 1px solid #88BDE5;background-color: rgba(231,242,250, .90);padding:30px;">Aus Sicherheitsgründen verfallen die Links nach einer gewissen Zeit automatisch. Bitte laden Sie die Seite neu.<br><form method="post" style="display:block;text-align:center;margin-top:20px;"><input style="border:1px solid #999;padding:4px;margin:10px;height:50px;cursor:pointer" class="gradientgrey tx_blue tx_13_15" type="button" value="Seite jetzt neu laden" onclick="window.location.reload()"></form></div>')
						$('#timeout').fadeIn(1000);
						$('#pdownloads a').attr('href', '#');
				},<?=$timeout?>);
				//	
				})
			</script>
			<?php	
		}
		?>
		<?php	
		while($t=push_db_fetch_array($q))
		{
			if($customer->login)
			{
				$target = make_download_link($t['id']);
				echo '<a href="downloads/' . $target .  '/' . $t['filename'] . '" target="_blank" class="tx_blue"><img src="images/assets/ico_download.png" style="vertical-align:middle;margin-right:10px;"> ' . $t['name'] . ' (' . $t['filetype'] .  ')<br></a><br>';
			}
			else
			{
				$target = '#';
				echo '<a href="#" style="line-height:30px;" class="tx_grey"><img src="images/assets/ico_download_disabled.png" style="vertical-align:middle;margin-right:10px;"> ' . $t['name'] . ' (' . $t['filetype'] .  ')</a><br><br>';
			}
		}
		?>
		</div>
	</div>
<?php






	}
}

function product_info($pid=false)
{
	global $customer;
	if(!$pid)
	{
		return false;
	}
	$product = new product;
	$product->load_product($pid);
	?>
		<div class="tx_13_20" style="border-bottom:1px dotted #ccc;padding-bottom:10px;margin-bottom:10px;">
			<h4 class="tx_15_30 tx_bold" style="margin-bottom:20px;">Produktbeschreibung</h4>
			<span itemprop="description">
			<?php
				$string=$product->products_description;
				$outputstring = $string;
				$zuhl =preg_match_all("/\d+/",$string,$result);
				if($zuhl>0){
					$fitsto = new product;
					foreach($result[0] as $possibleid)
					{
						if($newid=$fitsto->model_exists($possibleid))
						{
							$replace = '<a href="' . push_href_link(FILENAME_PRODUCT_INFO . '?products_id='.$newid) . '" class="tx_blue">'.$possibleid.'</a>';
							$outputstring = str_replace($possibleid,$replace, $outputstring);
						}
					}
				}
				echo stripslashes(stripslashes(nl2br($outputstring)));
				
				//echo $product->products_short_description;
			?></span>
		</div>
		<div class="tx_13_20" style="border-bottom:1px dotted #ccc;padding-bottom:10px;margin-bottom:10px;">
			<h4 class="tx_15_30"style="margin-bottom:0;">Verpackungsinformationen</h4>
			<?php
				echo $product->products_package_info;
			?>
		</div>
		<?php 
		if($product->products_usage_info <> '')
		{
			?>
		<div class="tx_13_20" style="border-bottom:1px dotted #ccc;padding-bottom:10px;margin-bottom:10px;">
			<h4 class="tx_15_30"style="margin-bottom:0;">Verwendung</h4>
			<?php
				echo $product->products_usage_info;
			?>
		</div>
		<?php
		}
	/**
	 * package
	 */
	 if(false){
?>
			<p><?=TEXT_CONTENT?>:</b> <span id="package"><?php echo $product->get_package();?>&nbsp;</span>
<?php
		/**
		 * abtropfgewicht
		 */
		 if( $pef->get_abtropfgewicht() ){
	?>
			<b>Abtropfgewicht:</b> <span id="package"><?php echo $product->get_abtropfgewicht();?>&nbsp;</span>
	<?php
		}
	}
	/**
	 * Manufacturer (just a query more!)
	 */
	
	if($product->manufacturers_name <>''){
		?>
		<div class="tx_13_20" style="border-bottom:1px dotted #ccc;padding-bottom:10px;margin-bottom:10px;">
<h4 class="tx_15_30"style="margin-bottom:0;"> <?= TABLE_HEADING_MANUFACTURER?> </h4>
<a  itemprop="brand" href="<?php echo push_href_link(FILENAME_BRANDS, 'mid='.$product->manufacturers_id) ;?>" class="tx_blue"><?= $product->manufacturers_name ?></a>
</div>
<?php
	}
	/**
	 * Artikelnummer
	 */
	 ?>
<div class="tx_13_20" style="border-bottom:none;padding-bottom:10px;margin-bottom:10px;">
<h4 class="tx_15_30"style="margin-bottom:0;"><?=TEXT_ART_NR?></h4>
<?=$product->products_model?>
</div>
<?php 	/**
	 * TODO: Bio -> Informationen einfuegen...
	 */

if($product->is_bio()){
?>
		<b>Bio:</b> <span>ja</span>
<?php
}
?>

<?php
/**
 * special Gültigkeit
 */
if($product->special && ($product->expires_date > 0)){
?>
<b>Angebot gilt bis:</b><span><?= date('d.m.Y',strtotime($product->expires_date)) ?></span>
<?php
	}
?>
<?php
	/**
	 * Product Added...
	 */

?>
<strong><?php
/* 
if($product->products_date_added != "0000-00-00 00:00:00"){
echo sprintf(TEXT_DATE_ADDED, push_date_short($product->products_date_added)); 
}else{
echo sprintf(TEXT_DATE_ADDED, push_date_short("2009-11-25 00:00:00")); 
}*/
?></strong>

<?php	
}
# [##########################################################################################################################]
//
# [##########################################################################################################################]
function product_question($pid=false)
{
	global $customer;
	if(!$pid)
	{
		return false;
	}
	$product = new product;
	$product->load_product($pid);
	
	?><div class="tx_13_20" >
	<span id="question_success" style="display:none;">Ihre Nachricht wurde versendet.</span>
	<h4 class="tx_15_30"style="margin-bottom:0;">Frage zum Produkt</h4>
	<strong>Sie fragen, wir antworten</strong><br>
	<span>
	Bitte füllen Sie das Formular aus, wenn Sie Fragen zu diesem Produkt haben.<br>
	Wir antworten kurzfristig per E-Mail oder Rufen Sie auf Wunsch zurück.
	</span>
	<br />
	<div class="gray-frame" style="margin-top:40px;">
		<form id="form-question" name="form-question" class="defaultForm " method="post">
		<input type="hidden" name="productid" value="<?php echo $product->id;?>">
 		<?php
		if(!$customer->login)
		{
		?>
		 <label>Kunden-Nr.</label>			
		<div class="input" id="input_customer_id">
			<input type="text" value="" name="customer_id">
		</div>
		<?php
		}
		else
		{
		?>	
			<input type="hidden" name="customer_id" value="<?php echo $customer->selectline_customers_id ;?>">
		<?php
		}
		?>
		<label>Name<span class="tx_red">*</span></label>			
		<div class="input" id="input_name">
			<input type="text" value="<?php if($customer->login){echo $customer->customers_firstname . " " . $customer->customers_lastname;}?>" name="name">
			<div class="error-msg">
				Tragen Sie bitte Ihren Namen ein.
			</div>
		</div>
 		<?php
		if(!$customer->login)
		{
		?>
		<label>Firma</label>			
		<div class="input" id="input_company">
			<input type="text" value="" name="company">
		</div>
		<?php
		}
		else
		{
		?>	
			<input type="hidden" value="" name="company">
		<?php
		}
		?>
		<label>E-Mail-Adresse<span class="tx_red">*</span></label>			
		<div class="input" id="input_email">
			<input type="text" value="<?php  if($customer->login){echo $customer->customers_email_address ;}?>" name="email">
			<div class="error-msg">
				Tragen Sie bitte Ihre E-Mail-Adresse ein.
			</div>
		</div>
		
		<label>Frage<span class="tx_red">*</span></label>			
		<div class="input" id="input_question">
			<textarea name="question"></textarea>
			<div class="error-msg">
				Stellen Sie eine Frage.
			</div>
		</div>

		<label style="overflow:hidden;">Rückruf gewünscht am</label>
		<div id="input_date" class="input">
		<div id="rueckruf-datepicker" style="display: inline-block; position: relative; width: 150px">
		<input id="datepicker" type="text" name="date" style="width: 75px" value="TT.MM.JJJJ" onclick="this.value=''" onblur="if(this.value=='') this.value='TT.MM.JJJJ';">
		</div>			
		<select name="timeslot" id="timeslot" style="width: 107px">
		<option id="defaultTimeslot" value="">Zeitraum</option>
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
		<label>Rückrufnummer</label>			
		<div class="input" id="input_phone">
			<input type="text" value="" name="phone">
		</div>
	<?php
	if(!$customer->login)
	{
	?>
		<label>Sicherheitscode<span class="tx_red">*</span></label>			
		<div class="input" id="input_captcha" style="position:relative;">
			<input type="text" value="" name="captcha_code"><span class="green_success"></span>
			<div class="error-msg">
				Bitte geben Sie den Sicherheitscode ein.
			</div>
		</div>
		<label>&nbsp;</label>
	<div class="input" id="input_captcha_img" style="height:41px;">
		<img id="captcha" src="includes/captcha/captcha.php?t<?=time()?>">
		<button class="gradientgrey" id="reload_captcha" name="reload_captcha" style="float:right;height:30px;border:1px solid #ccc;padding:4px 10px;vertical-align:top;width:auto;">Anderes Bild <img src="./images/assets/ico_reload.png" alt="" style="vertical-align:middle;line-height:10px;"/></button>
	</div>


<label>&nbsp;</label>
   <div style="padding: 0 20px 0 10px" class="input tx_12_15">Bitte geben Sie den Text aus dem Bild ohne Leerzeichen in das oben stehende Feld ein.</div>
                    <div style="text-align: right; padding: 0 20px 0 0" class="tx_12_15"><span class="tx_red">*</span>Pflichtfelder</div>
	<?php
	}
	else
	{
	?>
    <div style="text-align: right; padding: 0 20px 0 0" class="tx_12_15"><span class="tx_red">*</span>Pflichtfelder</div>

	<?php
	}?>
                    <label></label>
                    <div style="padding: 15px 0 0 8px" class="input" id="send-button-container"><input type="submit" name="submit" value="Frage senden" class="submitBtn w130 darkblue tx_12_15"></div>	
      	</form>
		
	</div>
	</div>
	        <script type="text/javascript">
            $(function()
            {
				
       		    $(".green_success").hide();
				$('#reload_captcha').on('click', function(evt){
					evt.preventDefault();
					$('img#captcha').attr('src', 'includes/captcha/captcha.php?t'+Math.random());
				})
                
                 $( "#datepicker" ).datepicker({
                    showOn: "button",
                    buttonImage: "images/push/calendar-icon.png",
					dateFormat: "dd.mm.yy"
                });
        
                $('#form-question').submit(function(evt)
                {
					evt.preventDefault();
                    var errors = new Array();
                    if ( this.name.value == '' )
                        errors.push('name');
                    if ( this.email.value.search(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/) == -1 )
                        errors.push('email');
                    if ( $("textarea[name='question']").val().length < 10 )
                        errors.push('question');
                    if (this.date.value != "TT.MM.JJJJ") {
                        if (!checkDate(this.date)) {
                            errors.push('date');
                        }
                    }
					<?php
					if(!$customer->login)
					{
					?>
					if ( $("input[name='captcha_code']").val().length > 0 ){
						$.post("<?=DIR_WS_MODULES."ajax/"?>captchatest.php",$("#form-question").serialize()).done(function(data){
							if(data == 'false')
							{
								 $('#reload_captcha').trigger('click');
								 $('#input_captcha').addClass('error');
								 $(".green_success").fadeOut();
								return false;
							}
							else
							{
								$(".green_success").fadeIn(200);
							}
						});
					}
					else
					{
						errors.push('captcha');
					}
					<?php	
					}	
					?>
                    // remove previous errors
                    $('.input').each(function() {
                        $(this).removeClass('error');
                    });
                    // no errors occured
                    if (errors.length == 0)
					{
					<?php
						//Add Captcha-Test for nonloggedin customer
					if(!$customer->login)
					{
					?>
						$.post("<?=DIR_WS_MODULES."ajax/"?>captchatest.php",$("#form-question").serialize()).done(function(data){
						if(data == 'false')
							{
								 $('#input_captcha').addClass('error');
								return false;
							}
							else
							{
								 submitQForm();
							}
						});
					
					<?php
					}
					else
					{
					?>
					   submitQForm();
					<?php
					}
					?>
					   
					}
                     
                    // show new errors			
                    $.each(errors, function(index,value){
                  	  $('#input_' + value).addClass('error');
					});
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
			function submitQForm(){
			//	$("#form-question input").attr('disabled','true');
			//	$("#form-question textarea").attr('disabled','true');
			//	$("#form-question select").attr('disabled','true');
				$(this).removeClass('error');
				$.post("<?=DIR_WS_MODULES."ajax/"?>sendquestion.php",$("#form-question").serialize()).done(function(data){
					if(data == 'true')
						{
							$("input[name='captcha_code']").val('')
							$(".green_success").hide();
							$("#form-question textarea").val('');
							$('img#captcha').attr('src', 'includes/captcha/captcha.php?t'+Math.random());
							$('#question_success').show();
						}
					});				
			}
        </script>
	<?php
}


?>