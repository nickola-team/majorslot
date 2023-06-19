@extends('frontend.newworld.layouts.app', ['logo' => 'cnm02'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop