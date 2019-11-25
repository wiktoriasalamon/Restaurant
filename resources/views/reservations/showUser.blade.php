@extends('layouts.app')

@section('content')
    <user-show-reservation :id="{{ $id }}"></user-show-reservation>
@endsection
