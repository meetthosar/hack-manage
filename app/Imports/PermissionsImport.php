<?php
namespace App\Imports;

use App\Models\Permission;
use Illuminate\Support\Facades\DB;
use Livewire\TemporaryUploadedFile;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PermissionsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithValidation, WithChunkReading
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

                 DB::table('permissions')->whereNull('deleted_at')->updateOrInsert(
            [
                'guard_name' => $row['guard_name'],
            ],
            [
                                    'guard_name'=> $row['guard_name'],
                                    'name'=> $row['name'],
                            ]
        );
    }

    public function rules(): array
    {
        return [
                            '*.guard_name'=> 'required',
                            '*.name'=> 'required',
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
