<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Stamp;


use Simensen\MessageTracing\Trace\TraceGenerator;

/**
 * @template TIdentity
 *
 * @implements TraceGenerator<TIdentity>
 */
abstract class MessageTracingStampGenerator implements TraceGenerator
{
}
