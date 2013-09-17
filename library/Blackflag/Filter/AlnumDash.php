<?php
/**
 * Defined by Zend_Filter_Interface
 *
 * Allows only AlphaNumeric characters and a '-'.  Has switch to also allow whitespace chars.
 * This is the es
 *
 * @param  string $value
 * @return string
 */
class Blackflag_Filter_AlnumDash extends Zend_Filter_Alnum
{
    /**
     * Defined by Zend_Filter_Interface
     *
     * Returns the string $value, removing all but alphabetic and digit characters
     *
     * @param  string $value
     * @return string
     */
    public function filter($value)
    {
        $whiteSpace = $this->allowWhiteSpace ? '\s' : '';
        if (!self::$_unicodeEnabled) {
            // POSIX named classes are not supported, use alternative a-zA-Z0-9 match
            $pattern = '/[^a-zA-Z0-9-' . $whiteSpace . ']/';
        } else if (self::$_meansEnglishAlphabet) {
            //The Alphabet means english alphabet.
            $pattern = '/[^a-zA-Z0-9-'  . $whiteSpace . ']/u';
        } else {
            //The Alphabet means each language's alphabet.
            $pattern = '/[^\p{L}\p{N}-' . $whiteSpace . ']/u';
        }

        $value = preg_replace($pattern, '', (string) $value);
		return strtolower($value);
    }
}

