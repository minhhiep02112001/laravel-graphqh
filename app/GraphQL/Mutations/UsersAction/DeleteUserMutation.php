<?php


namespace App\GraphQL\Mutations\UsersAction;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Rebing\GraphQL\Support\Mutation;

class DeleteUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'deleteUser',
        'description' => 'User a book'
    ];

    public function type(): Type
    {
        return Type::boolean();
    }


    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::int(),
                'rules' => ['required']
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $user = User::findOrFail($args['id']);

        return  $user->delete() ? true : false;
    }
}
