<?php
namespace Hooloovoo\Framework\Encoder;

/**
 * Class JSON
 */
class JSON implements EncoderInterface
{
    const TYPE = 'json';

    /**
     * @param array $data
     * @return string
     */
    public function encode(array $data) : string
    {
        return json_encode($data);
    }

    /**
     * @param string $data
     * @return array
     */
    public function decode(string $data) : array
    {
        return (array) json_decode($data, true);
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return self::TYPE;
    }
}