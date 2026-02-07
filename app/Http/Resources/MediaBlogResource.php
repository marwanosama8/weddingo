<?php

namespace App\Http\Resources;

use App\Models\Partner;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class MediaBlogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $loveReac = DB::table('galleryblog_reactions')->where('reaction_id',1)->where('galleryblog_id',$this->id)->count();
        $angryReac = DB::table('galleryblog_reactions')->where('reaction_id',2)->where('galleryblog_id',$this->id)->count();
        $apperReac = DB::table('galleryblog_reactions')->where('reaction_id',3)->where('galleryblog_id',$this->id)->count();
        $sadReac = DB::table('galleryblog_reactions')->where('reaction_id',4)->where('galleryblog_id',$this->id)->count();
        $userReaction = DB::table('galleryblog_reactions')->where('user_id',$request->user()->id)->where('galleryblog_id',$this->id)->first();
        // dd($this->partner);
        return [
            'id' => $this->id,
            'partner_id' => $this->partner_id,
            'partner_business_name' => $this->partner->business_name ?? '',
            'caption' => $this->caption,
            'love_reaction' => $loveReac,
            'angry_reaction' => $angryReac,
            'appreciate_reaction' => $apperReac,
            'sad_reaction' => $sadReac,
            'comment' => CommentsResource::collection($this->comments()->get()),
            'comment_count' => $this->comments()->count(),
            'user_reaction' => $userReaction ? $userReaction->reaction_id : null,
            'media_url' => $this->getFirstMediaUrl(),
        ];
    }
}
