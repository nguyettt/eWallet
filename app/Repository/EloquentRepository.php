<?php
namespace App\Repository;

use App\Repository\RepositoryInterface;
use Illuminate\Http\Request;
use DB;

abstract class EloquentRepository implements RepositoryInterface {
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $_model;

    protected $_attributes;
    /**
     * EloquentRepository constructor.
     */
    public function __construct()
    {
        $this->setModel();
        $this->setAttributes();
    }

    /**
     * get model
     * @return string
     */
    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        $this->_model = app()->make(
            $this->getModel()
        );
    }

    /**
     * get attributes
     */
    abstract public function getAttributes();

    /**
     * Set attributes
     */
    public function setAttributes() {
        $this->_attributes = $this->getAttributes();
    }

    /**
     * Get All
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll($page)
    {
        DB::statement(DB::raw("set @row:=($page - 1) * 20"));
        return $this->_model->select(DB::raw('*, @row:=@row+1 as no'));
    }

    /**
     * Search in model
     *
     * @param string $q
     * @param int $page
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function search($q, $page) {
        $q = str_replace(' ', '%', $q);
        $q = '%'.$q.'%';
        $raw = [];
        foreach($this->_attributes as $attr) {
            array_push($raw, "{$attr} like '{$q}'");
        }
        $raw = implode(' or ', $raw);
        DB::statement(DB::raw("set @row:=($page - 1) * 20"));
        return $this->_model->whereRaw($raw);
    }
    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        $result = $this->_model->find($id);

        return $result;
    }

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {

        return $this->_model->create($attributes);
    }

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return bool|mixed
     */
    public function update($id, array $attributes)
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }

        return false;
        // print_r($attributes);
    }

    /**
     * Delete
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }

    /**
     * Multiple Delete
     *
     * @param array $id_arr
     * @return bool
     */
    public function multiDelete(array $id_arr) {
        $result = $this->whereIn('id', $id_arr)->get();
        if ($result) {
            $result->delete();
            return true;
        }

        return false;
    }

    /**
     * Create an array of model objects
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_model->get();
    }
}