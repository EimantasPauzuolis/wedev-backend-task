<?php

namespace App\Mapper;

use App\Interfaces\Mapper;
use Illuminate\Support\Arr;

class UserDetailsMapper implements Mapper
{
    public function map(array $data): array
    {
        $detailFields = Arr::only($data, config('user_details.allowed_fields'));

        if (!empty($detailFields)) {
            $userDetails = [];
            foreach ($detailFields as $key => $value) {
                $userDetails[] = [
                    'key' => $key,
                    'value' => $value
                ];
                unset($data[$key]);
            }
            $data['userDetails'] = $userDetails;
        }
        return $data;
    }

    public function parse(array $data): array
    {
        if (Arr::has($data, 'user_details')) {
            foreach (Arr::get($data, 'user_details') as $details) {
                $data[$details['key']] = $details['value'];
            }
            unset($data['user_details']);
        }
        return $data;
    }
}
