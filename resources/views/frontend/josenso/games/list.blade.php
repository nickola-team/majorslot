@extends('frontend.iris.layouts.app', ['logo' => 'josenso'])
@section('page-title', $title)

@section('content')
@include('frontend.iris.games.list')
@stop