<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Testing;

use Simensen\MessageTracing\Adapter\DefaultTraceStack;
use Simensen\MessageTracing\Adapter\SpyingTraceStack;
use Simensen\MessageTracing\Testing\MessageTracingScenario;
use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\CausationTracedEnvelopeManager;
use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\CorrelationTracedEnvelopeManager;
use Simensen\SymfonyMessenger\MessageTracing\Stamp\MessageTracingStampGenerator;
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
        /** @var MessageTracingStampGenerator<Uuid> $traceGenerator */
        $traceGenerator = new MessageTracingStampGenerator();
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
