<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;

/**
 * @OA\Schema(
 *     title="Partner",
 *     description="Partner model",
 *     @OA\Xml(
 *         name="Partner"
 *     )
 * )
 */

class Partner extends Model
{
    use PostgisTrait;

    protected $hidden = ['created_at', 'updated_at'];

    protected $fillable = ['tradingName','ownerName','document','coverageArea','address'];

    protected $postgisFields = [
        'coverageArea',
        'address'
    ];

}
