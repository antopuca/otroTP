<?php
require_once ('includes/pdo.php');
require_once 'clases/Conexion.php';
require_once 'clases/Producto.php';


$producto = new Producto();
function obtenerListaProductos($db){
  try {
    $sql = "SELECT id_producto as id,nombre,descripcion,precio,cantidad as stock,marca,categoria,descuento,img 
      FROM productos as p
        inner join categorias as c on p.id_categoria=c.id_categoria
        inner join marcas as m on p.id_marca=m.id_marca";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    //$variable = $stmt->fetchAll(PDO::FETCH_ASSOC);//array asociado
    $variable = $stmt->fetchAll(PDO::FETCH_CLASS,"Producto");//objeto
    $stmt->closeCursor();
    return $variable;  
  } catch (\Exception $e) {
    echo "Error al obtener Lista de Productos";
    $e->getMessage();
  }
  
}
function obtenerListaMarcas(){
  $db=Conexion::conectar();
  try {
    $sql = "SELECT id_marca,marca 
      FROM marcas";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $variable = $stmt->fetchAll(PDO::FETCH_ASSOC);//array asociado
    $stmt->closeCursor();
    return $variable;  
  } catch (\Exception $e) {
    echo "Error al obtener Lista de Marcas";
    $e->getMessage();
  }  
}
function obtenerListaCategorias(){
  $db=Conexion::conectar();

  try {
    $sql = "SELECT id_categoria,categoria
      FROM categorias";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $variable = $stmt->fetchAll(PDO::FETCH_ASSOC);//array asociado
    $stmt->closeCursor();
    return $variable;
  } catch (\Exception $e) {
    echo "Error al obtener Lista de Categorias";
    $e->getMessage();
  }
}
$variable=$producto->obtenerListaProductos($db);
//var_dump($variable);
if ($_POST) {
 // var_dump($_POST);
 // exit;
  if (isset($_POST["btnCargar"])) {
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio= $_POST["precio"];
    $stock = $_POST["stock"];
    $marca = $_POST["marca"];
    $categoria = $_POST["categoria"];
    $descuento = $_POST["descuento"];
    $img = "img/productos/phone.jpg";//$_POST["img"]; esto lo deje asi para que funciones pero tendria que ir la direccion

    $producto->altaProducto($img);
  }elseif (isset($_POST["btnBorrar"])) {
    $id = $_POST["id"];

    $producto->borrarProducto($id);
  }
  if (isset($_POST["modificar_id"])) {
    $id=2;//de alguna manera le tiene que llegar un id 
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $precio= $_POST["precio"];
    $stock = $_POST["stock"];
    $marca = $_POST["marca"];
    $categoria = $_POST["categoria"];
    $descuento = $_POST["descuento"];
    $img = "img/productos/phone.jpg";
    $producto->modificarProducto($id,$nombre, $descripcion,$precio, $stock, $marca, $categoria, $descuento, $img);
  }
}
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<?php include 'includes/head.php';?>
<title>ABM Productos</title>

