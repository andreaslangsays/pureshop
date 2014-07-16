<?php 
//ÖA DW-UTF8-BUG 

$numProdOnPage = 15;
if(is_object($wishList))
{
	if ($customer->login)
	{
	$numProducts = $wishList->count_wishlist();
	}
}
?>
<div id="sortiment">
	<img src="<?= ($customer->login && $numProducts > 0) ? 'images/push/icons/ico_wishlist-filled.png' : 'images/push/sortiment.png' ?>" alt="" /> Sortiment
	<span class="selectarrow" style="position:absolute;top:22px;right:15px;">&nbsp;&nbsp;&nbsp;</span>
</div>
<?php
if(is_object($wishList))
{
?>
<div id="sortiment_info">
	<?php if ($customer->login) { ?>
			<div class="tx_15_20" style="margin-bottom: 15px">
				<?= $numProducts ?> Artikel		
			</div>
			<?php 
				if ($numProducts > 0) {
					$numOfPages = (int) ($numProducts / $numProdOnPage);
					$numOfPages += ($numProducts % $numProdOnPage == 0 ? 0 : 1);
					$pagesDivWidth = $numOfPages * 130;
			?>
					<div id="sortiment-info-gallery">
						<div id="sortiment-box-products">
							<div id="sortiment-box-pages" style="width: <?= $pagesDivWidth ?>px">
								<?php 
									reset($wishList->wishID);
								
									$cc = 0;
									while (list($_SESSION['wishlist_id'], ) = each($wishList->wishID)) {		
														
										if ($_SESSION['wishlist_id'] != "") {	// there are sometimes empty products IDs in db, don't know why...
										
											if ($cc % $numProdOnPage == 0) {
								?>
												<div class="sortiment-box-page"<?= $cc == 0 ? ' id="sortiment-box-first-page"' : '' ?>>
										<?php
											}
															
												$p->load_product( push_get_prid($_SESSION['wishlist_id']));							
										?>
												<a href="<?= push_href_link(FILENAME_PRODUCT_INFO, 'products_id=' . $_SESSION['wishlist_id'], 'NONSSL'); ?>" title="<?= $p->products_name ?>">
													<div class="sortiment-box-product"<?= $cc % 3 == 2 ? " style=\"margin-right: 0px\"" : "" ?>>										
															<img src="<?= DIR_WS_IMAGES . $p->get_image('sortiment',30) ?>" alt="<?= $p->products_name ?>" title="<?= $p->products_name ?>" />
													</div>
												</a>
										<?php
											$cc++;
											if ($cc % $numProdOnPage == 0) {
										?>
												</div>
								<?php
											}
										}
									}	
									if ($cc % $numProdOnPage != 0) {
								?>
										</div>
								<?php
									}		
								?>
							</div>
						</div>
						<div id="sortiment-box-pages-control"<?= $numProducts <= $numProdOnPage ? ' style="display: none"' : '' ?>>
							<a id="sortiment-box-prev-page" class="button w40 gradientgrey tx_12_15"></a>
							<a id="sortiment-box-next-page" class="button w40 gradientgrey tx_12_15"></a>
						</div>
					</div>
			<?php
				}
			?>
			<?php if($customer->login){?><a class="button w110 darkblue tx_white tx_12_15" href="<?= push_href_link(FILENAME_WISHLIST,'','SSL'); ?>" title="Zum Sortiment" style="margin-top: 10px; border: 1px solid #4195D5">Zum Sortiment <img style="position: absolute; right: 11px; top: 11px" src="images/push/icons/ico_arrow-fw_S-double_white.png"></a><?php }?>
	<?php }	else { ?>
			<div class="tx_13_20" style="margin-bottom: 15px">
				Um Ihr Sortiment zu bearbeiten oder zusammenzustellen müssen Sie angemeldet sein.
			</div>
			<a class="button w110 darkblue tx_white tx_12_15" style="border: 1px solid #4195D5" title="Anmelden" href="<?= push_href_link(FILENAME_LOGIN,'','SSL') ?>">Anmelden <img style="position: absolute; right: 11px; top: 11px" src="images/push/icons/ico_arrow-fw_S-double_white.png"></a>
			<div class="tx_13_20 top_border" style="margin-top: 20px; padding-top: 15px">Noch kein Konto?</div>
			<a class="tx_12_15 tx_blue" title="Gleich registrieren!" href="<?= push_href_link(FILENAME_LOGIN,'','SSL') ?>">Gleich registrieren!</a>
	<?php } ?>
</div>
<?php
}
else
{
?>
<div id="sortiment_info">
<?php if($customer->login){?>
	<div class="tx_15_20" style="margin-bottom: 15px"><br>
		Ihr Sortiment ist noch leer.
	</div>
	<a class="button w110 gradientgrey tx_12_15" href="<?= push_href_link(FILENAME_WISHLIST); ?>" title="Zum Sortiment" style="margin-top: 10px">Zum Sortiment</a>
<?php }
else
{
?>
			<div class="tx_13_20" style="margin-bottom: 15px">
				Um Ihr Sortiment zu bearbeiten oder zusammenzustellen müssen Sie angemeldet sein.
			</div>
			<a class="button w110 darkblue tx_white tx_12_15" style="border: 1px solid #4195D5" title="Anmelden" href="<?= push_href_link(FILENAME_LOGIN,'','SSL') ?>">Anmelden <img style="position: absolute; right: 11px; top: 11px" src="images/push/icons/ico_arrow-fw_S-double_white.png"></a>
			<div class="tx_13_20 top_border" style="margin-top: 20px; padding-top: 15px">Noch kein Konto?</div>
			<a class="tx_12_15 tx_blue" title="Gleich registrieren!" href="<?= push_href_link(FILENAME_LOGIN,'','SSL') ?>">Gleich registrieren!</a>
	<?php } ?>
</div>
	
<?php
}
	?>

