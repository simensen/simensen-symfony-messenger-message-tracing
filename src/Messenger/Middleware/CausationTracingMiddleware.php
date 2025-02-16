<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Messenger\Middleware;

use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\CausationTracedEnvelopeManager;
use Simensen\SymfonyMessenger\MessageTracing\Messenger\Middleware\Behavior\HandleBehavior;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;

/**
 * @template TIdentity
 */
class CausationTracingMiddleware implements MiddlewareInterface, TracingMiddleware
{
    use HandleBehavior;

    /**
     * @param CausationTracedEnvelopeManager<TIdentity> $tracedEnvelopeManager
     */
    public function __construct(
        protected readonly CausationTracedEnvelopeManager $tracedEnvelopeManager,
    ) {
    }
}
