<?php

namespace Module\Source\Vk\Models;

use Illuminate\Database\Eloquent\Model;

class VkLead extends Model
{
    protected $table = 'vk_leads';

    protected $fillable = [
        'source_id',
        'group_id',
        'object',
        'type',
        'user_id',
        'ref',
        'ref_source',
    ];

    protected $casts = [
        'object' => 'json',
    ];
}
