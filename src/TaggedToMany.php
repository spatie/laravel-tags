<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class TaggedToMany extends Relation
{

    public function __construct(Model $parent, string $related, public string|null $type = null)
    {
        $instance = new $related;

        parent::__construct($instance->query(), $parent);
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function addConstraints()
    {
        $this->query->join("taggables as taggables_related", "taggables_related.taggable_id",
            $this->related->getTable().".".$this->related->getKeyName())
                    ->join("taggables as taggables_parent", "taggables_parent.tag_id", "taggables_related.tag_id")
                    ->join("tags", "tags.id", "taggables_related.tag_id")
                    ->where("taggables_parent.taggable_type", get_class($this->parent))
                    ->where("taggables_parent.taggable_id", $this->parent->getKey())
                    ->where("taggables_related.taggable_type", get_class($this->related))
        ;

        if ($this->type) {
            $this->query->where("tags.type", $this->type);
        }
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function addEagerConstraints(array $models)
    {
        // TODO: Implement addEagerConstraints() method.
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function initRelation(array $models, $relation)
    {
        // TODO: Implement initRelation() method.
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function match(array $models, Collection $results, $relation)
    {
        // TODO: Implement match() method.
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function getResults()
    {
        return $this->query->get();
    }
}