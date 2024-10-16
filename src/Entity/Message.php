<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:read','instrument:read', 'review:read', 'transaction:read','message:read', 'message:write'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['user:read','instrument:read', 'review:read', 'transaction:read','message:read', 'message:write'])]
    private ?int $sender_id = null;

    #[ORM\Column]
    #[Groups(['user:read','instrument:read', 'review:read', 'transaction:read','message:read', 'message:write'])]
    private ?int $receiver_id = null;

    #[ORM\Column]
    #[Groups(['user:read','instrument:read', 'review:read', 'transaction:read','message:read', 'message:write'])]
    private ?int $instrument_id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read','instrument:read', 'review:read', 'transaction:read','message:read', 'message:write'])]
    private ?string $message = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSenderId(): ?int
    {
        return $this->sender_id;
    }

    public function setSenderId(int $sender_id): static
    {
        $this->sender_id = $sender_id;

        return $this;
    }

    public function getReceiverId(): ?int
    {
        return $this->receiver_id;
    }

    public function setReceiverId(int $receiver_id): static
    {
        $this->receiver_id = $receiver_id;

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

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }
}
