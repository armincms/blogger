<?php

namespace Armincms\Blogger;

use Illuminate\Database\Eloquent\{Model, Builder, SoftDeletes}; 
use Armincms\Concerns\{IntractsWithMedia, Authorization};
use Armincms\Contracts\Authorizable;
use Armincms\Targomaan\Concerns\InteractsWithTargomaan;
use Armincms\Targomaan\Contracts\Translatable;
use Armincms\Categorizable\Categorizable;
use Armincms\Taggable\Taggable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Cviebrock\EloquentSluggable\Sluggable;
use Core\HttpSite\Concerns\{IntractsWithSite, HasPermalink}; 
use Core\HttpSite\Component;    

class Blog extends Model implements HasMedia, Translatable, Authorizable
{
	use SoftDeletes, IntractsWithMedia, Authorization, InteractsWithTargomaan, Categorizable, Taggable;

	use IntractsWithSite, HasPermalink, Sluggable {
		scopeFindSimilarSlugs as sluggableSimilarSlugs;
	}

	const LOCALE_KEY = 'language';

	protected $casts = [
		'source' => 'json',
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
}
