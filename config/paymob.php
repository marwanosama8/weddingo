<?php


return [

    /*
    |--------------------------------------------------------------------------
    | PayMob Default Order Model
    |--------------------------------------------------------------------------
    |
    | This option defines the default Order model.
    |
    */

    'order' => [
        'model' => 'App\Models\Subscription'
    ],

    /*
    |--------------------------------------------------------------------------
    | PayMob username and password
    |--------------------------------------------------------------------------
    |
    | This is your PayMob username and password to make auth request.
    |
    */

    'api_key' => env('PAYMOB_API_KEY',"ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SnVZVzFsSWpvaWFXNXBkR2xoYkNJc0luQnliMlpwYkdWZmNHc2lPamN4TWpneE55d2lZMnhoYzNNaU9pSk5aWEpqYUdGdWRDSjkubU9jYmQ0Q3JGaTFGd0dsb2lHdG40YzNULTI0SFBYdWZjQ3VlVVV5a045YWhuNWkzOHZVTW5MWWNvOWZuM0t3cXhqSHZ2dHVoYUJpVUdTZy1Ja0lGZUE="),

];
