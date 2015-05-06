<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model {

	//
    protected $fillable = [ 'name', 'ip_address',
                            'snmp_version', 'snmp_comm_ro',
                            'poll_enabled' ];

    // getter & setter
    public function getPollEnabledAttribute($poll_enabled)
    {
        switch ($poll_enabled) {
         case 'y':
            return 'yes';

         case 'n':
         default:
            return 'no';
        }
    }

    public function setPollEnabledAttribute($poll_enabled)
    {
        switch ($poll_enabled) {
         case 'yes':
            $this->attributes['poll_enabled'] = 'y';
            break;

         case 'no':
         default:
            $this->attributes['poll_enabled'] = 'n';
        }
    }

    // getter
    public function getSnmpVersionAttribute($snmp_version)
    {
        switch ($snmp_version) {
         case '1':
            return '1';
            break;

         case '2':
            return '2c';
            break;

         case '0':
            return 'disabled';
            break;
        }

        return 'no';
    }

    /**
     * setter snmp_version
     */
    public function setSnmpVersionAttribute($snmp_version)
    {
        switch ($snmp_version) {
         case '1':
            $this->attributes['snmp_version'] = '1';
            break;

         case '2c':
            $this->attributes['snmp_version'] = '2c';
            break;

         case 'disabled':
         default:
            $this->attributes['snmp_version'] = '0';
            break;
        }

        return 'no';
    }

    /*
     * node has many ports
     */
    public function ports() {
        return $this->hasMany('\App\Port');
    }

    /*
     * node has many pings
     */
    public function pings() {
        return $this->hasMany('\App\Ping');
    }

    /*
     * node has many snmps
     */
    public function snmps() {
        return $this->hasMany('\App\Snmp');
    }

}
