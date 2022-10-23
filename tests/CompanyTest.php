<?php

declare(strict_types=1);

namespace Tests;

use App\Models\User;

class CompanyTest extends TestCase
{
    private array $firstCompanyData = [
        'title' => 'Test company',
        'description' => 'Test company description',
        'phone' => '2132131231'
    ];

    private array $secondCompanyData = [
        'title' => 'Test second company',
        'description' => 'Test second company description',
        'phone' => '87687679879'
    ];

    private User $firstUser;
    private User $secondUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->firstUser = User::factory()->create();
        $this->secondUser = User::factory()->create();
    }

    public function testIndex()
    {
        $this->firstUser->companies()->create($this->firstCompanyData);
        $this->secondUser->companies()->create($this->secondCompanyData);

        $response = $this->actingAs($this->firstUser)->call('GET', 'api/user/companies');
        $response->assertJsonCount(1,);
        $response->assertJsonFragment($this->firstCompanyData);
    }

    public function testStore()
    {
        $response = $this->actingAs($this->firstUser)
            ->call('POST', 'api/user/companies', $this->firstCompanyData);
        $response->assertJsonFragment($this->firstCompanyData);

        $this->actingAs($this->firstUser)
            ->call('GET', 'api/user/companies', $this->firstCompanyData)
            ->assertJsonCount(1)
            ->assertJsonFragment($this->firstCompanyData);
    }
}
