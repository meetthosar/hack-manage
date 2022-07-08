<?php
namespace Tests\Unit\Permission;
use Tests\TestCase;
use App\Models\Permission;
use App\Http\Livewire\CreatePermission;
use App\Http\Livewire\Permissions\Permissions;
use Livewire\Livewire;
use Zekini\CrudGenerator\Factory\AdminFactory;
use Spatie\Permission\Models\Role;

class PermissionTest extends TestCase
{
    /**
     * Test we can create Permission
     * @group  Permission_test
     * @return  void
     */
    public function test_we_can_create_Permission()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = AdminFactory::create();
      $admin->givePermissionTo('admin.permission.create');

      $this->actingAs($admin, $guard);

      $firstData = 'raw_data';
      Livewire::test(Permissions::class)
                                ->set('state.guard_name', $this->faker->word())
                              ->set('state.name', $firstData)
                                        ->set('state.roles', \App\Models\Role::factory()->create()->id)
               ->call('submit')
        ->assertHasNoErrors();

        $this->assertTrue(Permission::where('guard_name', $firstData)->exists());
    }

    /**
     * Test we can update Permission
     * @group  Permission_test
     * @return  void
     */
    public function test_we_can_update_Permission()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = AdminFactory::create();
      $admin->givePermissionTo('admin.permission.edit');

      $this->actingAs($admin, $guard);

      $model = Permission::factory()->create();

      $firstData = 'raw_data';
      Livewire::test(Permissions::class)
      ->call('launchPermissionEditModal', [$model->id])
                              ->set('state.guard_name', $this->faker->word())
                              ->set('state.name', $firstData)
                                        ->set('state.roles', \App\Models\Role::factory()->create()->id)
               ->call('editSubmit')
        ->assertHasNoErrors();

        $this->assertTrue(Permission::where('guard_name', $firstData)->exists());
    }
    
    /**
     * Test Required field
     *
     * @return  void
     */
    public function test_guard_name_is_required()
    {
        $guard = config('zekini-admin.defaults.guard');
      
        $admin  = AdminFactory::create();
        $admin->givePermissionTo('admin.permission.create');
  
        $this->actingAs($admin, $guard);

        Livewire::test(Permissions::class)
                                      ->set('state.guard_name', $this->faker->word())
                                      ->set('state.name', '')
                                              ->set('state.roles', \App\Models\Role::factory()->create()->id)
                 ->call('submit')
          ->assertHasErrors(['state.guard_name'=> 'required']);
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
        $role->revokePermissionTo('admin.permission.create');
  
        $this->actingAs($admin, $guard);

        Livewire::test(Permissions::class)
                                      ->set('state.guard_name', $this->faker->word())
                                      ->set('state.name', '')
                                              ->set('state.roles', \App\Models\Role::factory()->create()->id)
                 ->call('submit')
          ->assertForbidden();
    }
}
