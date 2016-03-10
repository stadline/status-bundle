<?php 

namespace Stadline\StatusPageBundle\Requirements;

class RequirementCollections implements \IteratorAggregate
{
    /**
     * @var RequirementCollectionInterface[]
     */
	private $collections = array();

    /**
     * Add a collection.
     *
     * @param RequirementCollectionInterface $collection
     */
	public function addCollection(RequirementCollectionInterface $collection)
	{
		$this->collections[] = $collection;
	}
	
    /**
     * Gets the current RequirementCollections as an Iterator.
     *
     * @return \Traversable A Traversable interface
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
        
        foreach ($this->collections as $collection) {
            if (count($failed = $collection->getFailedRequirements())) {
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
    
        foreach ($this->collections as $collection) {
            if (count($failed = $collection->getFailedRecommendations())) {
                $array[$collection->getName()] = $failed;
            }
        }
    
        return $array;
    }

    /**
     * Checks if the requirement collection has issue.
     *
     * @param int $ignoreWarnings
     * @return bool
     */
    public function hasIssue($ignoreWarnings = 0)
    {
        if ($this->countFailedRequirements()+($ignoreWarnings?0:1)*$this->countFailedRecommendations() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Count the number of requirements.
     *
     * @return int
     */
    public function countRequirements()
    {
        $count = 0;
        
        foreach ($this->collections as $collection) {
            $count += count($collection->getRequirements());
        }
        
        return $count;
    }

    /**
     * Count the number of recommendations.
     *
     * @return int
     */
    public function countRecommendations()
    {
        $count = 0;
    
        foreach ($this->collections as $collection) {
            $count += count($collection->getRecommendations());
        }
    
        return $count;
    }

    /**
     * Count the number of failed requirements.
     *
     * @return int
     */
    public function countFailedRequirements()
    {
        return count($this->getFailedRequirements(), COUNT_RECURSIVE) - count($this->getFailedRequirements());
    }

    /**
     * Count the number of failed recommendations.
     *
     * @return int
     */
    public function countFailedRecommendations()
    {
        return count($this->getFailedRecommendations(), COUNT_RECURSIVE) - count($this->getFailedRecommendations());
    }
    
}