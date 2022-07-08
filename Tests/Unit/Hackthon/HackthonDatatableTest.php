<?php
namespace Tests\Unit\Hackthon;

use Tests\TestCase;
use App\Models\Hackthon;
use App\Http\Livewire\Hackthons\Datatable\HackthonsTable;
use Livewire\Livewire;
use App\Imports\HackthonsImport;
use Maatwebsite\Excel\Facades\Excel;
use Zekini\CrudGenerator\Factory\AdminFactory;
use Spatie\Permission\Models\Role;

class HackthonDatatableTest extends TestCase
{
    /**
     * Test we can create Hackthon
     * @group  Hackthon_test
     * @return  void
     */
    public function test_we_can_destroy_hackthon()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = AdminFactory::create();
      $admin->givePermissionTo('admin.hackthon.delete');
      $admin->givePermissionTo('admin.hackthon.index');
      $this->actingAs($admin, $guard);

      $firstData = 'raw_data';

      $model = Hackthon::factory()->create();

      Livewire::test(HackthonsTable::class)
        ->call('delete', $model->id);

        $this->assertFalse(Hackthon::find($model->id)->exists());
       
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
        $role->revokePermissionTo('admin.hackthon.delete');
        
  
        $this->actingAs($admin, $guard);

        $model = Hackthon::factory()->create();

      Livewire::test(HackthonsTable::class)
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

        $admin->givePermissionTo('admin.hackthon.delete');
  
        $this->actingAs($admin, $guard);

        $model = Hackthon::factory()->create();

        $model->delete();

      Livewire::test(HackthonsTable::class)
          ->call('restore', $model->id);
    
          $this->assertTrue(Hackthon::find($model->id)->exists());
    }

    


   




}
