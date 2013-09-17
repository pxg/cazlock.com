<?php
/**
 * Make a byte value human-readable
 */
class BlackFlag_View_Helper_HumanFilesize
{
    /**
     * Converts bytes to a human-readable format.
     *
     * @param int $bytes
     * @return string
     */
    public function HumanFilesize($bytes)
    {
        $unit = 0;
        $iec = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        while ($bytes / 1024 > 1) {
            $bytes /= 1024;
            $unit++;
        }

        if ($unit == 0) {
            return $bytes . ' ' . $iec[$unit];
        } else {
            return number_format($bytes, 1, '.', '') . ' ' . $iec[$unit];
        }
    }
}