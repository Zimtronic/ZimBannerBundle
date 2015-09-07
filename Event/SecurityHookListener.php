<?php
namespace Zimtronic\BannerBundle\Event;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Zimtronic\BannerBundle\Document\Mongo\BannedAddress;
use Zimtronic\BannerBundle\Document\Mongo\Incident;

class SecurityHookListener implements EventSubscriberInterface
{
	/**
	 * Service container
	 */
	private $container;
	
	/**
	 * MongoDB manager
	 */
	private $manager;
	
	/**
	 * Incident life time
	 */
	private $incidentLifeTime;
	
	/**
	 * Number of incidents to ban source address
	 */
	private $incidentsToBan;
	
	/**
	 * Amount of time in seconds applyed for a banned address.
	 */
	private $banTimeFrame;
	
	/**
	 * Constructor
	 */
	public function __construct($service_container) 
	{
		$this->container = $service_container;
		$this->manager = $this->container->get('doctrine_mongodb')->getManager();
		
		$this->incidentLifeTime = $this->container->getParameter('zimban.incident.lifetime');
		$this->incidentsToBan = $this->container->getParameter('zimban.banner.incidents_to_ban');
		$this->banTimeFrame = $this->container->getParameter('zimban.banner.ban_time_frame');
	}
	
	/**
	 * getSubscribedEvents
	 *
	 * @return 	array
	 */
	public static function getSubscribedEvents()
	{
		return array(
				AuthenticationEvents::AUTHENTICATION_FAILURE => array('onAuthenticationFailure', 2048), //before anything
				KernelEvents::REQUEST => array('onKernelRequest', 2048), //before anything.
				KernelEvents::EXCEPTION => array('onKernelException', 2048) //before anything.
		);
	}
	
	/**
	 * onAuthenticationFailure
	 *
	 * @param 	AuthenticationFailureEvent $event
	 */
	public function onAuthenticationFailure( AuthenticationFailureEvent $event )
	{
		// executes on failed login
		$request = $this->container->get('request');
		$type = Incident::$SECURITY_INCIDENT_LOGIN_FAILURE;
		$token = $event->getAuthenticationToken();
		$username = strval($token->getUser());
		$this->registerSecurityIncident($request, $type, $username);
	}
	
	/**
	 * onKernelRequest
	 *
	 * @param 	AuthenticationFailureEvent $event
	 */
	public function onKernelRequest(GetResponseEvent $event)
	{
		$request = $event->getRequest();
		
		if (!$this->isRequestAllowedToProceed($request)) {
			$event->setResponse(new Response('The access is temporary disabled due to activity from your ip, try it later.', 403));
		}
	}
	
	public function onKernelException(GetResponseForExceptionEvent $event)
	{
		$exception = $event->getException();
		$request = $event->getRequest();
	
		if ($exception instanceof NotFoundHttpException) {
			$type = Incident::$SECURITY_INCIDENT_BAD_URL;
			$uri = $request->getRequestUri();
			$this->registerSecurityIncident($request, $type, $uri);
		}
	}
	
	/**
	 * registerSecurityIncident
	 * 
	 * @param Request $request
	 * @param type $type
	 * @param string $info
	 */
	private function registerSecurityIncident($request, $type, $info) {
		$clientIp = $request->getClientIp();

		$expiration = $this->getExpirationDate($this->incidentLifetime);
		
		$incident = new Incident();
		$incident->setAddress($clientIp);
		$incident->setType($type);
		$incident->setExpandedInfo($info);
		$incident->setExpireAt($expiration);
		$this->manager->persist($incident);
		
		$attempts = $this->manager->createQueryBuilder('SprocketBaseBundle:Security\Incident')
							->field('address')->equals($clientIp)
							->count()->getQuery()->execute();
		
		$max = 5;
		if ($attempts >= ($max-1)) { //Wait to make just one flush.
			$expiration = $this->getExpirationDate($this->banTimeFrame);
			
			$banned = new BannedAddress();
			$banned->setAddress($clientIp);
			$banned->setExpireAt($expiration);
			$this->manager->persist($banned);
		}
		
		$this->manager->flush();
	}
	
	/**
	 * registerSecurityIncident
	 *
	 * @param Request $request
	 * @return boolean
	 */
	private function isRequestAllowedToProceed($request) {
		$clientIp = $request->getClientIp();
		$banned = $this->manager->createQueryBuilder('SprocketBaseBundle:Security\BannedAddress')
						 ->field('address')->equals($clientIp)
						 ->count()->getQuery()->execute();
		
		return ($banned == 0);
	}

	/**
	 * get expiration date
	 * 
	 * @param int $offset
	 * @return \DateTime
	 */
	private function getExpirationDate($offset) {
		$expiration = new \DateTime();
		$expiration->modify('+'.strval($offset).' seconds');
		return $expiration;
	}
}
