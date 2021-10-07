<?php
namespace BA\BasysCatalog\Api\Data;

interface ChecksumInterface
{
    /**
     * Return calculated CRC checksum
     * 
     * @return int
     */
    public function getChecksum();

    /**
     * Get checksum column names
     * 
     * @return array
     */
    public function getChecksumColumnNames();
}