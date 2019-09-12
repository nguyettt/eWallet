<?php
namespace App\Repository;

use Illuminate\Http\Request;

interface RepositoryInterface {
    /**
     * Get all
     * @return mixed
     */
    public function getAll($page);

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id);

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes);

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function update($id, array $attributes);

    /**
     * Delete
     * @param $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Multiple delete
     *
     * @param array $id_arr
     * @return mixed
     */
    public function multiDelete(array $id_arr);

    /**
     * Create an array of objects
     *
     * @return array objects
     */
    public function toArray();
}