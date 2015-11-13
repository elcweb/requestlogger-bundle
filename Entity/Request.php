<?php

namespace Elcweb\RequestLoggerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Request
 * @package Evolocity\UserBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="Requests")
 */
class Request
{
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     * @ORM\Column(type="object")
     */
    protected $request;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     * @ORM\Column(type="object")
     */
    protected $response;

    /**
     * @var object
     * @ORM\Column(type="object")
     */
    protected $user;

    /**
     * Request constructor.
     *
     * @param SymfonyRequest     $request
     * @param Response           $response
     * @param UserInterface|null $user
     */
    public function __construct(SymfonyRequest $request, Response $response, UserInterface $user = null)
    {
        $this->request = $request;
        $this->response = $response;
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }
}