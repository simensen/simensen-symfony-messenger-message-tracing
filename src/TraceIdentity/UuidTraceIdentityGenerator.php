<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\TraceIdentity;

use Simensen\MessageTracing\TraceIdentity\TraceIdentityGenerator;
use Symfony\Component\Uid\Uuid;

/**
 * @implements TraceIdentityGenerator<Uuid>
 */
class UuidTraceIdentityGenerator implements TraceIdentityGenerator
{
    public function generateTraceIdentity(): mixed
    {
        return Uuid::v7();
    }
}
