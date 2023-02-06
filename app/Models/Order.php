<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $fillable = [
        'address_of_the_recipient',
        'channel',
        'client_id',
        'commission_percent',
        'commission_rub',
        'delivery_address',
        'name',
        'price',
        'promocode',
        'size_box',
        'status_product',
        'study_or_teacher',
        'telegram_contact',
        'telegram_number',
        'the_number_of_boxes',
        'translation_curse',
        'url_for_product',
    ];

    protected $casts = [
        'the_number_of_boxes' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

}
