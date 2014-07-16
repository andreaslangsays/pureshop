<?php
//set the parameters as GET-Variables 
//$listing_split = new splitPageResults($listing_sql, $epp, 'p.products_id');

$productsPerPage = array(20, 60, 100, 1000);

if  ($listing_split->number_of_rows > $productsPerPage[0]) {
?>
<div class="grid_12 alpha top_separator tx_12_15" style="padding: 7px 0 7px 0; margin-top: 20px">
	<?php		
			echo push_draw_form('sortieren', push_href_link(basename($_SERVER['SCRIPT_NAME']), $parameters), 'post' , 'id="sortandorderbottom" class="product-listing-navi-bottom"');	
	?>
			<div class="grid_8 alpha" style="position: relative">
				<div style="padding: 6px 3px 0 0; float: left">Artikel pro Seite:</div>
			<?php				
				foreach ($productsPerPage as $ppp) {
					if ($epp == $ppp) {
				?>
						<div class="select-epp-active tx_12_15" title="<?= $ppp == 1000 ? 'Alle' : $ppp ?> Artikel pro seite"><?= $ppp == 1000 ? 'Alle' : $ppp ?></div>
				<?php
					} else {
				?>
						<input type="submit" class="select-epp-inactive tx_blue tx_12_15" name="epp" value="<?= $ppp == 1000 ? 'Alle' : $ppp ?>" title="<?= $ppp == 1000 ? 'Alle' : $ppp ?> Artikel pro seite">
				<?php
					}
				}
			?>
			</div>
			<?php
				if ($listing_split->number_of_pages > 1) {
			?>
					<div class="grid_4 omega" style="position: relative">
						<?php  
							// page select 
						?>
						<div style="padding-left: 30px; line-height: 30px">
						Seite 
						<input class="pageselector" type="text" name="psel" value="<?= $listing_split->current_page_number ?>" width="3" maxlength="3" /> 
						von <?= ($epp == 1000) ? '1' : $listing_split->number_of_pages ?>
					</div>
					<?php
						if ($epp != 1000) echo $listing_split->display_links(MAX_DISPLAY_PAGE_LINKS, push_get_all_get_params(array('page', 'info', 'x', 'y'))); 
					?>
					</div>
			<?php
				}
			?>
		</form>
</div>
<?php 
}
?>