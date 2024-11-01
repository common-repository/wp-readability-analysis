<?php
	
    
// Admin functions
    
function readanalysis_add_meta_box() {
	add_meta_box('readanalysis', 'Readability Analysis', 'readanalysis_meta_box', 'post','side','high');
	add_meta_box('readanalysis', 'Readability Analysis', 'readanalysis_meta_box', 'page','side','high');
}


function readanalysis_meta_box() {
	global $wpdb;
	global $post;
	global $wp_version;

	if ($post->post_content != '') {
		$readability = new ReadAnalysis();
		$readability->set_text($post->post_content);
		$template =
			'<table width="100%%"><tr>'
			.'<td align="left" width="40%%">Sentences<br>%d</td> '
			.'<td align="left" width="15%%" title="Readability score between 0 (worst) and 100 (best).">Gulpease<br>%2.1f</td> '
			.'<td align="left" width="15%%" title="Readability score between 0 (best) and 100 (worst).">Fog<br>%2.1f</td> '
			.'<td align="left" width="15%%" title="Readability score between 0 (best) and 100 (worst).">Kincaid<br>%2.1f</td> '
			.'<td align="left" width="15%%" title="Readability score between 0 (worst) and 100 (best).">Flesch<br>%3.0f</td> '
			.'</tr></table>';
		printf($template,
			$readability->get_sentences(),
            $readability->get_Gulpease(),
			$readability->get_fog(),
			$readability->get_flesch_kincaid(),
			$readability->get_flesch()
		);
	}
}

?>