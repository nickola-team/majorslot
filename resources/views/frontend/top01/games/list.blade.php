@extends('frontend.cinema.layouts.app', ['logo' => 'top01'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop