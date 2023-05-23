<?php

namespace Database\Seeders;

use App\Models\Movie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MovieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Movie::insert([

            ['title' => 'Bad Boys',
            'description' => 'Two hip detectives protect a witness to a murder while investigating a case of stolen heroin from the evidence storage room from their police precinc',
            'genre' => 'comedy, action',
            'publish_day' => '1995-04-07',
            'image' => 'images/badboys.jpg',
            ],
            
            ['title' => 'Titanic',
            'description' => 'A seventeen-year-old aristocrat falls in love with a kind but poor artist aboard the luxurious, ill-fated R.M.S. Titanic.',
            'genre' => 'drama, romance',
            'publish_day' => '1997-12-19',
            'image' => 'images/titanic.jpg',
            ],

            ['title' => 'Coda',
            'description' => 'As a CODA (Child of Deaf Adults) Ruby is the only hearing person in her deaf family. When the family\'s fishing business is threatened, Ruby finds herself torn between pursuing her passion at Berklee College of Music and her fear of abandoning her parents.',
            'genre' => 'comedy, drama',
            'publish_day' => '2021-01-29',
            'image' => 'images/coda.jpg',
            ],

            ['title' => 'Dune',
            'description' => 'A noble family becomes embroiled in a war for control over the galaxy\'s most valuable asset while its heir becomes troubled by visions of a dark future.',
            'genre' => 'action, adventure',
            'publish_day' => '2021-09-03',
            'image' => 'images/dune.jpg',
            ],

            ['title' => 'Tenet',
            'description' => 'Armed with only one word, Tenet, and fighting for the survival of the entire world, a Protagonist journeys through a twilight world of international espionage on a mission that will unfold in something beyond real time.',
            'genre' => 'action',
            'publish_day' => '2020-08-22',
            'image' => 'images/tenet.jpg',
            ],

            ['title' => 'John Wick',
            'description' => 'An ex-hit-man comes out of retirement to track down the gangsters that killed his dog and took his car.',
            'genre' => 'action',
            'publish_day' => '2014-09-24',
            'image' => 'images/wick.jpg',
            ],

            ['title' => 'Interstellar',
            'description' => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival.',
            'genre' => 'adventure, drama',
            'publish_day' => '2013-10-26',
            'image' => 'images/interstellar.jpg',
            ],

            ['title' => 'Extraction',
            'description' => 'Tyler Rake, a fearless black market mercenary, embarks on the most deadly extraction of his career when he\'s enlisted to rescue the kidnapped son of an imprisoned international crime lord.',
            'genre' => 'action',
            'publish_day' => '2020-04-24',
            'image' => 'images/extraction.jpg',
            ],

            ['title' => 'Joker',
            'description' => 'The rise of Arthur Fleck, from aspiring stand-up comedian and pariah to Gotham\'s clown prince and leader of the revolution.',
            'genre' => 'action, drama',
            'publish_day' => '2019-06-30',
            'image' => 'images/joker.jpg',
            ],
        ]);
    }
}
