<?php

namespace App\Entity;

use App\Repository\TestDomenyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TestDomenyRepository::class)
 */
class TestDomeny
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pocet_let_v_provozu;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $posledni_modifikace;

    /**
     * @ORM\Column(type="integer")
     */
    private $result;

    /**
     * @ORM\OneToOne(targetEntity=Firm::class, mappedBy="test_domeny", cascade={"persist", "remove"})
     */
    private $firm_id;

    public function getFirmId(): ?Firm
    {
        return $this->firm_id;
    }

    public function setFirmId(Firm $firm_id): self
    {
        // set the owning side of the relation if necessary
        if ($firm_id->getTestDomenyId() !== $this) {
            $firm_id->setTestDomenyId($this);
        }

        $this->firm_id = $firm_id;

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPocetLetVProvozu()
    {
        return $this->pocet_let_v_provozu;
    }


    public function setPocetLetVProvozu($pocet_let_v_provozu): void
    {
        $this->pocet_let_v_provozu = $pocet_let_v_provozu;
    }

    public function getPosledniModifikace()
    {
        return $this->posledni_modifikace;
    }


    public function setPosledniModifikace($posledni_modifikace): void
    {
        $this->posledni_modifikace = $posledni_modifikace;
    }



    public function getResult(): ?int
    {
        return $this->result;
    }

    public function setResult(int $result): self
    {
        $this->result = $result;

        return $this;
    }
}
