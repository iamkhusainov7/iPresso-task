<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SubscriptionRepository::class)
 */
class Subscription
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *     max=10,
     *     maxMessage = "Currency name cannot be longer than {{ limit }} characters",
     *     allowEmptyString = false
     * )
     * @Assert\Regex(
     *  pattern="/[A-Za-z]+$/u",
     *  match=true,
     *  message="Currency name can not include any number or special chars"
     * )
     */
    private $currency;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     * @Assert\Regex(
     *  pattern="/[^a-zA-Z]+$/u",
     *  match=true,
     *  message="Min value must be numerical value"
     * )
     */
    private $min;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     * @Assert\Regex(
     *  pattern="/[^a-zA-Z]+$/u",
     *  match=true,
     *  message="Min value must be numerical value"
     * )
     */
    private $max;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="subscriptions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function __construct(array $data = [])
    {
        $this->min = $data['min'] ?? null;
        $this->max = $data['max'] ?? null;
        $this->currency = $data['currency-name'] ?? null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getMin(): ?float
    {
        return $this->min;
    }

    public function setMin(float $min): self
    {
        $this->min = $min;

        return $this;
    }

    public function getMax(): ?float
    {
        return $this->max;
    }

    public function setMax(float $max): self
    {
        $this->max = $max;

        return $this;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
