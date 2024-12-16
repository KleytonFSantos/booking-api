<?php

namespace App\Entity\Base;

interface EntityBaseInterface
{
    /**
     * @ORM\PrePersist
     *
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void;

    public function getCreatedAt(): ?\DateTime;

    public function setCreatedAt(\DateTime $createdAt): self;

    public function getUpdatedAt(): ?\DateTime;

    public function setUpdatedAt(\DateTime $updatedAt): self;
}
