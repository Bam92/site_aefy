# Nullable database fields for the Laravel PHP Framework
## v1.0.2

![Travis Build Status](https://travis-ci.org/deringer/laravel-nullable-fields.svg?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/deringer/laravel-nullable-fields/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/deringer/laravel-nullable-fields/?branch=master)

Often times, database fields that are not assigned values are defaulted to `null`. This is particularly important when creating records with foreign key constraints, where the relationship is not yet established.

As of version 1.0, this package also supports converting empty arrays to `null` in fields that are cast to an array, or not.

Note, the database field must be configured to allow null.

```php
public function up()
{
    Schema::create('profile_user', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->nullable()->default(null);
        $table->foreign('user_id')->references('users')->on('id'); 
        $table->string('twitter_profile')->nullable()->default(null);
        $table->string('facebook_profile')->nullable()->default(null);
        $table->string('linkedin_profile')->nullable()->default(null);
        $table->text('array_casted')->nullable()->default(null);
        $table->text('array_not_casted')->nullable()->default(null);
    });
}
```
    

More recent versions of MySQL will convert the value to an empty string if the field is not configured to allow null. Be aware that older versions may actually return an error.

Laravel does not currently support automatically setting nullable database fields as `null` when the value assigned to a given attribute is empty.

# Installation

This trait is installed via [Composer](http://getcomposer.org/). To install, simply add it to your `composer.json` file:

```
{
	"require": {
		"iatstuti/laravel-nullable-fields": "~1.0"
	}
}
```

Then run composer to update your dependencies:

```
$ composer update
```

In order to use this trait, import it in your Eloquent model, then set the protected `$nullable` property as an array of fields you would like to be saved as `null` when empty.

```php
<?php

use Iatstuti\Database\Support\NullableFields;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
	use NullableFields;
	
	protected $nullable = [
		'facebook_profile',
		'twitter_profile',
		'linkedin_profile',
		'array_casted',
		'array_not_casted',
	];
	
	protected $casts = [ 'array_casted' => 'array', ];
	
}
```

Now, any time you are saving a `UserProfile` profile instance, any empty attributes that are set in the `$nullable` property will be saved as `null`.

```php
<?php

$profile = new UserProfile::find(1);
$profile->facebook_profile = ' '; // Empty, saved as null
$profile->twitter_profile  = 'michaeldyrynda';
$profile->linkedin_profile = '';  // Empty, saved as null
$profile->array_casted = []; // Empty, saved as null
$profile->array_not_casted = []; // Empty, saved as null
$profile->save();
```

# More information

[Working with nullable fields in Eloquent models](https://iatstuti.net/blog/working-with-nullable-fields-in-eloquent-models) - first iteration

[Working with nullable fields in Eloquent models - Part Deux](https://iatstuti.net/blog/working-with-nullable-field-in-eloquent-models-part-deux) - second iteration, covers the details of this package

# Support

If you are having general issues with this package, feel free to contact me on [Twitter](https://twitter.com/michaeldyrynda).

If you believe you have found an issue, please report it using the [GitHub issue tracker](https://github.com/deringer/laravel-nullable-fields/issues), or better yet, fork the repository and submit a pull request.

If you're using this package, I'd love to hear your thoughts. Thanks!
