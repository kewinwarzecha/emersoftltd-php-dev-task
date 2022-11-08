<?php

namespace App\Entity;

use App\Repository\CsvFileRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CsvFileRepository::class)]
class CsvFile extends AbstractEntity
{

    #[ORM\Column(length: 512)]
    private ?string $path = null;

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }
}
