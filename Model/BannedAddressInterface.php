<?php
namespace Zimtronic\BannerBundle\Model;

/**
 *
 * @author Ariel Chavez <arielcxl@gmail.com>
 */
interface BannedAddressInterface
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
	 * @return string address
	 */
	public function setAddress($address);
	
	/**
	 * Get address
	 *
	 * @return address $address
	 */
	public function getAddress();
}
