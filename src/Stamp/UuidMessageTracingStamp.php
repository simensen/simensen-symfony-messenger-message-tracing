<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Stamp;

use Simensen\MessageTracing\TraceIdentity\TraceIdentityComparator;
use Simensen\SymfonyMessenger\MessageTracing\TraceIdentity\SymfonyUidIdentityComparator;
use Symfony\Component\Uid\Uuid;

/**
 * @extends MessageTracingStamp<Uuid>
 */
class UuidMessageTracingStamp extends MessageTracingStamp
{
    /**
     * @return TraceIdentityComparator<Uuid>
     */
    protected function getDefaultTraceIdentityComparator(): TraceIdentityComparator
    {
        /** @var SymfonyUidIdentityComparator<Uuid> */
        return new SymfonyUidIdentityComparator();
    }

    public static function supports(mixed $type): bool
    {
        assert(is_string($type) || is_object($type));

        return is_a($type, Uuid::class, true);
    }
}
