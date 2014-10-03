<?php

namespace WhiteOctober\SwiftMailerDBBundle;

interface EmailInterface
{
    const STATUS_FAILED = -1;
    const STATUS_READY = 1;
    const STATUS_PROCESSING = 2;
    const STATUS_COMPLETE = 3;

    public function getMessage();

    public function getStatus();

    public function getEnvironment();
}
