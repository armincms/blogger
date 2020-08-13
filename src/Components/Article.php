<?php 
namespace Armincms\Blogger\Components;
  
class Article extends Blog
{      
	/**
	 * Name of section
	 * 
	 * @var null
	 */
	protected $name = 'article';

	/**
	 * Label of section
	 * 
	 * @var null
	 */
	protected $label = 'blog::title.articles';

	/**
	 * SingularLabe of section
	 * 
	 * @var null
	 */
	protected $singularLabel = 'blog::title.article';   
}
