<?php

test('showLoginForm', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});


test('Logoff', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

test('login', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});