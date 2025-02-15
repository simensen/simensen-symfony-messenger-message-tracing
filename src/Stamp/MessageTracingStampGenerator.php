<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Stamp;

use Simensen\MessageTracing\Trace;
use Simensen\MessageTracing\TraceGenerator;
use Simensen\MessageTracing\TraceIdentityGenerator;

/**
 * @template TIdentity
 *
 * @implements TraceGenerator<TIdentity>
 */
class MessageTracingStampGenerator implements TraceGenerator
{
    public function generateTrace(TraceIdentityGenerator $traceIdentityGenerator): Trace
    {
        return MessageTracingStamp::start($traceIdentityGenerator);
    }
}
