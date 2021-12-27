<?php


namespace App\GraphQL\Mutations\UsersAction;


use App\Models\User;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Hash;
use Rebing\GraphQL\Support\Facades\GraphQL;
use Rebing\GraphQL\Support\Mutation;

class CreateUserMutation extends Mutation
{
    protected $attributes = [
        'name' => 'createUser',
        'description' => 'create new user'
    ];

    public function type(): Type
    {
        return GraphQL::type('User');
    }

    public function args(): array
    {
        return [
            'name' => [
                'name' => 'name',
                'type' =>  Type::nonNull(Type::string()),
                'rules' => ['required' , 'max:50']
            ],
            'email' => [
                'name' => 'email',
                'type' =>  Type::nonNull(Type::string()),
                'rules' => ['required' , 'email' , 'unique:users']
            ],
            'password' => [
                'name' => 'password',
                'type' =>  Type::nonNull(Type::string()),
                'rules' => ['required' , 'min:6' , 'confirmed']
            ],
            'password_confirmation' => [
                'name' => 'password_confirmation',
                'type' =>  Type::nonNull(Type::string()),
                'rules' => ['required' , 'min:6']
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $user = new User();
        $user->name = $args['name'];
        $user->email = $args['email'];
        $user->password = Hash::make($args['password']);
        $user->save();

        return $user;
    }
}
