<?php
/**
 * @package DernieresSorties
 */
/*
Plugin Name: Dernieres Sorties
Description: Provide a shortcode to display last manga release.
Version: 1.0.0
Author: Alexandre Nguyen
Author URI: http://alexandrenguyen.fr
License: GPLv2 or later
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Make sure we don't expose any info if called directly
if (!function_exists('add_action')) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

define('BASE_URL_DERNIERES_SORTIES_SERVICE', 'http://local.dernieressorties/app_dev.php/dernieres-sorties');
define('DERNIERES_SORTIES_SERVICE_LIMIT', 500);

function dernieres_sorties_fn($atts)
{
    $limit = $atts['limit'] ? $atts['limit'] : DERNIERES_SORTIES_SERVICE_LIMIT;
    $fromDate = $atts['fromdate'] ? $atts['fromdate'] : date('Y-m-d');

    $params = array('limit' => $limit, 'from_date' => $fromDate);

    $url = BASE_URL_DERNIERES_SORTIES_SERVICE . '?' . http_build_query($params);

    $jsonSorties = file_get_contents($url);

    $sorties = json_decode($jsonSorties, true);

    $table = '<table class="sorties-table">';

    $table .= '<thead>';
        $table .= '<tr>';

        $table .= '<td>Date de sortie</td>';
        $table .= '<td>Volume</td>';
        $table .= '<td>Edition</td>';

        $table .= '</tr>';
    $table .= '</thead>';

    $table .= '<tbody>';

    foreach($sorties as $sortie) {

        $table .= '<tr>';

            $table .= '<td>';
            $table .= date('d/m/Y', strtotime($sortie['dateSortie']['date']));
            $table .= '</td>';

            $table .= '<td>';
            $table .= $sortie['productName'];
            $table .= '</td>';

            $table .= '<td>';
            $table .= $sortie['editor'];
            $table .= '</td>';

        $table .= '</tr>';
    }

    $table .= '</tbody>';
    $table .= '</table>';

    return $table;
}

add_shortcode('dernieres_sorties', 'dernieres_sorties_fn');