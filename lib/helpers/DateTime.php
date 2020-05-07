<?php

namespace helpers;

/**
 * Class DateTime
 * 
 * @package helpers
 */
trait DateTime
{
    /**
     * Get current timestamp
     *
     * @param string $timezone
     * @return int
     */
    public function getCurrentTimestamp($timezone = 'Europe/Kiev')
    {
        $dt = $this->_getDateTimeObject($timezone);
        return $dt->getTimestamp();
    }

    /**
     * Get datetime by timestamp
     *
     * @param $timestamp
     * @param string $timezone
     * @return string
     */
    public function getDateTime($timestamp, $timezone = 'Europe/Kiev')
    {
        $dt = $this->_getDateTimeObject($timezone);
        $dt->setTimestamp((int) $timestamp);
        return $dt->format('Y-m-d H:i');
    }

    /**
     * Get php datetime class with application timezone
     *
     * @param string $timezone
     * @return \DateTime
     */
    protected function _getDateTimeObject($timezone = 'Europe/Kiev')
    {
        $dtTimezone = new \DateTimeZone($timezone);
        return new \DateTime('now', $dtTimezone);
    }
}