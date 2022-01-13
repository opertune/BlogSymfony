<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="L'email est déjà utilisé")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="3", minMessage="Votre nom doit comporter au moins 3 caractères")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez saisir une adresse valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\EqualTo(propertyPath="confirmPassword", message="Votre mot de passe doit être confirmé correctement")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Votre mot de passe doit être confirmé correctement")
     */
    public $confirmPassword;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    
    public function getSalt()
    {
        
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
        
    }
}
