<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Zekini\CrudGenerator\Traits\HasModelRelations;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Yajra\Auditable\AuditableWithDeletesTrait;

class Hackthon extends Model
{
    use AuditableWithDeletesTrait;
    use HasFactory;
    use HasModelRelations;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'description',
        'name',
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['*']);
        // Chain fluent methods for configuration options
    }



    // Relationships start here
    }
