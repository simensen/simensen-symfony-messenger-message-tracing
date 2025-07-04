<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Stamp;

use Simensen\MessageTracing\Trace\Trace;
use Simensen\MessageTracing\TraceIdentity\TraceIdentityGenerator;
use Simensen\SymfonyMessenger\MessageTracing\Uid\Ulid;

/**
 * @extends MessageTracingStampGenerator<Ulid>
 */
class UlidMessageTracingStampGenerator extends MessageTracingStampGenerator
{
    public function generateTrace(TraceIdentityGenerator $traceIdentityGenerator): Trace
    {
        return UlidMessageTracingStamp::start($traceIdentityGenerator);
    }
}
