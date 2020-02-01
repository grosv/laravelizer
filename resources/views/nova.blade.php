{!! $php_open !!}

namespace App\Nova;

use App\Nova\Resource;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Code;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;

class {{ $model }} extends Resource
{
    /** @var string */
    public static $model = 'Domains\{{$domain}}\Models\{{ $model }}';
    /** @var string */
    public static $group = '{{ $domain }}';
    /** @var boolean */
    public static $displayInNavigation = true;
    /** @var array */
    public static $with = [];
    /** @var array */
    public static $orderBy = [];

    /**
    * The single value that should be used to represent the resource when being displayed.
    *
    * @var string
    */
    public static $title = 'id';

    /**
    * The columns that should be searched.
    *
    * @var array
    */
    public static $search = [
        'id',
    ];


    /**
    * Get the fields displayed by the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function fields(Request $request)
    {
        return [
            @foreach ($columns as $column)
            {!! $column['nova'] !!},
            @endforeach
        ];
    }

    /**
    * Get the cards available for the request.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function cards(Request $request)
    {
    return [];
    }

    /**
    * Get the filters available for the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function filters(Request $request)
    {
        return [];
    }

    /**
    * Get the lenses available for the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
    * Get the actions available for the resource.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array
    */
    public function actions(Request $request)
    {
        return [];
    }
}