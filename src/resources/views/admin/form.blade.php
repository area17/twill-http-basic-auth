@extends('twill::layouts.form')

@php
    use A17\TwillHttpBasicAuth\Support\Facades\TwillHttpBasicAuth;
@endphp

@inject('twillHttpBasicAuth', TwillHttpBasicAuth::class)

@section('contentFields')

    @formField('input', [
        'type' => $twillHttpBasicAuth::config('inputs.username.type'),
        'name' => 'username',
        'label' => 'Username',
        'required' => true,
        'disabled' => $twillHttpBasicAuth::hasDotEnv(),
    ])

    @formField('input', [
        'type' => $twillHttpBasicAuth::config('inputs.password.type'),
        'name' => 'password',
        'label' => 'Password',
        'required' => true,
        'disabled' => $twillHttpBasicAuth::hasDotEnv(),
        'password' => true,
    ])

    @formField('checkbox', [
        'name' => 'allow_laravel_login',
        'label' => 'Allow Laravel users to login',
        'disabled' => $twillHttpBasicAuth::hasDotEnv(),
    ])

    @formField('checkbox', [
        'name' => 'allow_twill_login',
        'label' => "Allow Twill users to login",
        'disabled' => $twillHttpBasicAuth::hasDotEnv(),
    ])
@stop
