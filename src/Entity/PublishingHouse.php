<?php

namespace App\Entity;

use App\Repository\PublishingHouseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PublishingHouseRepository::class)
 */
class PublishingHouse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Positive()
     * @Assert\Expression(
     *      "this.isCorrectYear() == true",
     *      message="Year of establishment should not be greater than current year"
     * )
     * @ORM\Column(type="integer")
     */
    private $yearOfEstablishment;
    
    /**
     * @ORM\OneToMany(targetEntity="Book", mappedBy="publishingHouse")
     */
    private $book;

    public function __construct()
    {
        $this->book = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getYearOfEstablishment(): ?int
    {
        return $this->yearOfEstablishment;
    }

    public function setYearOfEstablishment(int $yearOfEstablishment): self
    {
        $this->yearOfEstablishment = $yearOfEstablishment;

        return $this;
    }
    
    /**
     * Metoda sprawdzająca, czy wprowadzony rok jest poprawny
     * @return bool
     */
    public function isCorrectYear(): bool
    {
        return $this->yearOfEstablishment <= (int) date('Y');
    }
    
    /**
     * Metoda zwracająca opis wydawnictwa w formie odpowiedniej dla użytkownika
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBook(): Collection
    {
        return $this->book;
    }

    public function addBook(Book $book): self
    {
        if (!$this->book->contains($book)) {
            $this->book[] = $book;
            $book->setPublishingHouse($this);
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        if ($this->book->removeElement($book)) {
            // set the owning side to null (unless already changed)
            if ($book->getPublishingHouse() === $this) {
                $book->setPublishingHouse(null);
            }
        }

        return $this;
    }
}
