<?php
namespace Tests\Unit\Role;
use Tests\TestCase;
use App\Models\Role;
use App\Http\Livewire\CreateRole;
use App\Http\Livewire\Roles\Roles;
use Livewire\Livewire;
use Zekini\CrudGenerator\Factory\AdminFactory;
use Spatie\Permission\Models\Role;

class RoleTest extends TestCase
{
    /**
     * Test we can create Role
     * @group  Role_test
     * @return  void
     */
    public function test_we_can_create_Role()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = AdminFactory::create();
      $admin->givePermissionTo('admin.role.create');

      $this->actingAs($admin, $guard);

      $firstData = 'raw_data';
      Livewire::test(Roles::class)
                                ->set('state.guard_name', $this->faker->word())
                              ->set('state.name', $firstData)
                                        ->set('state.permissions', \App\Models\Permission::factory()->create()->id)
               ->call('submit')
        ->assertHasNoErrors();

        $this->assertTrue(Role::where('guard_name', $firstData)->exists());
    }

    /**
     * Test we can update Role
     * @group  Role_test
     * @return  void
     */
    public function test_we_can_update_Role()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = AdminFactory::create();
      $admin->givePermissionTo('admin.role.edit');

      $this->actingAs($admin, $guard);

      $model = Role::factory()->create();

      $firstData = 'raw_data';
      Livewire::test(Roles::class)
      ->call('launchRoleEditModal', [$model->id])
                              ->set('state.guard_name', $this->faker->word())
                              ->set('state.name', $firstData)
                                        ->set('state.permissions', \App\Models\Permission::factory()->create()->id)
               ->call('editSubmit')
        ->assertHasNoErrors();

        $this->assertTrue(Role::where('guard_name', $firstData)->exists());
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
        $admin->givePermissionTo('admin.role.create');
  
        $this->actingAs($admin, $guard);

        Livewire::test(Roles::class)
                                      ->set('state.guard_name', $this->faker->word())
                                      ->set('state.name', '')
                                              ->set('state.permissions', \App\Models\Permission::factory()->create()->id)
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
        $role->revokePermissionTo('admin.role.create');
  
        $this->actingAs($admin, $guard);

        Livewire::test(Roles::class)
                                      ->set('state.guard_name', $this->faker->word())
                                      ->set('state.name', '')
                                              ->set('state.permissions', \App\Models\Permission::factory()->create()->id)
                 ->call('submit')
          ->assertForbidden();
    }
}
