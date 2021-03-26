<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("email")
 */
class User
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
     *     max=255,
     *     maxMessage = "Your firstname cannot be longer than {{ limit }} characters",
     *     allowEmptyString = false
     * )
     * @Assert\Regex(
     *  pattern="/\p{L}+$/u",
     *  match=true,
     *  message="Name can not include any number or special chars"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Length(
     *     max=255,
     *     maxMessage = "Your surname cannot be longer than {{ limit }} characters",
     *     allowEmptyString = false
     * )
     * @Assert\Regex(
     *  pattern="/\p{L}+$/u",
     *  match=true,
     *  message="Surname can not include any number or special chars"
     * )
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email
     * @Assert\NotBlank
     * @Assert\Length(
     *     max=255,
     *     maxMessage = "Your email cannot be longer than {{ limit }} characters",
     *     allowEmptyString = false
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=9)
     * @Assert\NotBlank
     * @Assert\Regex(
     *  pattern="/^[1-9]{9}$/u",
     *  match=true,
     *  message="The phone-number can not start with 0, and must consist of 9 digits"
     * )
     */
    private $phone_number;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     * @Assert\Date
     * @Assert\LessThanOrEqual("-18 years")
     */
    private $bday;

    public function __construct(array $data = [])
    {
        $this->name = $data['name'] ?? null;
        $this->surname = $data['surname'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->phone_number = $data['phone_number'] ?? null;
        $this->bday = new \DateTime($data['bday']) ?? null;
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

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getBday(): ?\DateTimeInterface
    {
        return $this->bday;
    }

    public function setBday(\DateTimeInterface $bday): self
    {
        $this->bday = $bday;

        return $this;
    }
}
