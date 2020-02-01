{!! $php_open !!}

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use {{ $model_namespace }}\{{ $model_name }};
use Illuminate\Support\Str;
use Faker\Generator as Faker;

$factory->define({{ $model }}::class, function (Faker $faker) {
return [
    @foreach ($columns as $column)
    "{{$column['name']}}" => {!! $column['faker'] ?? '' !!},
    @endforeach
    ];
});