<?php

declare(strict_types=1);

namespace Simensen\SymfonyMessenger\MessageTracing\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Simensen\MessageTracing\Testing\MessageTracingScenario;
use Simensen\MessageTracing\Testing\MessageTracingScenarioBehavior;
use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\EnvelopeUtils;
use Simensen\SymfonyMessenger\MessageTracing\Stamp\MessageTracingStamp;
use Simensen\SymfonyMessenger\MessageTracing\Testing\UlidEnvelopeTracingScenario;
use Simensen\SymfonyMessenger\MessageTracing\Uid\Ulid;
use Symfony\Component\Messenger\Envelope;

class UlidSmokeTest extends TestCase
{
    /**
     * @use MessageTracingScenarioBehavior<Envelope,Ulid>
     */
    use MessageTracingScenarioBehavior;

    protected static function buildMessageTracingScenario(): MessageTracingScenario
    {
        return UlidEnvelopeTracingScenario::create();
    }

    public function testBasicFunctionality(): void
    {
        $envelopeOne = Envelope::wrap(new \stdClass());

        self::assertNull(EnvelopeUtils::last($envelopeOne, MessageTracingStamp::class));
        self::assertTrue($this->messageTracingScenario()->getTraceStack()->isEmpty());

        $envelopeOne = $this->messageTracingScenario()->getCausationTracedContainerManager()->push($envelopeOne);

        self::assertNotNull($envelopeTracerOne = EnvelopeUtils::last($envelopeOne, MessageTracingStamp::class));
        self::assertTrue($this->messageTracingScenario()->getTraceStack()->isNotEmpty());
        self::assertTrue($this->messageTracingScenario()->getTraceStack()->isTail($envelopeTracerOne));

        $envelopeTwo = Envelope::Wrap(new \stdClass());

        $envelopeTwo = $this->messageTracingScenario()->getCorrelationTracedContainerManager()->push($envelopeTwo);

        self::assertNotNull($envelopeTracerTwo = EnvelopeUtils::last($envelopeTwo, MessageTracingStamp::class));
        self::assertFalse($envelopeTracerTwo->equals($envelopeTracerOne));
        self::assertTrue($this->messageTracingScenario()->getTraceStack()->isTail($envelopeTracerOne));

        $envelopeThree = Envelope::Wrap(new \stdClass());

        $envelopeThree = $this->messageTracingScenario()->getCausationTracedContainerManager()->push($envelopeThree);

        self::assertNotNull($envelopeTracerThree = EnvelopeUtils::last($envelopeThree, MessageTracingStamp::class));
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
