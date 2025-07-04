# Symfony Messenger Message Tracing

[![PHP Version](https://img.shields.io/badge/php-%3E%3D8.2-blue.svg)](https://php.net/)
[![Symfony](https://img.shields.io/badge/symfony-%5E6.4%20%7C%7C%20%5E7.0-green.svg)](https://symfony.com/)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

A Symfony Messenger extension that provides **causation** and **correlation** tracing capabilities for message handling. This library extends the core [`simensen/message-tracing`](https://github.com/simensen/message-tracing) library specifically for Symfony Messenger integration.

## Features

- **Causation Tracing**: Track the chain of messages that cause other messages
- **Correlation Tracing**: Group related messages under a common correlation ID
- **UUID/ULID Support**: Built-in support for Symfony UID components
- **Middleware Integration**: Easy integration with Symfony Messenger middleware stack
- **Type Safety**: Full PHP 8.2+ type safety with generics
- **Testing Support**: Comprehensive testing scenarios and utilities

## Installation

Install via Composer:

```bash
composer require simensen/symfony-messenger-message-tracing
```

## Quick Start

### 1. Basic Setup

```php
use Simensen\SymfonyMessenger\MessageTracing\Stamp\SymfonyUidMessageTracingStampGenerator;
use Simensen\SymfonyMessenger\MessageTracing\TraceIdentity\UuidTraceIdentityGenerator;
use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\CausationTracedEnvelopeManager;
use Simensen\MessageTracing\TraceStack\Adapter\DefaultTraceStack;

// Create the trace identity generator
$identityGenerator = new UuidTraceIdentityGenerator();

// Create the stamp generator
$stampGenerator = new SymfonyUidMessageTracingStampGenerator();

// Create the trace stack
$traceStack = new DefaultTraceStack($stampGenerator, $identityGenerator);

// Create envelope managers
$causationManager = new CausationTracedEnvelopeManager($traceStack);
$correlationManager = new CorrelationTracedEnvelopeManager($traceStack);
```

### 2. Using with Envelopes

```php
use Symfony\Component\Messenger\Envelope;
use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\EnvelopeUtils;
use Simensen\SymfonyMessenger\MessageTracing\Stamp\MessageTracingStamp;

// Create a message envelope
$envelope = Envelope::wrap(new YourMessage());

// Add causation tracing
$envelope = $causationManager->push($envelope);

// Retrieve the tracing stamp (inheritance-aware)
$tracingStamp = EnvelopeUtils::last($envelope, MessageTracingStamp::class);

echo "Message ID: " . $tracingStamp->getId();
echo "Causation ID: " . $tracingStamp->getCausationId();
echo "Correlation ID: " . $tracingStamp->getCorrelationId();
```

### 3. Middleware Integration

```php
use Simensen\SymfonyMessenger\MessageTracing\Messenger\Middleware\CausationTracingMiddleware;
use Simensen\SymfonyMessenger\MessageTracing\Messenger\Middleware\CorrelationTracingMiddleware;

// In your Symfony configuration
$bus = new MessageBus([
    new CausationTracingMiddleware($causationManager),
    new CorrelationTracingMiddleware($correlationManager),
    // ... other middleware
]);
```

## Architecture

### Core Components

#### Message Tracing Stamps
- **`MessageTracingStamp`** - Abstract base class implementing both `Trace` and `StampInterface`
- **`SymfonyUidMessageTracingStamp`** - Concrete implementation using Symfony UID components
- **Stamp generators** - Handle creation of tracing stamps

#### Envelope Managers
- **`CausationTracedEnvelopeManager`** - Manages causation tracing for message envelopes
- **`CorrelationTracedEnvelopeManager`** - Manages correlation tracing for message envelopes
- Uses behavior traits from the core message-tracing library

#### Middleware
- **`CausationTracingMiddleware`** - Symfony Messenger middleware for causation tracing
- **`CorrelationTracingMiddleware`** - Symfony Messenger middleware for correlation tracing
- **`TracingMiddleware`** - Interface for tracing middleware implementations

#### Identity Management
- **`UlidTraceIdentityGenerator`** - Generates ULID-based trace identities
- **`UuidTraceIdentityGenerator`** - Generates UUID-based trace identities
- **`SymfonyUidIdentityComparator`** - Compares Symfony UID instances

### EnvelopeUtils - Inheritance-Aware Stamp Operations

Symfony Messenger's built-in stamp methods don't support inheritance. This library provides `EnvelopeUtils` to work around this limitation:

```php
use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\EnvelopeUtils;

// Instead of $envelope->last(MessageTracingStamp::class) which returns null
$stamp = EnvelopeUtils::last($envelope, MessageTracingStamp::class);

// Instead of $envelope->withoutStampsOfType() which doesn't remove child classes
$envelope = EnvelopeUtils::withOnly($envelope, MessageTracingStamp::class, $newStamp);
```

## Advanced Usage

### UUID vs ULID

The library supports both UUID and ULID for trace identities:

```php
// Using UUIDs
$uuidGenerator = new UuidTraceIdentityGenerator();
$uuidScenario = UuidEnvelopeTracingScenario::create();

// Using ULIDs  
$ulidGenerator = new UlidTraceIdentityGenerator();
$ulidScenario = UlidEnvelopeTracingScenario::create();
```

### Custom Identity Generators

Implement your own identity generator:

```php
use Simensen\MessageTracing\TraceIdentity\TraceIdentityGenerator;

class CustomTraceIdentityGenerator implements TraceIdentityGenerator
{
    public function generateTraceIdentity(): string
    {
        return 'custom-' . uniqid();
    }
}
```

### Testing

The library provides testing scenarios for easy unit testing:

```php
use Simensen\SymfonyMessenger\MessageTracing\Testing\UuidEnvelopeTracingScenario;
use Simensen\MessageTracing\Testing\MessageTracingScenarioBehavior;

class YourTest extends TestCase
{
    use MessageTracingScenarioBehavior;
    
    protected static function buildMessageTracingScenario(): MessageTracingScenario
    {
        return UuidEnvelopeTracingScenario::create();
    }
    
    public function testMessageTracing(): void
    {
        $envelope = Envelope::wrap(new YourMessage());
        
        // Test causation tracing
        $envelope = $this->messageTracingScenario()
            ->getCausationTracedContainerManager()
            ->push($envelope);
            
        $stamp = EnvelopeUtils::last($envelope, MessageTracingStamp::class);
        $this->assertNotNull($stamp);
    }
}
```

## Requirements

- **PHP**: 8.2 or higher
- **Symfony Messenger**: 6.4 or 7.0+
- **Dependencies**:
  - `simensen/message-tracing`: ~0.1@dev
  - `symfony/messenger`: ^6.4 || ^7.0

### Development Requirements

- **Symfony UID**: ^6.4 || ^7.0 (for UUID/ULID support)
- **PHPUnit**: ^11.2.8
- **PHPStan**: Level 9 analysis
- **PHP CS Fixer**: @PER-CS and @Symfony rules

## Development

### Setup

```bash
# Clone the repository
git clone https://github.com/simensen/symfony-messenger-message-tracing.git
cd symfony-messenger-message-tracing

# Install dependencies
composer install

# Install development tools
make tools
```

### Development Commands

```bash
# Run full development pipeline
make

# Individual commands
make cs          # Fix code style
make phpstan     # Run static analysis  
make tests       # Run tests
make clover      # Generate coverage report
```

### Using Nix

This project includes Nix flake support for reproducible development environments:

```bash
# Enter development shell
nix develop

# Or with direnv
echo "use flake" > .envrc
direnv allow
```

## Documentation

- **[Inheritance-Based Stamp Matching](docs/inheritance-based-stamp-matching.md)** - Deep dive into the EnvelopeUtils solution
- **[CLAUDE.md](CLAUDE.md)** - Project overview and development guide

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run the test suite (`make`)
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

### Code Quality

- All code must pass PHPStan level 9 analysis
- Follow PSR-12 coding standards (enforced by PHP CS Fixer)
- Maintain 100% test coverage where possible
- Use strict types (`declare(strict_types=1)`)

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Related Projects

- **[simensen/message-tracing](https://github.com/simensen/message-tracing)** - Core message tracing library
- **[Symfony Messenger](https://symfony.com/doc/current/messenger.html)** - Symfony's message bus component

## Support

- **Issues**: [GitHub Issues](https://github.com/simensen/symfony-messenger-message-tracing/issues)
- **Discussions**: [GitHub Discussions](https://github.com/simensen/symfony-messenger-message-tracing/discussions)

---

Built with ❤️ by [Beau Simensen](https://github.com/simensen)