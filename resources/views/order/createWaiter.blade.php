@extends('layouts.app')

@section('content')
<worker-order-create :tableid="{{$tableId}}"></worker-order-create>
@endsection
