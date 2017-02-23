<?php

namespace WhiteOctober\SwiftMailerDBBundle\Spool;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use WhiteOctober\SwiftMailerDBBundle\EmailInterface;

/**
 * Class DatabaseSpool
 * @package WhiteOctober\SwiftMailerDBBundle\Spool
 */
class DatabaseSpool extends \Swift_ConfigurableSpool
{
    /**
     * @var ManagerRegistry
     */
    protected $doc;

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
    protected $environment;

    /**
     * @param RegistryInterface $doc
     * @param string        $entityClass
     * @param string        $environment
     * @param bool          $keepSentMessages
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(RegistryInterface $doc, $entityClass, $environment, $keepSentMessages = false)
    {
        $this->doc              = $doc;
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
        $this->getManager()->persist($mailObject);
        $this->getManager()->flush();

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

        $repoClass = $this->getManager()->getRepository($this->entityClass);
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
            $this->getManager()->persist($email);
            $this->getManager()->flush();

            $message = unserialize($email->getMessage());
            $count += $transport->send($message, $failedRecipients);
            if ($this->keepSentMessages === true) {
                $email->setStatus(EmailInterface::STATUS_COMPLETE);
                $this->getManager()->persist($email);
            } else {
                $this->getManager()->remove($email);
            }
            $this->getManager()->flush();

            if ($this->getTimeLimit() && (time() - $time) >= $this->getTimeLimit()) {
                break;
            }
        }

        return $count;
    }

    /**
     * @return ObjectManager
     */
    protected function getManager()
    {
        return $this->doc->getManagerForClass($this->entityClass);
    }
}
