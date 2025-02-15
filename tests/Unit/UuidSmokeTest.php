<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Simensen\MessageTracing\Testing\MessageTracingScenario;
use Simensen\MessageTracing\Testing\MessageTracingScenarioBehavior;
use Simensen\SymfonyMessenger\MessageTracing\Stamp\MessageTracingStamp;
use Simensen\SymfonyMessenger\MessageTracing\Testing\UuidEnvelopeTracingScenario;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Uid\Uuid;

class UuidSmokeTest extends TestCase
{
    /**
     * @use MessageTracingScenarioBehavior<Envelope,Uuid>
     */
    use MessageTracingScenarioBehavior;

    protected static function buildMessageTracingScenario(): MessageTracingScenario
    {
        return UuidEnvelopeTracingScenario::create();
    }

    public function testBasicFunctionality(): void
    {
        $envelopeOne = Envelope::wrap(new \stdClass());

        self::assertNull($envelopeOne->last(MessageTracingStamp::class));
        self::assertTrue($this->messageTracingScenario()->getTraceStack()->isEmpty());

        $envelopeOne = $this->messageTracingScenario()->getCausationTracedContainerManager()->push($envelopeOne);

        self::assertNotNull($envelopeTracerOne = $envelopeOne->last(MessageTracingStamp::class));
        self::assertTrue($this->messageTracingScenario()->getTraceStack()->isNotEmpty());
        self::assertTrue($this->messageTracingScenario()->getTraceStack()->isTail($envelopeTracerOne));

        $envelopeTwo = Envelope::Wrap(new \stdClass());

        $envelopeTwo = $this->messageTracingScenario()->getCorrelationTracedContainerManager()->push($envelopeTwo);

        self::assertNotNull($envelopeTracerTwo = $envelopeTwo->last(MessageTracingStamp::class));
        self::assertFalse($envelopeTracerTwo->equals($envelopeTracerOne));
        self::assertTrue($this->messageTracingScenario()->getTraceStack()->isTail($envelopeTracerOne));

        $envelopeThree = Envelope::Wrap(new \stdClass());

        $envelopeThree = $this->messageTracingScenario()->getCausationTracedContainerManager()->push($envelopeThree);

        self::assertNotNull($envelopeTracerThree = $envelopeThree->last(MessageTracingStamp::class));
        self::assertFalse($envelopeTracerThree->equals($envelopeTracerOne));
        self::assertFalse($envelopeTracerThree->equals($envelopeTracerTwo));
        self::assertTrue($this->messageTracingScenario()->getTraceStack()->isTail($envelopeTracerThree));

        $envelopeThree = $this->messageTracingScenario()->getCausationTracedContainerManager()->pop($envelopeThree);

        self::assertTrue($this->messageTracingScenario()->getTraceStack()->isTail($envelopeTracerOne));

        $envelopeTwo = $this->messageTracingScenario()->getCausationTracedContainerManager()->pop($envelopeTwo);

        self::assertTrue($this->messageTracingScenario()->getTraceStack()->isTail($envelopeTracerOne));

        $envelopeOne = $this->messageTracingScenario()->getCausationTracedContainerManager()->pop($envelopeOne);

        self::assertTrue($this->messageTracingScenario()->getTraceStack()->isEmpty());
    }
}
