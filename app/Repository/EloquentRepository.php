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
     * Ready the query
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function query()
    {
        return $this->_model->where('user_id', auth()->user()->id);
    }

    /**
     * Get All
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll()
    {
        return $this->query()->get();
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

    public function findWithRelationship($id, array $relationship = array())
    {
        $result = $this->make($relationship);

        return $result->find($id); 
    }

    /**  
    * Make a new instance of the entity to query on  
    *  
    * @param array $with  
    */  
    public function make(array $with = array())  
    {  
    return $this->_model->with($with);  
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
    public function multiDelete(array $id_arr)
    {
        $result = $this->whereIn('id', $id_arr)->get();
        if ($result) {
            $result->delete();
            return true;
        }

        return false;
    }

    /**
     * Restore an item and all of its ancestors
     *
     * @param int $id
     * @return void
     */
    public function restore($id)
    {
        $record = $this->find($id);

        $record->delete_flag = null;
        $record->save();

        // $deleted = $this->deleted();

        // foreach ($deleted as $item) {
        //     if ($record->parent_id == $item->id) {
        //         $this->restore($item->id);
        //     }
        // }
    }

    /**
     * Return a collection of all deleted items (records with delete_flag = 1) in db
     *
     * @return collection
     */
    public function deleted()
    {
        $deleted = $this->query()
                        ->where('delete_flag', '<>', null)
                        ->get();
        
        return $deleted;
    }
}