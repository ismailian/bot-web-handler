<?php

declare(strict_types=1);

namespace Tests\Unit\System\Telegram\Support;

use DateTime;
use PHPUnit\Framework\TestCase;
use TeleBot\System\Telegram\Support\Hydrator;
use TeleBot\System\Telegram\Traits\MapProp;

enum FixtureStatus: string
{
    case ACTIVE = 'active';
    case DISABLED = 'disabled';
}

class FixtureChild
{
    public ?string $value;

    public function __construct(array $data)
    {
        $this->value = $data['value'] ?? null;
    }
}

class FixtureSelfPayload
{
    public function __construct(public array $data)
    {
    }
}

class FixtureHydratedTarget
{
    public ?string $seed = null;

    #[MapProp('name')]
    public ?string $name = null;

    #[MapProp('child', FixtureChild::class)]
    public ?FixtureChild $child = null;

    /** @var FixtureChild[] */
    #[MapProp('items', FixtureChild::class, true)]
    public array $items = [];

    #[MapProp('status', FixtureStatus::class, false, false, true)]
    public ?FixtureStatus $status = null;

    #[MapProp('timestamp', null, false, true)]
    public ?DateTime $timestamp = null;

    #[MapProp(':', FixtureSelfPayload::class)]
    public ?FixtureSelfPayload $selfPayload = null;

    public function __construct(array $data)
    {
        $this->seed = $data['seed'] ?? null;
    }
}

class HydratorTest extends TestCase
{
    public function testHydrateMapsAttributesAndTypes(): void
    {
        $data = [
            'seed' => 'constructor-value',
            'name' => 'Alice',
            'child' => ['value' => 'first-child'],
            'items' => [
                ['value' => 'one'],
                ['value' => 'two'],
            ],
            'status' => 'active',
            'timestamp' => 1_700_000_000,
        ];

        $result = Hydrator::hydrate(FixtureHydratedTarget::class, $data);

        $this->assertSame('constructor-value', $result->seed);
        $this->assertSame('Alice', $result->name);
        $this->assertInstanceOf(FixtureChild::class, $result->child);
        $this->assertSame('first-child', $result->child?->value);

        $this->assertCount(2, $result->items);
        $this->assertContainsOnlyInstancesOf(FixtureChild::class, $result->items);
        $this->assertSame('one', $result->items[0]->value);
        $this->assertSame('two', $result->items[1]->value);

        $this->assertSame(FixtureStatus::ACTIVE, $result->status);
        $this->assertInstanceOf(DateTime::class, $result->timestamp);
        $this->assertSame(1_700_000_000, $result->timestamp?->getTimestamp());

        $this->assertInstanceOf(FixtureSelfPayload::class, $result->selfPayload);
        $this->assertSame('Alice', $result->selfPayload?->data['name']);
    }
}
