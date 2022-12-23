@extends('errors::minimal')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', __($exception->getMessage() ?: 'Forbidden'))

<div>
    <a href="{{ route('client.home') }}" style="padding: 20px;margin: 0 auto;display: block;width: fit-content;">Go to Home</a>
</div>