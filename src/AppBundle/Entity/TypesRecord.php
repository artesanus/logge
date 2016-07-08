<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TiposRegistro
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TypesRecord
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="typeRecord", type="string", length=255)
     */
    private $typeRecord;

    /**
     * @ORM\OneToMany(targetEntity="Records", mappedBy="typesRecord")
     */
    private $records;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->records = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set typeRecord
     *
     * @param string $typeRecord
     * @return TypesRecord
     */
    public function setTypeRecord($typeRecord)
    {
        $this->typeRecord = $typeRecord;

        return $this;
    }

    /**
     * Get typeRecord
     *
     * @return string 
     */
    public function getTypeRecord()
    {
        return $this->typeRecord;
    }

    /**
     * Add records
     *
     * @param \AppBundle\Entity\Records $records
     * @return TypesRecord
     */
    public function addRecord(\AppBundle\Entity\Records $records)
    {
        $this->records[] = $records;

        return $this;
    }

    /**
     * Remove records
     *
     * @param \AppBundle\Entity\Records $records
     */
    public function removeRecord(\AppBundle\Entity\Records $records)
    {
        $this->records->removeElement($records);
    }

    /**
     * Get records
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRecords()
    {
        return $this->records;
    }
}