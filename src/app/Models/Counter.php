<?php


namespace App\Models;


use BaoPham\DynamoDb\DynamoDbModel;

class Counter extends DynamoDbModel
{
    protected $table = 'counter';
    protected $primaryKey = 'key';
    public $fillable = [
        'key',
        'count'
    ];
    
}