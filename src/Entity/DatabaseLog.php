<?php

namespace CreamIO\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CreamIO\BaseBundle\Repository\DatabaseLogRepository")
 * @ORM\Table(name="creamio_log")
 * @ORM\HasLifecycleCallbacks
 */
class DatabaseLog
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $message;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $level;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="array")
     */
    private $context;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $levelName;

    /**
     * @ORM\Column(type="array")
     */
    private $extra;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return null|string
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return DatabaseLog
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLevel(): ?string
    {
        return $this->level;
    }

    /**
     * @param string $level
     *
     * @return DatabaseLog
     */
    public function setLevel(string $level): self
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     *
     * @return DatabaseLog
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist(): void
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return array|null
     */
    public function getContext(): ?array
    {
        return $this->context;
    }

    /**
     * @param array $context
     *
     * @return DatabaseLog
     */
    public function setContext(array $context): self
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getLevelName(): ?string
    {
        return $this->levelName;
    }

    /**
     * @param string $levelName
     *
     * @return DatabaseLog
     */
    public function setLevelName(string $levelName): self
    {
        $this->levelName = $levelName;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getExtra(): ?array
    {
        return $this->extra;
    }

    /**
     * @param array $extra
     *
     * @return DatabaseLog
     */
    public function setExtra(array $extra): self
    {
        $this->extra = $extra;

        return $this;
    }
}
