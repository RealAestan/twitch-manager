<?php

namespace App\Entity;

use App\Repository\BroadcasterRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BroadcasterRepository::class)
 */
class Broadcaster
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $twitchId;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $streamData = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fetchedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTwitchId(): ?string
    {
        return $this->twitchId;
    }

    public function setTwitchId(string $twitchId): self
    {
        $this->twitchId = $twitchId;

        return $this;
    }

    public function getStreamData(): ?array
    {
        return $this->streamData;
    }

    public function setStreamData(?array $streamData): self
    {
        $this->streamData = $streamData;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getFetchedAt(): ?\DateTimeInterface
    {
        return $this->fetchedAt;
    }

    public function setFetchedAt(?\DateTimeInterface $fetchedAt): self
    {
        $this->fetchedAt = $fetchedAt;

        return $this;
    }
}
