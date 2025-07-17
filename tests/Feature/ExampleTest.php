<?php

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it("cannot access admin page without login", function () {
    $response = $this->get("/admin");
    $response->assertStatus(302);
});
