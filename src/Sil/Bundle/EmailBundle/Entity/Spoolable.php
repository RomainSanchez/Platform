<?php

/*
 * This file is part of the Blast Project package.
 *
 * Copyright (C) 2015-2017 Libre Informatique
 *
 * This file is licenced under the GNU LGPL v3.
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Librinfo\EmailBundle\Entity;

use Librinfo\EmailBundle\Services\SwiftMailer\Spool\SpoolStatus;

abstract class Spoolable
{
    /**
     * @var Swift_Mime_Message
     */
    private $message;

    /**
     * @var string
     */
    private $messageId;

    /**
     * @var string
     */
    private $status;

    /**
     * @var string
     */
    private $environment;

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return string
     */
    public function getMessageId()
    {
        return $this->messageId;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param $message string Serialized \Swift_Mime_Message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param $messageId string Serialized \Swift_Mime_Message
     */
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
    }

    /**
     * @param $status string
     */
    public function setStatus($status)
    {
        if ($status == SpoolStatus::STATUS_COMPLETE) {
            $this->setSent(true);
        }
        $this->status = $status;
    }

    /**
     * @param $environment string
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }
}
