<?php

class AnunciosController {

  private $db = null;

  function __construct($conexion) {
    $this->db = $conexion;
  }





  public function enviarAnuncio() {
    if(IDUSER){
      $anuncio = json_decode(file_get_contents("php://input"));
      
      $eval = 'INSERT INTO anuncio (titulo,texto,precio,idanunciante) VALUES (?,?,?,?)';
      $peticion = $this->db->prepare($eval);
      $peticion->execute([
        $anuncio->titulo,$anuncio->texto,$anuncio->precio,$anuncio->idanunciante
      ]);
      
      http_response_code(201);
      exit(json_encode("Mensaje enviado correctamente"));
    }
    else{
      http_response_code(401);
      exit(json_encode(["error" => "Fallo de autorizacion"]));
    }
  }
    public function listarAnuncio() {
    
      $eval = "SELECT DISTINCT * FROM anuncio,usuarios WHERE anuncio.idanunciante=usuarios.id ORDER BY fecha DESC ";
      $peticion = $this->db->prepare($eval);
      $peticion->execute();
      $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);
      exit(json_encode($resultado));
    
  }
    
    
    public function buscarAnuncio(){
        $busqueda = null;
      if(!empty($_GET["busqueda"])) $busqueda = $_GET["busqueda"];
      
      $eval = "SELECT titulo,texto,precio,fecha,idanunciante FROM anuncio ORDER BY fecha DESC";
      //$eval = "SELECT * FROM anuncio";
      
      $eval .= $busqueda ? " AND CONCAT_WS(titulo,texto,precio) LIKE '%".$busqueda."%'" : null;

      $peticion = $conexion->prepare($eval);
      $peticion->execute();
      $resultado = $peticion->fetchAll(PDO::FETCH_OBJ);
      exit(json_encode($resultado));
    }
}