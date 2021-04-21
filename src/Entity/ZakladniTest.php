<?php

namespace App\Entity;

use App\Repository\ZakladniTestRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ZakladniTestRepository::class)
 */
class ZakladniTest
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="integer")
     */
    private $result;

    /**
     * @ORM\OneToOne(targetEntity=Firm::class, mappedBy="zakladni_test", cascade={"persist", "remove"})
     */
    private $firm_id;

    public function getFirmId(): ?Firm
    {
        return $this->firm_id;
    }

    public function setFirmId(Firm $firm_id): self
    {
        // set the owning side of the relation if necessary
        if ($firm_id->getZakladniTestId() !== $this) {
            $firm_id->setZakladniTestId($this);
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

    public function getResult(): ?int
    {
        return $this->result;
    }

    public function setResult(int $result): self
    {
        $this->result = $result;

        return $this;
    }
    function itClass() {
        $arr=[];
        foreach ($this as $key => $value) {
            if($key === 'id' || $key === 'result')
                continue;
            $arr[$key]=$value;
        }
        return $arr;
    }
}
