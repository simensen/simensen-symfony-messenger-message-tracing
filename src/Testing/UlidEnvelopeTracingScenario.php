<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Testing;

use Simensen\MessageTracing\Testing\MessageTracingScenario;
use Simensen\MessageTracing\TraceStack\Adapter\DefaultTraceStack;
use Simensen\MessageTracing\TraceStack\Adapter\SpyingTraceStack;
use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\CausationTracedEnvelopeManager;
use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\CorrelationTracedEnvelopeManager;
use Simensen\SymfonyMessenger\MessageTracing\Stamp\UlidMessageTracingStampGenerator;
use Simensen\SymfonyMessenger\MessageTracing\TraceIdentity\UlidTraceIdentityGenerator;
use Simensen\SymfonyMessenger\MessageTracing\Uid\Ulid;
use Symfony\Component\Messenger\Envelope;

/**
 * @extends MessageTracingScenario<Envelope,Ulid>
 */
final readonly class UlidEnvelopeTracingScenario extends MessageTracingScenario
{
    public static function create(): self
    {
        $traceIdentityGenerator = new UlidTraceIdentityGenerator();
        $traceGenerator = new UlidMessageTracingStampGenerator();
        $traceStack = new DefaultTraceStack(
            $traceGenerator,
            $traceIdentityGenerator
        );

        $spyingTraceStack = new SpyingTraceStack($traceStack);

        $causationTracedContainerManager = new CausationTracedEnvelopeManager($spyingTraceStack);
        $correlationTracedContainerManager = new CorrelationTracedEnvelopeManager($spyingTraceStack);

        return new self(
            $traceIdentityGenerator,
            $traceGenerator,
            $traceStack,
            $spyingTraceStack,
            $causationTracedContainerManager,
            $correlationTracedContainerManager,
        );
    }
}
