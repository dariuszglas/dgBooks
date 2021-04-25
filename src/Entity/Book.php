<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
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
    private $title;

    /**
     * @ORM\Column(type="string", length=13)
     */
    private $isbn;
    
    /**
     * @Assert\NotBlank()
     * @Assert\Expression(
     *      "this.isCorrectIsbnFormat() == true",
     *      message="ISBN number should contain exactly 13 digits"
     * )
     * @var string
     */
    public $isbnField;

    /**
     * @Assert\NotBlank()
     * @Assert\Positive()
     * @Assert\Expression(
     *      "this.isCorrectYear() == true",
     *      message="Year of publication should not be greater than current year"
     * )
     * @ORM\Column(type="integer")
     */
    private $yearOfPublication;
    
    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="Author", inversedBy="book")
     */
    private $author;
    
    /**
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity="PublishingHouse", inversedBy="book")
     */
    private $publishingHouse;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getIsbn(): ?string
    {
        return $this->isbn;
    }

    public function setIsbn(string $isbn): self
    {
        $this->isbn = $isbn;

        return $this;
    }

    public function getYearOfPublication(): ?int
    {
        return $this->yearOfPublication;
    }

    public function setYearOfPublication(int $yearOfPublication): self
    {
        $this->yearOfPublication = $yearOfPublication;

        return $this;
    }
    
    /**
     * Metoda usuwająca wprowadzone znaki '-' z numeru ISBN
     * 
     * @return self
     */
    public function formatIsbn(): self
    {
        $this->isbn = $this->getCleanIsbn();
        
        return $this;
    }
    
    private function getCleanIsbn(): string
    {
        return str_replace("-", "", $this->isbn);
    }
    
    /**
     * Metoda sprawdzająca, czy wprowadzony rok wydania jest poprawny
     * @return bool
     */
    public function isCorrectYear(): bool
    {
        return $this->yearOfPublication <= (int) date('Y');
    }
    
    /**
     * Metoda sprawdzająca poprawność numeru ISBN
     * @return bool
     */
    public function isCorrectIsbnFormat(): bool
    {
        return ctype_digit($this->isbn) && strlen($this->isbn) === 13;
    }

    public function getAuthor(): ?Author
    {
        return $this->author;
    }

    public function setAuthor(?Author $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPublishingHouse(): ?PublishingHouse
    {
        return $this->publishingHouse;
    }

    public function setPublishingHouse(?PublishingHouse $publishingHouse): self
    {
        $this->publishingHouse = $publishingHouse;

        return $this;
    }
    
    public function getIsbnField(): ?string
    {
        return $this->isbnField;
    }

    public function setIsbnField(?string $isbn): self
    {
        $this->isbnField = $isbn;

        return $this;
    }
}
