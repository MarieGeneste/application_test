<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\Length(max=255)
     */
    private $type;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $amountByPage;

    /**
     * @ORM\Column(type="float", nullable=false)
     */
    private $amount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Document", inversedBy="product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $document;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\SendMode", inversedBy="product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sendMode;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderProduct", mappedBy="product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $orderProducts;

    public function __construct()
    {
        $this->orderProducts = new ArrayCollection();
        $this->type = "document";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAmountByPage(): ?float
    {
        return $this->amountByPage;
    }

    public function setAmountByPage(float $amountByPage): self
    {
        $this->amountByPage = $amountByPage;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = round($amount, 2);

        return $this;
    }

    public function getDocument(): ?Document
    {
        return $this->document;
    }

    public function setDocument(?Document $document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getSendMode(): ?SendMode
    {
        return $this->sendMode;
    }

    public function setSendMode(?SendMode $sendMode): self
    {
        $this->sendMode = $sendMode;

        return $this;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection|OrderProduct[]
     */
    public function getOrderProducts(): \Doctrine\Common\Collections\Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): self
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts[] = $orderProduct;
            $orderProduct->setProduct($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): self
    {
        if ($this->orderProducts->contains($orderProduct) && $orderProduct->getPurchaseOrder()->getStatus() < 4) {
            $this->orderProducts->removeElement($orderProduct);
            // set the owning side to null (unless already changed)
            if ($orderProduct->getProduct() === $this) {
                // TO DO : Service to $entityManager->remove($purchaseOrder)
            }
        }

        return $this;
    }



    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function setDocumentAmount()
    {
        if ($this->type == "document" && !empty($this->amountByPage)) {
            $documentPages = $this->document->getPages();
            $productAmount = $documentPages * $this->amountByPage;
            $this->setAmount($productAmount);
        }
    }
}
