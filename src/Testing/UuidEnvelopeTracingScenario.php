<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Testing;

use Simensen\MessageTracing\Testing\MessageTracingScenario;
use Simensen\MessageTracing\TraceStack\Adapter\DefaultTraceStack;
use Simensen\MessageTracing\TraceStack\Adapter\SpyingTraceStack;
use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\CausationTracedEnvelopeManager;
use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\CorrelationTracedEnvelopeManager;
use Simensen\SymfonyMessenger\MessageTracing\Stamp\UuidMessageTracingStampGenerator;
use Simensen\SymfonyMessenger\MessageTracing\TraceIdentity\UuidTraceIdentityGenerator;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Uid\Uuid;

/**
 * @extends MessageTracingScenario<Envelope,Uuid>
 */
final readonly class UuidEnvelopeTracingScenario extends MessageTracingScenario
{
    public static function create(): self
    {
        $traceIdentityGenerator = new UuidTraceIdentityGenerator();
        $traceGenerator = new UuidMessageTracingStampGenerator();
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
