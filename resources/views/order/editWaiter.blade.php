@extends('layouts.app')

@section('content')
    <worker-order-edit token="{{$token}}" status="{{$status}}"></worker-order-edit>
@endsection
