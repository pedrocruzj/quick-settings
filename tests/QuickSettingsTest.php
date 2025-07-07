<?php

use Pedrocruzj\QuickSettings\QuickSettings;
use Tests\TestCase;

uses(TestCase::class);

it('Should return correct string value', function () {
    $settings = new QuickSettings(cacheEnabled: false);
    $settings->set('foo', 'bar');
    expect($settings->get('foo'))->toBe('bar');
});

it('Should return numeric value as string', function () {
    $settings = new QuickSettings(cacheEnabled: false);
    $settings->set('value', 10);
    expect($settings->get('value'))->toBe('10');
});

it('Should return correct json string from setted array', function () {
    $settings = new QuickSettings(cacheEnabled: false);
    $settings->set('data', ['name' => 'Pedro', 'age' => 26]);
    expect($settings->get('data'))->toBe('{"name":"Pedro","age":26}');
});

it('Should return correct json string from setted object', function () {
    $obj = new \stdClass;
    $obj->name = "Pedro";
    $obj->age = 26;

    $settings = new QuickSettings(cacheEnabled: false);
    $settings->set('data', $obj);
    expect($settings->get('data'))->toBe('{"name":"Pedro","age":26}');
});
