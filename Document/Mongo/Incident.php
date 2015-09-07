<?php

namespace Zimtronic\BannerBundle\Document\Mongo;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Zimtronic\BannerBundle\Model\IncidentInterface;

/**
 * @author Ariel Chavez <arielcxl@gmail.com>
 * @MongoDB\Document(collection="security_incidents")
 */

class Incident implements IncidentInterface
{
	 public static $SECURITY_INCIDENT_LOGIN_FAILURE=1;
	 public static $SECURITY_INCIDENT_BAD_URL=2;
	 
    /** @MongoDB\Id */
    protected $id;

    /** @MongoDB\Date
     *  @MongoDB\Index(expireAfterSeconds=0)
     */
    protected $expireAt;
    
    /** @MongoDB\String */
    protected $address;

    /** @MongoDB\Int*/
    protected $type;
    
    /** @MongoDB\String */
    protected $expandedInfo;
    
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
     * @return self
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
    
    /**
     * Set type
     *
     * @param int $type
     * @return self
     */
    public function setType($type)
    {
    	$this->type = $type;
    	return $this;
    }
    
    /**
     * Get type
     *
     * @return type $type
     */
    public function getType()
    {
    	return $this->type;
    }
    
    /**
     * Set expandedInfo
     *
     * @param string $info
     * @return self
     */
    public function setExpandedInfo($info)
    {
    	$this->expandedInfo = $info;
    	return $this;
    }
    
    /**
     * Get expandedInfo
     *
     * @return string $expandedInfo
     */
    public function getExpandedInfo()
    {
    	return $this->expandedInfo;
    }
}
