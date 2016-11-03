<?php

namespace Veebiteenus\controllers;

use Veebiteenus\Controller;

class sportproducts extends Controller
{

    function get($parameters)
    {
        // Ensure that name exists
        $sportproducts = empty($_GET['name']) ? '' : $_GET['name'];

        // Split pipe separated list of sportproducts into array
        $sportproducts = explode('|', $sportproducts);

        // Retrieve requested sportproducts from database
        $sportproducts = $this->db->from("sportproducts")->where("name", $sportproducts)->fetchAll();

        // Output json encoded data
        $this->output($sportproducts);
    }

    function post()
    {

        $result = $this->db->insertInto('sportproducts', $_POST)->execute();
        $this->output((array)$result);
    }

    function put()
    {

        // Convert json encoded request body into object
        $request = json_decode(file_get_contents('php://input'));

        // Validate releaseDate
        if (!empty($request->releaseYear) && !valid_date($request->releaseYear)) {
            output_error(405, "ReleaseYear is not valid");
        }

        // Define fields allowed for updating
        $allowed_fields = array_flip([
            'name',
            'description',
            'sport',
            'releaseYear'
        ]);

        // Filter request fields
        $data = array_intersect_key((array)$request, $allowed_fields);

        // Update database
        $query = $this->db->update('sportproducts')->set($data)->where('id', $request->id)->execute();

        // Verify that query succeeded
        if ($query === false) {
            output_error(500, "Server error");
        }
        exit();
    }

    function delete($id)
    {
        if (!$id > 0) {
            output_error(405, "ID is not valid");
        }

        $movie = $this->db->from("sportproducts")->where("id", $id)->fetch();
        if (!$movie) {
            output_error(404, "Sportproduct not found!");
        }

        $query = $this->db->deleteFrom("sportproducts")->where('id', $id);
        $query->execute();
        if ($query === false) {
            output_error(500, "Server error");
        }
        header("HTTP/1.1 204 Ok!");
    }
}