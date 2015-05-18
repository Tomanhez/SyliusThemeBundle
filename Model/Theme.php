<?php

namespace Sylius\Bundle\ThemeBundle\Model;

/**
 * @author Kamil Kokot <kamil.kokot@lakion.com>
 */
class Theme implements ThemeInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $logicalName;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $path;

    public function __toString()
    {
        return $this->getLogicalName();
    }

    /**
     * {@inheritdoc}
     */
    public function equals(ThemeInterface $theme)
    {
        return $this->getLogicalName() === $theme->getLogicalName() || $this->getPath() === $theme->getPath();
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setLogicalName($logicalName)
    {
        $this->logicalName = $logicalName;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogicalName()
    {
        return $this->logicalName;
    }

    /**
     * {@inheritdoc}
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    public function getPath()
    {
        return $this->path;
    }
}