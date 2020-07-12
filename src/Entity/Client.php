<?php

namespace App\Entity;

use App\Form\Model\Admin\CreateClient;
use App\Form\Model\Admin\EditClient;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid_binary")
     */
    private UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $postcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $phone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $email;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTimeInterface $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ClientContact", mappedBy="client", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private Collection $contacts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProjectGroup", mappedBy="client", cascade={"persist"})
     */
    private Collection $projectGroups;

    private function __construct(string $name)
    {
        $this->id = Uuid::uuid4();
        $this->name = $name;
        $this->createdAt = new DateTimeImmutable();

        $this->contacts = new ArrayCollection();
        $this->projectGroups = new ArrayCollection();
    }

    public static function createFromAdminCreationForm(CreateClient $createClient): self
    {
        $client = new self($createClient->name);
        $client->address = $createClient->address;
        $client->postcode = $createClient->postcode;
        $client->city = $createClient->city;
        $client->phone = $createClient->phone;
        $client->email = $createClient->email;

        return $client;
    }

    public function updateFromAdminEditionForm(EditClient $editClient): void
    {
        $this->name = $editClient->name;
        $this->address = $editClient->address;
        $this->postcode = $editClient->postcode;
        $this->city = $editClient->city;
        $this->phone = $editClient->phone;
        $this->email = $editClient->email;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection|ClientContact[]
     */
    public function getContacts(): Collection
    {
        return $this->contacts;
    }

    /**
     * @return Collection|ProjectGroup[]
     */
    public function getProjectGroups(): Collection
    {
        return $this->projectGroups;
    }
}
