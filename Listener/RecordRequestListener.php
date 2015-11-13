<?php

namespace Elcweb\RequestLoggerBundle\Listener;

use Doctrine\ORM\EntityManager;
use Elcweb\RequestLoggerBundle\Entity\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class RecordRequestListener
 * @package Elcweb\RequestLoggerBundle\Listener
 */
class RecordRequestListener
{
    /** @var TokenStorageInterface  */
    private $tokenStorage;

    /** @var EntityManager  */
    private $entityManager;

    /**
     * RecordRequestListener constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     * @param EntityManager         $entityManager
     */
    public function __construct(TokenStorageInterface $tokenStorage, EntityManager $entityManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
    }

    /**
     * @param PostResponseEvent $event
     */
    public function onKernelTerminate(PostResponseEvent $event)
    {
        $request = new Request($event->getRequest(), $event->getResponse(), $this->getUsername());
        $this->entityManager->persist($request);
        $this->entityManager->flush();
    }

    /**
     * Return the current User username or null if the username is unavailable
     * 
     * @return string|null
     */
    private function getUsername()
    {
        $token = $this->tokenStorage->getToken();
        if ($token && $token->getUser()) {
            if (method_exists($token->getUser(), 'getUsername')) {
                return $token->getUser()->getUsername();
            }
        }

        return null;
    }
}
