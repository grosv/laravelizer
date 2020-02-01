{!! $php_open !!}

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class {{ $model_name }}Test extends TestCase
{
    public funciton setUp(): void
    {
        parent::setUp();
        config(['database.default' => '{{ $connection }}']);
    }

    /** @test */
    public function test{{ $model_name }}()
    {
        $record = factory({{ $model_name }}::class)->create();
        $this->assertDatabaseHas('{{ $table }}', $record->toArray();
    }
}
