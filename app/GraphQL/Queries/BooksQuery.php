<?php
namespace App\GraphQL\Queries;

use App\Models\Book;
use Rebing\GraphQL\Support\Query;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Facades\GraphQL;
class BooksQuery extends Query
{
    protected $attributes = [
        'name' => 'books',
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('Book'));
    }

    public function resolve($root, $args)
    {
        return Book::all();
    }
}
