<?php

namespace App\Types;

use Doctrine\DBAL\Types\JsonType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use function is_resource;
use function json_decode;
use function stream_get_contents;

/**
 * Array Type which can be used to generate json arrays.
 *
 * @deprecated Use JsonType instead
 */
class CustomJsonbType extends JsonType
{
    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value === '') {
            return [];
        }

        $value = is_resource($value) ? stream_get_contents($value) : $value;

        return is_string($value) ? json_decode($value, true) : $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'cjsonb';
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
