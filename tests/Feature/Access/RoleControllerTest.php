<?php

namespace Tests\Feature\Access;

use App\Models\Access\Role;
use App\Common\RoleAdminView;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_displays_roles_list()
    {
        $this->withoutVite();
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('admin.roles.index'));

        $response->assertStatus(200)
                ->assertViewIs(RoleAdminView::getListView());
    }

    /** @test */
    public function create_displays_create_form()
    {
        $this->withoutVite();
        $this->actingAs(User::factory()->create());

        $response = $this->get(route('admin.roles.create'));

        $response->assertStatus(200)
                ->assertViewIs(RoleAdminView::getCreateOrEditView());
    }

    /** @test */
    public function store_creates_role_successfully()
    {
        //TO CONNECT USER 
        $this->withoutVite();
        $this->actingAs(User::factory()->create());

        $roleData = [
            'roleName' => 'Super Admin',
            'description' => 'Full access administrator',
            'is_active' => 1,
        ];

        $response = $this->post(route('admin.roles.store'), $roleData);

        $response->assertRedirect(route('admin.roles.index'))
                ->assertSessionHas('success', __('Role created successfully.'));

        $this->assertDatabaseHas('roles', [
            'roleName' => 'Super Admin',
            'description' => 'Full access administrator',
            'is_active' => 1,
        ]);
    }

    /** @test */
    public function store_validates_required_fields()
    {
        //TO CONNECT USER 
        $this->withoutVite();
        $this->actingAs(User::factory()->create());
        $response = $this->post(route('admin.roles.store'), []);

        $response->assertSessionHasErrors(['roleName'])
                    ->assertSessionHasErrors(['description'])
                ->assertRedirect();
    }

    /** @test */
    public function store_creates_role_with_optional_description()
    {
        //TO CONNECT USER 
        $this->withoutVite();
        $this->actingAs(User::factory()->create());

        $roleData = [
            'roleName' => 'Manager',
            'is_active' => true,
        ];

        $this->post(route('admin.roles.store'), $roleData);

        $this->assertDatabaseHas('roles', [
            'roleName' => 'Manager',
            'description' => null,
            'is_active' => true,
        ]);
    }

    /** @test */
    public function store_creates_inactive_role()
    {
        //TO CONNECT USER 
        $this->withoutVite();
        $this->actingAs(User::factory()->create());
        $roleData = [
            'roleName' => 'Inactive Role',
            'is_active' => false,
        ];

        $this->post(route('admin.roles.store'), $roleData);

        $this->assertDatabaseHas('roles', [
            'roleName' => 'Inactive Role',
            'is_active' => false,
        ]);
    }

    /** @test */
    public function store_handles_duplicate_role_name()
    {
        //TO CONNECT USER 
        $this->withoutVite();
        $this->actingAs(User::factory()->create());

        Role::create(['roleName' => 'Duplicate Role']);

        $roleData = [
            'roleName' => 'Duplicate Role',
            'description' => 'Test',
            'is_active' => true,
        ];

        $response = $this->post(route('admin.roles.store'), $roleData);

        $response->assertRedirect()
                ->assertSessionHasErrors(['roleName']);
    }

    /** @test */
    public function edit_displays_edit_form()
    {
        //TO CONNECT USER 
        $this->withoutVite();
        $this->actingAs(User::factory()->create());

        $role = Role::factory()->create();

        $response = $this->get(route('admin.roles.edit', $role));

        $response->assertStatus(200)
                ->assertViewIs(RoleAdminView::getCreateOrEditView())
                ->assertViewHas('role', $role);
    }

    /** @test */
    public function update_modifies_role_successfully()
    {
        //TO CONNECT USER 
        $this->withoutVite();
        $this->actingAs(User::factory()->create());

        $role = Role::factory()->create([
            'roleName' => 'Old Name',
        ]);

        $updateData = [
            'roleName' => 'New Admin',
            'description' => 'Updated description',
            'is_active' => false,
        ];

        $response = $this->put(route('admin.roles.update', $role), $updateData);

        $response->assertRedirect()
                ->assertSessionHas('success', __('Role updated successfully.'));

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'roleName' => 'New Admin',
            'description' => 'Updated description',
            'is_active' => false,
        ]);
    }

    /** @test */
    public function update_validates_required_fields()
    {
        //TO CONNECT USER 
        $this->withoutVite();
        $this->actingAs(User::factory()->create());

        $role = Role::factory()->create();

        $response = $this->put(route('admin.roles.update', $role), []);

        $response->assertSessionHasErrors(['roleName'])
                ->assertRedirect();
    }

    /** @test */
    public function update_removes_description_when_null()
    {
        //TO CONNECT USER 
        $this->withoutVite();
        $this->actingAs(User::factory()->create());

        $role = Role::factory()->create([
            'roleName' => 'Test Role',
            'description' => 'Old description'
        ]);

        $updateData = [
            'roleName' => 'Updated Role',
            'is_active' => true,
        ];

        $this->put(route('admin.roles.update', $role), $updateData);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'roleName' => 'Updated Role',
            'description' => null,
        ]);
    }

    /** @test */
    public function update_handles_duplicate_role_name()
    {
        //TO CONNECT USER 
        $this->withoutVite();
        $this->actingAs(User::factory()->create());

        $role1 = Role::factory()->create(['roleName' => 'Unique Role']);
        Role::factory()->create(['roleName' => 'Duplicate Role']);

        $updateData = [
            'roleName' => 'Duplicate Role', // Conflit avec role2
            'description' => 'Test',
            'is_active' => true,
        ];

        $response = $this->put(route('admin.roles.update', $role1), $updateData);

        $response->assertRedirect()
                ->assertSessionHasErrors(['roleName']);
    }
}