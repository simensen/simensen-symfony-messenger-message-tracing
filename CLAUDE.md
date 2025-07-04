# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Symfony Messenger message tracing library that provides causation and correlation tracing capabilities. It extends the `simensen/message-tracing` library specifically for Symfony Messenger.

## Development Commands

### Essential Commands
- `make` - Runs the full development pipeline: tools installation, code style fixes, PHPStan analysis, and tests
- `make cs` - Fixes code style issues using PHP CS Fixer
- `make phpstan` - Runs PHPStan static analysis at level 9
- `make tests` - Runs PHPUnit tests
- `make phpunit` - Runs PHPUnit tests directly
- `make clover` - Generates code coverage report with clover format

### Setup and Maintenance
- `make tools` - Installs development tools via PHIVE
- `make vendor` - Validates composer.json and installs dependencies
- `make clean` - Removes vendor and tools directories
- `make dependency-analysis` - Runs composer-require-checker for dependency analysis

## Architecture

### Core Components

**Message Tracing Stamps** (`src/Stamp/`):
- `MessageTracingStamp` - Abstract base class implementing both `Trace` and `StampInterface`
- `SymfonyUidMessageTracingStamp` - Concrete implementation using Symfony UID components
- Stamp generators handle creation of tracing stamps

**Envelope Managers** (`src/EnvelopeManager/`):
- `CausationTracedEnvelopeManager` - Manages causation tracing for message envelopes
- `CorrelationTracedEnvelopeManager` - Manages correlation tracing for message envelopes
- Uses behavior traits from the core message-tracing library

**Middleware** (`src/Messenger/Middleware/`):
- `CausationTracingMiddleware` - Symfony Messenger middleware for causation tracing
- `CorrelationTracingMiddleware` - Symfony Messenger middleware for correlation tracing
- `TracingMiddleware` - Interface for tracing middleware implementations

**Identity Management** (`src/TraceIdentity/`):
- `UlidTraceIdentityGenerator` - Generates ULID-based trace identities
- `UuidTraceIdentityGenerator` - Generates UUID-based trace identities
- `SymfonyUidIdentityComparator` - Compares Symfony UID instances

### Dependencies

- Depends on `simensen/message-tracing` for core tracing functionality
- Requires Symfony Messenger 6.4+ or 7.0+
- Uses Symfony UID components for identity generation
- Built for PHP 8.2+

### Testing

- Tests are located in `tests/Unit/` and `tests/Fixtures/`
- Uses PHPUnit 11.2+ for testing
- Includes smoke tests for UUID and ULID functionality
- Testing scenarios available in `src/Testing/` for envelope tracing

## Code Quality

- Strict PHP 8.2+ typing with `declare(strict_types=1)`
- PSR-4 autoloading
- PHP CS Fixer with @PER-CS and @Symfony rules
- PHPStan analysis at level 9
- Uses generic templates extensively for type safety