<?php

it('has hackathon page', function () {
    $response = $this->get('/hackathons');

    $response->assertStatus(302);
});
