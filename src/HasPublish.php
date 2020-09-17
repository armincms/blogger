<?php 

namespace Armincms\Blogger;

use Zareismail\Markable\{Markable, HasArchive, HasPending, HasDraft};  

trait HasPublish 
{
	use Markable, HasArchive, HasPending, HasDraft;
    
    /**
     * Mark the model with the "publish" value.
     *
     * @return $this
     */
    public function asPublished()
    {
        return $this->markAs($this->getPublishVlaue());
    } 

    /**
     * Determine if the value of the model's "marked as" attribute is equal to the "publish" value.
     * 
     * @param  string $value 
     * @return bool       
     */
    public function isPublished()
    {
        return $this->markedAs($this->getPublishVlaue());
    }

    /**
     * Query the model's `marked as` attribute with the "publish" value.
     * 
     * @param  \Illuminate\Database\Eloquent\Builder $query  
     * @return \Illuminate\Database\Eloquent\Builder       
     */
    public function scopePublished($query)
    {
        return $this->mark($this->getPublishVlaue());
    }

    /**
     * Set the value of the "marked as" attribute as "publish" value.
     * 
     * @return $this
     */
    public function setPublished()
    {
        return $this->setMarkedAs($this->getPublishVlaue());
    }

    /**
     * Get the value of the "publish" mark.
     *
     * @return string
     */
    public function getPublishVlaue()
    {
        return defined('static::PUBLISH_VALUE') ? static::PUBLISH_VALUE : 'published';
    }
}