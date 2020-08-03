<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use IteratorAggregate;
use Tests\TestCase;

class CollectionTest extends TestCase
{

    /** @test */
    public function empty_instance_collection_returns_no_data(){
        $collection = new \App\MyCollection;

        $this->assertEmpty($collection->first());
    }

    /** @test */
    public function get_the_count_of_collection(){
        $collection = new \App\MyCollection([
            1,2,3,4,5
        ]);

        $this->assertEquals(5,$collection->count());
    }

    /** @test */
    public function is_collection_instance_of_iterator_aggregate(){
        $collection = new \App\MyCollection();

        $this->assertInstanceOf(IteratorAggregate::class,$collection);
    }

    /** @test */
    public function can_collection_be_iteratored(){
        $collection = new \App\MyCollection([
            1,2,3,4,5,6,7
        ]);
        $items = [];
        foreach($collection as $item){
            $items[] = $item;
        }

        $this->assertEquals($collection->count(),count($items));
    }

}
