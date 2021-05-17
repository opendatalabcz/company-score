<?php

namespace App\Entity;

use App\Repository\TestSubjektuRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TestSubjektuRepository::class)
 */
class TestSubjektu
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
    private $pocet_zamestnancu;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $pocet_let_na_trhu;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nespolehlivy_platce;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $jine_subjekty_na_sidle;

    /**
     * @ORM\Column(type="integer")
     */
    private $result;

    /**
     * @ORM\OneToOne(targetEntity=Firm::class, mappedBy="test_subjektu", cascade={"persist", "remove"})
     */
    private $firm_id;

    public function getFirmId(): ?Firm
    {
        return $this->firm_id;
    }

    public function setFirmId(Firm $firm_id): self
    {
        // set the owning side of the relation if necessary
        if ($firm_id->getTestSubjektuId() !== $this) {
            $firm_id->setTestSubjektuId($this);
        }

        $this->firm_id = $firm_id;

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPocetZamestnancu(): ?int
    {
        return $this->pocet_zamestnancu;
    }

    public function setPocetZamestnancu(?int $pocet_zamestnancu): self
    {
        $this->pocet_zamestnancu = $pocet_zamestnancu;

        return $this;
    }

    public function getPocetLetNaTrhu(): ?int
    {
        return $this->pocet_let_na_trhu;
    }

    public function setPocetLetNaTrhu(int $pocet_let_na_trhu): self
    {
        $this->pocet_let_na_trhu = $pocet_let_na_trhu;

        return $this;
    }

    public function getNespolehlivyPlatce(): ?string
    {
        return $this->nespolehlivy_platce;
    }

    public function setNespolehlivyPlatce(?string $nespolehlivy_platce): self
    {
        $this->nespolehlivy_platce = $nespolehlivy_platce;

        return $this;
    }

    public function getJineSubjektyNaSidle(): ?int
    {
        return $this->jine_subjekty_na_sidle;
    }

    public function setJineSubjektyNaSidle(?int $jine_subjekty_na_sidle): self
    {
        $this->jine_subjekty_na_sidle = $jine_subjekty_na_sidle;

        return $this;
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
