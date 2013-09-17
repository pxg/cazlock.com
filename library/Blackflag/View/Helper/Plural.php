<?php
/**
 * Plural helper
 */
class Blackflag_View_Helper_Plural
{
    /**
     * Return either singular or plural depending on the given number
     *
     * @param  string        $singular
     * @param  string        $plural
     * @param  integer|float $number
     * @return string
     */
    public function plural($singular, $plural, $number)
    {
        $string = ($number === 1 || $number === 1.) ? $singular : $plural;
        
        return sprintf($string, $number);
    }
}