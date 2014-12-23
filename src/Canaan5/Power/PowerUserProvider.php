<?php
namespace Canaan5\Power;

use Illuminate\Hashing\HasherInterface;
use Illuminate\Auth\UserProviderInterface;
use Illuminate\Auth\UserInterface;
use Canaan5\Power\Exceptions\UserUnverifiedException;
use Canaan5\Power\Exceptions\UserDeletedException;
use Canaan5\Power\Exceptions\UserDisabledException;
use Canaan5\Power\Exceptions\UserNotFoundException;
use Canaan5\Power\Exceptions\UserPasswordIncorrectException;

class PowerUserProvider implements UserProviderInterface
{
    /**
     * The hasher implementation.
     *
     * @var Illuminate\Hashing\HasherInterface
     */
    protected $hasher;

    /**
     * The Eloquent user model.
     *
     * @var string
     */
    protected $model;

    /**
     * Create a new database user provider.
     *
     * @param  Illuminate\Hashing\HasherInterface  $hasher
     * @param  string  $model
     * @return void
     */
    public function __construct(HasherInterface $hasher, $model)
    {
        $this->model = $model;
        $this->hasher = $hasher;
    }

    /**
     * Retrieve a user by their unique id.
     *
     * @param  mixed  $id
     * @return Illuminate\Auth\UserInterface|null
     */
    public function retrieveByID($id)
    {
        return $this->createModel()->newQuery()->find($id);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return Illuminate\Auth\UserInterface|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        // Are we checking by id?
        if (array_key_exists('id', $credentials)) {
            // Grab each val to be identifed against
            foreach (\Config::get('power::id') as $id) {
                // Create a new query for each check
                $query = $this->createModel()->newQuery();
                // Start off the query with the first id value
                $query->where($id, $credentials['id']);

                // Add any other values to user has passed in
                foreach ($credentials as $key => $value) {
                    if (
                        !str_contains($key, 'password') &&
                        !str_contains($key, 'id')
                    ) {
                        $query->where($key, $value);
                    }
                }

                if ($query->count() != 0) {
                    break;
                }
            }
        }
        else
        {

            // Now i will create a queary and add the provided credential element and a were clause
            // Then i we run the query
            // If i find any user i will send it to Eloquent User model.

            $query = $this->createModel()->newQuery();

            foreach ($credentials as $key => $value) {
                if (!str_contains($key, 'password')) {
                    $query->where($key, $value);
                }
            }
        }

        // if no user is found throw an exception
        if ($query->count() == 0) {
            throw new UserNotFoundException('User can not be found');
        }

        return $query->first();
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  Illuminate\Auth\UserInterface  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserInterface $user, array $credentials)
    {
        $plain = $credentials['password'];
        // Is user password is valid?
        if(!$this->hasher->check($user->salt.$plain, $user->getAuthPassword())) {
            throw new UserPasswordIncorrectException('User password is incorrect');
        }

        // Valid user, but are they verified?
        if (!$user->verified) {
            throw new UserUnverifiedException('User is not verified');
        }

        // Is the user disabled?
        if ($user->disabled) {
            throw new UserDisabledException('User is disabled');
        }

        // Is the user deleted?
        if ($user->deleted_at !== NULL) {
            throw new UserDeletedException('User is deleted');
        }

        return true;
    }

    /**
     * Create a new instance of the model.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function createModel()
    {
         $class = '\\'.ltrim($this->model, '\\');
         $object = new $class;

         if ( is_a( $object, '\Illuminate\Support\Facades\Facade' ) )
         {
             $object = $object->getFacadeRoot();
         }

         return $object;
    }

    /**
     * Retrieve a user by by their unique id and "remember me" token.
     *
     * @param  mixed  $id
     * @param  string  $token
     * @return \Illuminate\Auth\UserInterface|null
     */
    public function retrieveByToken($id, $token)
    {
        $model = $this->createModel();

        return $model->newQuery()
                        ->where($model->getKeyName(), $id)
                        ->where($model->getRememberTokenName(), $token)
                        ->first();
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Auth\UserInterface  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(UserInterface $user, $token)
    {
        $user->setRememberToken($token);

        $user->save();
    }
}