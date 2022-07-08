<?php
namespace Tests\Unit\Hackthon;
use Tests\TestCase;
use App\Models\Hackthon;
use App\Http\Livewire\CreateHackthon;
use App\Http\Livewire\Hackthons\Hackthons;
use Livewire\Livewire;
use Zekini\CrudGenerator\Factory\AdminFactory;
use Spatie\Permission\Models\Role;

class HackthonTest extends TestCase
{
    /**
     * Test we can create Hackthon
     * @group  Hackthon_test
     * @return  void
     */
    public function test_we_can_create_Hackthon()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = AdminFactory::create();
      $admin->givePermissionTo('admin.hackthon.create');

      $this->actingAs($admin, $guard);

      $firstData = 'raw_data';
      Livewire::test(Hackthons::class)
                                ->set('state.created_by', $this->faker->randomNumber())
                                        ->set('state.deleted_by', $this->faker->randomNumber())
                                        ->set('state.description', $this->faker->sentence())
                              ->set('state.name', $firstData)
                                        ->set('state.updated_by', $this->faker->randomNumber())
                            ->call('submit')
        ->assertHasNoErrors();

        $this->assertTrue(Hackthon::where('created_by', $firstData)->exists());
    }

    /**
     * Test we can update Hackthon
     * @group  Hackthon_test
     * @return  void
     */
    public function test_we_can_update_Hackthon()
    {
      $guard = config('zekini-admin.defaults.guard');
      $admin  = AdminFactory::create();
      $admin->givePermissionTo('admin.hackthon.edit');

      $this->actingAs($admin, $guard);

      $model = Hackthon::factory()->create();

      $firstData = 'raw_data';
      Livewire::test(Hackthons::class)
      ->call('launchHackthonEditModal', [$model->id])
                              ->set('state.created_by', $this->faker->randomNumber())
                                      ->set('state.deleted_by', $this->faker->randomNumber())
                                      ->set('state.description', $this->faker->sentence())
                              ->set('state.name', $firstData)
                                      ->set('state.updated_by', $this->faker->randomNumber())
                            ->call('editSubmit')
        ->assertHasNoErrors();

        $this->assertTrue(Hackthon::where('created_by', $firstData)->exists());
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
        $admin->givePermissionTo('admin.hackthon.create');
  
        $this->actingAs($admin, $guard);

        Livewire::test(Hackthons::class)
                                      ->set('state.created_by', $this->faker->randomNumber())
                                                ->set('state.deleted_by', $this->faker->randomNumber())
                                                ->set('state.description', $this->faker->sentence())
                                      ->set('state.name', '')
                                                ->set('state.updated_by', $this->faker->randomNumber())
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
        $role->revokePermissionTo('admin.hackthon.create');
  
        $this->actingAs($admin, $guard);

        Livewire::test(Hackthons::class)
                                      ->set('state.created_by', $this->faker->randomNumber())
                                                ->set('state.deleted_by', $this->faker->randomNumber())
                                                ->set('state.description', $this->faker->sentence())
                                      ->set('state.name', '')
                                                ->set('state.updated_by', $this->faker->randomNumber())
                                    ->call('submit')
          ->assertForbidden();
    }
}
