@extends('frontend.newworld.layouts.app', ['logo' => 'bigwin'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop