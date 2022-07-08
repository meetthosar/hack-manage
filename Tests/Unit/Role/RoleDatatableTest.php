<?php
namespace Tests\Unit\Role;

use Tests\TestCase;
use App\Models\Role;
use App\Http\Livewire\Roles\Datatable\RolesTable;
use Livewire\Livewire;
use App\Imports\RolesImport;
use Maatwebsite\Excel\Facades\Excel;
use Zekini\CrudGenerator\Factory\AdminFactory;
use Spatie\Permission\Models\Role;

class RoleDatatableTest extends TestCase
{
    /**
     * Test we can create Role
     * @group  Role_test
     * @return  void
     */
    public function test_we_can_destroy_role()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = AdminFactory::create();
      $admin->givePermissionTo('admin.role.delete');
      $admin->givePermissionTo('admin.role.index');
      $this->actingAs($admin, $guard);

      $firstData = 'raw_data';

      $model = Role::factory()->create();

      Livewire::test(RolesTable::class)
        ->call('delete', $model->id);

        $this->assertFalse(Role::find($model->id)->exists());
       
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
        $role->revokePermissionTo('admin.role.delete');
        
  
        $this->actingAs($admin, $guard);

        $model = Role::factory()->create();

      Livewire::test(RolesTable::class)
          ->call('forceDelete', $model->id)
          ->assertForbidden();
    }

    /**
     * Restore sofdelete
     *
     * @return  void
     */
    public function test_we_can_restore_soft_deletes()
    {
        $guard = config('zekini-admin.defaults.guard');
      
        $admin  = AdminFactory::create();

        $admin->givePermissionTo('admin.role.delete');
  
        $this->actingAs($admin, $guard);

        $model = Role::factory()->create();

        $model->delete();

      Livewire::test(RolesTable::class)
          ->call('restore', $model->id);
    
          $this->assertTrue(Role::find($model->id)->exists());
    }

    


   




}
