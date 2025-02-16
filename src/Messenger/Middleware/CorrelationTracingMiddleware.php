<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Messenger\Middleware;

use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\CorrelationTracedEnvelopeManager;
use Simensen\SymfonyMessenger\MessageTracing\Messenger\Middleware\Behavior\HandleBehavior;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;

/**
 * @template TIdentity
 */
class CorrelationTracingMiddleware implements MiddlewareInterface, TracingMiddleware
{
    use HandleBehavior;

    /**
     * @param CorrelationTracedEnvelopeManager<TIdentity> $tracedEnvelopeManager
     */
    public function __construct(
        protected readonly CorrelationTracedEnvelopeManager $tracedEnvelopeManager,
    ) {
    }
}
