<?php

namespace Tests\Feature;

use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class DoctorTest extends TestCase
{
    /**
     * @return mixed
     */
    public function test_create_sucessful_response()
    {
        $doctor = [
            'name' => 'Doctor Test' . time(),
            'crm' => random_int(0001, 9999),
            'code' => null,
            'specialities' => [
                1
            ]
        ];

        $response = $this
            ->assertAuthenticated()
            ->post('/api/v1/doctors', $doctor);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['id', 'name', 'code', 'crm', 'specialities', 'created_at', 'updated_at']);

        return $response->json();
    }

    /**
     * @depends test_create_sucessful_response
     * @return mixed
     */
    public function test_list_successful_response($value)
    {
        $query = http_build_query(
            [
                'scope[search][all]' => $value['name'],
                'specialities[load]' => true,
                'paginate' => 5,
                'page' => 1,
            ]
        );

        $response = $this
            ->assertAuthenticated()
            ->get(sprintf(
                "/api/v1/doctors?%s",
                $query
            ));

        $response
            ->assertStatus(200)
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has('meta')
                    ->has('links')
                    ->has('data.0', fn ($json) => $json->where('id', $value['id'])->etc())
            );

        return $response->json('data.0');
    }

    /**
     * @depends test_list_successful_response
     * @param mixed $value
     * @return mixed
     */
    public function test_show_successful_response($value)
    {
        $this
            ->assertAuthenticated()
            ->get("/api/v1/doctors/{$value['id']}?specialities[load]=1")
            ->assertStatus(200)
            ->assertJson($value);

        return $value;
    }

    /**
     * @depends test_show_successful_response
     * @param mixed $value
     * @return mixed
     */
    public function test_update_successful_response($value)
    {
        $change = [
            'name' => 'Doctor Update',
            'code' => 'test' . random_int(001, 999),
            'specialities' => [
                1,
                2,
                5
            ]
        ];

        $this
            ->assertAuthenticated()
            ->put("/api/v1/doctors/{$value['id']}", $change)
            ->assertStatus(200)
            ->assertJsonPath('name', $change['name'])
            ->assertJsonPath('code', $change['code'])
            ->assertJson(
                fn (AssertableJson $json) => $json
                    ->has(
                        'specialities',
                        count($change['specialities'])
                    )->etc()
            );

        return $value;
    }

    /**
     * @depends test_update_successful_response
     * @return void
     */
    public function test_delete_successful_response($value)
    {
        $this
            ->assertAuthenticated()
            ->delete("/api/v1/doctors/{$value['id']}")
            ->assertStatus(204);
    }
}
