<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CustomerController
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function listClients(Request $request, Response $response, array $args) {
        try {
            $sql = "SELECT * FROM customers WHERE estado = 1";
            $stmt = $this->db->query($sql);
            $customers = $stmt->fetchAll(PDO::FETCH_OBJ);

            return $response->withJson($customers, 200);
        } catch (PDOException $e) {
            return $response->withJson(['error' => $e->getMessage()], 500);
        }
        finally {
            if ($stmt != null) {
                $stmt = null;
            }
        }
    }

    public function createClient(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody();

        // Validar el DNI
        if (!validateDniPeru($data['dni'])) {
            return $response->withJson(['error' => 'El DNI es inválido elvis'], 400);
        }
    
        // Validar si el DNI ya existe en la base de datos
        if (isDniAlreadyExists($data['dni'])) {
            return $response->withJson(['error' => 'El DNI ya está registrado'], 400);
        }
    
        // Validar la fecha de nacimiento
        if (!validatDatOfBirth($data['birthdate'])) {
            return $response->withJson(['error' => 'La fecha de nacimiento es inválida'], 400);
        }

        $sql = "INSERT INTO customers (name, lastName, age, birthdate, dni) VALUES (:name, :lastName, :age, :birthdate, :dni)";
    
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);
    
            return $response->withJson(['message' => 'Registro creado correctamente'], 200);
        } catch (PDOException $e) {
            return $response->withJson(['error' => $e->getMessage()], 500);
        }
        finally {
            $stmt = null;
        }
    }

    public function getClient(Request $request, Response $response, array $args) {
        $dni = $args['dni'];

        try {
            $sql = "SELECT * FROM customers WHERE dni = :dni";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':dni', $dni);
            $stmt->execute();

            $customer = $stmt->fetch(PDO::FETCH_OBJ);

            return $response->withJson($customer, 200);
        } catch (PDOException $e) {
            return $response->withJson(['error' => $e->getMessage()], 500);
        }
        finally {
            $stmt = null;
        }
    }
    public function deleteClient(Request $request, Response $response, array $args) {
        $dni = $args['dni'];

        try {
            $sql = "UPDATE customers SET estado = 0 WHERE dni = :dni";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':dni', $dni);
            $result = $stmt->execute();
    
            if ($result) {
                return $response->withJson(['message' => 'Registro eliminado correctamente'], 200);
            } else {
                return $response->withJson(['error' => 'No se pudo eliminar el registro'], 500);
            }
        } catch (PDOException $e) {
            return $response->withJson(['error' => 'Error en la base de datos: ' . $e->getMessage()], 500);
        } finally {
            $stmt = null;
        }
    }

    public function updateClient(Request $request, Response $response, array $args) {
        $dni = $args['dni'];
        $data = $request->getParsedBody();
    
        try {
            $sql = "UPDATE customers SET name = :name, lastName = :lastName, age = :age, birthdate = :birthdate WHERE dni = :dni";
            $stmt = $this->db->prepare($sql);
    
            $stmt->bindParam(':dni', $data['dni']);
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':lastName', $data['lastName']);
            $stmt->bindParam(':age', $data['age']);
            $stmt->bindParam(':birthdate', $data['birthdate']);
    
            $result = $stmt->execute();
    
            return $response->withJson(['message' => 'Registro actualizado correctamente'], 200);
        } catch (PDOException $e) {
            return $response->withJson(['error' => $e->getMessage()], 500);
        }
        finally {
            $stmt = null;
        }
    }

    public function updateClientState(Request $request, Response $response, array $args) {
        $dni = $args['dni'];

        try {
            $sql = "UPDATE customers SET estado = 1 WHERE dni = :dni";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':dni', $dni);
            $updateResult = $stmt->execute();
    
            if ($updateResult) {
                return $response->withJson(['message' => 'Estado del cliente actualizado correctamente'], 200);
            } else {
                return $response->withJson(['error' => 'Error al cambiar el estado del cliente'], 500);
            }
        } catch (PDOException $e) {
            return $response->withJson(['error' => $e->getMessage()], 500);
        }
        finally {
            $stmt = null;
        }
    }

    public function consultarDni(Request $request, Response $response, array $args) {
        $dni = $args['dni'];

        $client = new \GuzzleHttp\Client();
        $url = 'https://dniruc.apisperu.com/api/v1/dni/' . $dni;

        try {
            $headers = [
                'Authorization' => 'Bearer ' . $_ENV['MY_BEARER_TOKEN'],
            ];

            $apiResponse = $client->get($url, ['headers' => $headers]);
            $datosPersona = json_decode($apiResponse->getBody(), true);

            if (isset($datosPersona['nombres'])) {
                return $response->withJson($datosPersona, 200);
            } else {
                return $response->withJson(['error' => 'No se encontraron datos para el DNI proporcionado'], 404);
            }
        } catch (\Exception $e) {
            return $response->withJson(['error' => 'Error al consultar la API de la Reniec'], 500);
        }
    }
}