<?php
/*
 * Krös
 */
require('includes/ajax_top.php');
//INSERT
//	var_dump($_POST);
if(!$customer->login || !isset($_GET['oID']) ||  $_GET['oID'] == "" ){
		push_redirect(push_href_link(FILENAME_LOGIN, '', 'SSL'));
}
//
if (!push_session_is_registered('customer_id')) {
	$navigation->set_snapshot();
	push_redirect(push_href_link(FILENAME_LOGIN, '', 'SSL'));
}
$pass = false;
if(isset($_GET['oID']))
{
	$oid=intval($_GET['oID']);
	$oQ = push_db_query("SELECT orders_id FROM orders WHERE customers_id = '" . $customer->selectline_customers_id  . "' AND orders_id='" . $oid . "'; ");	
	if($oR = push_db_fetch_array($oQ))
	{
		$pass = true;
	}
	else
	{
		$pass = false;	
	}

}
if(false && !$pass)
{
	if (sizeof($navigation->snapshot) > 0)
	{
		$origin_href = push_href_link($navigation->snapshot['page'], push_array_to_string($navigation->snapshot['get'], array(push_session_name())), $navigation->snapshot['mode']);
		$navigation->clear_snapshot();
		push_redirect($origin_href);
	}
	else
	{
		if(isset($_POST['redirectto']) && ($_POST['redirectto']<>'') )
		{
			push_redirect(push_href_link($_POST['redirectto']));
		}else{
			push_redirect(push_href_link(FILENAME_DEFAULT));
		}
	}
}
$breadcrumb->reset();

$breadcrumb->add('Konto', push_href_link(FILENAME_ACCOUNT, '', 'SSL'));
$breadcrumb->add('Bestellungen', push_href_link(FILENAME_ACCOUNT_HISTORY, '', 'SSL'));
$breadcrumb->add('Reklamation', push_href_link(FILENAME_ACCOUNT_COMPLAINT, 'oID=' . $oid, 'SSL'));
  
require(DIR_WS_BOXES . 'html_header.php');

//include(DIR_WS_BOXES . 'static_menu.php'); 
?>

<script type="text/javascript" src="javascript/jquery.html5uploader.min.js"></script>
<script type="text/javascript">
$(function() {
	var fileTemplate="<div id=\"{{id}}\"><div class=\"closer\" style=\"float:right;text-decoration:underline;cursor:pointer;margin:10px;color:#660033\">x entfernen</div>";
	fileTemplate+="<div class=\"preview\" style=\"width:102px;float:left;\"></div>";
	fileTemplate+="<div class=\"clearfix\"></div><div class=\"progressbar\"></div>";
	fileTemplate+="<div class=\"filename\">{{filename}}</div>";
	fileTemplate+="<input type=\"hidden\" name=\"bild[]\" value=\"{{filename}}\" />";
	fileTemplate+="</div>";
	function slugify(text)
	{	
		text=text.replace(/[^-a-zA-Z0-9,&\s]+/ig,'');
		text=text.replace(/-/gi,"_");
		text=text.replace(/\s/gi,"-");
		return text;
	}
	$("#multiple").html5Uploader({
		name: "foto",
		postUrl: "includes/modules/ajax/upload.php"	,
		onClientLoadStart:function(e,file){
			var upload=$("#upload");
			if(upload.is(":hidden"))
			{
				upload.show();
			}
			upload.append(fileTemplate.replace(/{{id}}/g,slugify(file.name)).replace(/{{filename}}/g,file.name));
			$(".closer").on("click",function(){$(this).parent().remove() });
		},
		onClientLoad:function(e,file){
				console.log(e);
				$("#"+slugify(file.name)).find(".preview").append("<img src=\""+e.target.result+"\" alt=\"\">");
		},
		onServerLoadStart:function(e,file){
				$("#"+slugify(file.name)).find(".progressbar").progressbar({value:0});
		},
		onServerProgress:function(e,file){
				if(e.lengthComputable){
					var percentComplete=(e.loaded/e.total)*100;
					$("#"+slugify(file.name)).find(".progressbar").progressbar({value:percentComplete});}
		},
		onServerLoad:function(e,file){
				$("#"+slugify(file.name)).find(".progressbar").progressbar({value:100});
		}
	});
	
	
			
});

