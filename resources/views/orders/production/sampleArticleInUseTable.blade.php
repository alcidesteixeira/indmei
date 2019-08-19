
<table class="table table-striped thead-dark" id="sampleArticleInUse">
    <thead>
    <tr>
        <th>Função</th>
        <th>Guiafios</th>
        <th>Gramas</th>
        <th>Referência INDMEI</th>
        <th>Cor #1</th>
        <th>Kg #1</th>
        <th>Cor #2</th>
        <th>Kg #2</th>
        <th>Cor #3</th>
        <th>Kg #3</th>
        <th>Cor #4</th>
        <th>Kg #4</th>
    </tr>
    </thead>
    <tbody>
    @foreach($sample as $k => $s) <!-- $i = 1; $i < sizeof($steps); $i++) -->
        {{--Esconder linhas com gramas iguais a zero--}}
        @php( $steps_not_allowed = [8, 14, 15, 16, 17, 18])
        @if(@$sample_colors[$k+1] && !in_array($k+1, $steps_not_allowed))
            <tr>
                <td data-col1="Função">
                    @foreach($guiafios as $guia)
                        @if($guia->id == $s->guiafios_id)
                            <span>{{$guia->description}}</span>
                        @endif
                    @endforeach
                </td>
                <td data-col2="Guiafios">
                    @foreach($steps as $step)
                        @if($step->id == $s->step_id)
                            <span>{{$step->step}}</span>
                        @endif
                    @endforeach
                </td>
                <td data-col3="Gramas">
                    <span>{{$s->grams}}</span>
                </td>
                <td data-col4="Refrência INDMEI">
                    @foreach($warehouseProducts as $product)
                        @if($product->id == $s->warehouse_product_id)
                            <span>{{$product->reference}}</span>
                        @endif
                    @endforeach
                </td>
                <td data-col5="Cor #1">
                    @foreach($color_name_and_key_array as $key => $color)
                        @if($key == $sample_colors[$k+1][1])
                            <span>{{$color}}</span>
                        @endif
                    @endforeach
                </td>
                <td data-col6="Kg #1"></td>
                <td data-col7="Cor #2">
                    @foreach($color_name_and_key_array as $key => $color)
                        @if($key == $sample_colors[$k+1][2])
                            <span>{{$color}}</span>
                        @endif
                    @endforeach
                </td>
                <td data-col8="Kg #2"></td>
                <td data-col9="Cor #3">
                    @foreach($color_name_and_key_array as $key => $color)
                        @if($key == $sample_colors[$k+1][3])
                            <span>{{$color}}</span>
                        @endif
                    @endforeach
                </td>
                <td data-col10="Kg #3"></td>
                <td data-col11="Cor #4">
                    @foreach($color_name_and_key_array as $key => $color)
                        @if($key == $sample_colors[$k+1][4])
                            <span>{{$color}}</span>
                        @endif
                    @endforeach
                </td>
                <td data-col12="Kg #4"></td>
            </tr>
        @endif
    @endforeach
    </tbody>
</table>
