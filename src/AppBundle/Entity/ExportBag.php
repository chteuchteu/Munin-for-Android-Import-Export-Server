<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExportBag
 * @ORM\Table(name="export_bag")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ExportBagRepository")
 */
class ExportBag
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(name="version", type="integer")
     */
    private $version;

    /**
     * @var \DateTime
     * @ORM\Column(name="exportDate", type="datetime")
     */
    private $exportDate;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255, unique=true)
     */
    private $password;

    /**
     * @var string
     * @ORM\Column(name="dataString", type="text")
     */
    private $dataString;

    /**
     * @var string
     * @ORM\Column(name="dataType", type="string", length=255)
     */
    private $dataType;


    public function __construct()
    {
        $this->version = 0;
        $this->exportDate = new \DateTime();
    }


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param int $version
     * @return ExportBag
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExportDate()
    {
        return $this->exportDate;
    }

    /**
     * @param \DateTime $exportDate
     * @return ExportBag
     */
    public function setExportDate($exportDate)
    {
        $this->exportDate = $exportDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return ExportBag
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataString()
    {
        return $this->dataString;
    }

    /**
     * @param string $dataString
     * @return ExportBag
     */
    public function setDataString($dataString)
    {
        $this->dataString = $dataString;
        return $this;
    }

    /**
     * @return string
     */
    public function getDataType()
    {
        return $this->dataType;
    }

    /**
     * @param string $dataType
     * @return ExportBag
     */
    public function setDataType($dataType)
    {
        $this->dataType = $dataType;
        return $this;
    }
}

