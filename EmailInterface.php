<?php

namespace WhiteOctober\SwiftMailerDBBundle;

/**
 * Interface EmailInterface
 * @package WhiteOctober\SwiftMailerDBBundle
 */
interface EmailInterface
{
    const STATUS_FAILED = -1;
    const STATUS_READY = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_COMPLETE = 3;

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @return string
     */
    public function getEnvironment();

    /**
     * @param $message string Serialized \Swift_Mime_Message
     */
    public function setMessage($message);

    /**
     * @param $status string
     */
    public function setStatus($status);

    /**
     * @param $environment string
     */
    public function setEnvironment($environment);
}
