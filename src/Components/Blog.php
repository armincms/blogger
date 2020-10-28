<?php 
namespace Armincms\Blogger\Components;
 
use Illuminate\Http\Request; 
use Core\HttpSite\Component;
use Core\HttpSite\Contracts\Resourceable;
use Core\HttpSite\Concerns\IntractsWithResource;
use Core\HttpSite\Concerns\IntractsWithLayout;
use Core\Document\Document;
use Armincms\Blogger\Blog as Model;

class Blog extends Component implements Resourceable
{       
	use IntractsWithResource, IntractsWithLayout;

	/**
	 * Route Conditions of section
	 * 
	 * @var null
	 */
	protected $wheres = [ 
		'id'	=> '[0-9]+'
	];  

	private $type = null;

	public function toHtml(Request $request, Document $docuemnt) : string
	{       
		$blog = Model::published()->where('url->'. app()->getLocale(), urlencode($request->relativeUrl()))->firstOrFail(); 

		$this->resource($blog);  

		$docuemnt->title(/*$blog->metaTitle()?:*/ $blog->title); 
		
		$docuemnt->description(/*$blog->metaDescription()?:*/ $blog->intro_text);   

		return $this->firstLayout($docuemnt, $this->config('layout'), 'citadel')
					->display($blog->toArray(), $docuemnt->component->config('layout', [])); 
	}   

	public function image(string $schema)
	{  
		return optional($this->resource->image)->get($schema) ?? schema_placeholder($schema);
	}

	public function categories()
	{
		return $this->resource->categories;
	}

	public function author()
	{
		return $this->resource->owner;
	}
}
