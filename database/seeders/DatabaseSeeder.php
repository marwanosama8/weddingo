<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\GalleryBlog;
use App\Models\Partner;
use App\Models\PartnerPriceList;
use App\Models\Review;
use App\Models\UniqueId;
use App\Models\User;
use Faker\Factory as Faker;
use Str;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // // seeder from category to partner blog with media 
        $category =  \App\Models\Category::factory(10)->create();
        $user = User::factory(50)->has(Partner::factory()->count(1)
            ->has(
                GalleryBlog::factory()->count(5)
            ))->create();

        $galleyBlogs = GalleryBlog::all();
        foreach ($galleyBlogs as $value) {
            $url = 'https://dummyimage.com/100.png/' . rand(1, 12344) . '6f/' . rand(456, 999);
            $value->addMediaFromUrl($url)->toMediaCollection();
        }

        $galleyBlogs = Category::all();
        foreach ($galleyBlogs as $value) {
            $url = 'https://dummyimage.com/100.png/' . rand(1, 12344) . '6f/' . rand(456, 999);
            $value->addMediaFromUrl($url)->toMediaCollection();
        }

        // // user iamge 
        $url = 'https://i.pravatar.cc/50';
        $user = User::all();
        foreach ($user as $value) {
            $value->addMediaFromUrl($url)->toMediaCollection();
        }

        // // add reviews
        Review::factory()->count(200)->create();


        // // add pricelist
        PartnerPriceList::factory()->count(200)->create();



        // // add reactions to rreactions table
        DB::table('reactions')->insert([
            [
                'id' => 1,
                'reaction' => "Love"
            ],
            [
                'id' => 2,
                'reaction' => "Angry"
            ],
            [
                'id' => 3,
                'reaction' => "Appreciate"
            ],
            [
                'id' => 4,
                'reaction' => "Sad"
            ],
        ]);


        // // add category view count number
        $category = \App\Models\Category::all();
        foreach ($category as  $value) {
            $value->update([
                "viewes_count" => random_int(100, 345)
            ]);
        }

        //        // add caption to gallery blogs
        $faker = Faker::create();
        $galleyBlogs = GalleryBlog::all();
        foreach ($galleyBlogs as $value) {
            $value->update([
                'caption' => $faker->sentence(6)
            ]);
        }

        // add first name and last name to all users
        $user = User::all();
        $faker = Faker::create();
        foreach ($user as  $value) {
            $value->update([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
            ]);
        }

        // sync categories to partners
        $faker = Faker::create();
        $partner = \App\Models\Partner::all();
        $categoryIds = Category::pluck('id')->toArray();

        // Get only the first 3 IDs
        foreach ($partner as  $value) {
            shuffle($categoryIds);
            $randomCategoryIds = array_slice($categoryIds, 0, 3);
            $value->categories()->sync($randomCategoryIds);
        }

        // add caption
        $galleyBlogs = GalleryBlog::all();
        $faker = Faker::create();
        foreach ($galleyBlogs as $value) {
            $value->update([
                'caption' => $faker->sentence()
            ]);
        }


        //add unique id
        UniqueId::create([
            'subscription_id' => 666,
            'unique_id' => 666 . '_' . time() . '_' . Str::random(6),
        ]);

        // \App\Models\Admin::create([
        //     'name' => 'Test User',
        //     'email' => 'admin@admin.com',
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        // ]);
    }
}
