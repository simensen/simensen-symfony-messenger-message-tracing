<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Stamp;

use Simensen\MessageTracing\Trace\Behavior\TraceComparisonBehavior;
use Simensen\MessageTracing\Trace\Behavior\TraceGenerationBehavior;
use Simensen\MessageTracing\Trace\Behavior\TraceGettersBehavior;
use Simensen\MessageTracing\Trace\Trace;
use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * @template TIdentity
 *
 * @implements Trace<TIdentity>
 */
abstract class MessageTracingStamp implements Trace, StampInterface
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
}
