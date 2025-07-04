<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\StampInterface;

final class EnvelopeUtils
{
    /**
     * Get the last stamp of a given type, supporting inheritance.
     *
     * @template T of StampInterface
     *
     * @param class-string<T> $stampClass
     *
     * @return ?T
     */
    public static function last(Envelope $envelope, string $stampClass): ?StampInterface
    {
        // Process stamps in reverse order to get the last matching stamp
        foreach (array_reverse($envelope->all(), true) as $stamps) {
            foreach (array_reverse($stamps) as $stamp) {
                if ($stamp instanceof $stampClass) {
                    return $stamp;
                }
            }
        }

        return null;
    }

    /**
     * Remove all stamps of a given type (supporting inheritance) and add a new stamp.
     *
     * @template T of StampInterface
     *
     * @param class-string<T> $stampClass
     * @param T $newStamp
     */
    public static function withOnly(Envelope $envelope, string $stampClass, StampInterface $newStamp): Envelope
    {
        // Remove all stamps that are instances of the given class
        $newEnvelope = $envelope;
        foreach ($envelope->all() as $concreteStampClass => $stamps) {
            foreach ($stamps as $stamp) {
                if ($stamp instanceof $stampClass) {
                    $newEnvelope = $newEnvelope->withoutStampsOfType($concreteStampClass);
                    break; // Only need to remove once per stamp class
                }
            }
        }

        return $newEnvelope->with($newStamp);
    }
}
