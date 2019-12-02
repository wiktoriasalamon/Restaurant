@extends('layouts.app')

@section('content')
    <user-menu :dishes="{{json_encode($dishes)}}" :categories="{{json_encode($categories)}}"></user-menu>
@endsection
