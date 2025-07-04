# Inheritance-Based Stamp Matching

## Problem

Symfony Messenger's `Envelope` class provides methods like `last()`, `all()`, and `withoutStampsOfType()` for working with stamps. However, these methods only perform exact class name matching and do not support inheritance-based matching.

This creates a problem when working with abstract stamp classes or interfaces. For example:

```php
// This will NOT work as expected
$envelope = $envelope->with(new SymfonyUidMessageTracingStamp(...));
$stamp = $envelope->last(MessageTracingStamp::class); // Returns null!
```

Even though `SymfonyUidMessageTracingStamp` extends `MessageTracingStamp`, the `last()` method cannot find it because it only looks for stamps that were added with the exact class name `MessageTracingStamp::class`.

### Root Cause

The issue stems from how Symfony Messenger internally stores stamps. Stamps are stored in an associative array where the key is the exact class name used when adding the stamp:

```php
// Internally, stamps are stored like this:
[
    'Simensen\SymfonyMessenger\MessageTracing\Stamp\SymfonyUidMessageTracingStamp' => [/* stamps */],
    'Some\Other\Stamp' => [/* stamps */]
]
```

When you call `$envelope->last(MessageTracingStamp::class)`, it looks for the key `'Simensen\SymfonyMessenger\MessageTracing\Stamp\MessageTracingStamp'`, which doesn't exist.

### Impact

This limitation affected:

1. **Envelope Manager Behavior**: The `extractTraceFromContainer()` method couldn't find stamps
2. **Stamp Removal**: The `withoutStampsOfType()` method couldn't remove child class stamps
3. **Tests**: Test assertions using `$envelope->last(MessageTracingStamp::class)` were failing

## Solution

We created the `EnvelopeUtils` utility class that provides inheritance-aware stamp operations.

### EnvelopeUtils Class

```php
final class EnvelopeUtils
{
    /**
     * Get the last stamp of a given type, supporting inheritance.
     *
     * @template T of StampInterface
     * @param class-string<T> $stampClass
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
```

### Usage

#### Before (Broken)
```php
// This doesn't work due to inheritance limitation
$stamp = $envelope->last(MessageTracingStamp::class);

// This doesn't remove child class stamps
$envelope = $envelope->withoutStampsOfType(MessageTracingStamp::class);
```

#### After (Fixed)
```php
use Simensen\SymfonyMessenger\MessageTracing\EnvelopeManager\EnvelopeUtils;

// This works with inheritance
$stamp = EnvelopeUtils::last($envelope, MessageTracingStamp::class);

// This removes child class stamps and adds a new one
$envelope = EnvelopeUtils::withOnly($envelope, MessageTracingStamp::class, $newStamp);
```

### Implementation Details

#### The `last()` Method

The `last()` method iterates through all stamps in reverse order (to match the behavior of `Envelope::last()`) and uses `instanceof` to check for inheritance:

1. Get all stamps using `$envelope->all()`
2. Reverse the order to find the last matching stamp
3. Use `instanceof` to check if each stamp is an instance of the target class
4. Return the first match found (which is the last stamp chronologically)

#### The `withOnly()` Method

The `withOnly()` method removes all stamps of a given type and adds a new stamp:

1. Iterate through all stamps to find instances of the target class
2. For each matching stamp, remove all stamps of that concrete class using `withoutStampsOfType()`
3. Add the new stamp using `with()`

### Integration

The solution was integrated into the existing codebase by:

1. **Updating `DefaultTracedEnvelopeBehavior`**:
   - `extractTraceFromContainer()` now uses `EnvelopeUtils::last()`
   - `injectTraceIntoContainer()` now uses `EnvelopeUtils::withOnly()`

2. **Fixing test classes**:
   - `UuidSmokeTest` and `UlidSmokeTest` now use `EnvelopeUtils::last()` instead of `$envelope->last()`

### Benefits

- **Inheritance Support**: Properly handles abstract classes and interfaces
- **Backward Compatible**: Doesn't break existing functionality
- **Reusable**: Can be used anywhere inheritance-based stamp matching is needed
- **Type Safe**: Uses PHP generics for proper type checking
- **Performance**: Minimal overhead compared to direct Envelope methods

### Alternative Solutions Considered

1. **Modifying Symfony Messenger**: Not feasible as it would require upstream changes
2. **Custom Envelope Class**: Too intrusive and would break compatibility
3. **Reflection-based Solutions**: More complex and slower than the instanceof approach

The `EnvelopeUtils` approach was chosen for its simplicity, performance, and ease of integration.