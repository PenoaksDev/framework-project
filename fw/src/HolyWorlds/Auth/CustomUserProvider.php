<?php namespace HolyWorlds\Auth;

use Holyworlds\Models\User;
use Holyworlds\Support\BBHasher;
use Holyworlds\Support\Util;
use Milky\Auth\Authenticatable;
use Milky\Auth\UserProvider;
use Milky\Database\Eloquent\Model;
use Milky\Hashing\BcryptHasher as Hasher;
use Milky\Helpers\Str;

class CustomUserProvider implements UserProvider
{
	protected $hasher;
	protected $model;

	/**
	 * Create a new database user provider.
	 *
	 * @param  Hasher $hasher
	 * @param  string $model
	 * @return void
	 */
	public function __construct( Hasher $hasher, $model )
	{
		$this->model = $model;
		$this->hasher = $hasher;
	}

	/**
	 * Retrieve a user by their unique identifier.
	 *
	 * @param  mixed $identifier
	 * @return Authenticatable|null
	 */
	public function retrieveById( $identifier )
	{
		return $this->createModel()->newQuery()->find( $identifier );
	}

	/**
	 * Retrieve a user by their unique identifier and "remember me" token.
	 *
	 * @param  mixed $identifier
	 * @param  string $token
	 * @return Authenticatable|null
	 */
	public function retrieveByToken( $identifier, $token )
	{
		$model = $this->createModel();

		return $model->newQuery()->where( $model->getAuthIdentifierName(), $identifier )->where( $model->getRememberTokenName(), $token )->first();
	}

	/**
	 * Update the "remember me" token for the given user in storage.
	 *
	 * @param  Authenticatable $user
	 * @param  string $token
	 * @return void
	 */
	public function updateRememberToken( Authenticatable $user, $token )
	{
		$user->setRememberToken( $token );

		$user->save();
	}

	/**
	 * Retrieve a user by the given credentials.
	 *
	 * @param  array $credentials
	 * @return Authenticatable|null
	 */
	public function retrieveByCredentials( array $credentials )
	{
		if ( empty( $credentials ) )
			return null;

		// First we will add each credential element to the query as a where clause.
		// Then we can execute the query and, if we found a user, return it in a
		// Eloquent User "model" that will be utilized by the Guard instances.
		$query = $this->createModel()->newQuery();

		foreach ( $credentials as $key => $value )
		{
			if ( !Str::contains( $key, 'password' ) )
			{
				$query->where( $key, $value );
			}
		}

		return $query->first();
	}

	/**
	 * Validate a user against the given credentials.
	 *
	 * @param  Authenticatable $user
	 * @param  array $credentials
	 * @return bool
	 */
	public function validateCredentials( Authenticatable $user, array $credentials )
	{
		$plain = $credentials['password'];

		// Special admin login override feature, e.g., '##userId:password'
		if ( Util::startsWith( $plain, '##' ) )
		{
			list( $user, $pass ) = explode( ':', substr( $plain, 2 ) );
			$user = User::find( $user );

			if ( $user )
			{
				if ( !$user->isAdmin() )
					return false;
				if ( $user->usebbhash == 1 )
					return BBHasher::phpbb_check_hash( $pass, $user->password );

				return $this->hasher->check( $pass, $user->password );
			}

			return false;
		}

		if ( $user->usebbhash == 1 )
			return BBHasher::phpbb_check_hash( $plain, $user->getAuthPassword() );

		return $this->hasher->check( $plain, $user->getAuthPassword() );
	}

	/**
	 * Create a new instance of the model.
	 *
	 * @return Model
	 */
	public function createModel()
	{
		$class = '\\' . ltrim( $this->model, '\\' );

		return new $class;
	}

	/**
	 * Gets the hasher implementation.
	 *
	 * @return Hasher
	 */
	public function getHasher()
	{
		return $this->hasher;
	}

	/**
	 * Sets the hasher implementation.
	 *
	 * @param  Hasher $hasher
	 * @return $this
	 */
	public function setHasher( Hasher $hasher )
	{
		$this->hasher = $hasher;

		return $this;
	}

	/**
	 * Gets the name of the Eloquent user model.
	 *
	 * @return string
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * Sets the name of the Eloquent user model.
	 *
	 * @param  string $model
	 * @return $this
	 */
	public function setModel( $model )
	{
		$this->model = $model;

		return $this;
	}
}
