<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Post;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $blogs = Blog::factory()->count(5)->create();

        foreach($blogs as $blog){
            Post::factory()->count(random_int(0, 20))->create([
                "blog_id" => $blog->id
            ]);
        };
    }
}
