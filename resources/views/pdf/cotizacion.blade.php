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
            /* background-image: url('logo-interior.png'); */
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-position: center;
            font-size: 10px;
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
            font-size: 10px;
            color: #000;
            font-weight: bold;
        }

        .titulo_pie {
            font-size: 10px;
            color: #000;
            font-weight: bold;
        }

        .datos {
            font-size: 10px;
            color: #000;
        }

        .datos_totales {
            font-size: 14px;
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

        .derecha {
            text-align: right;
        }

        .izquierda {
            text-align: left;
        }

        .centro {
            text-align: center;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <header>
        <div style="float: left; width:50%;text-align:left;">
            <!-- <img src="logo.png" style="width:60%" /> -->
            <img src="{{ public_path($cotizacion->empresa_logo)}}" style="width:60%" />
        </div>
        <div style="float: left; width:50%;text-align:right;">
            <p>{{$cotizacion->empresa_rz}}<br>
                {{$cotizacion->empresa_direccion}}.<br>
                Fono {{$cotizacion->empresa_telefono}}<br>
                {{$cotizacion->empresa_sitio_web}} / {{$cotizacion->empresa_correo}}
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
                    <td class="datos">: {{$cotizacion->rut}}</td>
                    <td></td>
                    <td class="nombre">Cliente</td>
                    <td class="datos">: {{$cotizacion->solicitante}}</td>
                    <td></td>
                    <td style="border: 2px solid #d2d2d2;background-color:#f7f2c5" rowspan="4">
                        <table cellspacing="2" style="width:100%;">
                            <tr>
                                <td class="nombre">Cotización N°</td>
                                <td>: {{$cotizacion->id}}</td>
                            </tr>
                            <tr>
                                <td class="nombre">Fecha</td>
                                <td class="datos">: {{$cotizacion->fecha}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="nombre">Razón Social</td>
                    <td class="datos">: {{$cotizacion->razon_social}}</td>
                    <td></td>
                    <td class="nombre">Giro</td>
                    <td class="datos">: {{$cotizacion->giro}}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="nombre">Dirección</td>
                    <td class="datos">: {{$cotizacion->direccion}}</td>
                    <td></td>
                    <td class="nombre">Comuna</td>
                    <td class="datos">: {{$cotizacion->comuna}}</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="nombre">Correo Electrónico</td>
                    <td class="datos">: {{$cotizacion->correo_electronico}}</td>
                    <td></td>
                    <td class="nombre">Teléfono</td>
                    <td class="datos">: {{$cotizacion->telefono}}</td>
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
                @php
                $total_descuentos = 0; $total_neto = 0;
                @endphp
                @foreach($productos as $p)
                @php
                $total_descuentos = $total_descuentos + ($p->descuento_pesos * $p->cantidad); $total_neto = $total_neto + $p->subtotal;
                @endphp
                <tr>
                    @if(isset($p->ruta))
                    <td style="border:2px solid #d2d2d2;text-align:center;"><img src="{{ public_path($p->ruta)}}" style="width:70px" /></td>
                    @else
                    <td style="border:2px solid #d2d2d2;text-align:center;"><img src="logo-interior.png" style="width:70px" /></td>
                    @endif
                    <td>
                        <p class="nombre">{{ $p->nombre }}</p>
                        <p class="modelo">{{$p->marca}} - {{$p->modelo}}</p>
                        <p class="sku">SKU: {{$p->sku}}</p>
                    </td>
                    <td class="datos derecha">{{ $p->cantidad }}</td>
                    <td class="datos derecha">$ {{ number_format($p->precio, 0, ',', '.') }}</td>
                    <td class="datos derecha">{{ $p->descuento }}%</td>
                    <td class="datos derecha">$ {{ number_format($p->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr style="background-color: #d2d2d2;">
                    <th colspan="6" class="izquierda">Despacho</th>
                </tr>
                <tr>
                    <td style="border:2px solid #d2d2d2;text-align:center;"><img src="despacho.png" style="width:70px" /></td>
                    <td>
                        <p class="nombre">Despacho</p>
                        <p class="modelo">Despacho a {{$cotizacion->region}}</p>
                        <p class="modelo">{{$cotizacion->transporte}}</p>

                    </td>
                    <td class="datos derecha">1</td>
                    <td class="datos derecha">$ {{number_format($cotizacion->costo_envio, 0, ',', '.')}}</td>
                    <td class="datos derecha">0%</td>
                    <td class="datos derecha">$ {{number_format($cotizacion->costo_envio, 0, ',', '.')}}</td>
                </tr>
            </tbody>
        </table>
        <br>
        <table style="width: 100%;border-top:1px solid #d2d2d2;">
            <tr>
                <td style="width: 50%;text-align:left;"><b>Observaciones</b></td>
                <td rowspan="3">
                    <table cellspacing="2" style="width:100%;">
                        <tr>
                            <td class="datos_totales">Descuento Total</td>
                            <td class="datos_totales derecha">$ {{number_format($total_descuentos, 0, ',', '.')}}</td>
                        </tr>
                        <tr>
                            <td class="datos_totales">Neto</td>
                            <td class="datos_totales derecha">$ {{number_format($total_neto, 0, ',', '.')}}</td>
                        </tr>
                        @php
                        $iva = $total_neto * 0.19;
                        $total_cotizacion = $total_neto + $iva;
                        @endphp
                        <tr>
                            <td class="datos_totales">Iva</td>
                            <td class="datos_totales derecha">$ {{number_format($iva, 0, ',', '.')}}</td>
                        </tr>
                        @if($cotizacion->costo_envio > 0)
                        @php
                        $total_cotizacion = $total_cotizacion + $cotizacion->costo_envio;
                        @endphp
                        <tr>
                            <td class="datos_totales">Valor Envío</td>
                            <td class="datos_totales derecha">$ {{number_format($cotizacion->costo_envio, 0, ',', '.')}}</td>
                        </tr>
                        @endif
                        <tr>
                            <td colspan="2" style="border: 2px solid #d2d2d2;background-color:#f7f2c5;font-weight:bold;font-size: 16px;" class="datos_totales">
                                <table style="width:100%">
                                    <tr>
                                        <td style="width: 50%;text-align:left;">Total</td>
                                        <td style="width: 50%;text-align:right;">$ {{ number_format($total_cotizacion, 0, ',', '.') }}</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="width: 50%;text-align:left;font-size:10px;vertical-align:top">Registro I.S.P. DM 546/22. Garantía de 8 años para el equipo. Electrodos Pad-Pak 01 (Adulto), consiste en electrodo unido a una batería conformando una sola unidad con una sola fecha de vencimiento. Seguimiento del DEA y electrodos (fechas vencimiento) vía tecnovigilancia. Capacitación RCP básica y uso del DEA modalidad virtual desde nuestra plataforma para 10 colaboradores por equipo adquirido. Entrega de certificados de forma virtual. Índice de protección más alto del mercado IP56 contra polvo y agua. Bolso de traslado y Kit primeros auxilios DEA. Servicio post venta. Adjunto fichas técnicas. Despacho incluido.</td>
            </tr>
        </table>
        <div class='page-break'></div>
        <table cellspacing="2" style="width:100%; background-color:#d2d2d2;">
            <tbody style="border:2px solid #fff;">
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
                                    <td>:</td>
                                    <td class="datos" nowrap>{{$cotizacion->tipo_pago}}</td>
                                </tr>
                                <tr>
                                    <td class="nombre">Plazo Pago</td>
                                    <td>:</td>
                                    <td class="datos">{{$cotizacion->plazo_pago}}</td>
                                </tr>
                                <tr>
                                    <td class="nombre">Tiempo Entrega</td>
                                    <td>:</td>
                                    <td class="datos">{{$cotizacion->tiempo}}</td>
                                </tr>
                                <tr>
                                    <td class="nombre">Fecha de validez cotización</td>
                                    <td>:</td>
                                    <td class="datos">{{$cotizacion->vencimiento}}</td>
                                </tr>
                                <tr>
                                    <td class="nombre">Método envío</td>
                                    <td>:</td>
                                    <td class="datos">{{$cotizacion->transporte}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>&nbsp;</td>
                    <td style="vertical-align: top;">
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td class="datos">{{$cotizacion->empresa_rz}}</td>
                                </tr>
                                <tr>
                                    <td class="datos">{{$cotizacion->empresa_rut}}</td>
                                </tr>
                                <tr>
                                    <td nowrap class="datos">{{$cotizacion->empresa_banco}} cuenta {{$cotizacion->empresa_tipo_cuenta}} {{$cotizacion->empresa_numero_cuenta}}</td>
                                </tr>
                                <tr>
                                    <td class="datos">{{$cotizacion->empresa_correo}}</td>
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
                                    <td nowrap class="nombre">Razón Social</td>
                                    <td>: </td>
                                    <td nowrap class="datos">{{$cotizacion->empresa_rz}}</td>
                                </tr>
                                <tr>
                                    <td class="nombre">RUT</td>
                                    <td>: </td>
                                    <td class="datos">{{$cotizacion->empresa_rut}}</td>
                                </tr>
                                <tr>
                                    <td class="nombre">Dirección</td>
                                    <td>: </td>
                                    <td nowrap class="datos">{{$cotizacion->empresa_direccion}}</td>
                                </tr>
                                <tr>
                                    <td class="nombre">Teléfonos</td>
                                    <td>: </td>
                                    <td class="datos">{{$cotizacion->empresa_telefono}}</td>
                                </tr>
                                <tr>
                                    <td class="nombre">Giro</td>
                                    <td>: </td>
                                    <td class="datos" nowrap>{{$cotizacion->empresa_giro}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td>&nbsp;</td>
                    <td>
                        <table style="width:100%;">
                            <tbody>
                                <tr>
                                    <td rowspan="6" style="vertical-align: top;">
                                        @if(isset($cotizacion->ejecutivo_foto))
                                        <img src="{{ public_path($cotizacion->ejecutivo_foto)}}" style="width:100px;border-radius: 50%;" />
                                        @else
                                        <img src="ejecutivo.png" style="width:100px;border-radius: 50%;" />
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="nombre">{{$cotizacion->ejecutivo}}</td>

                                </tr>
                                <tr>
                                    <td class="nombre">{{$cotizacion->ejecutivo_correo}}</td>

                                </tr>
                                <tr>
                                    <td class="nombre">{{$cotizacion->telefono}}</td>

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
                    <td class="datos" colspan="3">
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
                    <td colspan="3" class="datos" style="padding:10px;">
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