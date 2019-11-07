@extends('layouts.app')

@section('content')
    <admin-menu :dishes="{{json_encode($dishes)}}"></admin-menu>
@endsection
