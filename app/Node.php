<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Node extends Model {

    //
    protected $fillable = [ 'name',         'ip_address',   'mac_address',
                            'snmp_version', 'snmp_comm_ro', 'ping_method',
                            'ping_params',  'ping_success', 'snmp_success',
                            'poll_enabled', 'location_id',  'project_id',
                            'nodegroup_id' ];
	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['snmp_comm_ro', 'snmp_comm_rw', 'snmp_version',
                         'ping_method', 'ping_params',
                         'mgmt_method', 'mgmt_params',
                         'poll_enabled', 'poll_class',
                         'created_at', 'updated_at',
                         ];

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

    /*
     * node belongs to nodegroup
     */
    public function nodegroup()
    {
        return $this->belongsTo('\App\Nodegroup');
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

    /**
     * Display sysUpTime
     *
     * @return String
     */
    public function getDspSysUpTimeAttribute() {

        $_ret = '';

        if ($this->sysUpTime == '') {
            $this->sysUpTime = 0;
        }

        $this->sysUpTime = round($this->sysUpTime / 100);

        // convert sysUpTime to human readble

        // days
        $days  = floor($this->sysUpTime / 86400);
        if ($days  > 0) {
            $_ret .= $days  . ' days, ';
            $this->sysUpTime = $this->sysUpTime - ($days * 86400);
        }

        // hours
        $hours = floor($this->sysUpTime / 3600);
        if ($hours > 0) {
            $_ret .= $hours . ' hours, ';
            $this->sysUpTime = $this->sysUpTime - ($hours * 3600);
        }

        // mins
        $mins  = floor($this->sysUpTime / 60);
        if ($mins  > 0) {
            $_ret .= $mins  . ' mins, ';
            $this->sysUpTime = $this->sysUpTime - ($mins * 60);
        }

        $secs  = $this->sysUpTime % 60;
        if ($secs  > 0) {
            $_ret .= $secs  . ' s.';
        }

        return $_ret;
    }

    /**
     * Generate Node Poll Class
     *
     * @return Array
     */
    static public function poll_class_select() {
        $_ret = array();

        $_ret[''] = '-- poll_class --';

        $poll_classes = \App\Node::where('poll_class', '<>', '')
                                 ->groupBy('poll_class')
                                 ->orderBy('poll_class')
                                 ->get();

        foreach ($poll_classes as $poll_class) {
            $_ret[$poll_class->poll_class] = $poll_class->poll_class;
        }

        $_ret['Unknown'] = 'Unknown';

        return $_ret;
    }

    static public function sysObjectID_select() {
        $_ret = array();

        $_ret[''] = '-- sysObjectID --';

        $sysObjectIDes = \App\Node::where('sysObjectID', '<>', '')
                                 ->groupBy('sysObjectID')
                                 ->orderBy('sysObjectID')
                                 ->get();

        foreach ($sysObjectIDes as $sysObjectID) {
            $_ret[$sysObjectID->sysObjectID] = $sysObjectID->sysObjectID;
        }

        return $_ret;
    }
}
