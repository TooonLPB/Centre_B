<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $UserId = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Menu $MenuId = null;


    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_and_time_of_order = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Plat $Type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->UserId;
    }

    public function setUserId(?User $UserId): static
    {
        $this->UserId = $UserId;

        return $this;
    }

    public function getMenuId(): ?Menu
    {
        return $this->MenuId;
    }

    public function setMenuId(?Menu $MenuId): static
    {
        $this->MenuId = $MenuId;

        return $this;
    }

    public function getDateAndTimeOfOrder(): ?\DateTimeInterface
    {
        return $this->date_and_time_of_order;
    }

    public function setDateAndTimeOfOrder(\DateTimeInterface $date_and_time_of_order): static
    {
        $this->date_and_time_of_order = $date_and_time_of_order;

        return $this;
    }

    public function getType(): ?Plat
    {
        return $this->Type;
    }

    public function setType(?Plat $Type): static
    {
        $this->Type = $Type;

        return $this;
    }
}
