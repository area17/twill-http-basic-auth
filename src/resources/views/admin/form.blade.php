@extends('twill::layouts.form')

@section('contentFields')
    @formField('input', [
    'name' => 'site_key',
    'label' => 'Site key',
    'required' => true,
    'disabled' => http_basic_auth()->hasConfig(),
    ])

    @formField('input', [
    'name' => 'private_key',
    'label' => 'Private key',
    'required' => true,
    'disabled' => http_basic_auth()->hasConfig(),
    ])
@stop
