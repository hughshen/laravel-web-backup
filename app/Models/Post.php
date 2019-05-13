<?php

namespace App\Models;

class Post extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'post';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'author'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'excerpt', 'slug', 'status', 'updated_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'parent', 'author',
    ];

    // Status const
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISH = 'publish';
    const STATUS_LIST = [
        self::STATUS_DRAFT,
        self::STATUS_PUBLISH,
    ];

    // Type const
    const TYPE_POST = 'post';
    const TYPE_PAGE = 'page';
    const TYPE_LIST = [
        self::TYPE_POST,
        self::TYPE_PAGE,
    ];

    /**
     * Get the post's created_at text.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * Get the post's updated_at text.
     *
     * @param  string  $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * Terms
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function terms()
    {
        return $this->belongsToMany(Term::class, 'post_term', 'post_id', 'term_id');
    }

    /**
     * Tags
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Term::class, 'post_term', 'post_id', 'term_id')->where('taxonomy', Term::TAX_TAG);
    }

    /**
     * Categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function cats()
    {
        return $this->belongsToMany(Term::class, 'post_term', 'post_id', 'term_id')->where('taxonomy', Term::TAX_CAT);
    }
}
