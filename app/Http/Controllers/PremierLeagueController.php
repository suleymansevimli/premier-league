<?php

namespace App\Http\Controllers;

use App\PremierLeague;

define('WIN_POINT', 3);
define('EQUALIZATION_POINT', 1);
define('TOTAL_WEEK', 4);
define('MAX_SCORE', 4);
define('MIN_SCORE', 0);

class PremierLeagueController extends Controller
{

    protected $teams;
    protected $matchedTeams;
    protected $weekResult;
    protected $week;
    protected $totalWeek;


    public function __construct()
    {
        $this->teams = PremierLeague::query()->get()->toArray();

        $this->week = 0;
        $this->totalWeek = TOTAL_WEEK;
    }


    public function matchTeams(): PremierLeagueController
    {
        $selectedTeams = array_rand($this->teams, 2);
        $primaryTeams = [
            $this->teams[$selectedTeams[0]],
            $this->teams[$selectedTeams[1]]
        ];

        unset($this->teams[$selectedTeams[0]]);
        unset($this->teams[$selectedTeams[1]]);

        $otherTeams = array_values($this->teams);

        $this->matchedTeams = [
            $primaryTeams, $otherTeams
        ];

        return $this;
    }


    public function play(): array
    {

        // scoring
        for ($i = 0; $i < count($this->matchedTeams); $i++) {
            // every competition has 2 teams
            $firstTeam = PremierLeague::query()->find($this->matchedTeams[$i][0]['id']);
            $secondTeam = PremierLeague::query()->find($this->matchedTeams[$i][1]['id']);

            $firstTeamGoals = $firstTeam->weekly_score = rand(MIN_SCORE, MAX_SCORE);
            $secondTeamsGoals = $secondTeam->weekly_score = rand(MIN_SCORE, MAX_SCORE);

            if ($firstTeamGoals == $secondTeamsGoals) {

                $firstTeam->point += EQUALIZATION_POINT;
                $firstTeam->equalization += 1;

                $secondTeam->point += EQUALIZATION_POINT;
                $secondTeam->equalization += 1;


            } elseif ($firstTeamGoals > $secondTeamsGoals) {

                $firstTeam->point += WIN_POINT;
                $firstTeam->won += 1;

                $secondTeam->lose += 1;

            } else {

                $firstTeam->lose += 1;

                $secondTeam->point += WIN_POINT;
                $secondTeam->won += 1;

            }

            $firstTeam->week += 1;
            $secondTeam->week += 1;

            $firstTeam->save();
            $secondTeam->save();

            $this->matchedTeams[$i] = [$firstTeam->toArray(), $secondTeam->toArray()];

        }

        // week
        $this->week += 1;
        $this->totalWeek -= 1;

        // result
        return [
            "week" => $this->week,
            "result" => $this->matchedTeams
        ];

    }

    public function calculateWhoChampion(): array
    {

        $calculated = [];

        $teams = array_merge($this->matchedTeams[0], $this->matchedTeams[1]);
        $this->teams = $teams;

        for ($i = 0; $i < count($teams); $i++) {
            $calculated[$i] = $teams[$i];
            $calculated[$i]['predict'] = $teams[$i]['point'] / 100;
            $calculated[$i]['week'] = $this->week;
        }

        $this->array_sort_by_column($calculated, 'point', SORT_DESC);

        return [
            'match_results' => $this->matchedTeams,
            'calculated' =>$calculated
        ];

    }

    public function nextWeek (): array
    {
        $this->matchTeams()->play();
        return $this->calculateWhoChampion();
    }

    public function result()
    {

        $result = [];
        for ($i = 0; $i < TOTAL_WEEK; $i++) {
            $this->matchTeams()->play();
            array_push($result, $this->calculateWhoChampion()['calculated']);
        }


        $result = array_pop($result);

        $this->array_sort_by_column($result, 'point', SORT_DESC);

        return $result;
    }

    function array_sort_by_column(&$arr, $col, $dir = SORT_ASC)
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }
}
