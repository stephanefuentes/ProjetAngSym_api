<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @ApiResource(
 * normalizationContext={"groups":{"customer:read"}}
 * )
 */
class Customer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"customer:read", "invoice:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customer:read"})
     * @Assert\NotBlank(message="le nom est obigatoire")
     * @Assert\Length(min=3, minMessage="le nom doit faire 3 carcteres minimum")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customer:read", "invoice:read"})
     * @Assert\NotBlank(message="le prenom est obigatoire")
     * @Assert\Length(min=3, minMessage="le prenom doit faire 3 carcteres minimum")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"customer:read", "invoice:read"})
     * @Assert\NotBlank(message="l email est obigatoire")
     * @Assert\Email(message="le mail doit etre correctement fprmatté")
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice", mappedBy="customer")
     *  @Groups({"customer:read", "invoice:read"})
     */
    private $invoices;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="customers")
     * @Groups({"invoice:read"})
     */
    private $user;




    /**
     * @Groups({"customer:read"})
     *
     * @return float
     */
    public function getInvoicesCount(): float
    {
        return count($this->invoices);
    }

    /**
     * @Groups({"customer:read"})
     *
     * @return float
     */
    public function getTotalAmount(): float
    {
        $total = 0;

        foreach ($this->invoices as $invoice) {
            $total += $invoice->getAmount();
        }

        return $total;
    }

    /**
     * @Groups({"customer:read"})
     *
     * @return float
     */
    public function getPaidAmount(): float
    {
        $total = 0;

        foreach ($this->invoices as $invoice) {
            if ($invoice->getStatus() === "PAID") {
                $total += $invoice->getAmount();
            }
        }
        return $total;
    }


    /**
     * @Groups({"customer:read"})
     *
     * @return float
     */
    public function getUnpaidAmount(): float
    {
       return ($this->getTotalAmount() - $this->getPaidAmount());
        
    }





    public function __construct()
    {
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

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

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices[] = $invoice;
            $invoice->setCustomer($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->contains($invoice)) {
            $this->invoices->removeElement($invoice);
            // set the owning side to null (unless already changed)
            if ($invoice->getCustomer() === $this) {
                $invoice->setCustomer(null);
            }
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }




    public function __toString()
    {
        return $this->firstName." ". $this->lastName;
    }


    
}
