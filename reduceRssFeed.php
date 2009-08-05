<?php
/*
Plugin Name: reduceTheF***ingRssFeed
Plugin URI: 
Description: Reduce RSS-Content to a few words. Normaly 4 words are used, but you could configure at the option Page. 
Author: HeinzCooler
Version: 1.05 RC5
Author URI: http://www.heinzcooler.de
*/ 

//	Optionsmenue
function rss_cutter_option_page() {
	if('insert' == $_POST['action_rss_cutter']) {
			update_option("rss_cutter_count", $_POST['rss_cutter_count'] + 1);
	}
	$count = get_option('rss_cutter_count');
	if($count == 0){
		$count = 5;
	}
	?>
	<div class="wrap">
		<h2>RSS-Cutter</h2>
		<p>How many words do you want in your RSS-Feed?</p>
		<form name="form" method="post" action="<?= $location ?>">
			<input type="text" name="rss_cutter_count" value="<?= $count-1 ?>"/>
	  		<p class="submit"><input type="submit" value="Speichern" /></p>
	  		<input type="hidden" name="action_rss_cutter" value="insert"/>
	  	</form>
	</div><?php
}


/*
 * Einstellungsseite
 */
function rss_cutter_add_page() {
	add_submenu_page('plugins.php', 'RSS-Cutter', 'RSS-Cutter', 10, __FILE__ ,'rss_cutter_option_page');
}

/*
 * Filterfunktion
 */
function reduceTheRSSFeedPlease( $content ) {
    if( is_feed())
    {
    	$content = trim($content);
		$count = get_option('rss_cutter_count');
		if($count == 0) {
			$count = 5;
		}
    
    	// erste Zeile nehmen
		$hit = array();
		
		preg_match("@\<p\>(.*)$@mu", $content, $hit);
		
		if(isset($hit[1])) {
			$content = strip_tags($hit[1]);
		} else {
			return $content;
		}
		$contarr = explode(" ",$content,$count);
		if(count($contarr) == $count) {
			unset($contarr[$count-1]);
		}
		
		$content = implode(" ",$contarr);
    }
    return $content;
}

add_filter( 'the_content', 'reduceTheRSSFeedPlease');
add_action('admin_menu', 'rss_cutter_add_page')
?>