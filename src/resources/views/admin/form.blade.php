@extends('twill::layouts.form')

@section('contentFields')
    @formField('input', [
        'name' => 'username',
        'label' => 'Username',
        'required' => true,
        'disabled' => \A17\TwillHttpBasicAuth\Support\Facades\TwillHttpBasicAuth::hasDotEnv(),
    ])

    @formField('input', [
        'name' => 'password',
        'label' => 'Password',
        'required' => true,
        'disabled' => \A17\TwillHttpBasicAuth\Support\Facades\TwillHttpBasicAuth::hasDotEnv(),
    ])

    @formField('checkbox', [
        'name' => 'allow_laravel_login',

        'label' => 'Allow Laravel users to login'
    ])

    @formField('checkbox', [
        'name' => 'allow_twill_login',

        'label' => "Allow Twill users to login"
    ])
@stop
