<?php

namespace Lauchoit\LaravelHexMod\SendNotification\Infrastructure\Repository\UserCases;

use Illuminate\Support\Facades\Mail;
use Lauchoit\LaravelHexMod\SendNotification\Domain\Factory\EmailMessageBuilder;

class SendEmailUseCaseImpl
{
    /**
     * @throws \Exception
     */
    public function sendNotification(EmailMessageBuilder $message): bool
    {
        try {
            Mail::html($message->getHtml(), function ($m) use ($message) {
                $m->from(config('mail.from.address'), config('mail.from.name'));
                $m->to($message->getTo())->subject($message->getSubject());
                if ($message->getReplyTo()) {
                    $m->replyTo($message->getReplyTo());
                }
                if (! empty($message->getCc())) {
                    $m->cc($message->getCc());
                }
                if (! empty($message->getBcc())) {
                    $m->bcc($message->getBcc());
                }

                foreach ($message->getAttachments() as $path) {
                    $m->attach($path);
                }
            });

            return true;
        } catch (\Exception $e) {
            \Log::channel('email')->error('Error enviando email: '.$e->getMessage());
            throw $e;
        }
    }
}
