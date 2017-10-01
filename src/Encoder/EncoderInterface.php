<?php
namespace Hooloovoo\Framework\Encoder;

/**
 * Interface EncoderInterface
 */
interface EncoderInterface
{
    /**
     * @param array $data
     * @return string
     */
    public function encode(array $data) : string ;

    /**
     * @param string $data
     * @return array
     */
    public function decode(string $data) : array ;

    /**
     * @return string
     */
    public function getType() : string ;
}