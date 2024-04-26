<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MenuRepository::class)]
class Menu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255)]
    private ?string $service = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $optionPlat = null;

    #[ORM\ManyToOne(inversedBy: 'menus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plat $entree = null;

    #[ORM\ManyToOne(inversedBy: 'menus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plat $platUn = null;

    #[ORM\ManyToOne(inversedBy: 'menus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plat $platDeux = null;

    #[ORM\ManyToOne(inversedBy: 'menus')]
    private ?Plat $platTrois = null;

    #[ORM\ManyToOne(inversedBy: 'menus')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Plat $dessert = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantitéviande = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantitépoisson = null;

    #[ORM\Column(nullable: true)]
    private ?int $quantitévegi = null;

    /**
     * @var Collection<int, Commande>
     */
    #[ORM\OneToMany(targetEntity: Commande::class, mappedBy: 'MenuId')]
    private Collection $commandes;
    public function __construct()
    {
        $this->plats = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(string $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getOptionPlat(): ?string
    {
        return $this->optionPlat;
    }

    public function setOptionPlat(string $optionPlat): static
    {
        $this->optionPlat = $optionPlat;

        return $this;
    }

    public function getEntree(): ?Plat
    {
        return $this->entree;
    }

    public function setEntree(?Plat $entree): static
    {
        $this->entree = $entree;

        return $this;
    }

    public function getPlatUn(): ?Plat
    {
        return $this->platUn;
    }

    public function setPlatUn(?Plat $platUn): static
    {
        $this->platUn = $platUn;

        return $this;
    }

    public function getPlatDeux(): ?Plat
    {
        return $this->platDeux;
    }

    public function setPlatDeux(?Plat $platDeux): static
    {
        $this->platDeux = $platDeux;

        return $this;
    }

    public function getPlatTrois(): ?Plat
    {
        return $this->platTrois;
    }

    public function setPlatTrois(?Plat $platTrois): static
    {
        $this->platTrois = $platTrois;

        return $this;
    }

    public function getDessert(): ?Plat
    {
        return $this->dessert;
    }

    public function setDessert(?Plat $dessert): static
    {
        $this->dessert = $dessert;

        return $this;
    }

    public function getQuantitéViande(): ?int
    {
        return $this->quantitéviande;
    }

    public function setQuantitéViande(?int $quantitéviande): static
    {
        $this->quantitéviande = $quantitéviande;

        return $this;
    }

    public function getQuantitépoisson(): ?int
    {
        return $this->quantitépoisson;
    }

    public function setQuantitépoisson(?int $quantitépoisson): static
    {
        $this->quantitépoisson = $quantitépoisson;

        return $this;
    }

    public function getQuantitévegi(): ?int
    {
        return $this->quantitévegi;
    }

    public function setQuantitévegi(?int $quantitévegi): static
    {
        $this->quantitévegi = $quantitévegi;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): static
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setMenuId($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): static
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getMenuId() === $this) {
                $commande->setMenuId(null);
            }
        }

        return $this;
    }
}