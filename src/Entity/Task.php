<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 */
class Task
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     */
    private $title;
    
    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string
     */
    private $description;
    
    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $deadline;
    
    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    protected $status;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tasks")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->status = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    
    /**
     * Set title
     *
     * @param string $title
     * @return Task
     */
    public function setTitle(string $title = null) : self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle() : ?string
    {
        return $this->title;
    }
    
    /**
     * Set description
     *
     * @param string $description
     * @return Task
     */
    public function setDescription(string $description = null) : self
    {
        $this->description = $description;

        return $this;
    }
    
    /**
     * Get description
     *
     * @return string
     */
    public function getDescription() : ?string
    {
        return $this->description;
    }
    
    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     * @return Task
     */
    public function setDeadline(\DateTime $deadline) : self
    {
        $this->deadline = $deadline;

        return $this;
    }
    
    /**
     * Get deadline
     *
     * @return \DateTime
     */
    public function getDeadline() : ?\DateTime
    {
        return $this->deadline;
    }
    
    /**
     * Set status
     *
     * @param int $status
     * @return Task
     */
    public function setStatus(int $status) : self
    {
        $this->status = $status;
        
        return $this;
    }
    
    /**
     * Get status
     *
     * @return int
     */
    public function getStatus() : ?int
    {
        return $this->status;
    }
    
    const STATUS_PENDING = 'Pending';
    const STATUS_DONE = 'Done';
    const STATUS_REJECTED = 'Rejected';
    
    public static function getStatusList() {
        return array(
            self::STATUS_PENDING,
            self::STATUS_DONE,
            self::STATUS_REJECTED
        );
    }
    
    /**
     * Set user
     *
     * @return Task
     */
    public function setUser(User $user) {
        $this->user = $user;
        
        return $this;
    }
    
    /**
     * Get user
     */
    public function getUser() {
        return $this->user;
    }
}
