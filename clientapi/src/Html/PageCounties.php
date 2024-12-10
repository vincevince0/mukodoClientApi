<?php

namespace App\Html;

class PageCounties extends AbstractPage {

    
    static function table(array $entities)
    {
        echo '<h1>Megyék</h1>';
        self::searchBar();
        echo '<table id="counties-table">';
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
                'btnEditCountyOnClick(%d, "%s")',
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
                id='btn-update-county-{$entity['id']}'
                onclick='btnEditCountyOnClick({$entity['id']}, '{$entity['name']}', event)'
                title='Módosít'>
                <i class='fa fa-edit'>Módosítás</i>
                </button>
            
                    </form>
                    <form method='post' action=''>
                        <button type='submit'
                            id='btn-del-county-{$entity['id']}'
                            name='btn-del-county'
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

    
    static function tableHead()
    {
        echo '<thead>
        <tr>
            <th class="id-col">#</th>
            <th>Megnevezés</th>
            <th>Műveletek&nbsp;</th>
        </tr>
        </thead>';
        
        
        self::editor();
    }

    
    static function editor()
    {
        echo '
        <form name="county-editor" method="post" action="">
            <input type="hidden" id="id" name="id">
            <input type="text" id="name" name="name" placeholder="Megye" required>
            <button type="submit" id="btn-save-county" name="btn-save-county" title="Ment"><i class="fa fa-save">Mentés</i></button>
            <button type="button" id="btn-cancel-county" title="Mégse"><i class="fa fa-cancel">Mégse</i></button>
        </form>
        ';
    }

    
    static function updateCounty($id, $name)
    {
        $client = new \App\RestApiClient\Client();
        $data = ['name' => $name];
        $response = $client->put('counties/' . $id, $data);
        return $response;
    }
}




