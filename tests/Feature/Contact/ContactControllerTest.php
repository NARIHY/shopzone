<?php

namespace Tests\Feature\Contact;

use App\Common\ContactAdminView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();        
        $this->actingAs(User::factory()->create());
    }

    /**
     * Test that the contact index page is displayed correctly.
     *
     * @return void
     */
    public function test_it_displays_the_contact_index_page()
    {
        $response = $this->get(route('admin.contact.index'));

        $response->assertStatus(200);
        $response->assertViewIs(ContactAdminView::getListView());
    }
}
