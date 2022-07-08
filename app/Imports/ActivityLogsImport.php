<?php
namespace App\Imports;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\DB;
use Livewire\TemporaryUploadedFile;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ActivityLogsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithValidation, WithChunkReading
{
    use RemembersRowNumber;

    public function __construct(
        public TemporaryUploadedFile $file
    ) {
    }

    /**
     * @return  \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        logger('Row: ' . $this->getRowNumber());

                 DB::table('activitylogs')->whereNull('deleted_at')->updateOrInsert(
            [
                'batch_uuid' => $row['batch_uuid'],
            ],
            [
                                    'batch_uuid'=> $row['batch_uuid'],
                                    'causer_id'=> $row['causer_id'],
                                    'causer_type'=> $row['causer_type'],
                                    'description'=> $row['description'],
                                    'event'=> $row['event'],
                                    'log_name'=> $row['log_name'],
                                    'properties'=> $row['properties'],
                                    'subject_id'=> $row['subject_id'],
                                    'subject_type'=> $row['subject_type'],
                            ]
        );
    }

    public function rules(): array
    {
        return [
                            '*.batch_uuid'=> 'required',
                            '*.causer_id'=> 'required',
                            '*.causer_type'=> 'required',
                            '*.description'=> 'required',
                            '*.event'=> 'required',
                            '*.log_name'=> 'required',
                            '*.properties'=> 'required',
                            '*.subject_id'=> 'required',
                            '*.subject_type'=> 'required',
                    ];
    }


    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
