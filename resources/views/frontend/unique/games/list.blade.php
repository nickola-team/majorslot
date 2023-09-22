@extends('frontend.unique.layouts.app', ['logo' => 'unique'])
@section('page-title', $title)

@section('content')
@include('frontend.kdior.games.index')
@stop