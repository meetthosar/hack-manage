<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Zekini\CrudGenerator\Traits\HasModelRelations;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Role extends Model 
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
        
        public function permissions()
    {
    
        return $this->belongsToMany(Permission::class
                , "role_has_permissions"
                        , "role_id"
                        , "permission_id"
                );
    }

        }
