<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager;

use Simensen\MessageTracing\Behavior\TracedContainerManager\CausationTracedContainerManagerBehavior;
use Simensen\MessageTracing\TracedContainerManager;
use Simensen\MessageTracing\TraceStack;
use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\Behavior\DefaultTracedEnvelopeBehavior;
use Symfony\Component\Messenger\Envelope;

/**
 * @template TIdentity
 *
 * @implements TracedContainerManager<Envelope,TIdentity>
 */
class CausationTracedEnvelopeManager implements TracedContainerManager
{
    /**
     * @use DefaultTracedEnvelopeBehavior<TIdentity>
     */
    use DefaultTracedEnvelopeBehavior;

    /**
     * @use CausationTracedContainerManagerBehavior<Envelope,TIdentity>
     */
    use CausationTracedContainerManagerBehavior;

    /**
     * @param TraceStack<TIdentity> $traceStack
     */
    public function __construct(protected readonly TraceStack $traceStack)
    {
    }
}
