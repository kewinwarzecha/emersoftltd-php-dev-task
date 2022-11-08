<?php

namespace App\Entity;

use App\Entity\Trait\Identifiable;
use App\Entity\Trait\Timestampable;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
#[HasLifecycleCallbacks]
abstract class AbstractEntity
{
    use Identifiable;
    use Timestampable;
}
