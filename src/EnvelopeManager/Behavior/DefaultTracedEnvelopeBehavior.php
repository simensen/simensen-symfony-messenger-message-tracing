<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\Behavior;

use Simensen\MessageTracing\Behavior\TracedContainerManager\DefaultTracedContainerManagerBehavior;
use Simensen\MessageTracing\Trace;
use Simensen\SymfonyMessenger\MessageTracing\Stamp\MessageTracingStamp;
use Symfony\Component\Messenger\Envelope;

/**
 * @template T
 */
trait DefaultTracedEnvelopeBehavior
{
    /**
     * @use DefaultTracedContainerManagerBehavior<Envelope,T>
     */
    use DefaultTracedContainerManagerBehavior;

    /**
     * @param Envelope $container
     *
     * @return ?MessageTracingStamp<T>
     */
    protected function extractTraceFromContainer(mixed $container): ?Trace
    {
        return $container->last(MessageTracingStamp::class);
    }

    /**
     * @param Envelope $container
     * @param Trace<T> $trace
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
