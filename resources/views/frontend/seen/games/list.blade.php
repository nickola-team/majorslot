@extends('frontend.dove.layouts.app', ['logo' => 'seen', 'telegram' => '#'])
@section('page-title', $title)

@section('content')
@include('frontend.dove.games.list')
@stop