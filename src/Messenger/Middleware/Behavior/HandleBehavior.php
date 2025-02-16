<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Messenger\Middleware\Behavior;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackInterface;

trait HandleBehavior
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        return $this->tracedEnvelopeManager->pop(
            $stack->next()->handle(
                $this->tracedEnvelopeManager->push($envelope),
                $stack
            )
        );
    }
}
