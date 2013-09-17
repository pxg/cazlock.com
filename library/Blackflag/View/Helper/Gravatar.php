<?php
/**
 * Gravatar helper
 */
class BlackFlag_View_Helper_Gravatar
{
    /**
     * Return an avatar URL for the given email address
     *
     * @param  string  $emailAddress
     * @param  integer $size
     * @return string
     */
    public function gravatar($emailAddress, $size = 80)
    {
        $hash      = md5(strtolower($emailAddress));
        $avatarUrl = sprintf('http://gravatar.com/avatar/%s?r=g&s=%d', $hash, $size);

        return $avatarUrl;
    }
}