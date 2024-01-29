@extends('frontend.josenso.layouts.app', ['logo' => 'josenso'])
@section('page-title', $title)

@section('content')
@include('frontend.iris.games.index')
@stop