<?php
namespace Tests\Unit\Permission;

use Tests\TestCase;
use App\Models\Permission;
use App\Http\Livewire\Permissions\Datatable\PermissionsTable;
use Livewire\Livewire;
use App\Imports\PermissionsImport;
use Maatwebsite\Excel\Facades\Excel;
use Zekini\CrudGenerator\Factory\AdminFactory;
use Spatie\Permission\Models\Role;

class PermissionDatatableTest extends TestCase
{
    /**
     * Test we can create Permission
     * @group  Permission_test
     * @return  void
     */
    public function test_we_can_destroy_permission()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = AdminFactory::create();
      $admin->givePermissionTo('admin.permission.delete');
      $admin->givePermissionTo('admin.permission.index');
      $this->actingAs($admin, $guard);

      $firstData = 'raw_data';

      $model = Permission::factory()->create();

      Livewire::test(PermissionsTable::class)
        ->call('delete', $model->id);

        $this->assertFalse(Permission::find($model->id)->exists());
       
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
        $role->revokePermissionTo('admin.permission.delete');
        
  
        $this->actingAs($admin, $guard);

        $model = Permission::factory()->create();

      Livewire::test(PermissionsTable::class)
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

        $admin->givePermissionTo('admin.permission.delete');
  
        $this->actingAs($admin, $guard);

        $model = Permission::factory()->create();

        $model->delete();

      Livewire::test(PermissionsTable::class)
          ->call('restore', $model->id);
    
          $this->assertTrue(Permission::find($model->id)->exists());
    }

    


   




}
