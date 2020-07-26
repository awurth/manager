<?php

namespace App\Entity;

use App\Repository\ServerUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass=ServerUserRepository::class)
 */
class ServerUser
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid_binary")
     */
    private UuidInterface $id;

    /**
     * @ORM\ManyToOne(targetEntity=Server::class)
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private Server $server;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $username = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $password = null;

    private function __construct(Server $server)
    {
        $this->id = Uuid::uuid4();
        $this->server = $server;
    }

    public function __toString(): string
    {
        return (string)$this->username;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getServer(): Server
    {
        return $this->server;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
