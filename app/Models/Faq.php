<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * An Eloquent Model: 'Faq'
 *
 * @property integer $id
 * @property string $question
 * @property string $answer
 */

class Faq extends Model
{
    protected $table = 'faq';
}
