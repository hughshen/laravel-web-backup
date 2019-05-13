<?php

use Illuminate\Database\Seeder;

class TermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Term::class, 15)->create();

        // Attach
        $posts = \App\Models\Post::get();
        $terms = \App\Models\Term::get();

        foreach ($posts as $post) {
            $pool = range(0, count($terms) - 1);
            shuffle($pool);

            for ($i = 0; $i < 6; $i++) {
                $post->terms()->attach([
                    $terms[$pool[$i]]->id => ['post_id' => $post->id],
                ]);
            }
        }
    }
}
