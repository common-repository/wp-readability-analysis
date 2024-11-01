<?php
	
class ReadAnalysis {
    
    // set variables
	var $text;
	var $words;
	var $sentences;
	var $syllables;
	var $hardwords;
	var $avgwordspersentence;
	var $avgsyllablespersentence;
	var $avgsyllablesperword;

	// Init Text value to be analyzed
	function set_text($text) {
		$this->text = strtolower(trim(strip_tags($text)));
		$this->words = -1;
		$this->sentences = -1;
		$this->hardwords = -1;
		$this->avgwordspersentence = -1;
		$this->avgsyllablespersentence = -1;
		$this->avgsyllablesperword = -1;
	}
	
	// Get the text value
	function get_text() {
		return($this->text);
	}
    
    
    
    //******************************************************************************************
    // Functions to get text length, words, sentences, syllable, average words and syllable etc.
    
	function get_text_length() {
	    return strlen($this->text);
	}
	
	function get_words() {
		if ($this->text == '') { return(0); }
		if ($this->words != -1) { return($this->words); }
		$count = str_word_count($this->text);
		if ($count <= 0) {
			$count = 1; // This prevents division by zero
		}
		$this->words = $count;
		return($count);
	}
	
	function get_sentences() {
		if ($this->text == '') { return(0); }
		if ($this->sentences != -1) { return($this->sentences); }
		$count = count(preg_split('/(!|\.|\?)/', $this->text))-1;
		if ($count <= 0) {
			$count = 1;
		}
		$this->sentences = $count;
		return($count);
	}
	
	function get_syllables($mode = 0) {
		if ($this->text == '') { return(0); }
		if ($this->syllables != -1 && $this->hardwords != -1) {
			if ($mode == 0) {
				return($this->syllables);
			}
			if ($mode == 1) {
				return($this->hardwords);
			}
			if ($mode == 2) {
				return(array($this->syllables, $this->hardwords));
			}
		}
		
		$count = 0;
		$hardwords = 0;
		
		foreach(str_word_count($this->text, 1) as $word) {
			$wordcount = 0;
			$lastchar = '';
			$thischar = '';
			for($i=0;$i<strlen($word);$i++) {
				$thischar = substr($word, $i, 1);
				if (preg_match('/[aeiouy]/i', $thischar) && !preg_match('/[aeiouy]/i', $lastchar)) {
					$wordcount++;
				}
				$lastchar = $thischar;
			}
			
			// Special rules for english words
			if (substr($word, -2) == 'ed') {if (preg_match('/[aeiouy]ed$/i', $word)) {}	}
			if (substr($word, -3) == 'ism') { $wordcount++; }
			if (strstr($word, 'use')) { $wordcount--; }
			if (substr($word, -1) == 'e') { $wordcount--; }
			if (substr($word, -2) == 'es' && substr($word, -3) != 'ies') {$wordcount--; }
			if (substr($word, -4) == 'ying') { $wordcount++; }
			// ****** end special rules ******
			
			if ($wordcount <= 0) { $wordcount = 1; }
			
			$count += $wordcount;
			if ($wordcount >= 3) {
				$hardwords++;
			}
		}
		
		
		$this->syllables = $count;
		$this->hardwords = $hardwords;
		
		if ($mode == 0) {
			return($count);
		}
		if ($mode == 1) {
			return($hardwords);
		}
		if ($mode == 2) {
			return(array($count, $hardwords));
		}
	}
	
	function get_avg_words_per_sentence() {
		if ($this->text == '') { return(0); }
		if ($this->avgwordspersentence != -1) { return($this->avgwordspersentence); }
		$count = $this->get_words()/$this->get_sentences();
		$this->avgwordspersentence = $count;
		return($count);
	}
	
	function get_avg_syllables_per_sentence() {
		if ($this->text == '') { return(0); }
		if ($this->avgsyllablespersentence != -1) { return($this->avgsyllablespersentence); }
		$count = $this->get_syllables()/$this->get_sentences();
		$this->avgsyllablespersentence = $count;
		return($count);
	}
	
	function get_avg_syllables_per_word() {
		if ($this->text == '') { return(0); }
		if ($this->avgsyllablesperword != -1) { return($this->avgsyllablesperword); }
		$count = $this->get_syllables()/$this->get_words();
		$this->avgsyllablesperword = $count;
		return($count);
	}
	
    //*************************************************************************************************
    
    
    //*************************************************************************************************
    //                          Measurements Functions
    //*************************************************************************************************
    
	// The Flesch index (higher is better)
	function get_flesch() {
		if ($this->text == '') { return(0); }
		$L = $this->get_avg_words_per_sentence();
		$N = $this->get_avg_syllables_per_word();
		return(206.835 - $L*1.015 - $N*84.6);
	}
	
	// The Gunning-Fog index (lower is better)
	function get_fog() {
		if ($this->text == '') { return(0); }
		$W = $this->get_words();
		$S = $this->get_sentences();
		$T = $this->get_syllables(1);
		return(($W/$S + $T*100/$W) * 0.4);
	}
	
    // The Gulpease index (higher is better)
    function get_gulpease() {
		if ($this->text == '') { return(0); }
		$LP =$this->get_text_length();
        $nF=$this->get_sentences();
        $nP=$this->get_words();
		return(89 - (10 *$LP / $nP ) + (300*$nF/$nP));
	}
    
	// The Flesch-Kincaid index (lower is better)
	function get_flesch_kincaid() {
		if ($this->text == '') { return(0); }
		$L = $this->get_avg_words_per_sentence();
		$N = $this->get_avg_syllables_per_word();
		return($L*0.39 + $N*11.8 - 15.59);
	}
    //*************************************************************************************************
}

?>