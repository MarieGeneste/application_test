<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PurchaseOrderRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PurchaseOrder
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FrontUser", inversedBy="purchaseOrders", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $frontUser;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $purchaseDate;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $purchaseTotalAmount;

    /**
     * @ORM\Column(type="boolean")
     * @param bool 0 : en Euro
     * @param bool 1 : en Coins
     */
    private $paymentMode;

    /**
     * Champs utilisé pour connaitre le statut de la facture
     * @param int 0 : Panier
     * @param int 1 : En attente d'infos pour facturation - TODO - Cron : Tous les jours supprimer PurchaseOrder status 0 & j-1 
     * @param int 2 : Facture préparée (pour facturation trimestrielle abonnement)
     * @param int 3 : Payée - en attente du traitement de la commande par le back-office (documents à envoyer par courrier ou fax)
     * @param int 4 : Payée - traitement de la commande terminée
     * @ORM\Column(type="integer", nullable=false)
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderProduct", mappedBy="purchaseOrder")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderProducts;

    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFrontUser(): ?FrontUser
    {
        return $this->frontUser;
    }

    public function setFrontUser(?FrontUser $frontUser): self
    {
        $this->frontUser = $frontUser;

        return $this;
    }

    public function getPurchaseDate(): ?\DateTimeInterface
    {
        return $this->purchaseDate;
    }

    public function setPurchaseDate(?\DateTimeInterface $purchaseDate): self
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    public function getPaymentMode(): ?bool
    {
        return $this->paymentMode;
    }

    /**
     * @param bool 0 : en Euro
     * @param bool 1 : en Coins
     */
    public function setPaymentMode(bool $paymentMode): self
    {
        $this->paymentMode = $paymentMode;

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
    * @param int 0 : Panier
    * @param int 1 : En attente d'infos pour facturation - TODO - Cron : Tous les jours supprimer PurchaseOrder status 0 & j-1 
    * @param int 2 : Facture préparée (pour facturation trimestrielle abonnement)
    * @param int 3 : Payée - en attente du traitement de la commande par le back-office (documents à envoyer par courrier ou fax)
    * @param int 4 : Payée - Terminé (livraisons OK)
    */
    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|OrderProducts[]
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): self
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts[] = $orderProduct;
            $orderProduct->setPurchaseOrder($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): self
    {
        if ($this->orderProducts->contains($orderProduct) && $this->status < 4) {
            // Si la commande existe et n'a pas été payée
            $this->orderProducts->removeElement($orderProduct);
            if ($orderProduct->getPurchaseOrder() === $this) {
                // TO DO : Service to $entityManager->remove($orderProduct)
            }
        }

        return $this;
    }

    public function getPurchaseTotalAmount(): ?float
    {
        return $this->purchaseTotalAmount;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setPurchaseTotalAmount()
    {
        $totalPrice = 0;
        foreach ($this->orderProducts as $orderProduct) {
           $totalPrice += $orderProduct->getOrderAmount();
        }
        $this->purchaseTotalAmount = $totalPrice;
    }
}
