<?php

namespace Armincms\Blogger\Models;

use Armincms\Categorizable\HasCategories;
use Armincms\Contract\Concerns\Authorizable;
use Armincms\Contract\Concerns\InteractsWithFragments;
use Armincms\Contract\Concerns\InteractsWithMedia;
use Armincms\Contract\Concerns\InteractsWithMeta;
use Armincms\Contract\Concerns\InteractsWithUri;
use Armincms\Contract\Concerns\InteractsWithWidgets;
use Armincms\Contract\Concerns\Localizable;
use Armincms\Contract\Concerns\Sluggable;
use Armincms\Contract\Contracts\Authenticatable;
use Armincms\Contract\Contracts\HasMedia;
use Armincms\Contract\Contracts\HasMeta;
use Armincms\Markable\Archivable;
use Armincms\Taggable\HasTags;
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; 
use Illuminate\Database\Eloquent\SoftDeletes; 

abstract class Blog extends Model implements Authenticatable, HasMedia, HasMeta
{ 
    use Archivable;
    use Authorizable;
    use HasCategories;
    use HasTags;
    use InteractsWithFragments;
    use InteractsWithMedia;
    use InteractsWithMeta;
    use InteractsWithUri;
    use InteractsWithWidgets;
    use InteractsWithTargomaan;
    use Localizable;
    use Sluggable;
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blogs';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [ 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [ 
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [ 
        'publish_date' => 'timestamp',
        'archive_date' => 'timestamp',
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(function ($builder) {
            $builder->where($builder->qualifyColumn('resource'), static::resourceName());
        });
    }

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return app()->make(\Armincms\Blogger\PostFactory::class);
    }

    /**
     * Query where has the gicen resoure tpes.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query     
     * @param  array  $resources 
     * @return \Illuminate\Database\Eloquent\Builder            
     */
    public function scopeResources($query, array $resources)
    {
        return $query->whereIn($query->qualifyColumn('resource'), $resources);
    } 

    /**
     * Get the corresponding cypress fragment.
     * 
     * @return 
     */
    public function cypressFragment(): string
    {
        return 'Armincms\Blogger\Cypress\Fragments\\'. class_basename(get_called_class());
    }

    /**
     * Get scoped resource name.
     * 
     * @return string
     */
    public static function resourceName(): string
    {
        return 'Armincms\Blogger\Nova\\'. class_basename(get_called_class());
    }

    /**
     * Get the targomaan driver.
     * 
     * @return string
     */
    public function translator() : string
    {
        return 'sequential';
    }
    
    /**
     * Serialize the model for pass into the client view.
     *
     * @param Zareismail\Cypress\Request\CypressRequest
     * @return array
     */
    public function serializeForWidget($request): array
    { 
        return array_merge($this->toArray(), $this->getFirstMediasWithConversions()->toArray(), [
            'creation_date' => $this->created_at->format('Y F d'),
            'last_update'   => $this->updated_at->format('Y F d'),
            'author'=> $this->auth->fullname(), 
            'url'   => $this->getUrl($request),
        ]);
    }
}
