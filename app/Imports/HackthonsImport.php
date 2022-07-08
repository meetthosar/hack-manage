<?php
namespace App\Imports;

use App\Models\Hackthon;
use Illuminate\Support\Facades\DB;
use Livewire\TemporaryUploadedFile;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class HackthonsImport implements ToModel, WithHeadingRow, WithBatchInserts, WithValidation, WithChunkReading
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

                 DB::table('hackthons')->whereNull('deleted_at')->updateOrInsert(
            [
                'created_by' => $row['created_by'],
            ],
            [
                                    'created_by'=> $row['created_by'],
                                    'deleted_by'=> $row['deleted_by'],
                                    'description'=> $row['description'],
                                    'name'=> $row['name'],
                                    'updated_by'=> $row['updated_by'],
                            ]
        );
    }

    public function rules(): array
    {
        return [
                            '*.created_by'=> 'required',
                            '*.deleted_by'=> 'required',
                            '*.description'=> 'required',
                            '*.name'=> 'required',
                            '*.updated_by'=> 'required',
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
