
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
    @for($i = 1; $i < sizeof($steps); $i++)
        {{--Esconder linhas com gramas iguais a zero--}}
        @php( $steps_not_allowed = [8, 14, 15, 16, 17, 18])
        @if($order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouse_product_id !== 'default' && !in_array($i, $steps_not_allowed))
            <tr>
                <td data-col1="Função">
                    @foreach($guiafios as $guia)
                        @if($guia->id == $order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->guiafios_id)
                            <span>{{$guia->description}}</span>
                        @endif
                    @endforeach
                </td>
                <td data-col2="Guiafios">
                    @foreach($steps as $step)
                        @if($step->id == $order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->step_id)
                            <span>{{$step->step}}</span>
                        @endif
                    @endforeach
                </td>
                <td data-col3="Gramas">
                    <span>{{$order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->grams}}</span>
                </td>
                <td data-col4="Refrência INDMEI">
                    @foreach($warehouseProducts as $product)
                        @if($product->id == $order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouse_product_id)
                            <span>{{$product->reference}}</span>
                        @endif
                    @endforeach
                </td>
                <td data-col5="Cor #1">
                    @if(@$order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct)
                        @foreach($order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct->warehouseProductSpecs()->get() as $wireSpecs)
                            @if($wireSpecs->id == $order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->wireColors()->get()->values()->get(0)->warehouse_product_spec_id)
                                <span>{{$wireSpecs->color}}</span>
                            @endif
                        @endforeach
                    @else
                        <span></span>
                    @endif
                </td>
                <td data-col6="Kg #1"></td>
                <td data-col7="Cor #2">
                    @if(@$order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct)
                        @foreach($order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct->warehouseProductSpecs()->get() as $wireSpecs)
                            @if($wireSpecs->id == $order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->wireColors()->get()->values()->get(1)->warehouse_product_spec_id)
                                <span>{{$wireSpecs->color}}</span>
                            @endif
                        @endforeach
                    @else
                        <span></span>
                    @endif
                </td>
                <td data-col8="Kg #2"></td>
                <td data-col9="Cor #3">
                    @if(@$order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct)
                        @foreach($order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct->warehouseProductSpecs()->get() as $wireSpecs)
                            @if($wireSpecs->id == $order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->wireColors()->get()->values()->get(2)->warehouse_product_spec_id)
                                <span>{{$wireSpecs->color}}</span>
                            @endif
                        @endforeach
                    @else
                        <span></span>
                    @endif
                </td>
                <td data-col10="Kg #3"></td>
                <td data-col11="Cor #4">
                    @if(@$order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct)
                        @foreach($order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->warehouseProduct->warehouseProductSpecs()->get() as $wireSpecs)
                            @if($wireSpecs->id == $order->sampleArticle->sampleArticleWires()->get()->values()->get($i-1)->wireColors()->get()->values()->get(3)->warehouse_product_spec_id)
                                <span>{{$wireSpecs->color}}</span>
                            @endif
                        @endforeach
                    @else
                        <span></span>
                    @endif
                </td>
                <td data-col12="Kg #4"></td>
            </tr>
        @endif
    @endfor
    </tbody>
</table>