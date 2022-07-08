<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Zekini\CrudGenerator\Traits\HasModelRelations;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ActivityLog extends Model 
{
    use HasFactory; 
    use HasModelRelations;
        use SoftDeletes;
        
    protected $fillable = [ 
        'batch_uuid',
        'causer_id',
        'causer_type',
        'description',
        'event',
        'log_name',
        'properties',
        'subject_id',
        'subject_type',
        ];

    
            public function causer(){
            return $this->morphTo();
        }

        public function subject(){
            return $this->morphTo();
        }
    
    
    // Relationships start here
    }
