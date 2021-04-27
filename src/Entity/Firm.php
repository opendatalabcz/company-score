<?php

namespace App\Entity;
use App\Repository\FirmRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping\JoinColumn;
use JMS\Serializer\Annotation as Serialize;

/**
 * @ORM\Entity(repositoryClass=FirmRepository::class)
 */
class Firm
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=8)
     * @Assert\Length(
     *     min=6,
     *     max=8,
     *     minMessage="Délka IČO nesmí být kratší než {{ limit }} znaků",
     *     maxMessage="Délka IČO nesmí být delší než {{ limit }} znaků"
     * )
     */
    private $ico;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $sidlo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $result;

    /**
     * @ORM\OneToOne(targetEntity=ZakladniTest::class, inversedBy="firm_id", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $zakladni_test;

    /**
     * @ORM\OneToOne(targetEntity=TestJednatelu::class, inversedBy="firm_id", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $test_jednatelu;

    /**
     * @ORM\OneToOne(targetEntity=BonusovyTest::class, inversedBy="firm_id", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $bonusovy_test;

    /**
     * @ORM\OneToOne(targetEntity=TestSubjektu::class, inversedBy="firm_id", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $test_subjektu;

    /**
     * @ORM\OneToOne(targetEntity=TestDomeny::class, inversedBy="firm_id", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $test_domeny;

    /**
     * @ORM\ManyToOne(targetEntity="Account", inversedBy="firms")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id")
     */
    private $account;

    public function setAccount(Account $account)
    {
        $this->account = $account;
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIco(): ?string
    {
        return $this->ico;
    }

    public function setIco(string $ico): self
    {
        $this->ico = $ico;

        return $this;
    }

    public function getSidlo(): ?string
    {
        return $this->sidlo;
    }

    public function setSidlo(string $sidlo): self
    {
        $this->sidlo = $sidlo;

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

    public function getZakladniTestId(): ?ZakladniTest
    {
        return $this->zakladni_test;
    }


    public function setZakladniTestId(?ZakladniTest $zakladni_test): self
    {
        $this->zakladni_test = $zakladni_test;

        return $this;
    }

    public function getTestJednateluId(): ?TestJednatelu
    {
        return $this->test_jednatelu;
    }

    public function setTestJednateluId(?TestJednatelu $test_jednatelu): self
    {
        $this->test_jednatelu = $test_jednatelu;

        return $this;
    }

    public function getBonusovyTestId(): ?BonusovyTest
    {
        return $this->bonusovy_test;
    }

    public function setBonusovyTestId(?BonusovyTest $bonusovy_test): self
    {
        $this->bonusovy_test = $bonusovy_test;

        return $this;
    }

    public function getTestSubjektuId(): ?TestSubjektu
    {
        return $this->test_subjektu;
    }

    public function setTestSubjektuId(?TestSubjektu $test_subjektu): self
    {
        $this->test_subjektu = $test_subjektu;

        return $this;
    }

    public function getTestDomenyId(): ?TestDomeny
    {
        return $this->test_domeny;
    }

    public function setTestDomenyId(?TestDomeny $test_domeny): self
    {
        $this->test_domeny = $test_domeny;

        return $this;
    }
}
