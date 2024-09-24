<div style="width: 25%;">
    &nbsp;
</div>
<div>
    <table style="width: 50%;">
        <tr>
            <td>
                <img src="{{ $message->embed($imagePath) }}" alt="image">

            </td>
        </tr>
        <tr>
            <td style="text-align:left;padding:20px;">{!! $body !!}</td>
        </tr>
        <tr>
            <td style="background-color: #ececec;text-align:left;padding:20px;">
                <img src="{{ $message->embed($imagenPie) }}" alt="image"><br>
                {!! $pie !!}
                <br>
                {!! $nota !!}
            </td>
        </tr>
    </table>
</div>
<div style="width: 25%;">
    &nbsp;
</div>