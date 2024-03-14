<?php

namespace App\Http\Requests\Admin\Egg;

use App\Http\Requests\Admin\AdminFormRequest;

class EggImportFormRequest extends AdminFormRequest
{
    public function rules(): array
    {
        return [
            'import_file' => 'bail|required|file|max:1000|mimetypes:application/json,text/plain',
        ];
    }
}
