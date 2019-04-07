<?php
/**
 * User: mpogge
 */

declare(strict_types=1);

namespace MrSpoocy\Doctrine\ODM\MongoDB;

use MrSpoocy\Doctrine\ODM\MongoDB\Types\BinDataUUIDType;
use Doctrine\ODM\MongoDB\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BinDataUUIDTypeBundle extends Bundle
{
    public function __construct()
    {
        // Wir überschreiben die Logik von Doctrine / MongoDB um auch Ramsey\Uuid\Uuid und UUID im String format zu unterstützen
        Type::overrideType(Type::BINDATAUUIDRFC4122, BinDataUUIDType::class);

        // Zusätzlich schaffen wir einen neuen Typ "Uuid" für leichteres handhaben
        if (!Type::hasType('uuid')) {
            Type::addType('uuid', BinDataUUIDType::class);
        }
    }
}