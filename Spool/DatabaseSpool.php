<?php

namespace WhiteOctober\SwiftMailerDBBundle\Spool;

use Doctrine\ORM\EntityManager;
use WhiteOctober\SwiftMailerDBBundle\EmailInterface;

class DatabaseSpool extends \Swift_ConfigurableSpool
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var boolean
     */
    protected $keepSentMessages;

    /**
     * @var string
     */
    private $environment;

    /**
     * @param EntityManager $em
     * @param string        $entityClass
     * @param string        $environment
     * @param bool          $keepSentMessages
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(EntityManager $em, $entityClass, $environment, $keepSentMessages = false)
    {
        $this->em               = $em;
        $this->keepSentMessages = $keepSentMessages;

        $obj = new $entityClass;
        if (!$obj instanceof EmailInterface) {
            throw new \InvalidArgumentException("The entity class '{$entityClass}'' does not extend from EmailInterface");
        }

        $this->entityClass = $entityClass;
        $this->environment = $environment;
    }

    /**
     * Starts this Spool mechanism.
     */
    public function start()
    {
    }

    /**
     * Stops this Spool mechanism.
     */
    public function stop()
    {
    }

    /**
     * Tests if this Spool mechanism has started.
     *
     * @return boolean
     */
    public function isStarted()
    {
        return true;
    }

    /**
     * Queues a message.
     *
     * @param \Swift_Mime_Message $message The message to store
     * @return boolean Whether the operation has succeeded
     * @throws \Swift_IoException if the persist fails
     */
    public function queueMessage(\Swift_Mime_Message $message)
    {
        $mailObject = new $this->entityClass;
        $mailObject->setMessage(serialize($message));
        $mailObject->setStatus(EmailInterface::STATUS_READY);
        $mailObject->setEnvironment($this->environment);
        try {
            $this->em->persist($mailObject);
            $this->em->flush();
        } catch (\Exception $e) {
            throw new \Swift_IoException("Unable to persist object for enqueuing message");
        }

        return true;
    }

    /**
     * Sends messages using the given transport instance.
     *
     * @param \Swift_Transport $transport         A transport instance
     * @param string[]        &$failedRecipients An array of failures by-reference
     *
     * @return int The number of sent emails
     */
    public function flushQueue(\Swift_Transport $transport, &$failedRecipients = null)
    {
        if (!$transport->isStarted())
        {
            $transport->start();
        }

        $repoClass = $this->em->getRepository($this->entityClass);
        $limit = $this->getMessageLimit();
        $limit = $limit > 0 ? $limit : null;
        $emails = $repoClass->findBy(
          array("status" => EmailInterface::STATUS_READY, "environment" => $this->environment),
          null,
          $limit
        );
        if (!count($emails)) {
            return 0;
        }

        $failedRecipients = (array) $failedRecipients;
        $count = 0;
        $time = time();
        foreach ($emails as $email) {
            $email->setStatus(EmailInterface::STATUS_PROCESSING);
            $this->em->persist($email);
            $this->em->flush();

            $message = unserialize($email->getMessage());
            $count += $transport->send($message, $failedRecipients);
            if ($this->keepSentMessages === true) {
                $email->setStatus(EmailInterface::STATUS_COMPLETE);
                $this->em->persist($email);
            } else {
                $this->em->remove($email);
            }
            $this->em->flush();

            if ($this->getTimeLimit() && (time() - $time) >= $this->getTimeLimit()) {
                break;
            }
        }

        return $count;
    }
}
