<?php


namespace App\GraphQL\Mutations\UsersAction;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Hash;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class SearchUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'searchUser',
        'description' => 'Search user'
    ];

    public function type(): Type
    {
        return Type::listOf(GraphQL::type('User'));
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(Type::int()),
            ],
            'name' => [
                'name' => 'name',
                'type' =>  Type::string(Type::string()),
            ],
            'email' => [
                'name' => 'email',
                'type' =>  Type::string(Type::string()),
            ],

        ];
    }

    public function resolve($root, $args)
    {
        $users = User::where(function ($q) use ($args) {
            foreach ($args as $key => $value) {
                $q->orWhere($key, 'like', "%{$value}%");
            }
        })->get();

        return $users;
    }
}

