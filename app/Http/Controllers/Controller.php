<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Format responses into the correct JSON structure
     */
    public function response($data)
    {
        $json = ['data' => $data];
        if ($data instanceof Collection) {
            $json = array_merge($json, [
                'meta' => [
                    'total' => $data->count()
                ]
            ]);
        }
        return response($json);
    }
}
