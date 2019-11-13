@extends('layouts.app')

@section('content')
<customer-order :dishes="{{json_encode($dishes)}}" :categories="{{json_encode($categories)}}"></customer-order>
@endsection
