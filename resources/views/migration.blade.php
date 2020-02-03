{!! $php_open !!}

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class {{ $class_name }} extends Migration
{
    public function up()
    {
        if (!Schema::connection('{{$connection}}')->hasTable('{{$table}}')) {
            Schema::connection('{{$connection}}')->create('{{ $table }}', function (Blueprint $table) {
            @foreach ($columns as $k => $v)
            {!! $v['migration'] !!}
            @endforeach
            @if ($timestamps)
            $table->timestamps();
            @endif
            @if ($soft_deletes)
            $table->softDeletes();
            @endif
            });
        }
        @if ($add_timestamps)
        Schema::connection('{{$connection}}')->table(function (Blueprint $table) {
            $table->timestamps();
        });
        @endif
        @if ($add_soft_deletes)
        Schema::connection('{{$connection}}')->table(function (Blueprint $table) {
            $table->timestamps();
        });
        @endif
    }

    public function down()
    {
        Schema::dropIfExists('{{ $table }}');
    }
}
