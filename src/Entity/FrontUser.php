<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FrontUserRepository")
 * @UniqueEntity(fields= {"username"},
 * message="Cet identifiant n'est pas disponible. Veuillez saisir un autre identifiant.")
 * @ORM\HasLifecycleCallbacks()
 */
class FrontUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(max=100)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\Length(max=100)
     */
    private $lastname;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\Length(max=255)
     * @Assert\Email
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Vous devez saisir un mot de passe", allowNull=true)
     * @Assert\Length(min=8, minMessage="Votre mot de passe doit contenir au minimum 8 caractères")
     */
    private $password;

    /**
     * @ORM\Column(name="username", type="string", length=50, nullable=false, unique=true)
     * @Assert\Length(max=50)
     */
    private $username;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amountCoins;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PurchaseOrder", mappedBy="frontUser")
     * @ORM\JoinColumn(nullable=true)
     */
    private $purchaseOrders;

    public function __construct()
    {
        $this->purchaseOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getAmountCoins(): ?float
    {
        return $this->amountCoins;
    }

    public function setAmountCoins(?float $amountCoins): self
    {
        $this->amountCoins = $amountCoins;

        return $this;
    }

    /**
     * @return Collection|PurchaseOrders[]
     */
    public function getPurchaseOrders(): Collection
    {
        return $this->purchaseOrders;
    }

    public function addPurchaseOrder(PurchaseOrder $purchaseOrder): self
    {
        if (!$this->purchaseOrders->contains($purchaseOrder)) {
            $this->purchaseOrders[] = $purchaseOrder;
            $purchaseOrder->setFrontUser($this);
        }

        return $this;
    }

    public function removePurchaseOrder(PurchaseOrder $purchaseOrder): self
    {
        if ($this->purchaseOrders->contains($purchaseOrder) && $purchaseOrder->getStatus() < 4) {
            // Si la commande existe et n'a pas été payée
            $this->purchaseOrders->removeElement($purchaseOrder);
            if ($purchaseOrder->getFrontUser() === $this) {
                // TO DO : Service to $entityManager->remove($purchaseOrder)
            }
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setAutoUsername()
    {
        if (empty($this->username)) {
            $this->username = $this->firstname . $this->lastname;
        }
    }
}
