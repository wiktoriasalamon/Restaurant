@extends('layouts.app')

@section('content')
    <worker-order-edit :orderid="{{$id}}"></worker-order-edit>
@endsection
