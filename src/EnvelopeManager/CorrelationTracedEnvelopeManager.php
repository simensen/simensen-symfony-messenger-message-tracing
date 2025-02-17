<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager;

use Simensen\MessageTracing\TracedContainerManager\Behavior\CorrelationTracedContainerManagerBehavior;
use Simensen\MessageTracing\TracedContainerManager\TracedContainerManager;
use Simensen\MessageTracing\TraceStack\TraceStack;
use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\Behavior\DefaultTracedEnvelopeBehavior;
use Symfony\Component\Messenger\Envelope;

/**
 * @template TIdentity
 *
 * @implements TracedContainerManager<Envelope,TIdentity>
 */
class CorrelationTracedEnvelopeManager implements TracedContainerManager
{
    /**
     * @use DefaultTracedEnvelopeBehavior<TIdentity>
     */
    use DefaultTracedEnvelopeBehavior;

    /**
     * @use CorrelationTracedContainerManagerBehavior<Envelope,TIdentity>
     */
    use CorrelationTracedContainerManagerBehavior;

    /**
     * @param TraceStack<TIdentity> $traceStack
     */
    public function __construct(protected readonly TraceStack $traceStack)
    {
    }
}
