<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Stamp;

use Simensen\MessageTracing\Behavior\Trace\TraceComparisonBehavior;
use Simensen\MessageTracing\Behavior\Trace\TraceGenerationBehavior;
use Simensen\MessageTracing\Behavior\Trace\TraceGettersBehavior;
use Simensen\MessageTracing\Trace;
use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * @template T
 *
 * @implements Trace<T>
 */
class MessageTracingStamp implements Trace, StampInterface
{
    /**
     * @use TraceGenerationBehavior<T>
     */
    use TraceGenerationBehavior;
    use TraceGettersBehavior;
    use TraceComparisonBehavior;
}
