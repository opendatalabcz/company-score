<?php

namespace App\Entity;

use App\Repository\TestJednateluRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TestJednateluRepository::class)
 */
class TestJednatelu
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $pocet_jednatelu;


    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $pocet_jinych_subjektu;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $pocet_jinych_subjektu_v_likvidaci;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $netypycky_vek_jednatele;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $insolvence;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bydleni_mimo_eu;

    /**
     * @ORM\Column(type="integer")
     */
    private $result;
    /**
     * @ORM\OneToOne(targetEntity=Firm::class, mappedBy="test_jednatelu", cascade={"persist", "remove"})
     */
    private $firm_id;

    public function getFirmId(): ?Firm
    {
        return $this->firm_id;
    }

    public function setFirmId(Firm $firm_id): self
    {
        // set the owning side of the relation if necessary
        if ($firm_id->getTestJednateluId() !== $this) {
            $firm_id->setTestJednateluId($this);
        }

        $this->firm_id = $firm_id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPocetJednatelu(): ?int
    {
        return $this->pocet_jednatelu;
    }

    public function setPocetJednatelu(int $pocet_jednatelu): self
    {
        $this->pocet_jednatelu = $pocet_jednatelu;

        return $this;
    }


    public function getPocetJinychSubjektu(): ?int
    {
        return $this->pocet_jinych_subjektu;
    }

    public function setPocetJinychSubjektu(int $pocet_jinych_subjektu): self
    {
        $this->pocet_jinych_subjektu = $pocet_jinych_subjektu;

        return $this;
    }

    public function getPocetJinychSubjektuVLikvidaci(): ?int
    {
        return $this->pocet_jinych_subjektu_v_likvidaci;
    }

    public function setPocetJinychSubjektuVLikvidaci(int $pocet_jinych_subjektu_v_likvidaci): self
    {
        $this->pocet_jinych_subjektu_v_likvidaci = $pocet_jinych_subjektu_v_likvidaci;

        return $this;
    }

    public function getNetypyckyVekJednatele(): ?string
    {
        return $this->netypycky_vek_jednatele;
    }

    public function setNetypyckyVekJednatele(?string $netypycky_vek_jednatele): self
    {
        $this->netypycky_vek_jednatele = $netypycky_vek_jednatele;

        return $this;
    }

    public function getInsolvence(): ?string
    {
        return $this->insolvence;
    }

    public function setInsolvence(?string $insolvence): self
    {
        $this->insolvence = $insolvence;

        return $this;
    }

    public function getBydleniMimoEu(): ?string
    {
        return $this->bydleni_mimo_eu;
    }

    public function setBydleniMimoEu(?string $bydleni_mimo_eu): self
    {
        $this->bydleni_mimo_eu = $bydleni_mimo_eu;

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
