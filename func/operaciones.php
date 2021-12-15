<?php
date_default_timezone_set('America/Lima');
setlocale(LC_TIME, 'spanish');

require_once dirname(__FILE__) . '/conexion.php';

class Operaciones {
    private $con;

    public function __construct() {
        $db = new Conexion();
        $this->con = $db->connect();
    }

    private function closeCon() {
        $this->con = null;
    }

    private function closeStmt(PDOStatement $stmt) {
        $stmt->closeCursor();
        $stmt = null;
    }

    private function printError(array $errorInfo) {
        //print_r($errorInfo);
    }

    public function createUser($idRole, $name, $lastName, $phone, $user, $password) {
        $stmt = $this->con->prepare('call createUser(?,?,?,?,?,?)');
        $stmt->bindParam(1, $idRole, PDO::PARAM_INT);
        $stmt->bindParam(2, $name);
        $stmt->bindParam(3, $lastName);
        $stmt->bindParam(4, $phone);
        $stmt->bindParam(5, $user);
        $stmt->bindParam(6, $password);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            $this->closeStmt($stmt);
            $this->closeCon();
            return true;
        }
        return false;
    }

    public function updaUser($idUser, $idRole, $name, $lastName, $phone, $user, $password) {

        $stmt = $this->con->prepare('call updaUser(?,?,?,?,?,?,?)');
        $stmt->bindParam(1, $idUser, PDO::PARAM_INT);
        $stmt->bindParam(2, $idRole, PDO::PARAM_INT);
        $stmt->bindParam(3, $name);
        $stmt->bindParam(4, $lastName);
        $stmt->bindParam(5, $phone);
        $stmt->bindParam(6, $user);
        $stmt->bindParam(7, $password);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            $this->closeStmt($stmt);
            $this->closeCon();
            return true;
        }
        return false;
    }

    public function getUsers($idRole) {
        $idRole = '%' . $idRole . '%';
        $stmt = $this->con->prepare('call getUsers(?)');
        $stmt->bindParam(1, $idRole);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function login($user, $password) {
        $stmt = $this->con->prepare('call login(?,?)');
        $stmt->bindParam(1, $user);
        $stmt->bindParam(2, $password);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function updaPassw($id, $user, $oldPassword, $newPassword) {
        $stmt = $this->con->prepare('call updaPassw(?,?,?,?)');
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->bindParam(2, $user);
        $stmt->bindParam(3, $oldPassword);
        $stmt->bindParam(4, $newPassword);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            $this->closeStmt($stmt);
            $this->closeCon();
            return true;
        }
        return false;
    }

    public function updaRole($id, $user, $idRole) {
        $stmt = $this->con->prepare('call updaRole(?,?,?)');
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->bindParam(2, $user);
        $stmt->bindParam(3, $idRole, PDO::PARAM_INT);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            $this->closeStmt($stmt);
            $this->closeCon();
            return true;
        }
        return false;
    }

    public function getRoles() {
        $stmt = $this->con->prepare('call getRoles()');
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function getTipoIncid() {
        $stmt = $this->con->prepare('call getTipoIncid()');
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function getEstadoIncid() {
        $stmt = $this->con->prepare('call getEstadoIncid()');
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function getIncidencias($idStateInci) {
        $idStateInci = '%' . $idStateInci . '%';
        $stmt = $this->con->prepare('call getIncidencias(?)');
        $stmt->bindParam(1, $idStateInci, PDO::PARAM_STR);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function createIncid($id_user, $id_estado_incid, $descripcion, $inst_educ, $cod_modul, $cod_local, $distrito, $provincia,
                                $region, $dir_nomb_apell, $dir_telefono, $dir_dni, $dir_correo, $cist_nomb_apell, $cist_telefono,
                                $cist_dni, $cist_correo) {
        $stmt = $this->con->prepare("insert into incidencia (id_user, id_estado_incid, descripcion, inst_educ, 
                        cod_modul, cod_local, distrito, provincia, region, dir_nomb_apell, dir_telefono, dir_dni, 
                        dir_correo, cist_nomb_apell, cist_telefono, cist_dni, cist_correo)
                        value (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $stmt->bindParam(1, $id_user, PDO::PARAM_INT);
        $stmt->bindParam(2, $id_estado_incid, PDO::PARAM_INT);
        $stmt->bindParam(3, $descripcion, PDO::PARAM_STR);
        $stmt->bindParam(4, $inst_educ, PDO::PARAM_STR);
        $stmt->bindParam(5, $cod_modul, PDO::PARAM_STR);
        $stmt->bindParam(6, $cod_local, PDO::PARAM_STR);
        $stmt->bindParam(7, $distrito, PDO::PARAM_STR);
        $stmt->bindParam(8, $provincia, PDO::PARAM_STR);
        $stmt->bindParam(9, $region, PDO::PARAM_STR);
        $stmt->bindParam(10, $dir_nomb_apell, PDO::PARAM_STR);
        $stmt->bindParam(11, $dir_telefono, PDO::PARAM_STR);
        $stmt->bindParam(12, $dir_dni, PDO::PARAM_STR);
        $stmt->bindParam(13, $dir_correo, PDO::PARAM_STR);
        $stmt->bindParam(14, $cist_nomb_apell, PDO::PARAM_STR);
        $stmt->bindParam(15, $cist_telefono, PDO::PARAM_STR);
        $stmt->bindParam(16, $cist_dni, PDO::PARAM_STR);
        $stmt->bindParam(17, $cist_correo, PDO::PARAM_STR);
        $stmt->execute();
        $id =$this->con->lastInsertId();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            $this->closeStmt($stmt);
            $this->closeCon();
            return $id;
        }
        return 0;
    }

    public function getIncidencia($idIncidencia) {
        $stmt = $this->con->prepare('call getIncidencia(?)');
        $stmt->bindParam(1, $idIncidencia, PDO::PARAM_INT);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function deleteIncidencia($idIncidencia) {
        $stmt = $this->con->prepare('call deleteIncidencia(?)');
        $stmt->bindParam(1, $idIncidencia, PDO::PARAM_INT);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            $this->closeStmt($stmt);
            $this->closeCon();
            return true;
        }
        return false;
    }

    public function getFichas($idIncidencia) {
        $stmt = $this->con->prepare('call getFichas(?)');
        $stmt->bindParam(1, $idIncidencia, PDO::PARAM_INT);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->closeStmt($stmt);
        return $data;
    }

    public function createFicha($id_incid, $id_tipo_incid, $marca, $modelo, $serie, $estado, $ubicacion, $observaciones) {
        $stmt = $this->con->prepare('call createFicha(?,?,?,?,?,?,?,?)');
        $stmt->bindParam(1, $id_incid, PDO::PARAM_INT);
        $stmt->bindParam(2, $id_tipo_incid, PDO::PARAM_INT);
        $stmt->bindParam(3, $marca, PDO::PARAM_STR);
        $stmt->bindParam(4, $modelo, PDO::PARAM_STR);
        $stmt->bindParam(5, $serie, PDO::PARAM_STR);
        $stmt->bindParam(6, $estado, PDO::PARAM_STR);
        $stmt->bindParam(7, $ubicacion, PDO::PARAM_STR);
        $stmt->bindParam(8, $observaciones, PDO::PARAM_STR);
        $stmt->execute();
        $this->printError($stmt->errorInfo());
        if ($stmt->rowCount() > 0) {
            $this->closeStmt($stmt);
            $this->closeCon();
            return true;
        }
        return false;
    }

}