<body>

  <?php include 'includes/headerAdm.php'; ?>

  <main>
    
    <div class="container">
      <div id="accordion">
        <div class="card">
          <div class="card-header" id="headingOne">
            <h5 class="mb-0">
              <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"  aria-controls="collapseOne">
                Agregar Productos
              </button>
            </h5>
          </div>

          <div id="collapseOne" class ="collapse" aria-labelledby="headingOne" data-parent="#accordion">
            <div class="card-body">
              <form class="altaProducto" action="" method="post" enctype="multipart/form-data">

              <div class="form-group">
                  <label for="nombre">Nombre</label>
                  <input type="text" class="form-control" id="nombre" name="nombre">
                </div>
                <div class="form-group">
                  <label for="descripcion">Descripcion</label>
                  <textarea class="form-control" id="descripcion" rows="8" cols="80" name="descripcion" value=""></textarea>
                </div>
                <div class="form-group">
                  <label for="precio">Precio:</label>
                  <input type="number" class="form-control " id="precio" name="precio" min="0" value="0" >
                </div>
                <div class="form-group">
                  <label for="stock">stock</label>
                  <input type="number" class="form-control" id="stock" name="stock" min="0" value="0">
                </div>
                <div class="form-group">
                  <label for="marca">Marca</label>
                  <select class="form-control" id="marca" name="marca">
                    <?php 
                      $marcas=obtenerListaMarcas($db);
                      foreach ($marcas as $key => $value) { 
                    ?>
                      <option value="<?=$value["id_marca"];?>"><?=$value["marca"];?></option>
                    <?php 
                      } 
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="categoria">Categoria</label>
                  <select class="form-control" id="categoria" name="categoria">
                  <?php 
                      $marcas=obtenerListaCategorias($db);
                      foreach ($marcas as $key => $value) { 
                    ?>
                      <option value="<?=$value["id_categoria"];?>"><?=$value["categoria"];?></option>
                    <?php 
                      } 
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="descuento">descuento</label>
                  <input type="number" class="form-control" id="descuento" name="descuento" min="0" max="100" value="0">
                </div>
                <div class="form-group">
                  <label for="img">Imagen</label>
                  <input type="file" class="form-control-file" id="img" name="img">
                </div>
                <button type="submit" class="btn btn-primary mb-2" name="btnCargar" value="cargar">Cargar</button>
              </form>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header" id="headingTwo">
            <h5 class="mb-0">
              <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                Modificar Productos
              </button>
            </h5>
          </div>
          <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
            <div class="card-body">
              <form class="modificarProducto" action="modificarProducto.php" method="post">
                <div class="form-group">
                  <label for="nombre">Nombre</label>
                  <input type="text" class="form-control" id="nombre" name="nombre" value="">
                </div>
                <div class="form-group">
                  <label for="descripcion">Descripcion</label>
                  <textarea class="form-control" id="descripcion" rows="8" cols="80" name="descripcion"></textarea>
                </div>
                <div class="form-group">
                  <label for="precio">Precio:</label>
                  <input type="number" class="form-control" id="precio" name="precio" min="0" value="">
                </div>
                <div class="form-group">
                  <label for="stock">stock</label>
                  <input type="number" class="form-control" id="stock" name="stock" min="0" value="">
                </div>
                <div class="form-group">
                  <label for="marca">Marca</label>
                  <select class="form-control" id="marca" name="marca">
                    <?php 
                      $marcas=obtenerListaMarcas($db);
                      foreach ($marcas as $key => $value) { 
                    ?>
                      <option value="<?=$value["id_marca"];?>"><?=$value["marca"];?></option>
                    <?php 
                      } 
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="categoria">Categoria</label>
                  <select class="form-control" id="categoria" name="categoria">
                  <?php 
                      $marcas=obtenerListaCategorias($db);
                      foreach ($marcas as $key => $value) { 
                    ?>
                      <option value="<?=$value["id_categoria"];?>"><?=$value["categoria"];?></option>
                    <?php 
                      } 
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="descuento">descuento</label>
                  <input type="number" class="form-control" id="descuento" name="descuento" min="0" max="100" value="">
                </div>
                <div class="form-group">
                  <label for="img">Imagen</label>
                  <input type="file" class="form-control-file" id="img" name="img">
                </div>
                <button type="submit" class="btn btn-primary mb-2" name="modificar_id">Modificar</button>
              </form>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header" id="headingThree">
            <h5 class="mb-0">
              <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                Eliminar Productos
              </button>
            </h5>
          </div>
          <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
            <div class="card-body">
              <form class="borrarProducto" action="" method="post">
                <label for="id">Id del producto que se desea borrar</label>
                <br>
                <input type="number" min=1 name="id" value="">

                <button type="submit" name="btnBorrar" value="borrar">Borrar</button>
              </form>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header" id="headingFour">
            <h5 class="mb-0">
              <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                Lista de Productos
              </button>
            </h5>
          </div>
          <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
            <ul class="list-group">
              <li class="list-group-item">
                <div class="card-body form-inline d-flex justify-content-between px-0">
                  <div class="form-group mb-1 col-1 px-1" >
                      <span  class="form-control-plaintext text-center">Id</span>
                  </div>
                  <div class="form-group mb-1 col-2 px-1" >
                      <span  class="form-control-plaintext text-center">Nombre</span>
                  </div>
                  <div class="form-group mb-1 col-1 px-1" >
                      <span  class="form-control-plaintext text-center">Descripcion</span>
                  </div>
                  <div class="form-group mb-1 col-1 " >
                      <span  class="form-control-plaintext text-center">Precio</span>
                  </div>
                  <div class="form-group mb-1 col-1 px-1" >
                      <span  class="form-control-plaintext text-center">Stock</span>
                  </div>
                  <div class="form-group mb-1 col-2 px-1" >
                      <span  class="form-control-plaintext text-center">Marca</span>
                  </div>
                  <div class="form-group mb-1 col-1 px-1" >
                      <span  class="form-control-plaintext text-center">Categoria</span>
                  </div>
                  <div class="form-group mb-1 col-1 px-1" >
                      <span  class="d-block form-control-plaintext text-center">Descuento</span>
                  </div>
                  <div class="form-group mb-1 col-2 px-1" >
                      <span  class="d-block text-center form-control-plaintext text-center">Imagen</span>
                  </div>
                </div>
              </li>
              <?php foreach ($variable as $key => $value) { ?>

                <li class="list-group-item">
                  <div class="card-body d-flex justify-content-between px-0">
                    <form class="form-inline" action="modificarProducto.php" method="post">
                      <div class="form-group mb-1 col-1 px-1" >
                        <input type="text" readonly class="form-control-plaintext text-center" id="id" value="<?=$value->getId();?>" name="id">
                      </div>
                      <div class="form-group mb-2 col-2 px-1">

                        <span  class="form-control-plaintext text-center" ><?=$value->getNombre();?></span>
                      </div>
                      <div class="form-group mb-2 col-1">

                        <span class="form-control-plaintext text-center" >Aqui</span>
                      </div>
                      <div class="form-group mb-2 col-1">

                        <span class="form-control-plaintext text-center" >$ <?=$value->getPrecio();?></span>
                      </div>
                      <div class="form-group mb-2 col-1 px-1">

                        <span class="form-control-plaintext text-center" ><?=$value->getStock();?></span>
                      </div>
                      <div class="form-group mb-2 col-2 px-1">

                        <span class="form-control-plaintext text-center" ><?=$value->getMarca();?></span>
                      </div>
                      <div class="form-group mb-2 col-1 px-1">

                        <span class="form-control-plaintext text-center" ><?=$value->getCategoria();?></span>
                      </div>
                      <div class="form-group mb-2 col-1 px-1">

                        <span class="form-control-plaintext text-center" ><?=$value->getDescuento();?>%</span>
                      </div>
                      <div class="form-group mb-2 col-2 text-center">

                        <img src="<?=$value->getImg();?>" alt="" sizes="30px">
                      </div>
                      <div class="form-group mb-2 col-12 text-center">
                        <button type="submit" class="btn btn-primary mx-2 mb-1 " name="modificar_l" value="<?=$value->getId();?>">Modificar</button>
                        <button type="submit" class="btn btn-primary mx-2 mb-1 " name="eliminar_l" value="<?=$value->getId();?>">Eliminar</button>
                      </div>
                    </form>
                  </div>
                </li>

              <?php } ?>
            </ul>
          </div>
        </div>
      </div>
    </div>

  </main>

  <?php
  include 'includes/footer.php';
  include 'includes/scriptBootstrap.php';
  ?>

</body>
</html>
