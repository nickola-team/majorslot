@extends('frontend.natural.layouts.app', ['logo' => 'sealwort'])
@section('page-title', $title)

@section('content')
@include('frontend.natural.games.list')
@stop