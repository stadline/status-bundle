<?php 

namespace Stadline\StatusPageBundle\Requirements;

class RequirementCollections implements \IteratorAggregate
{
	private $collections = array();
	
	public function addCollection($collection)
	{
		$this->collections[] = $collection;
	}
	
    /**
     * Gets the current RequirementCollections as an Iterator.
     *
     * @return Traversable A Traversable interface
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->collections);
    }
    
    /**
     * Gets failed requirements
     * 
     * @return array
     */
    public function getFailedRequirements()
    {
        $array = array();
        
        foreach ($this->collections as $collection)
        {
            if (count($failed = $collection->getFailedRequirements()))
            {
                $array[$collection->getName()] = $failed;
            }
        }
        
        return $array;
    }

    /**
     * Gets failed recommendations
     *
     * @return array
     */
    public function getFailedRecommendations()
    {
        $array = array();
    
        foreach ($this->collections as $collection)
        {
            if (count($failed = $collection->getFailedRecommendations()))
            {
                $array[$collection->getName()] = $failed;
            }
        }
    
        return $array;
    }

    public function hasIssue($ignoreWarnings = 0)
    {
       
        if ($this->countFailedRequirements()+($ignoreWarnings?0:1)*$this->countFailedRecommendations() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function countRequirements()
    {
        $count = 0;
        
        foreach ($this->collections as $collection)
        {
            $count += count($collection->getRequirements());
        }
        
        return $count;
    }

    public function countRecommendations()
    {
        $count = 0;
    
        foreach ($this->collections as $collection)
        {
            $count += count($collection->getRecommendations());
        }
    
        return $count;
    }
    
    public function countFailedRequirements()
    {
        return count($this->getFailedRequirements(), COUNT_RECURSIVE) - count($this->getFailedRequirements());
    }

    public function countFailedRecommendations()
    {
        return count($this->getFailedRecommendations(), COUNT_RECURSIVE) - count($this->getFailedRecommendations());
    }
    
}