<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Zekini\CrudGenerator\Traits\HasModelRelations;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Permission extends Model 
{
    use HasFactory; 
    use HasModelRelations;
        use SoftDeletes;
            use LogsActivity;
    
    protected $fillable = [ 
        'guard_name',
        'name',
        ];

    
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }
    
    
    
    // Relationships start here
        
        public function roles()
    {
    
        return $this->belongsToMany(Role::class
                , "role_has_permissions"
                        , "permission_id"
                        , "role_id"
                );
    }

        }
