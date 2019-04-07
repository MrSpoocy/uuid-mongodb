<?php
/**
 * User: mpogge
 */

declare(strict_types=1);

namespace MrSpoocy\Doctrine\ODM\MongoDB\Mapping\Annotations;

use MrSpoocy\Doctrine\ODM\MongoDB\Id\UuidGenerator;
use Doctrine\ODM\MongoDB\Mapping\Annotations\AbstractField;

/**
 * @Annotation
 */
class Uuid extends AbstractField
{
    public $id = true;
    public $type = 'bin_uuid_rfc4122';
    public $strategy = 'custom';
    public $options = [
        'class' => UuidGenerator::class
    ];
}