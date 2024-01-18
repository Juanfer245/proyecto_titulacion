<?php

require('./fpdf.php');

class PDF extends FPDF
{

   // Cabecera de página
   function Header()
   {
      include '../../php/conexion.php'; //llamamos a la conexion BD

      $consulta_info = $conexion->query(" select * from empresa "); //traemos datos de la empresa desde BD
      $dato_info = $consulta_info->fetch_object();
      $this->Image('../../vista/login/img/pag2.png', 230, 9, 55); //logo de la empresa,moverDerecha,moverAbajo,tamañoIMG
      $this->SetFont('Arial', 'B', 19); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(95); // Movernos a la derecha
      $this->SetTextColor(0, 0, 0); //color
      //creamos una celda o fila
      $this->Cell(110, 15, utf8_decode($dato_info->nombre), 1, 1, 'C', 0); // AnchoCelda,AltoCelda,titulo,borde(1-0),saltoLinea(1-0),posicion(L-C-R),ColorFondo(1-0)
      $this->Ln(3); // Salto de línea
      $this->SetTextColor(103); //color

      /* UBICACION */
      $this->Cell(5);  // mover a la derecha
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(96, 10, utf8_decode("Ubicación : " . $dato_info->telefono), 0, 0, '', 0);
      $this->Ln(5);

      /* TELEFONO */
      $this->Cell(5);  // mover a la derecha
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(59, 10, utf8_decode("Teléfono : " . $dato_info->telefono), 0, 0, '', 0);
      $this->Ln(5);

      /* COREEO */
      $this->Cell(5);  // mover a la derecha
      $this->SetFont('Arial', 'B', 10);
      $this->Cell(85, 10, utf8_decode("Correo : " . $dato_info->ruc), 0, 0, '', 0);
      $this->Ln(10);

      /* TITULO DE LA TABLA */
      //color
      $this->SetTextColor(88, 21, 21);
      $this->Cell(100); // mover a la derecha
      $this->SetFont('Arial', 'B', 15);
      $this->Cell(100, 10, utf8_decode("REPORTE DE PRODUCTOS "), 0, 1, 'C', 0);
      $this->Ln(7);

      /* CAMPOS DE LA TABLA */
      //color
      $this->SetFillColor(141, 29, 29); //colorFondo
      $this->SetTextColor(219, 219, 219); //colorTexto
      $this->SetDrawColor(163, 163, 163); //colorBorde
      $this->SetFont('Arial', 'B', 11);
      $this->Cell(15, 10, utf8_decode('N°'), 1, 0, 'C', 1);
      $this->Cell(50, 10, utf8_decode('Nombre Producto'), 1, 0, 'C', 1);
      $this->Cell(60, 10, utf8_decode('Descripcion'), 1, 0, 'C', 1);
      $this->Cell(20, 10, utf8_decode('Precio'), 1, 0, 'C', 1);
      $this->Cell(40, 10, utf8_decode('Imagen'), 1, 0, 'C', 1);
      $this->Cell(25, 10, utf8_decode('Inventario'), 1, 0, 'C', 1);
      $this->Cell(40, 10, utf8_decode('Categoria'), 1, 1, 'C', 1);
   }

   // Pie de página
   function Footer()
   {
      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, negrita(B-I-U-BIU), tamañoTexto
      $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C'); //pie de pagina(numero de pagina)

      $this->SetY(-15); // Posición: a 1,5 cm del final
      $this->SetFont('Arial', 'I', 8); //tipo fuente, cursiva, tamañoTexto
      $hoy = date('d/m/Y');
      $this->Cell(540, 10, utf8_decode($hoy), 0, 0, 'C'); // pie de pagina(fecha de pagina)
   }
}

include '../../php/conexion.php';
/* CONSULTA INFORMACION DEL HOSPEDAJE */


$pdf = new PDF();
$pdf->AddPage("landscape"); /* aqui entran dos para parametros (horientazion,tamaño)V->portrait H->landscape tamaño (A3.A4.A5.letter.legal) */
$pdf->AliasNbPages(); //muestra la pagina / y total de paginas

$i = 0;
$pdf->SetFont('Arial', '', 12);
$pdf->SetDrawColor(163, 163, 163); //colorBorde

$consulta_reporte_producto = $conexion->query(" select productos.*, categorias.nombre as catego from
productos 
inner join categorias on productos.id_categoria = categorias.id");

while ($datos_reporte = $consulta_reporte_producto->fetch_object()) {
   $i = $i + 1;
   /* TABLA */
   $pdf->Cell(15, 10, utf8_decode($i), 1, 0, 'C', 0);
   $pdf->Cell(50, 10, utf8_decode($datos_reporte->nombre), 1, 0, 'C', 0);
   $pdf->Cell(60, 10, utf8_decode($datos_reporte->descripcion), 1, 0, 'C', 0);
   $pdf->Cell(20, 10, utf8_decode($datos_reporte->precio), 1, 0, 'C', 0);
   $pdf->Cell(40, 10, utf8_decode($datos_reporte->imagen), 1, 0, 'C', 0);
   $pdf->Cell(25, 10, utf8_decode($datos_reporte->inventario), 1, 0, 'C', 0);
   $pdf->Cell(40, 10, utf8_decode($datos_reporte->catego), 1, 1, 'C', 0);

}



$pdf->Output('ReporteProducto.pdf', 'I'); //nombreDescarga, Visor(I->visualizar - D->descargar)
