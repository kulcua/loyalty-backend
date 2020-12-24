<?php

namespace Kulcua\ExtensionBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DateMaintenance
 *
 * @ORM\Table(name="date_maintenance")
 * @ORM\Entity(repositoryClass="Kulcua\ExtensionBundle\Repository\DateMaintenanceRepository")
 */
class DateMaintenance
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var OpenLoyalty\Bundle\UserBundle\Entity\User
     * @ORM\ManyToOne(targetEntity="OpenLoyalty\Bundle\UserBundle\Entity\User")
     * @ORM\JoinTable(name="ol__user")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $productSku;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $orderDate;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $createdAt;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * Set productSku
     *
     * @param string $productSku
     *
     * @return DateMaintenance
     */
    public function setProductSku($productSku)
    {
        $this->productSku = $productSku;

        return $this;
    }

    /**
     * Get productSku
     *
     * @return string
     */
    public function getProductSku()
    {
        return $this->productSku;
    }

    /**
     * Set orderDate
     *
     * @param \DateTime $orderDate
     *
     * @return DateMaintenance
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return \DateTime
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return DateMaintenance
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return DateMaintenance
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}

