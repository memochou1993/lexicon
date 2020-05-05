<?php

namespace App\Models;

use App\Traits\HasForms;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasForms;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
}
