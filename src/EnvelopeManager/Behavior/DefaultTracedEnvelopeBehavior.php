<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\Behavior;

use Simensen\MessageTracing\Trace\Trace;
use Simensen\MessageTracing\TracedContainerManager\Behavior\DefaultTracedContainerManagerBehavior;
use Simensen\SymfonyMessenger\MessageTracing\Stamp\MessageTracingStamp;
use Symfony\Component\Messenger\Envelope;

/**
 * @template TIdentity
 */
trait DefaultTracedEnvelopeBehavior
{
    /**
     * @use DefaultTracedContainerManagerBehavior<Envelope,TIdentity>
     */
    use DefaultTracedContainerManagerBehavior;

    /**
     * @param Envelope $container
     *
     * @return ?MessageTracingStamp<TIdentity>
     */
    protected function extractTraceFromContainer(mixed $container): ?Trace
    {
        return $container->last(MessageTracingStamp::class);
    }

    /**
     * @param Envelope $container
     * @param Trace<TIdentity> $trace
     *
     * @return Envelope
     */
    protected function injectTraceIntoContainer(mixed $container, Trace $trace): mixed
    {
        assert($trace instanceof MessageTracingStamp);

        return $container
            ->withoutStampsOfType(MessageTracingStamp::class)
            ->with($trace);
    }
}
