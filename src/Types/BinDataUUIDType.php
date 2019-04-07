<?php
/**
 * User: mpogge
 */

declare(strict_types=1);

namespace MrSpoocy\Doctrine\ODM\MongoDB\Types;

use Doctrine\ODM\MongoDB\Types\BinDataType;
use Doctrine\ODM\MongoDB\Types\ClosureToPHP;
use LogicException;
use MongoBinData;
use MongoDB\BSON\Binary;
use MongoDB\Driver\Exception\UnexpectedValueException;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use function get_class;
use function gettype;
use function is_integer;
use function is_object;
use function sprintf;
use function strlen;

class BinDataUUIDType extends BinDataType
{
    use ClosureToPHP;

    /**
     * @param mixed $value
     *
     * @return mixed|Binary|UuidInterface|null
     */
    public function convertToDatabaseValue($value)
    {
        // \dump(__METHOD__ . '::' . (is_object($value) ? get_class($value) : gettype($value)));

        if ($value === null) {
            return null;
        }

        // Umwandeln in UuidInterface (später wird noch Version geprüft)
        if (is_string($value)) {
            $length = strlen($value);
            if ($length === 32 || $length === 36) {
                $value = Uuid::fromString($value);
            } else {
                $value = Uuid::fromBytes($value);
            }
        } elseif (is_integer($value)) {
            $value = Uuid::fromInteger($value);
        }

        if ($value instanceof UuidInterface) {
            if ($value->getVersion() !== Uuid::UUID_TYPE_RANDOM) {
                throw new UnexpectedValueException(sprintf('Ungültiger Binary-Type "%d", erlaubt: "%d"', $value->getVersion(), Uuid::UUID_TYPE_RANDOM));
            }

            return new Binary($value->getBytes(), Binary::TYPE_UUID);
        } elseif ($value instanceof MongoBinData) {
            if ($value->type !== MongoBinData::UUID_RFC4122) {
                throw new UnexpectedValueException(sprintf('Ungültiger Binary-Type "%d", erlaubt: "%d"', $value->type, MongoBinData::UUID_RFC4122));
            }

            return new Binary($value->bin, MongoBinData::UUID_RFC4122);
        } elseif ($value instanceof Binary) {
            if ($value->getType() !== Binary::TYPE_UUID) {
                throw new UnexpectedValueException(sprintf('Ungültiger Binary-Type "%d", erlaubt: "%d"', $value->getType(), Binary::TYPE_UUID));
            }

            return $value;
        }

        throw new UnexpectedValueException(sprintf('Ungültiger Typ "%s"', is_object($value) ? get_class($value) : gettype($value)));
    }

    /**
     * @param mixed $value
     *
     * @return mixed|UuidInterface|string|null
     */
    public function convertToPHPValue($value)
    {
        // \dump(__METHOD__ . '::' . (\is_object($value) ? \get_class($value) : \gettype($value)));
        if ($value === null) {
            return null;
        }

        if ($value instanceof UuidInterface) {
            return $value;
        } elseif ($value instanceof Binary) {
            return Uuid::fromBytes($value->getData());
        } elseif ($value instanceof MongoBinData) {
            return Uuid::fromBytes($value->bin);
        } else {
            throw new UnexpectedValueException(sprintf('Ungültiger Typ "%s"', is_object($value) ? get_class($value) : gettype($value)));
        }
    }

    public function closureToMongo()
    {
        throw new LogicException(sprintf('"%s" hat die Methode "%s" nicht Implementiert', self::class, __METHOD__));
    }
}