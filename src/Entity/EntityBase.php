<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

class EntityBase implements EntityBaseInterface
{
    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: true)]
    protected ?\DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: true)]
    protected ?\DateTime $updatedAt;

    #[ORM\PrePersist()]
    #[ORM\PreUpdate()]
    public function updatedTimestamps(): void
    {
        $dateTimeNow = new \DateTime('now');

        $this->setUpdatedAt($dateTimeNow);

        if (null === $this->getCreatedAt()) {
            $this->setCreatedAt($dateTimeNow);
        }
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
