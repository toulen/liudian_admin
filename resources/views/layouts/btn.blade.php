@if(\AdminAuth::user()->can($route))
    {{$slot}}
@endif