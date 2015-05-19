<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class PagesController extends Controller {

    /*
     * Constructor
     *
     */
    public function __construct()
    {
        // must auth before
        $this->middleware('auth');
    }

	/**
     *
     */
    public function home() {

        // summary by bssid
        $bssids = \App\Bssid::groupBy('bssidName')
                            ->orderBy('bssidName')
                            ->get();
        for ($i=0; $i<count($bssids); $i++) {
            $bssid = \App\Bssid::where('bssidName', $bssids[$i]->bssidName)->get();
            $bssids[$i]->bssidCount = $bssid->count();

            $bssids[$i]->clientCount = 0;

            foreach ($bssid as $bds) {
                $clients = \App\Bd::where('bssid_id', $bds->id)->get();
                $bssids[$i]->clientCount += $clients->count();
            }


        }

        // summary by location
        $locations = \App\Location::orderBy('name')->get();

        for ($i=0; $i<count($locations); $i++) {

            $nodesAll = \App\Node::where('location_id', $locations[$i]->id)->get();

            $nodesUp = \App\Node::where('location_id', $locations[$i]->id)
                                ->where('ping_success', '100')
                                ->get();

            $locations[$i]->nodesUp   = $nodesUp->count();
            $locations[$i]->nodesDown = $nodesAll->count() - $nodesUp->count();

        }

        // summary by project
        $projects = \App\Project::orderBy('name')->get();

        for ($i=0; $i<count($projects); $i++) {

            $nodesAll = \App\Node::where('project_id', $projects[$i]->id)->get();

            $nodesUp = \App\Node::where('project_id', $projects[$i]->id)
                                ->where('ping_success', '100')
                                ->get();

            $projects[$i]->nodesUp   = $nodesUp->count();
            $projects[$i]->nodesDown = $nodesAll->count() - $nodesUp->count();

        }

        // summary by nodegroup
        $nodegroups = \App\Nodegroup::orderBy('name')->get();

        for ($i=0; $i<count($nodegroups); $i++) {

            $nodesAll = \App\Node::where('nodegroup_id', $nodegroups[$i]->id)->get();

            $nodesUp = \App\Node::where('nodegroup_id', $nodegroups[$i]->id)
                                ->where('ping_success', '100')
                                ->get();

            $nodegroups[$i]->nodesUp   = $nodesUp->count();
            $nodegroups[$i]->nodesDown = $nodesAll->count() - $nodesUp->count();

        }

        return view('pages.home', compact('locations', 'projects', 'nodegroups', 'bssids'));
    }

}
