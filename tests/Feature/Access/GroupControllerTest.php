<?php

namespace Tests\Feature\Access;

use App\Common\GroupAdminView;
use App\Models\Access\Group;
use App\Models\Access\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->actingAs(User::factory()->create());
    }

    /** @test */
    public function it_displays_the_groups_index_page()
    {
        $response = $this->get(route('admin.groups.index'));

        $response->assertStatus(200);
        $response->assertViewIs(GroupAdminView::getListView()); 
    }

    /** @test */
    public function it_displays_the_group_create_page()
    {
        $response = $this->get(route('admin.groups.create'));

        $response->assertStatus(200);
        $response->assertViewIs(GroupAdminView::getCreateOrEditView()); 
    }

    /** @test */
    public function it_can_store_a_new_group()
    {
        $role = Role::factory()->create();
        $data = [
            'name' => 'Test Group',
            'description' => 'A test group description',
            'role_id' => $role->id,
            'is_active' => true,
        ];

        $response = $this->post(route('admin.groups.store'), $data);

        $response->assertRedirect(route('admin.groups.index'));
        $this->assertDatabaseHas('groups', [
            'name' => 'Test Group',
            'description' => 'A test group description',
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function it_fails_to_store_group_with_invalid_data()
    {
        $response = $this->post(route('admin.groups.store'), [
            'name' => '', // Invalid: name is required
            'description' => 'A test group description',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseMissing('groups', [
            'description' => 'A test group description',
        ]);
    }

    /** @test */
    public function it_displays_the_group_edit_page()
    {
        $group = Group::factory()->create();
        $role = Role::factory()->create();

        $response = $this->get(route('admin.groups.edit', $group));

        $response->assertStatus(200);
        $response->assertViewIs(GroupAdminView::getCreateOrEditView()); 
        $response->assertViewHas('group', $group);
        $response->assertViewHas('rolesInput', Role::all());
    }

    /** @test */
    public function it_can_update_a_group()
    {
        $group = Group::factory()->create();
        $role = Role::factory()->create();
        $data = [
            'name' => 'Updated Group',
            'description' => 'Updated description',
            'role_id' => $role->id,
            'is_active' => false,
        ];

        $response = $this->put(route('admin.groups.update', $group), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => 'Updated Group',
            'description' => 'Updated description',
            'role_id' => $role->id,
            'is_active' => false,
        ]);
    }

    /** @test */
    public function it_fails_to_update_group_with_invalid_data()
    {
        $group = Group::factory()->create();
        $data = [
            'name' => '', // Invalid: name is required
            'description' => 'Updated description',
        ];

        $response = $this->put(route('admin.groups.update', $group), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['name']);
        $this->assertDatabaseHas('groups', [
            'id' => $group->id,
            'name' => $group->name, // Original name should remain
        ]);
    }

    /** @test */
    public function it_can_delete_a_group()
    {
        $group = Group::factory()->create();

        $response = $this->delete(route('admin.groups.destroy', $group));

        $response->assertRedirect(route('admin.groups.index'));
        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }
}
