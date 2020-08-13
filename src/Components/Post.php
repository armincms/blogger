<?php 
namespace Armincms\Blogger\Components;
  
class Post extends Blog
{      
	/**
	 * Name of section
	 * 
	 * @var null
	 */
	protected $name = 'post';

	/**
	 * Label of section
	 * 
	 * @var null
	 */
	protected $label = 'blog::title.posts';

	/**
	 * SingularLabe of section
	 * 
	 * @var null
	 */
	protected $singularLabel = 'blog::title.post';   
}
