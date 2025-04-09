<?php

use App\Models\Portal;
use App\Models\PortalPlacement;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

beforeEach(function () {
    $this->token = 'correct-token';
    $this->link = 'http://google.com';
    config()->set('api.token', $this->token);
});

test('Get Portal Placements without params', function () {
    $response = $this->getJson(route('get-portal-placements'));
    $response->assertStatus(422)->assertJsonStructure([
        'message',
    ]);
});

test('Get Portal Placements with params but placements is empty', function () {
    $response = $this->getJson(route('get-portal-placements', [
        'limit' => 5,
    ]));
    $response->assertStatus(204);
});

test('Add new link to ping, check validation to fail', function () {
    $response = $this->postJson(route('add-new-links-to-ping'), [
        'portal_id' => 1,
    ]);
    $response->assertStatus(400);
});

test('Add new link to ping, incorrect token is fail', function () {
    $response = $this->postJson(route('add-new-links-to-ping'), [
        'token' => 'bad-token',
        'portal_id' => 1,
        'link' => $this->link,
    ]);
    $response->assertStatus(403);
});

test('Add new link to ping, placement is added', function () {

    $new_portal_placement = createPortalPlacement($this);

    $portal_placement = PortalPlacement::query()->where('external_url', $this->link)->first();
    expect($portal_placement)->not()->toBeNull()
        ->and($portal_placement->external_url)->toBe($this->link);
    $new_portal_placement->assertStatus(200);
});

test('Get Portal Placements add new link and get this link', function () {
    createPortalPlacement($this);

    $response = $this->getJson(route('get-portal-placements', [
        'limit' => 5,
    ]))->assertJsonPath('links.0.external_url', $this->link);

    $response->assertStatus(200);
});

test('Get Portal Placements With Domain', function () {
    \App\Models\Domain::factory()->create([
        'is_active_for_ping' => 1,
    ]);
    createPortalPlacement($this);

    $response = $this->getJson(route('get-portal-placements-with-domain', [
        'limit' => 5,
    ]))->assertJsonPath('links.0.external_url', $this->link);

    $response->assertStatus(200);
});

function createPortalPlacement(TestCase $test): TestResponse
{
    $portal = PortalPlacementEnvironment();

    return $test->postJson(route('add-new-links-to-ping'), [
        'token' => $test->token,
        'portal_id' => $portal->id,
        'link' => $test->link,
    ]);
}

function PortalPlacementEnvironment(): Portal
{

    $topic = \App\Models\Topic::factory()->create();

    return Portal::factory()->create([
        'topic_id' => $topic->id,
    ]);
}
