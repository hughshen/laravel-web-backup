<?php

namespace App\Models;

class Term extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'term';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'slug', 'taxonomy', 'status', 'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'parent',
    ];

    // Taxonomy const
    const TAX_TAG = 'tag';
    const TAX_CAT = 'category';
    const TAX_LIST = [
        self::TAX_TAG,
        self::TAX_CAT,
    ];

    /**
     * Get the term's created_at text.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * Get the term's updated_at text.
     *
     * @param  string  $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * Posts
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_term', 'term_id', 'post_id');
    }
}