</script>

<!-- body_text //-->
<div class="grid_8 ">
	<h1>Reklamation</h1>
	<span class="tx_13_20"> Sie sind mit Ihrer Lieferung nicht zufrieden? Das tut uns leid. 
Bitte helfen Sie uns, gemeinsam eine schnelle Lösung zu finden. Folgende Informationen benötigen wir daher von Ihnen:</span>
	<div class="gray-frame" style="margin-top:40px;position:relative;">
		<form id="form-complaint" name="form-question" class="defaultForm " method="post"  enctype="multipart/form-data">
			<input type="hidden" name="productid" value="<?php echo $product->id;?>">
			<label>Bestellung Nr.<span class="tx_red">*</span></label>
			<div class="input" id="input_ordernr">
				<div class="dselect">
				<select name="ordernr" id="ordernr" class="tx_12_15" style="width:260px;">
					<option value="Bitte wählen ...">Bitte wählen ...</option>
				<?php
					$orQ=push_db_query("SELECT orders_id FROM orders WHERE customers_id = '" . $customer->selectline_customers_id . "' ORDER BY date_purchased DESC");
					while($orR = push_db_fetch_array($orQ))
					{
						echo '<option value="' . $orR['orders_id'] . '" ' . (((int)$_GET['oID']==$orR['orders_id'])?'selected="selected" ':'') . '>' . $orR['orders_id'] . '</option>';	
					}
				?>
				</select>
				</div>
				<div class="error-msg">
					Wählen Sie eine Bestellung aus.
				</div>
			</div>
			<label>Lieferschein Nr.<span class="tx_red">*</span></label>			
			<div class="input" id="input_cargo">
				<input type="text" value="" name="cargo">
				<div class="error-msg">
					Geben Sie die Lieferschein Nr an.
				</div>
			</div>

			<label>Position<span class="tx_red">*</span></label>
			<div class="input" id="input_position">
			<input type="text" value="" name="position">
			<div class="error-msg">
				Geben Sie die Position an.
			</div>
			</div>

			<label>Artikel Nr.</label>
			<div class="input" id="input_artnr">
				<input type="text" value="" name="artnr">
			</div>
			<label>Bezeichnung</label>
			<div class="input" id="input_name">
				<input type="text" value="" name="name">
			</div>
			<label>Menge<span class="tx_red">*</span></label>
			<div class="input" id="input_quantity">
				<input type="text" value="" name="quantity">
				<div class="error-msg">
					Geben Sie die Menge an.
				</div>
			</div>

			<label>Reklamationsgrund<span class="tx_red">*</span></label>
			<div class="input" id="input_reason">
				<div class="dselect">
				<select name="reason" id="reason" class="tx_12_15" style="width:260px;">
					<option value="Bitte wählen ...">Bitte wählen ...</option>
					<option value="Ware kaputt geliefert">Ware kaputt geliefert</option>
					<option value="MHD zu kurz">MHD zu kurz</option>
					<option value="Ware ist nicht die, die bestellt wurde">Ware ist nicht die, die bestellt wurde</option>
					<option value="Die Ware wurde von mir falsch bestellt">Die Ware wurde von mir falsch bestellt</option>
					<option value="Sonstiges">Sonstiges</option>
				</select>
				</div>
				<div class="error-msg">
					Wählen Sie eine Reklamationsgrund aus.
				</div>
			</div>
			
			<label>Bemerkung zur Reklamation</label>
			<div class="input" id="input_annotation">
				<textarea name="annotation"></textarea>
			</div>

			<label>Welche Lösung wünschen Sie sich?</label>
			<div class="input" id="input_solution">
				<textarea name="solution"></textarea>
			</div>
	
			<label>Bild-Upload</label>
			<div class="input" id="input_foto">
				<input id="multiple" type="file" accept="image/*" />
				<div id="upload"></div>
				<div class="tx_12_15"><br>
				Bei <strong>defekt</strong> gelieferter Ware laden Sie bitte hier entsprechende Fotos hoch.<br>
				Max. 2 MB, Format: *.jpg
				</div>
			</div>
			
			<label>MHD-Angabe</label>
				<div class="input" id="input_MHD">
					<input type="text" value="" name="MHD">
				</div>
	
			<label>Ansprechpartner<span class="tx_red">*</span></label>
			<div class="input" id="input_person">
				<input type="text" value="" name="person">
				<div class="error-msg">
					Geben Sie einen Ansprechpartner an.
				</div>
			</div>


			<label style="overflow:hidden;">Rückruf gewünscht am</label>
			<div id="input_date" class="input">
			<div id="rueckruf-datepicker" style="display: block; position: relative; width: 150px;float:left;">
			<input id="datepicker" type="text" name="date" style="width: 75px" value="TT.MM.JJJJ" onclick="this.value=''" onblur="if(this.value=='') this.value='TT.MM.JJJJ';">
			</div>			
			<div class="dselect" style="float:right;">
			<select name="timeslot" id="timeslot" style="width: 107px">
			<option id="defaultTimeslot" value="Zeitraum">Zeitraum</option>
			<option value="7:30-8:30">7:30-8:30</option>
			<option value="8:30-9:30">8:30-9:30</option>
			<option value="9:30-10:30">9:30-10:30</option>
			<option value="10:30-11:30">10:30-11:30</option>
			<option value="11:30-12:30">11:30-12:30</option>
			<option value="12:30-13:30">12:30-13:30</option>
			<option value="13:30-14:30">13:30-14:30</option>
			<option value="14:30-15:30">14:30-15:30</option>
			<option value="15:30-16:30">15:30-16:30</option>
			<option value="16:30-17:30">16:30-17:30</option>
			<option value="17:30-18:30">17:30-18:30</option>
			</select>
			</div>
			<div class="error-msg">
			Geben Sie bitte ein gültiges Datum und Zeit ein.
			</div>
			</div>       
			<label>Rückrufnummer</label>			
			<div class="input" id="input_phone">
				<input type="text" value="" name="phone">
			</div>
			<div style="text-align: right; padding: 5px 32px 0 0" class="tx_12_15"><span class="tx_red">*</span>Pflichtfelder</div>
		
		 	<label></label>
			<div style="padding: 15px 0 0 8px" class="input" id="send-button-container"><input type="submit" name="submit" value="Reklamation senden" class="submitBtn w130 darkblue tx_12_15"></div>	
		</form>

		<script type="text/javascript">
		$(function()
		{
				
			$( "#datepicker" ).datepicker({
				showOn: "button",
				buttonImage: "images/push/calendar-icon.png",
				dateFormat: "dd.mm.yy"
			});
			$("input[type='text']").on("blur",function(){$(this).parent().removeClass("error");});
			$(".dselect").on("change",function(){$(this).parent().removeClass("error");});
			$('#form-complaint').submit(function(evt)
			{
				evt.preventDefault();
				var errors = new Array();
				if(this.ordernr.value == "Bitte wählen ...")
					errors.push('ordernr');
				if ( this.cargo.value == '' )
					errors.push('cargo');
				if ( this.position.value == '' )
					errors.push('position');
				if ( this.quantity.value == '' )
					errors.push('quantity');
				if(this.reason.value == "Bitte wählen ...")
					errors.push('reason');
				if ( this.person.value == '' )
					errors.push('person');
				if (this.date.value != "TT.MM.JJJJ")
				{
					if (!checkDate(this.date))
					{
						errors.push('date');
					}
				}
				$('.input').each(function() {
					$(this).removeClass('error');
				});
				if (errors.length == 0)
				{
				   submitQForm();
				}
				$.each(errors, function(index,value){
				  $('#input_' + value).addClass('error');
				});
				return false;
			});
			
			$('#datepicker').val("TT.MM.JJJJ");
			$('#datepicker').change(function() {
				// enable all timeslots
				$('#timeslot option:disabled').each(function()
				{
					$(this).removeAttr('disabled');
				});
				// reset timeslot
				$('#defaultTimeslot').prop("selected", true);
				if (!($(this).val() == '' || $(this).val() == "TT.MM.JJJJ"))
				{
					if (checkDate(document.getElementById("datepicker")))
					{
						if (isToday($(this).val()))
						{
							var now = new Date();						
							var timeslot = new Date();
							timeslot.setHours(6, 30, 0, 0);
							$('#timeslot option').each(function()
							{
								if ($(this).attr("id") != "defaultTimeslot" && timeslot < now)
								{
									$(this).prop("disabled", true);
								}
								timeslot.setHours(timeslot.getHours() + 1);
							});
						}
					}
				}
			});

			function isToday(dateString)
			{
				re = /^(\d{1,2})\.(\d{1,2})\.(\d{4})$/; 
				if(regs = dateString.match(re))
				{
					var today = new Date();
					var date = new Date(regs[3], regs[2] - 1, regs[1]);
					if (date.setHours(0,0,0,0) == today.setHours(0,0,0,0))
					{
						return true;
					}
				}
				return false;
			}
                
		function checkDate(field)
		{
			var date = new Date();
			var minYear = date.getUTCFullYear();
			var maxYear = 2099;
			var minMonth = date.getUTCMonth() + 1;
			var minDay = date.getUTCDate();
			re = /^(\d{1,2})\.(\d{1,2})\.(\d{4})$/;
			if(field.value != '')
			{
				if(regs = field.value.match(re))
				{
					if(regs[1] < 1 || regs[1] > 31)
					{
						return false;
					}
					else if(regs[2] < 1 || regs[2] > 12)
					{ 
						return false;
					}
					else if(regs[3] < minYear || regs[3] > maxYear)
					{
						return false;
					}
					else
					{
						if (regs[3] == minYear)
						{ 
							if (regs[2] == minMonth)
							{
								if (regs[1] < minDay)
								{
									return false;
								}
							}
							else if (regs[2] < minMonth)
							{
								return false;
							}
						}
					}
				}
				else
				{ 
					return false;
				} 
			}
			else 
			{ 
				return false;
			}
			if($('select[name="timeslot"]').val() == "Zeitraum")
			{
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
				$(".gray-frame").prepend("<div class=\"frame_loading\">&nbsp;</div>");
				$(".frame_loading").fadeTo(0,0.3);
				$.post("<?=DIR_WS_MODULES."ajax/"?>sendcomplaint.php",$("#form-complaint").serialize()).done(function(data)
				{
					if(data == 'true')
					{
						//$("input[name='captcha_code']").val('')
						//$(".green_success").hide();
						$("#form-complaint textarea").val('');
						$("#form-complaint input[type='text']").val('');
						$("#form-complaint input[type='file']").val('');
						$("#upload").html("");
						$('#datepicker').val("TT.MM.JJJJ");
						//$("#form-complaint").reset();
						$(".frame_loading").remove();
						$('#question_success').show();
					}
					});				
			}
        </script>
	</div>
	<div id="question_success" style="display:none">Ihre Anfrage wurde erfolgreich gesendet.</div>
</div>


<!-- body_text_eof //-->

<!-- footer //-->
<?php require(DIR_WS_BOXES . 'html_footer.php'); ?>
<!-- footer_eof //-->

<?php require(DIR_WS_LIB . 'end.php'); ?>