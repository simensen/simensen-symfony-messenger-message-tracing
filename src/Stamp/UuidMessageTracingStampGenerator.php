<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Stamp;

use Simensen\MessageTracing\Trace\Trace;
use Simensen\MessageTracing\TraceIdentity\TraceIdentityGenerator;
use Symfony\Component\Uid\Uuid;

/**
 * @extends MessageTracingStampGenerator<Uuid>
 */
class UuidMessageTracingStampGenerator extends MessageTracingStampGenerator
{
    public function generateTrace(TraceIdentityGenerator $traceIdentityGenerator): Trace
    {
        return UuidMessageTracingStamp::start($traceIdentityGenerator);
    }
}