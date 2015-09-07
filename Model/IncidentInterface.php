<?php
namespace Zimtronic\BannerBundle\Model;

/**
 *
 * @author Ariel Chavez <arielcxl@gmail.com>
 */
interface IncidentInterface
{  
    /**
     * Set expireAt
     *
     * @param DateTime $expireAt
     * @return self
     */
    public function setExpireAt($expireAt);

    /**
     * Get expireAt
     *
     * @return DateTime $expireAt
     */
    public function getExpireAt();
    
    /**
     * Set address
     *
     * @param address $address
     * @return self
     */
    public function setAddress($address);
    
    /**
     * Get address
     *
     * @return address $address
     */
    public function getAddress();
    
    /**
     * Set type
     *
     * @param int $type
     * @return self
     */
    public function setType($type);
    
    /**
     * Get type
     *
     * @return type $type
     */
    public function getType();
    
    /**
     * Set expandedInfo
     *
     * @param string $info
     * @return self
     */
    public function setExpandedInfo($info);
    
    /**
     * Get expandedInfo
     *
     * @return string $expandedInfo
     */
    public function getExpandedInfo();
}