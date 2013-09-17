<?php
class Blackflag_Validate_BadWords extends Zend_Validate_Abstract
{

	/*
	 *
	 */
	const MSG_URI = 'msgUri';


	/*
	 *
	 */
	protected $_commentTemplates = array(
		self::MSG_URI => "Bad word found: ",
	);
	
	
	/*
	 * Check against a single file only
	 */
	protected $rudeWordsFile = "/BadWords/emoderation.txt";
	
	
	/*
	 *
	 */
	protected $rudeWordsDir = "/BadWords/";
	
		
	/*
	 *
	 */
	public function isValid($value)
	{
		$this->_setValue($value);

		//Validate the string
		return $this->badLanguage($value);
	}
	
	
	
	/*
	 *
	 */
	private function badLanguage($string) {
		$final = array();
		//$obscenities = file(dirname(__FILE__) . $this->rudeWordsFile);
		$obscenities = $this->createObscenities();
		$words = $this->getWordSubstitutes($string);

		foreach ($obscenities as $curse_word) {
			$curse_word = $this->escapeCurseWord($curse_word);

			foreach ($words as $key => $word)
			{
				//$valid = false;
				$valid = preg_match("/\b$curse_word\b/i", $word);

				if ($valid) {
					//$this->_error();
					
					//echo 'BAD LANGUAGE FOUND: <strong>"' . $curse_word . '"</strong> found in <strong>"'.$word . '"</strong><br/>';
					// return true
					return array(
						"badLanguageFound" => true,
						"badLanguage" => $curse_word,
						"commentWithFlags" => str_replace($curse_word, '<span class="flag">'.$curse_word.'</span>', $word),
						"debug" => "Bad language found"
					);
				} 
			}
		}

		// passed validation, continue
		return array(
			"badLanguageFound" => false,
			"debug" => "Entry valid"
		);
	}


	
	/*
	 *
	 */
	private function escapeCurseWord($word) {
		
		// replace dodgy characters
		$word = trim($word);
		$word = preg_quote($word);
		$word = str_replace("/", "\/", $word);
		
		return $word;
	}


	/*
	 *
	 */
	private function getWordSubstitutes($string) {
		
		$words = array();
		$words[] = $string;

		$characters[] = '1';
		$replacements[] = 'i';
		$characters[] = '@';
		$replacements[] = 'a';
		$characters[] = '3';
		$replacements[] = 'e';
		$characters[] = '!';
		$replacements[] = 'i';
		$characters[] = '$';
		$replacements[] = 's';
		$characters[] = '5';
		$replacements[] = 's';
		$characters[] = '0';
		$replacements[] = 'o';

		// swap number for letters (regex or functions)
		$substitute = str_replace($characters, $replacements, $string);
		$words[] = $substitute;

		// drop "spaces" and other masking characters?
		$words[] = str_replace(" ", "", $string);
		$words[] = str_replace("_", "", $string);
		$words[] = str_replace("*", "", $string);
		$words[] = str_replace(".", "", $string);
		$words[] = str_replace("-", "", $string);

		// drop numbers (and anything not in alphabetic)
		$words[] = ereg_replace("[^A-Za-z]", "", $string);

		// drop duplicates from the array
		$words = array_unique($words);
		
		//echo "<pre>";
		//print_r($words);
		//echo "</pre>";

		return $words;
	}
	
	
	/*
	 *
	 */
	private function createObscenities() {
		
		$words = array();
		
		// Automatically pull in every file within the 'BadWords' directory
		$currentDir = dirname(__FILE__);
		$files = scandir($currentDir.$this->rudeWordsDir);
		foreach($files as $currentFile) {
			if (!is_dir($currentFile) && substr($currentFile, 0, 1) != ".") {
				$words = array_merge($words, file($currentDir.$this->rudeWordsDir.$currentFile));
			}
		}
		
		//echo "<pre>";
		//print_r($words);
		//echo "</pre>";
		
		return $words;
	}
}