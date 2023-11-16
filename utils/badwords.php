<?php     
	// Helper function for adding something on database
	function isDataHaveBadWord($string) {
        // List of bad words, add more
        $badWords = array("gago","tanga","inutil","tarantado");

		// Convert string to lowercase for case-insensitive matching
		$string = strtolower($string);

		// Split the string into an array of words
		$words = explode(' ', $string);

		// Check if any bad words are present
		foreach ($words as $word) {
			foreach ($badWords as $badWord) {
				if (strpos($word, $badWord) !== false) {
					return true; // Bad word found
				}
			}
		}

		return false; // No bad words found
	}