<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\TraceIdentity;

use Simensen\MessageTracing\Trace\Trace;
use Simensen\MessageTracing\TraceIdentity\Adapter\StringableTraceIdentityComparator;
use Simensen\MessageTracing\TraceIdentity\TraceIdentityComparator;
use Simensen\SymfonyMessenger\MessageTracing\Uid\Ulid;
use Symfony\Component\Uid\Uuid;

/**
 * @template TIdentity of string|Uuid|Ulid
 *
 * @implements TraceIdentityComparator<TIdentity>
 */
class SymfonyUidIdentityComparator implements TraceIdentityComparator
{
    private StringableTraceIdentityComparator $stringableTraceIdentityComparator;

    public function areEqual(mixed $one, mixed $two): bool
    {
        if ($one instanceof Trace) {
            $one = $one->getId();
        }

        if ($two instanceof Trace) {
            $two = $two->getId();
        }

        if ($one instanceof Uuid || $two instanceof Uuid) {
            if ($one instanceof Uuid && $two instanceof Uuid) {
                return $one->equals($two);
            }

            return false;
        }

        return $this->getStringableTraceIdentityComparator()->areEqual($one, $two);
    }

    private function getStringableTraceIdentityComparator(): StringableTraceIdentityComparator
    {
        return $this->stringableTraceIdentityComparator ??= new StringableTraceIdentityComparator();
    }

    public function areNotEqual(mixed $one, mixed $two): bool
    {
        return !$this->areEqual($one, $two);
    }
}
