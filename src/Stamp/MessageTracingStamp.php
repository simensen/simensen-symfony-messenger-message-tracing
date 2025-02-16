<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Stamp;

use Simensen\MessageTracing\Behavior\Trace\TraceComparisonBehavior;
use Simensen\MessageTracing\Behavior\Trace\TraceGenerationBehavior;
use Simensen\MessageTracing\Behavior\Trace\TraceGettersBehavior;
use Simensen\MessageTracing\Trace;
use Simensen\MessageTracing\TraceIdentityComparator;
use Simensen\SymfonyMessenger\MessageTracing\TraceIdentity\SymfonyUidIdentityComparator;
use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * @template TIdentity
 *
 * @implements Trace<TIdentity>
 */
class MessageTracingStamp implements Trace, StampInterface
{
    /**
     * @use TraceGenerationBehavior<TIdentity>
     */
    use TraceGenerationBehavior;

    /**
     * @use TraceComparisonBehavior<TIdentity>
     */
    use TraceComparisonBehavior;

    use TraceGettersBehavior;

    /**
     * @return TraceIdentityComparator<TIdentity>
     */
    protected function getDefaultTraceIdentityComparator(): TraceIdentityComparator
    {
        return new SymfonyUidIdentityComparator(); /* @phpstan-ignore return.type */
    }
}
