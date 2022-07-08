<?php
namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\TemporaryUploadedFile;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithBatchInserts, WithValidation, WithChunkReading
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

                 DB::table('users')->whereNull('deleted_at')->updateOrInsert(
            [
                'created_by' => $row['created_by'],
            ],
            [
                                    'created_by'=> $row['created_by'],
                                    'current_team_id'=> $row['current_team_id'],
                                    'deleted_by'=> $row['deleted_by'],
                                    'email'=> $row['email'],
                                    'name'=> $row['name'],
                                    'profile_photo_path'=> $row['profile_photo_path'],
                                    'two_factor_confirmed_at'=> $row['two_factor_confirmed_at'],
                                    'updated_by'=> $row['updated_by'],
                            ]
        );
    }

    public function rules(): array
    {
        return [
                            '*.created_by'=> 'required',
                            '*.current_team_id'=> 'required',
                            '*.deleted_by'=> 'required',
                            '*.email'=> 'required',
                            '*.name'=> 'required',
                            '*.profile_photo_path'=> 'required',
                            '*.two_factor_confirmed_at'=> 'required',
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
