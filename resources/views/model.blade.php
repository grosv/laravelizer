{!! $php_open !!}

namespace {{ $model_namespace }};

use Illuminate\Database\Eloquent\Model;
@if ($soft_delete)
use Illuminate\Database\Eloquent\SoftDeletes;
@endif

class {{ $model_name }} extends Model
{
    @if ($soft_delete)
    use SoftDeletes;
    @endif

    protected $connection = "{{ $connection }}";
    protected $table = "{{ $table }}";

    protected $fillable = [];

    protected $casts = [
        @foreach ($casts as $k => $v)
        "{{$k}}" => "{{$v}}",
        @endforeach
    ];

    protected $attributes = [
        @foreach ($attributes as $k => $v)
        "{{$k}}" => "{{$v}}",
        @endforeach
    ];

    @if (!$timestamps)
    public $timestamps = false;
    @endif
}
