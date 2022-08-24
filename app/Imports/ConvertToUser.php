<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ConvertToUser implements ToCollection
{
    /**
     * @var Collection $data
     */
    public $data;
    public $errors;

    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        $this->data = collect();
        $this->errors = collect();
        foreach ($rows as $row) {
            $phoneNumber = str_replace('-', '', $row[0]);
            $phoneNumber = str_replace('+', '', $phoneNumber);
            if ($phoneNumber == '') {
                continue;
            }
            $user = User::query()
                ->selectRaw("phone_number, users.id as id")
                ->havingRaw("phone_number = {$phoneNumber}")
                ->first();

            if ($user) {
                $this->data->push($user->id);
            } else {
                $this->errors->push($phoneNumber);
            }
        }
    }
}
