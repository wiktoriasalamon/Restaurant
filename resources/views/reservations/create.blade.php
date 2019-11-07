@extends('layouts.app')

@section('content')
<user-create-reservation :user="{{ json_encode( Auth::user()) ?? ""}}"></user-create-reservation>
@endsection
