<?php

use PHPUnit\Framework\TestCase;
use tderenko\pagination\Pagination;

class PaginationTest extends TestCase
{
    public function testPagination()
    {
        $pagination = Pagination::init([
            'total' => 500,
            'limit' => 50,
            'currentPage' => 1,
        ])->render();

        $this->assertEquals(0, $pagination['offset']);
        $this->assertEquals(6, count($pagination['pages']));

    }
}