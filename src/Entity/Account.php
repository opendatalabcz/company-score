<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serialize;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccountRepository")
 * @UniqueEntity("username")
 * @Serialize\ExclusionPolicy("none")
 */
class Account implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(unique=true,type="string",nullable=false)
     * @Assert\Length(
     *     min=3,
     *     max=50,
     *     minMessage="Délka username musí být alespoň {{ limit }} znaků",
     *     maxMessage="Délka přihlášení nesmí být delší než {{ limit }} znaků"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="Firm",mappedBy="account", cascade={"remove", "persist"})
     * @Serialize\MaxDepth(1)
     */
    private $firms;

    /**
     * Employee constructor.
     */
    public function __construct()
    {
        $this->firms = new ArrayCollection();
    }
    public function addFirm ( Firm $firm ): self
    {
        $this->$firm->add($firm);
        return $this;
    }

    public function removeFirm ( Firm $firm ): self
    {
        $this->$firm->removeElement($firm);
        return $this;
    }

    /**
     * @return Collection
     */
    public function getFirms(): Collection
    {
        return $this->firms;
    }

    /**
     * @param Collection $firms
     */
    public function setFirms(Collection $firms): void
    {
        $this->firms = $firms;
    }



    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getRoles() : array
    {
        $roles = [];
        $roles[] = 'ROLE_USER';

        return array_unique($roles);

    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
