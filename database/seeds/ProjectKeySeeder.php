<?php

use Illuminate\Database\Seeder;
use App\Project;

class ProjectKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Author: Umar Farooq Qazi
     *
     * @return void
     */
    public function run()
    {
        // Retrieving Projects from Project Table

        $projects = Project::pluck('name')->all();

        foreach ($projects as $project) {
            $newArray  = strtoupper($this->get_key($project));
            DB::table('projects')->where('name', $project)->update([
               'key' => $newArray
            ]);
        }
    }

    public function get_key($str)
    {
        // Generating Project Key From Project Name

        $acronym = '';
        $word = '';

        $words = preg_split("/(\s|\-|\.)/", $str);
        foreach($words as $w) {
            $acronym .= substr($w,0,1);
        }

        $numbers = range(1, 10000);
        $rand = shuffle($numbers);
        $random = array_slice($numbers, 0, 1);

        $word = $word . $acronym .$random[0];
        return $word;
    }
}
