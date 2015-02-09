<?php


namespace db;



class SBuilder/* extends SPDO*/
{


    public $spdo = null;


    public function __construct($connectName=false)
    {
        $this->spdo = new SPDO($connectName);
    }


    /**
     * @param $connectName
     * @return SPDO | null
     */
    public function initConnect($connectName)
    {
        return $this->spdo->initConnect($connectName);
    }


    /** @var SBuilder | null $instance */
    private static $instance = null;


    /**
     * @return SBuilder
     */
    private static function getInstance()
    {
        if(self::$instance==null){
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * @param $connectName
     * @return SPDO | null
     */
    public static function initStaticConnect($connectName)
    {
        $instance = self::getInstance();
        return $instance->initConnect($connectName);
    }

    public function getConnectName($connectName=false)
    {
        return $this->spdo->getConnectName($connectName);
    }



    public function createCommand($sql, $prepare=[])
    {

    }



    public function select($columns){}

}



