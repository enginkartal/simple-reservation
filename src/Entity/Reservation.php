<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $ref = null;

    #[ORM\Column]
    private ?int $customer_id = null;

    #[ORM\Column]
    private int $room_id;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $check_in = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $check_out = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column]
    private ?string $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function setRef(string $ref): self
    {
        $this->ref = $ref;

        return $this;
    }

    public function getCustomerId(): ?int
    {
        return $this->customer_id;
    }

    public function setCustomerId(int $customer_id): self
    {
        $this->customer_id = $customer_id;

        return $this;
    }

    public function getRoomId(): int
    {
        return $this->room_id;
    }

    public function setRoomId(int $room_id): self
    {
        $this->room_id = $room_id;

        return $this;
    }

    public function getCheckIn(): ?\DateTimeInterface
    {
        return $this->check_in;
    }

    public function setCheckIn(\DateTimeInterface $check_in): self
    {
        $this->check_in = $check_in;

        return $this;
    }

    public function getCheckOut(): ?\DateTimeInterface
    {
        return $this->check_out;
    }

    public function setCheckOut(\DateTimeInterface $check_out): self
    {
        $this->check_out = $check_out;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function setCreatedAt(\Datetime $created_at): self
    {
        $this->created_at = $created_at->format('Y-m-d H:i:s');

        return $this;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('customer_id', new Assert\Positive());
        $metadata->addPropertyConstraint('room_id', new Assert\Positive());
        $metadata->addPropertyConstraint('check_in', new Assert\Date());
        $metadata->addPropertyConstraint('check_out', new Assert\Blank());
        $metadata->addPropertyConstraint('check_out', new Assert\Date());
        $metadata->addPropertyConstraint('amount', new Assert\Positive());
    }
}
