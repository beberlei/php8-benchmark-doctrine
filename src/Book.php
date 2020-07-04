<?php

namespace App;

use Doctrine\ORM\Mapping as ORM;
use Datetime;

/**
 * @ORM\Entity
 */
class Book
{
    /** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue */
    private ?int $id;
    /** @ORM\Column(type="datetime") */
    private DateTime $created;

    public function __construct(
        /** @ORM\Column(type="string") */
        private string $name,
        /** @ORM\ManyToOne(targetEntity=Author::class) */
        private Author $author,
    )
    {
        $this->created = new DateTime();
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getName()
    {
        return $this->name;
    }
}
