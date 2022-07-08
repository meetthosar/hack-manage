<?php
namespace Tests\Unit\User;
use Tests\TestCase;
use App\Models\User;
use App\Http\Livewire\CreateUser;
use App\Http\Livewire\Users\Users;
use Livewire\Livewire;
use Zekini\CrudGenerator\Factory\AdminFactory;
use Spatie\Permission\Models\Role;

class UserTest extends TestCase
{
    /**
     * Test we can create User
     * @group  User_test
     * @return  void
     */
    public function test_we_can_create_User()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = AdminFactory::create();
      $admin->givePermissionTo('admin.user.create');

      $this->actingAs($admin, $guard);

      $firstData = 'raw_data';
      Livewire::test(Users::class)
                                ->set('state.created_by', $this->faker->randomNumber())
                                        ->set('state.current_team_id', \App\Models\Current_team::inRandomOrder()->firstOrFail()->id)
                                        ->set('state.deleted_by', $this->faker->randomNumber())
                                        ->set('state.email', $this->faker->unique()->safeEmail())
                              ->set('state.name', $firstData)
                                        ->set('state.profile_photo_path', $this->faker->word())
                                        ->set('state.two_factor_confirmed_at', $this->faker->dateTime())
                                        ->set('state.updated_by', $this->faker->randomNumber())
                                        ->set('state.roles', \App\Models\Role::factory()->create()->id)
               ->call('submit')
        ->assertHasNoErrors();

        $this->assertTrue(User::where('created_by', $firstData)->exists());
    }

    /**
     * Test we can update User
     * @group  User_test
     * @return  void
     */
    public function test_we_can_update_User()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = AdminFactory::create();
      $admin->givePermissionTo('admin.user.edit');

      $this->actingAs($admin, $guard);

      $model = User::factory()->create();

      $firstData = 'raw_data';
      Livewire::test(Users::class)
      ->call('launchUserEditModal', [$model->id])
                              ->set('state.created_by', $this->faker->randomNumber())
                                      ->set('state.current_team_id', \App\Models\Current_team::inRandomOrder()->firstOrFail()->id)
                                      ->set('state.deleted_by', $this->faker->randomNumber())
                                      ->set('state.email', $this->faker->unique()->safeEmail())
                              ->set('state.name', $firstData)
                                      ->set('state.profile_photo_path', $this->faker->word())
                                      ->set('state.two_factor_confirmed_at', $this->faker->dateTime())
                                      ->set('state.updated_by', $this->faker->randomNumber())
                                        ->set('state.roles', \App\Models\Role::factory()->create()->id)
               ->call('editSubmit')
        ->assertHasNoErrors();

        $this->assertTrue(User::where('created_by', $firstData)->exists());
    }
    
    /**
     * Test Required field
     *
     * @return  void
     */
    public function test_created_by_is_required()
    {
        $guard = config('zekini-admin.defaults.guard');
      
        $admin  = AdminFactory::create();
        $admin->givePermissionTo('admin.user.create');
  
        $this->actingAs($admin, $guard);

        Livewire::test(Users::class)
                                      ->set('state.created_by', $this->faker->randomNumber())
                                                ->set('state.current_team_id', \App\Models\Current_team::inRandomOrder()->firstOrFail()->id)
                                                ->set('state.deleted_by', $this->faker->randomNumber())
                                                ->set('state.email', $this->faker->unique()->safeEmail())
                                      ->set('state.name', '')
                                                ->set('state.profile_photo_path', $this->faker->word())
                                                ->set('state.two_factor_confirmed_at', $this->faker->dateTime())
                                                ->set('state.updated_by', $this->faker->randomNumber())
                                              ->set('state.roles', \App\Models\Role::factory()->create()->id)
                 ->call('submit')
          ->assertHasErrors(['state.created_by'=> 'required']);
    }


    /**
     * Test Access Forbidden
     *
     * @return  void
     */
    public function test_access_is_forbidden()
    {
        $guard = config('zekini-admin.defaults.guard');
      
        $admin  = AdminFactory::create();

        // by default admin has all permissions 
        $role =  Role::findByName(config('zekini-admin.defaults.role'));

        // by default admin has all permissions 
        $role->revokePermissionTo('admin.user.create');
  
        $this->actingAs($admin, $guard);

        Livewire::test(Users::class)
                                      ->set('state.created_by', $this->faker->randomNumber())
                                                ->set('state.current_team_id', \App\Models\Current_team::inRandomOrder()->firstOrFail()->id)
                                                ->set('state.deleted_by', $this->faker->randomNumber())
                                                ->set('state.email', $this->faker->unique()->safeEmail())
                                      ->set('state.name', '')
                                                ->set('state.profile_photo_path', $this->faker->word())
                                                ->set('state.two_factor_confirmed_at', $this->faker->dateTime())
                                                ->set('state.updated_by', $this->faker->randomNumber())
                                              ->set('state.roles', \App\Models\Role::factory()->create()->id)
                 ->call('submit')
          ->assertForbidden();
    }
}
