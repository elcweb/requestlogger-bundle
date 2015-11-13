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
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $user = null;
        if ($this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();
        }

        $request = new Request($event->getRequest(), $user);
        $this->entityManager->persist($request);
        $this->entityManager->flush();
    }

    /**
     * @param PostResponseEvent $event
     */
    public function onKernelTerminate(PostResponseEvent $event)
    {
        $user = null;
        if ($this->tokenStorage->getToken()) {
            $user = $this->tokenStorage->getToken()->getUser();
        }

        $request = new Request($event->getRequest(), $event->getResponse(), $user);
        $this->entityManager->persist($request);
        $this->entityManager->flush();
    }
}
