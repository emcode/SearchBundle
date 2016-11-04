<?php

namespace Acme\SearchBundle\Entity;

/**
 * SearchIndexItem
 */
class SearchIndexItem
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $entityType;

    /**
     * @var int
     */
    private $entityId;

    /**
     * @var string
     */
    private $content;


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
     * Set entityType
     *
     * @param string $entityType
     *
     * @return SearchIndexItem
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * Get entityType
     *
     * @return string
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * Set entityId
     *
     * @param integer $entityId
     *
     * @return SearchIndexItem
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return SearchIndexItem
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }


}

