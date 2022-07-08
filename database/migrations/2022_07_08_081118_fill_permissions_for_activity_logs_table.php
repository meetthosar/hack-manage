<?php

use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Config\Repository as Config;
use Zekini\CrudGenerator\Traits\CreatesPermissionObject;
use Spatie\Permission\Models\Role;

class FillPermissionsForActivityLogsTable extends Migration
{
    use CreatesPermissionObject;

    protected string $guardName;

    protected Config $config;

    protected Collection $permissions;

    protected Collection $roles;

    /**
     * FillPermissionsForActivityLogsTable constructor.
     */
    public function __construct()
    {
        $this->config = config();

        $this->guardName = $this->config->get('zekini-admin.defaults.guard', 'web');


        $this->permissions = collect([
            'admin.activity_log',
            'admin.activity_log.index',
            'admin.activity_log.create',
            'admin.activity_log.show',
            'admin.activity_log.edit',
            'admin.activity_log.delete'
        ]);
    }


    protected function attachPermissiontoAdminRole():void
    {
        $adminRoles = $this->config->get('zekini-admin.admin_roles');

        foreach($adminRoles as $roleName) 
        {
            $role = Role::findByName($roleName['name'], $this->guardName);
            if(! $role) continue;
            $role->givePermissionTo($this->permissions->toArray());
        }
    }


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::transaction(function(){

            // Setup Permissions
            $this->setupPermissions();

            $this->attachPermissiontoAdminRole();

        });
        app()['cache']->forget(config('permission.cache.key'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        DB::transaction(function(){

            $this->clearRoleHasPermissions();

            $this->clearPermissions();
        });
        
        app()['cache']->forget(config('permission.cache.key'));
    }

    /**
     * clearRoleHasPermissions
     *
     * @return  void
     */
    protected function clearRoleHasPermissions()
    {
        $adminRoles = $this->config->get('zekini-admin.admin_roles');

        foreach($adminRoles as $roleName) 
        {
            $role = Role::findByName($roleName['name'], $this->guardName);
            if(! $role) continue;
            $role->revokePermissionTo($this->permissions->toArray());
        }
    }
    
    /**
     * clearPermissions
     *
     * @return  void
     */
    protected function clearPermissions()
    {
        $permissions  = DB::table('permissions')->whereIn('name', $this->permissions->toArray())->delete();
    }
}
