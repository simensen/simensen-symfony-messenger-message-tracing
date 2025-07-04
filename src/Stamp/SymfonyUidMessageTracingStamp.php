<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Stamp;

use Simensen\MessageTracing\TraceIdentity\TraceIdentityComparator;
use Simensen\SymfonyMessenger\MessageTracing\TraceIdentity\SymfonyUidIdentityComparator;
use Simensen\SymfonyMessenger\MessageTracing\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

/**
 * @extends MessageTracingStamp<string|Uuid|Ulid>
 */
class SymfonyUidMessageTracingStamp extends MessageTracingStamp
{
    /**
     * @return TraceIdentityComparator<string|Uuid|Ulid>
     */
    protected function getDefaultTraceIdentityComparator(): TraceIdentityComparator
    {
        return new SymfonyUidIdentityComparator();
    }

    public static function supports(mixed $type): bool
    {
        assert(is_string($type) || is_object($type));

        return is_a($type, Uuid::class, true)
            || is_a($type, Ulid::class, true)
            || $type === 'string';
    }
}
