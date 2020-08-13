<?php 
namespace Armincms\Blogger\Components;
 
use Illuminate\Http\Request; 
use Core\HttpSite\Component;
use Core\Document\Document; 
use Core\HttpSite\Contracts\Resourceable;
use Core\HttpSite\Concerns\IntractsWithResource;
use Core\HttpSite\Concerns\IntractsWithLayout;
use Armincms\Taggable\Tag as Model;

class Tag extends Component implements Resourceable
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

	public function toHtml(Request $request, Document $docuemnt) : string
	{       
		$category = Tag::whereHas('translates', function($q) use ($request) {   
			$q
				->where('url', 'LIKE', "%{$request->relativeUrl()}")
				->where($q->qualifyColumn('language'), app()->getLocale());
		})->firstOrFail();

		if(! $category->isVisible()) {
			abort(404, "404 Not Found Error ...!");
		}

		$this->resource($category);  

		$docuemnt->title($category->metaTitle()?: $category->title); 
		
		$docuemnt->description($category->metaDescription()?: $category->intro_text);   

		return $this->firstLayout($docuemnt, $this->config('layout'), 'citadel')
					->display($category->toArray(), $docuemnt->component->config('layout', [])); 
	}    
}
