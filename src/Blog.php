<?php

namespace Armincms\Blogger;

use Illuminate\Support\Str; 
use Illuminate\Support\Collection; 
use Illuminate\Database\Eloquent\{Model, Builder, SoftDeletes}; 
use Armincms\Concerns\{IntractsWithMedia, Authorization};
use Armincms\Contracts\Authorizable;
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;
use Armincms\Targomaan\Contracts\Translatable;
use Armincms\Fields\TargomaanField;
use Armincms\Categorizable\Categorizable;
use Armincms\Taggable\Taggable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Core\HttpSite\Concerns\{IntractsWithSite, HasPermalink}; 
use Core\HttpSite\Component;    

class Blog extends Model implements HasMedia, Translatable, Authorizable
{
	use SoftDeletes, Authorization, InteractsWithTargomaan; 
    use Categorizable, Taggable, IntractsWithSite, HasPermalink, HasPublish;
    use Sluggable {
		scopeFindSimilarSlugs as sluggableSimilarSlugs; 
	}
    use IntractsWithMedia {
        getMedia as spatieGetMedia; 
    }

	const LOCALE_KEY = 'language';

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
     * Get media collection by its collectionName.
     *
     * @param string $collectionName
     * @param array|callable $filters
     *
     * @return \Illuminate\Support\Collection
     */
    public function getMedia(string $collectionName = 'default', $filters = []): Collection
    {
        return $this->spatieGetMedia(
            Str::before($collectionName, TargomaanField::Delimiter()), $filters
        );
    }

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
}
