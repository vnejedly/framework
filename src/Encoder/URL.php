<?php
namespace Hooloovoo\Framework\Encoder;

/**
 * Class URL
 */
class URL implements EncoderInterface
{
    const TYPE = 'x-www-form-urlencoded';

    /**
     * @param array $data
     * @return string
     */
    public function encode(array $data) : string
    {
        $params = [];
        foreach ($data as $name => $value) {
            $params[] = $name . '=' . $value;
        }

        return implode('&', $params);
    }

    /**
     * @param string $data
     * @return array
     */
    public function decode(string $data) : array
    {
        parse_str($data, $params);

        $result = [];
        foreach ($params as $name => $value) {
            $result[$name] = $value;
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getType() : string
    {
        return self::TYPE;
    }
}