<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        Http::fake([
            'https://reqres.in/api/users' =>
                function (Request $request) {
                    if ($request->method() == 'GET') {
                        return Http::response([
                            'get' => 'ok',
                        ], 200, ['Headers']);
                    }
                    if ($request->method() == 'POST') {
                        return Http::response([
                            'post' => 'ok',
                        ], 200, ['Headers']);
                    }
                },

        ]);
        $response = $this->get('/test');

        $response->assertStatus(200);
    }
}
