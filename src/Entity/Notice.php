<?php

namespace App\Entity;

use App\Repository\NoticeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoticeRepository::class)]
class Notice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $postedBy = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $postedAt = null;

    #[ORM\ManyToOne(inversedBy: 'notices')]
    private ?ShopKeeper $shopKeeper = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPostedBy(): ?string
    {
        return $this->postedBy;
    }

    public function setPostedBy(string $postedBy): static
    {
        $this->postedBy = $postedBy;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getPostedAt(): ?\DateTimeImmutable
    {
        return $this->postedAt;
    }

    public function setPostedAt(\DateTimeImmutable $postedAt): static
    {
        $this->postedAt = $postedAt;

        return $this;
    }

    public function getShopKeeper(): ?ShopKeeper
    {
        return $this->shopKeeper;
    }

    public function setShopKeeper(?ShopKeeper $shopKeeper): static
    {
        $this->shopKeeper = $shopKeeper;

        return $this;
    }
}
