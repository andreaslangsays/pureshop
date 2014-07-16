
<?php
			//we need all categories with parent-categories_id = 0
			$cc=0;
			$q = push_db_query("SELECT cg.categories_name , cg.categories_id FROM categories_description cg JOIN categories c ON (cg.categories_id = c.categories_id) WHERE c.parent_id = 0  AND language_id = '" . (int)$languages_id . "' ORDER BY cg.categories_id");
			while($r = push_db_fetch_array($q))
			{
				$cc++;
				if($cc==1)
				{
					echo '<ul class="mmenu grid_3">';
				}
				elseif( ($cc == 4) || ($cc == 6) || ($cc == 7) || ($cc == 8) )
				{
					echo '</ul><ul class="mmenu grid_3">';
				}
				echo "	<li class=' cat_" . $r['categories_id'] . " tx_13_30 mmenu' data-sel='" . $r['categories_id'] . "'><a class='main_cat' href='" . push_href_link(FILENAME_DEFAULT, 'cPath=' . $r['categories_id'] .'_' .$sr['categories_id'],'NONSSL') . "'>" . trim($r['categories_name']) . "<a>\n";
				
				$sc = push_db_query("SELECT cg.categories_name , cg.categories_id FROM categories_description cg JOIN categories c ON (cg.categories_id = c.categories_id) WHERE c.parent_id = '" . $r['categories_id'] . "' AND language_id = '" . (int)$languages_id . "' ORDER BY cg.categories_name");
				echo "<ul>";
				while($sr = push_db_fetch_array($sc) )
				{
					$pq=push_db_query("SELECT p.products_id FROM products_to_categories p2c JOIN products p ON p.products_id = p2c.products_id WHERE p.products_status = 1 AND p2c.categories_id IN ( SELECT c.categories_id FROM categories c WHERE  c.parent_id = '" . $sr['categories_id'] . "' OR c.categories_id='" . $sr['categories_id'] . "') ");
					if($apt=push_db_fetch_array($pq))
					{
						echo "		<li class='subcat tx_13_20' data-sel='" . $sr['categories_id'] . "'><a href='" . push_href_link(FILENAME_DEFAULT, 'cPath=' . $r['categories_id'] .'_' .$sr['categories_id'],'NONSSL') . "'>" . trim($sr['categories_name']) . "</a></li>\n";
					}
				}
				echo "
				</ul>
				</li>";
			}
			 
		?>
</ul>