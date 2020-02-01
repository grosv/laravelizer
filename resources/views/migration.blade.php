{!! $php_open !!}

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class {{ $class_name }} extends Migration
{
    public function up()
    {
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

    public function down()
    {
        Schema::dropIfExists('{{ $table }}');
    }
}
