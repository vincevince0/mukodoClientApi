<?php

namespace App\Repositories;

class CityRepository extends BaseRepository
{
    function __construct(
        $host = self::HOST, 
        $user = self::USER,
        $password = self::PASSWORD,
        $database = self::DATABASE)
    {
        parent::__construct($host, $user, $password, $database);
        $this->tableName = 'cities';
    }

    public function getCitiesByCountyAndLetter(int $countyId, string $letter): array
    {
        $query = $this->select() . "WHERE county_id = $countyId AND name LIKE '$letter%' ORDER BY name";
        return $this->mysqli->query($query)->fetch_all(MYSQLI_ASSOC);
    }
}
