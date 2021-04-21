<?php

namespace App\Entity;

use App\Repository\BonusovyTestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BonusovyTestRepository::class)
 */
class BonusovyTest
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
    private $pocet_provozoven;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $ochranne_znamky;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rust_zakladniho_kapitala;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $aktualni_nabidky_prace;

    /**
     * @ORM\OneToOne(targetEntity=Firm::class, mappedBy="bonusovy_test", cascade={"persist", "remove"})
     */
    private $firm_id;
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $result;

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param mixed $result
     */
    public function setResult($result): void
    {
        $this->result = $result;
    }


    public function getFirmId(): ?Firm
    {
        return $this->firm_id;
    }

    public function setFirmId(Firm $firm_id): self
    {
        // set the owning side of the relation if necessary
        if ($firm_id->getBonusovyTestId() !== $this) {
            $firm_id->setBonusovyTestId($this);
        }

        $this->firm_id = $firm_id;

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPocetProvozoven(): ?int
    {
        return $this->pocet_provozoven;
    }

    public function setPocetProvozoven(?int $pocet_provozoven): self
    {
        $this->pocet_provozoven = $pocet_provozoven;

        return $this;
    }

    public function getOchranneZnamky(): ?string
    {
        return $this->ochranne_znamky;
    }

    public function setOchranneZnamky(?string $ochranne_znamky): self
    {
        $this->ochranne_znamky = $ochranne_znamky;

        return $this;
    }

    public function getRustZakladnihoKapitala(): ?string
    {
        return $this->rust_zakladniho_kapitala;
    }

    public function setRustZakladnihoKapitala(?string $rust_zakladniho_kapitala): self
    {
        $this->rust_zakladniho_kapitala = $rust_zakladniho_kapitala;

        return $this;
    }

    public function getAktualniNabidkyPrace(): ?string
    {
        return $this->aktualni_nabidky_prace;
    }

    public function setAktualniNabidkyPrace(?string $aktualni_nabidky_prace): self
    {
        $this->aktualni_nabidky_prace = $aktualni_nabidky_prace;

        return $this;
    }
}
