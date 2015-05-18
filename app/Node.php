<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model {

	//
    protected $fillable = [ 'name', 'ip_address',
                            'snmp_version', 'snmp_comm_ro',
                            'poll_enabled', 'location_id' ];

    /**
     * node has many ports
     *
     * @return
     */
    public function ports() {
        return $this->hasMany('\App\Port');
    }

    /**
     * node has many vlans
     *
     * @return
     */
    public function vlans() {
        return $this->hasMany('\App\Vlan');
    }

    /**
     * node has many macs
     */
    public function macs() {
        return $this->hasMany('\App\Mac');
    }

    /**
     * node has many ips
     */
    public function ips() {
        return $this->hasMany('\App\Ip');
    }

    /**
     * node has many arps
     */
    public function arps() {
        return $this->hasMany('\App\Arp');
    }

    /**
     * node has many routes
     */
    public function routes() {
        return $this->hasMany('\App\Route');
    }

    /**
     * node has many bssids
     */
    public function bssids() {
        return $this->hasMany('\App\Bssid');
    }

    /**
     * node has many bds
     */
    public function bds() {
        return $this->hasMany('\App\Bd');
    }

    /**
     * node has many pings
     *
     * @return
     */
    public function pings() {
        return $this->hasMany('\App\Ping');
    }

    /**
     * node has many snmps
     *
     * @return
     */
    public function snmps() {
        return $this->hasMany('\App\Snmp');
    }

    /*
     * node belongs to project
     */
    public function project()
    {
        return $this->belongsTo('\App\Project');
    }

    /*
     * node belongs to location
     */
    public function location()
    {
        return $this->belongsTo('\App\Location');
    }

    /**
     * Display Poll Enabled
     *
     * @return String
     */
    public function getDspPollEnabledAttribute() {
        switch (strtoupper($this->poll_enabled)) {
         case 'Y':
            return 'Yes';
            break;

         case 'N':
         default:
            return 'No';
            break;
        }
    }

    /**
     * Display SNMP Version
     *
     * @return String
     */
    public function getDspSnmpVersionAttribute() {
        switch (strtoupper($this->snmp_version)) {
         case '1':
            return '1';
            break;

         case '2':
            return '2c';
            break;

         default:
            return 'disabled';
            break;
        }
    }

    /**
     * Generate Node Status
     *
     * @return Array
     */
    static public function status_select() {
        $_ret = array();

        $_ret = [ 'all'     => 'all',
                  'up'      => 'up',
                  'down'    => 'down',
                  'unknown' => 'unknown'];

        return $_ret;
    }
}
