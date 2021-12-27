<?php


namespace App\GraphQL\Mutations\UsersAction;

use App\Models\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Hash;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class UpdateUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'updateUser'
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'id' => [
                'name' => 'id',
                'type' =>  Type::nonNull(Type::int()),
            ],
            'name' => [
                'name' => 'name',
                'type' =>  Type::nonNull(Type::string()),

            ],
            'email' => [
                'name' => 'email',
                'type' =>  Type::nonNull(Type::string()),

            ],
            'password' => [
                'name' => 'password',
                'type' =>   Type::string(),


            ],
            'password_confirmation' => [
                'name' => 'password_confirmation',
                'type' =>  Type::string(),
            ]
        ];
    }

    protected function rules(array $args = []): array
    {
        return [
            'name' => ['required' , 'min:6'],
            'email' => ['required' , "unique:users,email,{$args['id']}"],
            'password' => ['nullable' , 'min:6' , 'confirmed']
        ];
    }

    public function resolve($root, $args)
    {
        $user = User::findOrFail($args['id']);
        $user->name = $args['name'];
        $user->email = $args['email'];
        if(!empty($args['password'])){
            $user->password = Hash::make($args['password']);
        }
        $user->save();

        return $user;
    }
}
