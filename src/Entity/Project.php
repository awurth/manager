<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProjectGroup", inversedBy="projects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $projectGroup;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProjectType", inversedBy="projects")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logoFilename;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProjectEnvironment", mappedBy="project", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $environments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProjectMember", mappedBy="project", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $members;

    public function __construct(string $slug, string $name)
    {
        $this->slug = $slug;
        $this->name = $name;

        $this->environments = new ArrayCollection();
        $this->members = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProjectGroup(): ?ProjectGroup
    {
        return $this->projectGroup;
    }

    public function setProjectGroup(?ProjectGroup $projectGroup): self
    {
        $this->projectGroup = $projectGroup;

        return $this;
    }

    public function getType(): ?ProjectType
    {
        return $this->type;
    }

    public function setType(?ProjectType $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLogoFilename(): ?string
    {
        return $this->logoFilename;
    }

    public function setLogoFilename(?string $logoFilename): self
    {
        $this->logoFilename = $logoFilename;

        return $this;
    }

    /**
     * @return Collection|ProjectEnvironment[]
     */
    public function getEnvironments(): Collection
    {
        return $this->environments;
    }

    public function addEnvironment(ProjectEnvironment $environment): self
    {
        if (!$this->environments->contains($environment)) {
            $this->environments[] = $environment;
            $environment->setProject($this);
        }

        return $this;
    }

    public function removeEnvironment(ProjectEnvironment $environment): self
    {
        if ($this->environments->contains($environment)) {
            $this->environments->removeElement($environment);
            // set the owning side to null (unless already changed)
            if ($environment->getProject() === $this) {
                $environment->setProject(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProjectMember[]
     */
    public function getMembers(): Collection
    {
        return $this->members;
    }

    public function addMember(ProjectMember $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setProject($this);
        }

        return $this;
    }

    public function removeMember(ProjectMember $member): self
    {
        if ($this->members->contains($member)) {
            $this->members->removeElement($member);
            // set the owning side to null (unless already changed)
            if ($member->getProject() === $this) {
                $member->setProject(null);
            }
        }

        return $this;
    }
}
