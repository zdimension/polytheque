<?php

namespace App\Http\Controllers;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class EDTController extends Controller
{
    const ADE_DEFAULT_PROJECT = 1; // "Savoie 2019-2020" -  getProjects pour trouver ça

    private $sessid = null;

    private $connexion = false;

    private $res = "null";

    public function index()
    {
        return view('edt');
    }

    public function search(string $query)
    {
        try
        {
            $res = $this->getTrainees($query);

            if (count($res) > 15)
                return ["error" => "tooMany"];
            else if (count($res) == 0)
                return ["error" => "zero"];

            return $res;
        }
        catch (\Exception $e)
        {
            $msg = $e->getMessage();
            if (Str::contains($msg, "could not be parsed as XML"))
                $msg .= "\n\n<<<\n" . $this->res . "EOF";
            return ["error" => $msg];
        }
    }

    public function getTrainees(string $query=null)
    {
        $res = (array)$this->requete("getResources", ["detail" => 4, "category" => "trainee"]);

        if ($query !== null)
        {
            $query = lighten($query);

            $res = array_filter($res["trainee"], function ($x) use($query) {
                return $x["isGroup"] == "false" && Str::contains(lighten($x["name"]), $query);
            });
        }

        return collect(array_map(function($x) {
            return current($x->attributes());
        }, $res))->sortBy(function($x) {
            return $x["path"] . $x["name"];
        })->values();
    }

    private function getProjects()
    {
        return $this->requete("getProjects", ["detail" => 4]);
    }

    private function setProject($id)
    {
        $res = $this->requete("setProject", ["projectId" => $id]);

        return $res !== null && (int)$res["projectId"] == $id && $res["sessionId"] == $this->sessid;
    }

    private function connect()
    {
        $this->connexion = true;

        try
        {
            $res = $this->requete("connect", ["login" => config("services.ade.username"), "password" => config("services.ade.password")]);

            if ($res !== null)
            {
                $this->sessid = (string)$res["id"];

                return $this->setProject(self::ADE_DEFAULT_PROJECT);
            }

            return false;
        }
        finally
        {
            $this->connexion = false;
        }
    }

    private function disconnect()
    {
        $this->requete("disconnect");
        $this->sessid = null;
    }

    private function requete($func, $args=[])
    {
        if ($this->sessid === null && $func != "connect")
        {
            if (!$this->connect())
            {
                var_dump("Erreur de connexion"); die();
                return null;
            }
        }

        $client = new Client();

        $params = $args;
        $params["function"] = $func;

        if ($this->sessid !== null)
            $params["sessionId"] = $this->sessid;

        try
        {
            $this->res = $client->get(config("services.ade.root"), ["query" => $params])->getBody()->getContents();
            return new \SimpleXMLElement($this->res);
        }
        finally
        {
            if ($this->sessid !== null && !$this->connexion && $func != "disconnect")
                $this->disconnect();
        }
    }
}
