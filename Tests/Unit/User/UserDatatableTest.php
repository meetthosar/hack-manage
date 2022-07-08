<?php
namespace Tests\Unit\User;

use Tests\TestCase;
use App\Models\User;
use App\Http\Livewire\Users\Datatable\UsersTable;
use Livewire\Livewire;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Zekini\CrudGenerator\Factory\AdminFactory;
use Spatie\Permission\Models\Role;

class UserDatatableTest extends TestCase
{
    /**
     * Test we can create User
     * @group  User_test
     * @return  void
     */
    public function test_we_can_destroy_user()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = AdminFactory::create();
      $admin->givePermissionTo('admin.user.delete');
      $admin->givePermissionTo('admin.user.index');
      $this->actingAs($admin, $guard);

      $firstData = 'raw_data';

      $model = User::factory()->create();

      Livewire::test(UsersTable::class)
        ->call('delete', $model->id);

        $this->assertFalse(User::find($model->id)->exists());
       
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
        $role->revokePermissionTo('admin.user.delete');
        
  
        $this->actingAs($admin, $guard);

        $model = User::factory()->create();

      Livewire::test(UsersTable::class)
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

        $admin->givePermissionTo('admin.user.delete');
  
        $this->actingAs($admin, $guard);

        $model = User::factory()->create();

        $model->delete();

      Livewire::test(UsersTable::class)
          ->call('restore', $model->id);
    
          $this->assertTrue(User::find($model->id)->exists());
    }

    


   




}
