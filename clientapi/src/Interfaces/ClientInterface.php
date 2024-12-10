<?php
namespace App\Interfaces;
interface ClientInterface {

function post($url, array $data = []);

function get($url, array $query = []);

function delete($url, $id);

function put($url, array $data = []);

}