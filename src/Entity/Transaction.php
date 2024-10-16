<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $buyer_id = null;

    #[ORM\Column]
    private ?int $instrument_id = null;

    #[ORM\Column(length: 255)]
    private ?string $transaction_amount = null;

    #[ORM\Column]
    private ?int $seller_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuyerId(): ?int
    {
        return $this->buyer_id;
    }

    public function setBuyerId(int $buyer_id): static
    {
        $this->buyer_id = $buyer_id;

        return $this;
    }

    public function getInstrumentId(): ?int
    {
        return $this->instrument_id;
    }

    public function setInstrumentId(int $instrument_id): static
    {
        $this->instrument_id = $instrument_id;

        return $this;
    }

    public function getTransactionAmount(): ?string
    {
        return $this->transaction_amount;
    }

    public function setTransactionAmount(string $transaction_amount): static
    {
        $this->transaction_amount = $transaction_amount;

        return $this;
    }

    public function getSellerId(): ?int
    {
        return $this->seller_id;
    }

    public function setSellerId(int $seller_id): static
    {
        $this->seller_id = $seller_id;

        return $this;
    }
}
