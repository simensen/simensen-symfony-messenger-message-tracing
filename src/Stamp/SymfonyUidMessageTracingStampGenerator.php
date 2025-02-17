<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Stamp;

use Simensen\MessageTracing\Trace\Trace;
use Simensen\MessageTracing\TraceIdentity\TraceIdentityGenerator;
use Simensen\SymfonyMessenger\MessageTracing\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

/**
 * @extends MessageTracingStampGenerator<string|Uuid|Ulid>
 */
class SymfonyUidMessageTracingStampGenerator extends MessageTracingStampGenerator
{
    public function generateTrace(TraceIdentityGenerator $traceIdentityGenerator): Trace
    {
        return SymfonyUidMessageTracingStamp::start($traceIdentityGenerator);
    }
}
