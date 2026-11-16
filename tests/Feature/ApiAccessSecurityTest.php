<?php

namespace Tests\Feature;

use Tests\TestCase;

class ApiAccessSecurityTest extends TestCase
{
    public function test_public_api_registration_is_unavailable(): void
    {
        $this->postJson('/api/register', [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'example-password',
            'password_confirmation' => 'example-password',
        ])->assertNotFound();
    }

    public function test_public_web_registration_is_unavailable(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_unauthenticated_visitors_cannot_read_patient_data(): void
    {
        $this->getJson('/api/patients')->assertUnauthorized();
        $this->getJson('/api/patients/1')->assertUnauthorized();
    }

    public function test_unauthenticated_visitors_cannot_read_invoices_or_dashboard(): void
    {
        $this->getJson('/api/invoices')->assertUnauthorized();
        $this->getJson('/api/dashboard/stats')->assertUnauthorized();
    }
}
