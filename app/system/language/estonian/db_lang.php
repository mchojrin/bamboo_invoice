<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2018, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2018, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['db_invalid_connection_str'] = 'Ei saa aru Andmebaasi seadistustest vastavalt esitatud stringile.';
$lang['db_unable_to_connect'] = 'Ei saa ühendust sinu andmebaasi serveriga kasutades antud stringi.';
$lang['db_unable_to_select'] = 'Ei saa valida määratud andmebaasi: %s';
$lang['db_unable_to_create'] = 'Ei saa luua määratud andmebaasi: %s';
$lang['db_invalid_query'] = 'Päring ei ole valiidne.';
$lang['db_must_set_table'] = 'Peate määrama oma päringuga kasutatava andmebaasi tabeli.';
$lang['db_must_use_set'] = 'Kirje uuendamiseks peate kasutama "set" meetodit.';
$lang['db_must_use_index'] = 'Peate määrama partii värskenduste jaoks sobiva indeksi.';
$lang['db_batch_missing_index'] = 'Partii uuendamiseks esitatud üks või mitu rida ei vasta määratud indeksile.';
$lang['db_must_use_where'] = 'Värskendused pole lubatud, kui need sisaldavad "where" klauslit.';
$lang['db_del_must_use_where'] = 'Kustutamised on keelatud, kui need ei sisalda "where" või "like" klauslit.';
$lang['db_field_param_missing'] = 'Väljade laadimiseks on vaja parameetrina tabeli nime.';
$lang['db_unsupported_function'] = 'See funktsioon ei ole teie kasutatava andmebaasi jaoks saadaval.';
$lang['db_transaction_failure'] = 'Toiming ebaõnnestus: muudatused on tagasivõetud.';
$lang['db_unable_to_drop'] = 'Nimetatud andmebaasi ei saa jätta.';
$lang['db_unsupported_feature'] = 'Kasutatava andmebaasi platvormi toetamata funktsioon.';
$lang['db_unsupported_compression'] = 'Teie poolt valitud failide tihendamise vorming ei toeta teie serverit.';
$lang['db_filepath_error'] = 'Andmeid ei saa salvestada teiepoolt antud aadressile.';
$lang['db_invalid_cache_path'] = 'Teie esitatud vahemälu aadress pole kehtiv või kirjutatav.';
$lang['db_table_name_required'] = 'Selle toimingu jaoks on vajalik tabeli nimi.';
$lang['db_column_name_required'] = 'Selle toimingu jaoks on vaja veeru nime.';
$lang['db_column_definition_required'] = 'Selle toimingu jaoks on vaja veeru määratlust.';
$lang['db_unable_to_set_charset'] = 'Ei saa määrata kliendipoolse ühenduse kooditabelit: %s';
$lang['db_error_heading'] = 'Tekkis andmebaasi viga';
