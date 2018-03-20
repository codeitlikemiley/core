<?php

namespace Laracommerce\Core\Roles\Repositories;

use Laracommerce\Core\Base\BaseRepository;
use Laracommerce\Core\Roles\Exceptions\CreateRoleErrorException;
use Laracommerce\Core\Roles\Role;
use Illuminate\Database\QueryException;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    /**
     * @var Role
     */
    protected $model;

    /**
     * RoleRepository constructor.
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        parent::__construct($role);
        $this->model = $role;
    }

    /**
     * @param array $data
     * @return Role
     * @throws CreateRoleErrorException
     */
    public function createRole(array $data) : Role
    {
        try {
            $role = new Role($data);
            $role->save();
            return $role;
        } catch (QueryException $e) {
            throw new CreateRoleErrorException($e);
        }
    }
}
