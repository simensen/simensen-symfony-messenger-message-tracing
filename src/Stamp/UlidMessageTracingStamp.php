<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Stamp;

use Simensen\MessageTracing\TraceIdentity\TraceIdentityComparator;
use Simensen\SymfonyMessenger\MessageTracing\TraceIdentity\SymfonyUidIdentityComparator;
use Simensen\SymfonyMessenger\MessageTracing\Uid\Ulid;

/**
 * @extends MessageTracingStamp<Ulid>
 */
class UlidMessageTracingStamp extends MessageTracingStamp
{
    /**
     * @return TraceIdentityComparator<Ulid>
     */
    protected function getDefaultTraceIdentityComparator(): TraceIdentityComparator
    {
        /** @var SymfonyUidIdentityComparator<Ulid> */
        return new SymfonyUidIdentityComparator();
    }

    public static function supports(mixed $type): bool
    {
        assert(is_string($type) || is_object($type));

        return is_a($type, Ulid::class, true);
    }
}