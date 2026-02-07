<?php 

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Custome\Response;
use App\Http\Requests\ShowBlogByCategory;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
/**
 * @group Categories
 *
 * APIs for Category Module
 */
class CategoryController extends Controller 
{
  use Response;
  
    /**
     * 
     *  All Categoryies
     *
     *  This endpoint allows you to get all categories .
     * 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
  public function index()
  {
    $data = CategoryResource::collection(Category::all());
    return $this->handleResponse($data,'OK');
  }

  
}

?>