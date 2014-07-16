<form id="quick-find" class="form-in-general" action="<?php echo push_href_link(FILENAME_DEFAULT); ?>" method="get">
		<span id="ctoggle" class="cselection gradientlight"><span id="cname">Alle Kategorien</span><span class="selectarrow" style="position:absolute;right:-15px;top:10px;">&nbsp;&nbsp;&nbsp;</span></span>
		
		<ul id="categorieslist" style="display:none;">
			<li data-sel="all">Alle Kategorien</li>
		
		<?php
			//we need all categories with parent-categories_id = 0
			
			$q = push_db_query("SELECT cg.categories_name , cg.categories_id FROM categories_description cg JOIN categories c ON (cg.categories_id = c.categories_id) WHERE c.parent_id = 0 AND language_id = '" . (int)$languages_id . "' ORDER BY cg.categories_id");
			while($r = push_db_fetch_array($q))
			{
				echo "<li data-sel='" . $r['categories_id'] . "'>" . trim($r['categories_name']) . "</li>";
			}
			 
		?>
		</ul>
		<input type="hidden" id="selectedCategory" name="categories_id" value="all">
		<input id="quick-find-keywords" name="keywords" type="text" maxlength="30" placeholder="Produkt oder Artikel-Nr. suchen" /><input src="images/push/lupe.png"  type="image" value="Los" style="margin:-1px;padding:0;position:relative;top:-1px;height:30px;width:30px;" />
<?php /*<!--<a title="<?php echo BOX_SEARCH_ADVANCED_SEARCH; ?>" href="<?php echo push_href_link(FILENAME_ADVANCED_SEARCH); ?>" >erweitert &raquo;</a>-->*/ ?>
</form>
