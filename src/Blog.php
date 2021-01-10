<?php

namespace Armincms\Blogger;

use Illuminate\Support\Str; 
use Illuminate\Support\Collection; 
use Illuminate\Database\Eloquent\{Model, Builder, SoftDeletes}; 
use Armincms\Concerns\{IntractsWithMedia, Authorization, InteractsWithLayouts};
use Armincms\Contracts\{Authorizable, HasLayout};
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;
use Armincms\Targomaan\Contracts\Translatable;
use Armincms\Fields\TargomaanField;
use Armincms\Categorizable\Contracts\Categorizable;
use Armincms\Categorizable\Concerns\InteractsWithCategories;
use Armincms\Taggable\Contracts\Taggable;
use Armincms\Taggable\Concerns\InteractsWithTags;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Core\HttpSite\Concerns\{IntractsWithSite, HasPermalink}; 
use Core\HttpSite\Component;       

class Blog extends Model implements HasMedia, Translatable, Authorizable, Categorizable, Taggable, HasLayout
{
	use SoftDeletes, Authorization, InteractsWithTargomaan, IntractsWithMedia, InteractsWithLayouts; 
    use InteractsWithCategories, InteractsWithTags, IntractsWithSite, HasPermalink, HasPublish;
    use Sluggable {
		scopeFindSimilarSlugs as sluggableSimilarSlugs; 
	} 

	const LOCALE_KEY = 'language';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blogs';


	protected $casts = [
		'seo' => 'json',
        'source' => 'json',
        'publish_date' => 'datetime',
        'archive_date' => 'datetime',
	];

	protected $medias = [
		'image' => [
			'disk' => 'armin.image',
			'schemas' => [
				'*', 'blog', 'blog.list'
			],
		]
	];  

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ]; 
    } 

    public function component() : Component
    {
    	$component = __NAMESPACE__.'\\Components\\'.class_basename($this->resource);

    	return new $component;
    }

    /**
     * Query scope for finding "similar" slugs, used to determine uniqueness.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $attribute
     * @param array $config
     * @param string $slug
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFindSimilarSlugs(Builder $query, string $attribute, array $config, string $slug): Builder
    { 
    	return $this->sluggableSimilarSlugs($query, $attribute, $config, $slug)
    			->where('resource', $this->resource);
    }

	/**
	 * Driver name of the targomaan.
	 * 
	 * @return [type] [description]
	 */
	public function translator(): string 
	{
		return 'sequential';
	}

    /**
     * Query the model's `resource` attribute with the 'post' value.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopePosts($query)
    {
        return $query->resource(Nova\Post::class);
    }

    /**
     * Query the model's `resource` attribute with the 'article' value.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeArticles($query)
    {
        return $query->resource(Nova\Article::class);
    } 

    /**
     * Query the model's `resource` attribute with the 'video' value.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeVideos($query)
    {
        return $query->resource(Nova\Video::class);
    } 

    /**
     * Query the model's `resource` attribute with the 'podcast' value.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopePodcasts($query)
    {
        return $query->resource(Nova\Podcast::class);
    } 

    /**
     * Query the model's `resource` attribute with the given value.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @param  string $resource  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopeResource($query, $resource)
    {
        return $query->where($query->qualifyColumn('resource'), $resource);
    }

    public function featuredImage(string $schema = 'main')
    {
        return $this->featuredImages()->get($schema);
    }

    /**
     * Get the class name for polymorphic relations.
     *
     * @return string
     */
    public function getMorphClass()
    {
        return self::class;
    }

    /**
     * Query the related categories.
     * 
     * @return \Illuminate\Database\Eloqenut\Relations\BelongsToMany
     */
    public function categories() 
    {
        return $this->morphToMany(Models\Category::class, 'categorizable', 'categorizable');
    }

    public function featuredImages()
    {
        return $this->getConversions(
            $this->getFirstMedia('image'), config('blog.schemas', ['main', 'thumbnail'])
        );
    }
}
