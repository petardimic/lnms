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
}
