<?php

namespace App\Http\Traits;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

trait CanLoadRelationships{   //FOLDER 'TRAITS' is used x reusable code blocks
    public function loadRelationships(
        Model | QueryBuilder|EloquentBuilder $for,
        ?array $relations = null  //use arr $relations wheter it was passed, otherwise create new arr $relations=null
    ): Model | QueryBuilder|EloquentBuilder{  //this is the type of return

        $relations = $relations ?? $this->relations ?? [];  // ?? in php is the Null Coalescing Operator, return the first !=null
        foreach($relations as $relation){
            $for->when(   //when(condition, actionToRunIfTrue, optionActionToRunIfFalse)
                $this->shouldIncludeRelation($relation),
                fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
            );   //if $for is a Eloquent Model(normal model) run $for->load(..) otherwise $q->with(..)
        }
        return $for;  //res updated
    }

    protected function shouldIncludeRelation(string $relation):bool{
        $include = request()->query('include');  //become false if 'include' isn't in the query (i.e. query ok GET /api/events?include=user,attendees,attendees.user)
        if(!$include){
            return false;
        }
        $relations =array_map('trim',explode(',',$include));  //explode() create an arr using ',' as divisor of the str, + trim()
        //dd($relations);  //x debug, preview GET http://127.0.0.1:8000/api/events?include=user,attendees,attendees.user
        return in_array($relation,$relations);  //check if str $relations exist in the newly created arr
    }
}
