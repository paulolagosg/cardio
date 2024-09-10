<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 100px 25px 150px 25px;
            /* Ajustamos márgenes para header y footer */
        }

        body {
            /* font-family: 'DejaVu Sans', sans-serif; */
            font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('logo-interior.png');
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
        }

        header {
            position: fixed;
            top: -80px;
            left: 0;
            right: 0;
            height: 80px;
            /* Coincide con el margen superior */
            text-align: center;
            font-size: 10px;
            border-bottom: 2px solid #811916;
            /* Centrar texto verticalmente */
        }

        footer {
            position: fixed;
            bottom: -150px;
            left: 0;
            right: 0;
            height: 150px;
            /* Coincide con el margen inferior */
            text-align: center;
            display: flex;
            justify-content: space-around;
            align-items: center;
        }

        .content {
            text-align: justify;
            margin-top: 0px;
            font-size: 14px;
            /* Ajuste para evitar desplazamiento a la segunda página */
        }

        .footer-logo {
            width: 20%;
            height: auto;
        }

        .page-number {
            position: absolute;
            bottom: 0;
            right: 10px;
            font-size: 10px;
        }

        .nombre {
            font-size: 12px;
            color: #000;
            font-weight: bold;
        }

        .titulo_pie {
            font-size: 13px;
            color: #000;
            font-weight: bold;
        }

        .datos {
            font-size: 12px;
            color: #000;
        }

        .modelo {
            font-size: 10px;
            color: #6c6c6c;
        }

        .sku {
            font-size: 9px;
            color: #d2d2d2;
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <header>
        <div style="float: left; width:50%;text-align:left;">
            <img src="logo.png" style="width:60%" />
        </div>
        <div style="float: left; width:50%;text-align:right;">
            <p>Cardioprotegido<br>
                Dinamsrca # 868. Temuco, Chile.<br>
                Fono 45 2 311 110 - 45 2 710 052<br>
                www.cardioprotegido.cl / ventas@cardioprotegido.cl
            </p>
        </div>
    </header>

    <footer>
        <img src="{{ public_path('stryker.jpg') }}" class="footer-logo" alt="Logo 1">
        <br>
        <img src="{{ public_path('stryker.jpg') }}" class="footer-logo" alt="Logo 1">
        <img src="{{ public_path('hertsine.jpg') }}" class="footer-logo" alt="Logo 2">
        <img src="{{ public_path('prestan.jpg') }}" class="footer-logo" alt="Logo 3">
        <div class="page-number">
        </div>
    </footer>

    <div class="content">
        <br>
        <table cellspacing="2" style="width:100%;">
            <tbody>
                <tr>
                    <td class="nombre">RUT</td>
                    <td class="datos">: 76.876.889-4</td>
                    <td></td>
                    <td class="nombre">Cliente</td>
                    <td class="datos">: Universidad Católica de Temuc</td>
                    <td></td>
                    <td style="border: 2px solid #d2d2d2;background-color:#f7f2c5" rowspan="4">
                        <table cellspacing="2" style="width:100%;">
                            <tr>
                                <td class="nombre">Cotización N°</td>
                                <td>: 543</td>
                            </tr>
                            <tr>
                                <td class="nombre">Fecha</td>
                                <td class="datos">: 01/09/2024</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="nombre">Razón Social</td>
                    <td class="datos">: UCT</td>
                    <td></td>
                    <td class="nombre">Giro</td>
                    <td class="datos">: Universidad</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="nombre">Dirección</td>
                    <td class="datos">: Avenida Alemania 0876</td>
                    <td></td>
                    <td class="nombre">Comuna</td>
                    <td class="datos">: Temuco</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="nombre">Correo Electrónico</td>
                    <td class="datos">: contacto@uct.cl</td>
                    <td></td>
                    <td class="nombre">Teléfono</td>
                    <td class="datos">: 452345345</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <p>&nbsp;</p>
        <table cellspacing="2" style="width:100%;">
            <thead>
                <tr style="background-color: #d2d2d2;">
                    <th colspan="2">Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Desc. x Un.</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productos as $p)
                <tr>
                    @if(isset($p->ruta))
                    <td style="border:2px solid #d2d2d2;text-align:center;"><img src="{{ public_path($p->ruta)}}" style="width:100px" /></td>
                    @else
                    <td style="border:2px solid #d2d2d2;text-align:center;"><img src="logo-interior.png" style="width:100px" /></td>
                    @endif


                    <td>
                        <p class="nombre">{{ $p->nombre }}</p>
                        <p class="modelo">{{$p->marca}} - {{$p->modelo}}</p>
                        <p class="sku">SKU: {{$p->sku}}</p>
                    </td>
                    <td></td>
                    <td>{{ $p->precio }}</td>
                    <td>{{ $p->descuento }}</td>
                    <td>Total</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class='page-break'></div>
        <table cellspacing="2" style="width:100%; background-color:#d2d2d2;">
            <tbody>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td class="titulo_pie">CONDICIONES</td>
                    <td>&nbsp;</td>
                    <td class="titulo_pie">DATOS EMISIÓN DE PAGO</td>
                </tr>

                <tr>
                    <td>
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>
                                    <td>: </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>
                                    <td>: </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>
                                    <td>: </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>
                                    <td>: </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>
                                    <td>: </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>&nbsp;</td>
                    <td style="vertical-align: top;">
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>
                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>
                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>
                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="titulo_pie">DATOS ORDENES DE COMPRA</td>
                    <td>&nbsp;</td>
                    <td class="titulo_pie">ATENDIDO POR</td>
                </tr>
                <tr>
                    <td>
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>
                                    <td>: </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>
                                    <td>: </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>
                                    <td>: </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>
                                    <td>: </td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>
                                    <td>: </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td rowspan="5" style="vertical-align: top;"> foto de perfil</td>
                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>

                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>

                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>

                                </tr>
                                <tr>
                                    <td class="nombre">Forma de Pago</td>

                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="3" class="titulo_pie">IMPORTANTE</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <ul>
                            <li> Esta cotización no reserva ni confirma stock.</li>
                            <li> La orden de compra debe ser enviada al e-mail del ejecutivo (a) que envió esta cotización.</li>
                            <li> Se incluyen fichas técnicas de los productos.</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td colspan="3" class="titulo_pie">ALCANCE</td>
                </tr>
                <tr>
                    <td colspan="3" style="padding:10px;">
                        Las Condiciones Comerciales y Contractuales establecidas en la presente cotización, formaran parte integrante de la Orden de Compra o Contrato entre R y E Group Spa. y la empresa Compradora. En caso de existir algún tipo de diferencias en la interpretación de alguna de las disposiciones establecidas en esta cotización, y las dispuestas en la Orden de Compra o Contrato del Comprador, prevalecerán las establecidas en la presente cotización. En caso de existir cualquier variación en las condiciones comerciales contenidas en la presente oferta, la dejan sin efecto.
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>&nbsp;</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>