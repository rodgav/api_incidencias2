<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Content-type: application/json;chartset=UTF-8");
header("Access-Control-Allow-Headers: *");

require_once dirname(__DIR__) . '/func/operaciones.php';

$headers = getallheaders();
$respuesta = array();
if (isset($headers['token'])) {
    $token = $headers['token'];
    require_once dirname(__DIR__) . '/func/constantes.php';
    if ($token == API_KEY) {
        $operations = new Operaciones();
        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            if (isset($_GET['accion'])) {
                $accion = $_GET['accion'];
                switch ($accion) {
                    case 'users':
                        if (isset($_GET['idRole'])) {
                            $data = $operations->getUsers($_GET['idRole']);
                            if (count($data) > 0) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Datos encontrados';
                                $respuesta['users'] = $data;
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'Faltan parametros';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'roles':
                        $data = $operations->getRoles();
                        if (count($data) > 0) {
                            $respuesta['error'] = false;
                            $respuesta['mensaje'] = 'Datos encontrados';
                            $respuesta['roles'] = $data;
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'typeInci':
                        $data = $operations->getTipoIncid();
                        if (count($data) > 0) {
                            $respuesta['error'] = false;
                            $respuesta['mensaje'] = 'Datos encontrados';
                            $respuesta['typeInci'] = $data;
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'stateInci':
                        $data = $operations->getEstadoIncid();
                        if (count($data) > 0) {
                            $respuesta['error'] = false;
                            $respuesta['mensaje'] = 'Datos encontrados';
                            $respuesta['stateInci'] = $data;
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'incides':
                        if (isset($_GET['idStateInci'])) {
                            $data = $operations->getIncidencias($_GET['idStateInci']);
                            if (count($data) > 0) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Datos encontrados';
                                $respuesta['incides'] = $data;
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'Datos no encontrados';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'incide':
                        if (isset($_GET['idIncid'])) {
                            $data = $operations->getIncidencia($_GET['idIncid']);
                            if (count($data) > 0) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Datos encontrados';
                                $respuesta['incides'] = $data;
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'Faltan parametros';

                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';

                        }
                        break;
                    case 'fichas':
                        if (isset($_GET['idIncid'])) {
                            $data = $operations->getFichas($_GET['idIncid']);
                            if (count($data) > 0) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Datos encontrados';
                                $respuesta['fichas'] = $data;
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'Faltan parametros';

                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';

                        }
                        break;
                    default:
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = 'Falta el parametro de acción';
                        break;
                }
            } else {
                $respuesta['error'] = true;
                $respuesta['mensaje'] = 'Falta el parametro de acción';
            }
        } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['accion'])) {
                $accion = $_POST['accion'];
                switch ($accion) {
                    case 'createUser':
                        if (isset($_POST['idRole']) && isset($_POST['name']) &&
                            isset($_POST['lastName']) && isset($_POST['phone']) && isset($_POST['user']) &&
                            isset($_POST['password'])) {
                            if ($operations->createUser($_POST['idRole'], $_POST['name'],
                                $_POST['lastName'], $_POST['phone'], $_POST['user'], $_POST['password'])) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Usuario creado exitosamente';
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'El Usuario no pudo ser creado';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'login':
                        if (isset($_POST['user']) && isset($_POST['password'])) {
                            $data = $operations->login($_POST['user'], $_POST['password']);
                            if (count($data) > 0) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Login correcto';
                                $respuesta['users'] = $data;
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'Usuario y/o contraseña incorrecta';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'createIncid':
                        if (isset($_POST['id_user']) && isset($_POST['id_estado_incid']) &&
                            isset($_POST['descripcion']) && isset($_POST['inst_educ']) && isset($_POST['cod_modul']) &&
                            isset($_POST['cod_local']) && isset($_POST['distrito']) && isset($_POST['provincia']) &&
                            isset($_POST['region']) && isset($_POST['dir_nomb_apell']) && isset($_POST['dir_telefono']) &&
                            isset($_POST['dir_dni']) && isset($_POST['dir_correo']) && isset($_POST['cist_nomb_apell']) &&
                            isset($_POST['cist_telefono']) && isset($_POST['cist_dni']) && isset($_POST['cist_correo'])) {
                            $id = $operations->createIncid($_POST['id_user'], $_POST['id_estado_incid'],
                                $_POST['descripcion'], $_POST['inst_educ'], $_POST['cod_modul'], $_POST['cod_local'],
                                $_POST['distrito'], $_POST['provincia'], $_POST['region'], $_POST['dir_nomb_apell'],
                                $_POST['dir_telefono'], $_POST['dir_dni'], $_POST['dir_correo'], $_POST['cist_nomb_apell'],
                                $_POST['cist_telefono'], $_POST['cist_dni'], $_POST['cist_correo']);
                                if ($id != 0) {
                                    $respuesta['error'] = false;
                                    $respuesta['id'] = $id;
                                    $respuesta['mensaje'] = 'Incidente creado exitosamente';
                                } else {
                                    $respuesta['error'] = true;
                                    $respuesta['id'] = 0;
                                    $respuesta['mensaje'] = 'El incidente no pudo ser creado';
                                }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'createFicha':
                        if (isset($_POST['id_incid']) && isset($_POST['id_tipo_incid']) &&
                            isset($_POST['marca']) && isset($_POST['modelo']) && isset($_POST['serie']) &&
                            isset($_POST['estado']) && isset($_POST['ubicacion']) && isset($_POST['observaciones'])) {
                            if ($operations->createFicha($_POST['id_incid'], $_POST['id_tipo_incid'],
                                $_POST['marca'], $_POST['modelo'], $_POST['serie'], $_POST['estado'],
                                $_POST['ubicacion'], $_POST['observaciones'])) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Ficha creada exitosamente';
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'La ficha no pudo ser creada';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    default:
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = 'Falta el parametro de acción';
                        break;
                }
            } else {
                $respuesta['error'] = true;
                $respuesta['mensaje'] = 'Falta el parametro de acción';
            }
        } else if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            parse_str(file_get_contents('php://input'), $put_vars);
            if (isset($put_vars['accion'])) {
                $accion = $put_vars['accion'];
                switch ($accion) {
                    case 'updaUser':
                        if (isset($put_vars['idUser']) && isset($put_vars['idRole']) && isset($put_vars['name']) &&
                            isset($put_vars['lastName']) && isset($put_vars['phone']) && isset($put_vars['user']) &&
                            isset($put_vars['password'])) {
                            if ($operations->updaUser($put_vars['idUser'], $put_vars['idRole'], $put_vars['name'],
                                $put_vars['lastName'], $put_vars['phone'], $put_vars['user'], $put_vars['password'])) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Usuario actualizado exitosamente';
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'El Usuario no pudo ser actualizado';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'updaPassw':
                        if (isset($put_vars['idUser']) && isset($put_vars['user']) &&
                            isset($put_vars['oldPassword']) && isset($put_vars['newPassword'])) {
                            if ($operations->updaPassw($put_vars['idUser'], $put_vars['user'], $put_vars['oldPassword'], $put_vars['newPassword'])) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Contraseña actualizada exitosamente';
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'La contraseña no pudo ser actualizada';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    case 'updaRole':
                        if (isset($put_vars['idUser']) && isset($put_vars['user']) &&
                            isset($put_vars['idRole'])) {
                            if ($operations->updaRole($put_vars['idUser'], $put_vars['user'], $put_vars['idRole'])) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Rol actualizado exitosamente';
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'El rol no pudo ser actualizado';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';
                        }
                        break;
                    default:
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = 'Falta parametro';
                        break;
                }
            } else {
                $respuesta['error'] = true;
                $respuesta['mensaje'] = 'Falta el parametro de acción';
            }
        } else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            parse_str(file_get_contents('php://input'), $delete_vars);
            if (isset($delete_vars['accion'])) {
                $accion = $delete_vars['accion'];
                switch ($accion) {
                    case 'deleteIncid':
                        if (isset($delete_vars['idIncid'])) {
                            if ($operations->deleteIncidencia($delete_vars['idIncid'])) {
                                $respuesta['error'] = false;
                                $respuesta['mensaje'] = 'Incidencia eliminada';
                            } else {
                                $respuesta['error'] = true;
                                $respuesta['mensaje'] = 'No se pudo eliminar la incidencia';
                            }
                        } else {
                            $respuesta['error'] = true;
                            $respuesta['mensaje'] = 'Faltan parametros';

                        }
                        break;
                    default:
                        $respuesta['error'] = true;
                        $respuesta['mensaje'] = 'Falta parametro';
                        break;
                }
            } else {
                $respuesta['error'] = true;
                $respuesta['mensaje'] = 'Falta el parametro de acción';
            }
        }
    } else {
        $respuesta['error'] = true;
        $respuesta['mensaje'] = 'Token incorrecto';
    }
} else {
    $respuesta['error'] = true;
    $respuesta['mensaje'] = 'Falta el token';
}
echo json_encode($respuesta, JSON_NUMERIC_CHECK);