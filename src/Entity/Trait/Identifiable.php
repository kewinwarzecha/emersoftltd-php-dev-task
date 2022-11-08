<?php

namespace App\Entity\Trait;

use App\Enum\Group\BaseGroups;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

trait Identifiable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    #[Groups([BaseGroups::DEFAULT])]
    private int $id;

    public function getId(): int
    {
        return $this->id;
    }
}
