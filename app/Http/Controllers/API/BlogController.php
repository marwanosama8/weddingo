<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Custome\Response;
use App\Http\Requests\AddCommentToGallerBlog;
use App\Http\Requests\DeleteGalleryBlogRequest;
use App\Http\Requests\ShowBlogByCategory;
use App\Http\Requests\ShowBlogById;
use App\Http\Requests\ShowPartnerRequest;
use App\Http\Requests\StoreImageToGalleryRequest;
use App\Http\Requests\UpdateGalleryBlogRequest;
use App\Http\Resources\MediaBlogResource;
use App\Models\Category;
use App\Models\GalleryBlog;
use App\Models\Partner;
use Illuminate\Http\Request;

/**
 * @group Blog
 *
 * APIs for Blog Module
 */
class BlogController extends Controller
{
    use Response;
    /**
     * 
     *  Blogs In Specific Category
     *
     *  This endpoint allows you to get all blogs .
     * 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function blogIndexByCategory(Request $request)
    {
        // $validator = $request->validated();
        // return GalleryBlog::all();
        // $categoryBlogs = Category::find($validator['category_id'])->catgeoryBlogs;
        return $this->successEnvelope(MediaBlogResource::collection(GalleryBlog::all()));
    }
    /**
     * 
     *  Store Blog
     *
     *  This endpoint allows you to get store blogs .
     * 
     * @bodyParam image string required . 
     * @bodyParam caption string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function storeImageToGallery(StoreImageToGalleryRequest $request)
    {
        // can partner store in gallery by his gallery limit
        $validator = $request->validated();
        $partner = $request->user()->partner;
        if ($partner->gallery_limit >= 1) {
            $media = GalleryBlog::create([
                'partner_id' => $partner->id,
                'caption' => $validator['caption'],
            ]);
            $media->addMediaFromRequest('image')->toMediaCollection();

            $partner->decrement('gallery_limit', 1);

            return $this->messageResponse('Image Stored Successfully!');
        } else {
            return $this->handleError('You reached gallery limit');
        }
    }

    /**
     * 
     *  Show Partner Iamges Gallery Blog
     *
     *  This endpoint allows you to get image blogs .
     * 
     * @bodyParam partner_id integer required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function showPartnerImageGallery(ShowPartnerRequest $request)
    {
        $validator = $request->validated();

        $partner = Partner::find($validator['partner_id']);
        $galleryBlogs = $partner->galleryBlogs()->get();
        return MediaBlogResource::collection($galleryBlogs);
    }
    /**
     * 
     *  Show Partner Iamges Gallery Blog By Id
     *
     *  This endpoint allows you to get image blogs .
     * 
     * @bodyParam partner_id integer required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function showPartnerImageGalleryById(ShowBlogById $request)
    {
        $validator = $request->validated();
        $galleryBlogs = GalleryBlog::find($validator['galleryblog_id']);
        return MediaBlogResource::make($galleryBlogs);
    }
    /**
     * 
     *  React To Blog
     *
     *  This endpoint allows you to React .
     * 
     * @bodyParam galleryblog_id integer required . 
     * @bodyParam reaction_id integer required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function reactToBlog(UpdateGalleryBlogRequest $request)
    {
        $validator = $request->validated();

        $blog = GalleryBlog::find($validator['galleryblog_id']);

        $attributes = ['user_id' => $request->user()->id];

        $blog->reactions()->toggle([$validator['reaction_id'] => $attributes]);
        return $this->messageResponse('Done!');
    }
    /**
     * 
     *  Comment To Blog
     *
     *  This endpoint allows you to Comment .
     * 
     * @bodyParam galleryblog_id integer required . 
     * @bodyParam comment string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function commentToBlog(AddCommentToGallerBlog $request)
    {
        $validator = $request->validated();

        $blog = GalleryBlog::find($validator['galleryblog_id']);

        $blog->comments()->create([
            'user_id' => $request->user()->id,
            'comment' => $validator['comment']
        ]);
        return $this->messageResponse('Done!');
    }
    /**
     * 
     *  Delete To Blog
     *
     *  This endpoint allows you to Delete Blog .
     * 
     * @bodyParam blog_id string required . 
     * @authenticated
     *   @response {
     *   Data: {Data},
     * }
     */
    public function deleteBlog(DeleteGalleryBlogRequest $request)
    {
        $validator = $request->validated();
        $partner = $request->user()->partner;
        if ($partner) {
            GalleryBlog::find($validator['blog_id'])->delete();
            $partner->increment('galley_limit', 1);
            return $this->messageResponse("Blog Delted Succsessfully");
        } else {
            return $this->handleError("Your are not partner");
        }
    }
}
