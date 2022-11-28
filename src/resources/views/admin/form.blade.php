@extends('twill::layouts.form')

@php
    use A17\TwillHttpBasicAuth\Support\Facades\TwillHttpBasicAuth;
@endphp

@section('contentFields')
    @formField('input', [
    'type' => TwillHttpBasicAuth::config('inputs.username.type'),
    'name' => 'username',
    'label' => 'Username',
    'required' => true,
    'disabled' => TwillHttpBasicAuth::hasDotEnv(),
    ])

    @formField('input', [
    'type' => TwillHttpBasicAuth::config('inputs.password.type'),
    'name' => 'password',
    'label' => 'Password',
    'required' => true,
    'disabled' => TwillHttpBasicAuth::hasDotEnv(),
    'password' => true,
    ])

    @formField('checkbox', [
    'name' => 'allow_laravel_login',

    'label' => 'Allow Laravel users to login',

    'disabled' => TwillHttpBasicAuth::hasDotEnv(),
    ])

    @formField('checkbox', [
    'name' => 'allow_twill_login',

    'label' => "Allow Twill users to login",

    'disabled' => TwillHttpBasicAuth::hasDotEnv(),
    ])
@stop
