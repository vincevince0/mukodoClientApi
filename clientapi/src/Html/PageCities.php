<?php

namespace App\Html;

class PageCities extends AbstractPage {

    
    static function table(array $entities)
    {
        echo '<h1>Városok</h1>';
        //self::searchBar();
        //self::dropdownMenu();
        echo '<table id="cities-table">';
        self::tableHead();
        self::tableBody($entities);
        echo "</table>";
    }

    
    static function tableBody(array $entities)
    {
        echo '<tbody>';
        $i = 0;
        foreach ($entities as $entity) {
            $onClick = sprintf(
                'btnEditCityOnClick(%d, "%s")',
                $entity['id'],
                $entity['name']
            );
            echo "
            <tr class='" . (++$i % 2 ? "odd" : "even") . "'>
                <td>{$entity['id']}</td>
                <td>{$entity['name']}</td>
                <td class='flex float-right'>
                <form method='post' action=''>

                <button type='submit'
                id='btn-update-city-{$entity['id']}'
                onclick='btnEditCityOnClick({$entity['id']}, '{$entity['name']}', event)'
                title='Módosít'>
                <i class='fa fa-edit'>Módosítás</i>
                </button>
            
                    </form>
                    <form method='post' action=''>
                        <button type='submit'
                            id='btn-del-city-{$entity['id']}'
                            name='btn-del-city'
                            value='{$entity['id']}'
                            title='Töröl'>
                            <i class='fa fa-trash'>Töröl</i>
                        </button>
                    </form>
                </td>
            </tr>";
        }
        echo '</tbody>';
    }

    static function dropdownMenu()
    {
        $counties = self::getAllCounties();

        echo '<tr><td colspan="4">
            <select name="county" id="county-dropdown">';
            foreach ($counties as $county) {
                echo '<option value="' . $county['id'] . '">' . $county['name'] . '</option>';
            }
        echo '</select>
            <button type="button" id="btn-ok-county" title="OK"><i class="fa fa-check">OK</i></button>
        </td></tr>';
    }

    static function tableHead()
    {

        $counties = self::getAllCounties();

        echo '<thead>
        
    <tr>
        <th class="id-col">#</th>
        <th>Megnevezés</th>
        <th>Megye</th>
        <th>Műveletek&nbsp;</th>
    </tr>
    </thead>
    <tr>
        <td colspan="4">
            <select id="county-dropdown" name="county">';
            foreach ($counties as $county) {
                echo '<option value="' . $county['id'] . '">' . $county['name'] . '</option>';
            }
        echo'    </select>
            <button type="button" id="btn-ok-county" title="OK"><i class="fa fa-check">OK</i></button>
        </td>
    </tr>
    <tbody id="cities-table">
        <!-- City rows will be populated here dynamically -->
    </tbody>

        </thead>';
   
    }

    
    static function editor()
    {
        echo '
        <form name="city-editor" method="post" action="">
            <input type="hidden" id="id" name="id">
            <input type="text" id="name" name="name" placeholder="Város" required>
            <button type="submit" id="btn-save-city" name="btn-save-city" title="Ment"><i class="fa fa-save">Mentés</i></button>
            <button type="button" id="btn-cancel-city" title="Mégse"><i class="fa fa-cancel">Mégse</i></button>
        </form>
        ';
    }

    
    static function updateCity($id, $name)
    {
        $client = new \App\RestApiClient\Client();
        $data = ['name' => $name];
        $response = $client->put('cities/' . $id, $data);
        return $response;
    }

    static function getAllCounties(): array
    {
        $client = new \App\RestApiClient\Client();
        $response = $client->get('counties');
        return $response['data'] ?? [];
    }

    static function getAllCities(): array
    {
        $client = new \App\RestApiClient\Client();
        $response = $client->get('cities');
        return $response['data'] ?? [];
    }

    static function getCitiesByCounty($countyId) {
        $client = new \App\RestApiClient\Client();
        $response = $client->get("counties/$countyId/cities"); // Assuming API endpoint for fetching cities by county
        return json_decode($response->getBody(), true);
    }

    
        
    
}




