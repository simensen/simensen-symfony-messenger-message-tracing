<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\TraceIdentity;

use Simensen\MessageTracing\TraceIdentity\TraceIdentityGenerator;
use Simensen\SymfonyMessenger\MessageTracing\Uid\Ulid;

/**
 * @implements TraceIdentityGenerator<Ulid>
 */
class UlidTraceIdentityGenerator implements TraceIdentityGenerator
{
    public function generateTraceIdentity(): mixed
    {
        return Ulid::generate();
    }
}
