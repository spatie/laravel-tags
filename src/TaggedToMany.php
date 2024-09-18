<?php

namespace Spatie\Tags;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\JoinClause;

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
        $this->query->select($this->related->getTable().".*")
                    ->join("taggables as taggables_related", "taggables_related.taggable_id",
                        $this->related->getTable().".".$this->related->getKeyName())
                    ->join("taggables as taggables_parent", "taggables_parent.tag_id", "taggables_related.tag_id")
                    ->where("taggables_parent.taggable_type", get_class($this->parent))
                    ->where("taggables_related.taggable_type", get_class($this->related))
        ;

        if ($this->parent->getKey()) {
            $this->query->where("taggables_parent.taggable_id", $this->parent->getKey());
        }

        if ($this->type) {
            $this->query->join("tags", "taggables_parent.tag_id", "tags.id")
                        ->where("tags.type", $this->type)
            ;
        }
    }

    /**
     * @inheritDoc
     */
    #[\Override]
    public function addEagerConstraints(array $models)
    {
        $this->query->select([$this->related->getTable().".*", "taggables_parent.taggable_id"])
                    ->whereIn("taggables_parent.taggable_id", array_map(fn(Model $model) => $model->getKey(), $models));
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function initRelation(array $models, $relation)
    {
        foreach ($models as $model) {
            $model->setRelation(
                $relation,
                $this->related->newCollection()
            );
        }

        return $models;
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function match($models, Collection $results, $relation)
    {
        if ($results->isEmpty()) {
            return $models ?? [];
        }

        foreach ($models as $model) {
            $model->setRelation(
                $relation,
                $results->unique()->filter(function (Model $related) use ($model) {
                    return $related->taggable_id === $model->id;
                })->values()
            );
        }

        return $models;
    }

    /**
     * @inheritDoc
     */
    #[\Override] public function getResults()
    {
        return $this->query->get();
    }

    /**
     * @inheritDoc
     */
    public function getRelationExistenceQuery(Builder $query, Builder $parentQuery, $columns = ['*'])
    {
        $query->join("taggables as taggables_related", "taggables_related.taggable_id",
            $this->related->getTable().".".$this->related->getKeyName())
              ->join("taggables as taggables_parent", function (JoinClause $join) {
                  $join->on("taggables_parent.tag_id", "taggables_related.tag_id")
                       ->on("taggables_parent.taggable_id",
                           $this->parent->getTable().".".$this->parent->getKeyName())
                  ;
              })
        ;



        if ($this->type) {
            $query->join("tags", "taggables_parent.tag_id", "tags.id")
                  ->where("tags.type", $this->type)
            ;
        }

        return $query;
    }
}