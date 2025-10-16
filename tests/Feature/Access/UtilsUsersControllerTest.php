<?php

namespace Tests\Feature\Access;

use App\Models\Access\Group;
use App\Models\User;
use App\Notifications\Access\SuccessClientRegistrationNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class UtilsUsersControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->actingAs(User::factory()->create());
    }

    /** @test */
    public function test_it_attaches_group_to_authenticated_user_and_sends_notification()
    {
        // Arrange
        $user = Auth::user();
        $group = Group::factory()->create(['id' => 1]); // Ensure group with ID 1 exists
        Notification::fake(); // Fake notifications to prevent actual sending

        // Act
        $response = $this->get(route('admin.utils.verifyUserGroupsToAttacheClient', ['userId' => $user->id]));

        // Assert
        $response->assertRedirect(route('public.home'));
        $response->assertSessionHas('success', 'Your account has been successfully confirmed. You can now access all features.');
        
        // Verify group attachment
        $this->assertDatabaseHas('group_user', [
            'user_id' => $user->id,
            'group_id' => 1,
        ]);

        // Verify notification was sent
        Notification::assertSentTo(
            $user,
            SuccessClientRegistrationNotification::class,
            function ($notification, $channels) use ($user) {
                return $notification->user->id === $user->id;
            }
        );
    }

    /** @test */
    public function test_it_fails_if_user_id_does_not_match_authenticated_user()
    {
        // Arrange
        $otherUser = User::factory()->create();
        $group = Group::factory()->create(['id' => 1]);

        // Act
        $response = $this->get(route('admin.utils.verifyUserGroupsToAttacheClient', ['userId' => $otherUser->id]));

        // Redirect not connected to login
        $response->assertStatus(302);
        $response->assertRedirect(config('app.url'.'/login'));
    }
}
