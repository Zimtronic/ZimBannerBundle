<?php

namespace Zimtronic\BannerBundle\Document\Mongo;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Zimtronic\BannerBundle\Model\BannedAddressInterface;

/**
 * @author Ariel Chavez <arielcxl@gmail.com>
 * @MongoDB\Document(collection="security_banned_address")
 */

class BannedAddress implements BannedAddressInterface
{
    /** @MongoDB\Id */
    protected $id;

    /** @MongoDB\Date
     *  @MongoDB\Index(expireAfterSeconds=0)
     */
    protected $expireAt;
    
    /** @MongoDB\Field(type="string") */
    protected $address;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
    	return $this->id;
    }
    
    /**
     * Set expireAt
     *
     * @param DateTime $expireAt
     * @return self
     */
    public function setExpireAt($expireAt)
    {
        $this->expireAt = $expireAt;
        return $this;
    }

    /**
     * Get expireAt
     *
     * @return DateTime $expireAt
     */
    public function getExpireAt()
    {
        return $this->expireAt;
    }
    
    /**
     * Set address
     *
     * @param address $address
     * @return string address
     */
    public function setAddress($address)
    {
    	$this->address = $address;
    	return $this;
    }
    
    /**
     * Get address
     *
     * @return address $address
     */
    public function getAddress()
    {
    	return $this->address;
    }    
}
