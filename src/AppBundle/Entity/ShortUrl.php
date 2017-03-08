<?php

namespace AppBundle\Entity;


use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * ShortUrl
 * @UniqueEntity("slug")
 */
class ShortUrl implements JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $originUrl;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var \DateTime
     */
    private $createdAt;


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
     * Set originUrl
     *
     *
     * @param string $originUrl
     * @return ShortUrl
     */
    public function setOriginUrl($originUrl)
    {
        $this->originUrl = $originUrl;

        return $this;
    }

    /**
     * Get originUrl
     *
     * @Assert\NotBlank()
     * @Assert\Url(
     *    message = "The url '{{ value }}' is not a valid url",
     * )
     * @AppAssert\UrlAvailability
     * @return string
     */
    public function getOriginUrl()
    {
        return $this->originUrl;
    }


    /**
     * Set slug
     *
     * @param string $slug
     * @return ShortUrl
     */
    public function setSlug($slug=null)
    {
        if(empty($slug)) {
            $slug = substr(md5(microtime()),0,8);
        }
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     * @Assert\Length(
     *     max = 8
     * )
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
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

    /**
     * Specify data which should be serialized to JSON
     */
    function jsonSerialize()
    {
        return array(
            'id'         => $this->getId(),
            'origin_url' => $this->getOriginUrl(),
            'slug'       => $this->getSlug(),
            'created_at' => $this->getCreatedAt()
        );
    }
}
