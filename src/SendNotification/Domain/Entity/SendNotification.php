<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Domain\Entity;

readonly class SendNotification
{
    /**
     * @var int
     */
    private string $id;

    private string $to;

    private string $subject;

    private string $body;

    private array $cc;

    private array $bcc;

    private array $attachments;

    private string $replyTo;

    private string $channel;

    private string $createdAt;

    private string $updatedAt;

    /**
     * @param  string  $id
     * @param  string  $to
     * @param  string  $subject
     * @param  string  $body
     * @param  string  $replyTo
     * @param  array  $data
     * @param  string  $channel
     * @param  string  $createdAt
     * @param  string  $updatedAt
     */
    public function __construct($id, $to, $subject, $body,
        array $cc,
        array $bcc,
        array $attachments, $replyTo, $channel, $createdAt, $updatedAt
    ) {
        $this->id = $id;
        $this->to = $to;
        $this->subject = $subject;
        $this->body = $body;
        $this->cc = $cc;
        $this->bcc = $bcc;
        $this->attachments = $attachments;
        $this->replyTo = $replyTo;
        $this->channel = $channel;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getCc(): array
    {
        return $this->cc;
    }

    public function getBcc(): array
    {
        return $this->bcc;
    }

    public function getAttachments(): array
    {
        return $this->attachments;
    }

    public function getReplyTo(): string
    {
        return $this->replyTo;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): string
    {
        return $this->updatedAt;
    }

    /**
     * @return int
     */
    public function getId(): string
    {
        return $this->id;
    }

    public function getTo(): string
    {
        return $this->to;
    }
}
