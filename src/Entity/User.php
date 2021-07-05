<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=16)
     */
    private $username;
    
    /**
     * @ORM\Column(type="array")
     */
    private $roles;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\Email()
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;
    
    /**
     * @var string
     */
    private $plainPassword;
    
    /**
     * One User has Many Tasks.
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="user", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $tasks;
    
    /**
     * One User has Many Subtasks.
     * @ORM\OneToMany(targetEntity="App\Entity\Subtask", mappedBy="user", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"id" = "ASC"})
     */
    private $subtasks;
    
    public function __construct()
    {
        $this->roles = array('ROLE_USER');
        $this->tasks = new ArrayCollection();
        $this->subtasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * Get username
     *
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }
    
    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        
        return $this;
    }
    
    public function getSalt()
    {
        // The bcrypt and argon2i algorithms don't require a separate salt.
        // You *may* need a real salt if you choose a different encoder.
        return null;
    }
    
    public function getRoles()
    {
        return $this->roles;
    }
    
    /**
     * Get email
     *
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }
    
    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        
        return $this;
    }
    
    /**
     * Get password
     *
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
    
    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        
        return $this;
    }
    
    public function getPlainPassword() : ?string 
    {
        return $this->plainPassword;
    }
    
    /**
     * Set plainPassword
     *
     * @param string $plainPassword
     * @return User
     */
    public function setPlainPassword(string $plainPassword) : self 
    {
        $this->plainPassword = $plainPassword;
        
        return $this;
    }
    
    public function eraseCredentials() 
    {
        
    }
}
