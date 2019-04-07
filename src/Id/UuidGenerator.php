<?php
/**
 * User: mpogge
 */

declare(strict_types=1);

namespace MrSpoocy\Doctrine\ODM\MongoDB\Id;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Id\AbstractIdGenerator;

class UuidGenerator extends AbstractIdGenerator
{
    /**
     * @param DocumentManager $dm
     * @param object          $document
     *
     * @return \Ramsey\Uuid\UuidInterface
     * @throws \Exception
     */
    public function generate(DocumentManager $dm, $document)
    {
        return \Ramsey\Uuid\Uuid::uuid4();
    }
}